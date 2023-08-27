<?php

namespace App\Http\Controllers;

use App\Models\Language;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use ZipArchive;

class SettingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $type = last(request()->segments());
        $type1 = str_replace('-', '_', $type);
        $data = Setting::select('data')->where('type', $type1)->pluck('data')->first();
        $languages = Language::all();
        return view('settings.' . $type, compact('data', 'type', 'languages'));
    }

    public function settings(Request $request)
    {
        $request->validate([
            'data' => 'required',
        ]);

        $type1 = $request->type;
        if ($type1 != '') {
            $message = Setting::where('type', $type1)->first();
            if (empty($message)) {
                Setting::create([
                    'type' => $type1,
                    'data' => $request->data
                ]);
            } else {
                $data['data'] = $request->data;
                Setting::where('type', $type1)->update($data);
            }
            return redirect(str_replace('_', '-', $type1))->with('success', 'Setting Update');
        } else {
            return redirect(str_replace('_', '-', $type1))->with('error', 'Something Wrong');
        }
    }

    public function system_settings(Request $request)
    {
        $input = $request->all();

        unset($input['btnAdd']);
        unset($input['_token']);
        foreach ($input as $key => $value) {
            $result = Setting::where('type', $key)->first();
            if (empty($result)) {
                Setting::create([
                    'type' => $key,
                    'data' => $value
                ]);
            } else {
                $data['data'] = ($value) ? $value : '';
                Setting::where('type', $key)->update($data);
            }
        }

        return redirect()->back()->with('success', 'Setting Update');
    }
    public function firebase_settings(Request $request)
    {
        $input = $request->all();

        unset($input['btnAdd1']);
        unset($input['_token']);
        foreach ($input as $key => $value) {
            $result = Setting::where('type', $key)->first();
            if (empty($result)) {
                Setting::create([
                    'type' => $key,
                    'data' => $value
                ]);
            } else {
                $data['data'] = ($value) ? $value : '';
                Setting::where('type', $key)->update($data);
            }
        }

        return redirect()->back()->with('success', 'Setting Update');
    }
    public function system_version()
    {
        return view('settings.system_version');
    }
    public function system_version_setting(Request $request)
    {
        // dd($request->toArray());
        $destinationPath = public_path() . '/update/tmp/';

        if (!has_permissions('customer', 'edit')) {
            $response = array(
                'error' => true,
                'message' => trans('no_permission_message')
            );
            return response()->json($response);
        }
        $validator = Validator::make($request->all(), [
            'purchase_code' => 'required',
            'file' => 'required|file|mimes:zip',
        ]);

        if ($validator->fails()) {


            return redirect()->back()->with('error', $validator->errors()->first());
        }
        $app_url = (string)url('/');
        $app_url = preg_replace('#^https?://#i', '', $app_url);

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://wrteam.in/validator/ebroker_validator?purchase_code=' . $request->purchase_code . '&domain_url=' . $app_url . '',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));
        $response = curl_exec($curl);
        $info = curl_getinfo($curl);
        // dd(curl_getinfo($curl));

        curl_close($curl);
        // dd($response);
        $response = json_decode($response, true);
        if ($response['error']) {
            $response = array(
                'error' => true,
                'message' => $response["message"],
                'info' => $info
            );

            return redirect()->back()->with('error', $response["message"]);
        } else {
            if (!is_dir($destinationPath)) {
                mkdir($destinationPath, 0777, TRUE);
            }

            // zip upload
            $zipfile = $request->file('file');
            $fileName = $zipfile->getClientOriginalName();
            $zipfile->move($destinationPath, $fileName);

            $target_path = getcwd() . DIRECTORY_SEPARATOR;

            $zip = new ZipArchive();
            $filePath = $destinationPath . '/' . $fileName;
            $zipStatus = $zip->open($filePath);
            if ($zipStatus) {
                $zip->extractTo($destinationPath);
                $zip->close();
                unlink($filePath);

                $ver_file = $destinationPath . 'version_info.php';
                $source_path = $destinationPath . 'source_code.zip';
                if (file_exists($ver_file) && file_exists($source_path)) {
                    $ver_file1 = $target_path . 'version_info.php';
                    $source_path1 = $target_path . 'source_code.zip';
                    if (rename($ver_file, $ver_file1) && rename($source_path, $source_path1)) {
                        $version_file = require_once($ver_file1);

                        $current_version = Setting::select('data')->where('type', 'system_version')->pluck('data')->first();
                        // dd($current_version);
                        if ($current_version == $version_file['current_version']) {
                            $zip1 = new ZipArchive();
                            $zipFile1 = $zip1->open($source_path1);
                            if ($zipFile1 === true) {
                                $zip1->extractTo($target_path); // change this to the correct site path
                                $zip1->close();

                                Artisan::call('migrate');
                                echo $target_path;
                                echo "<br>";

                                echo $source_path1;
                                echo "<br>";
                                echo $ver_file1;

                                // unlink($source_path1);
                                // unlink($ver_file1);
                                // Setting::where('type', 'system_version')->update([
                                //     'data' => $version_file['update_version']
                                // ]);

                                // return redirect()->back()->with('success', 'system_update_successfully');
                            } else {
                                unlink($source_path1);
                                unlink($ver_file1);

                                return redirect()->back()->with('error', 'something_wrong_try_again');
                            }
                        } else if ($current_version == $version_file['update_version']) {
                            unlink($source_path1);
                            unlink($ver_file1);


                            return redirect()->back()->with('error', 'system_already_updated');
                        } else {
                            unlink($source_path1);
                            unlink($ver_file1);

                            return redirect()->back()->with('error', $current_version . ' ' . trans('your_version_update_nearest'));
                        }
                    } else {

                        return redirect()->back()->with('error', 'invalid_zip_try_again');
                    }
                } else {

                    return redirect()->back()->with('error', 'invalid_zip_try_again');
                }
            } else {
                return redirect()->back()->with('error', 'something_wrong_try_again');
            }
        }
    }
}
