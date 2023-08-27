<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Property;
use App\Models\PropertysInquiry;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {


        if (!has_permissions('read', 'dashboard')) {
            return redirect('dashboard')->with('error', PERMISSION_ERROR_MSG);
        } else {


            $list['total_property'] = Property::all()->count();
            $list['total_active_property'] = Property::where('status', '1')->get()->count();
            $list['total_inactive_property'] = Property::where('status', '0')->get()->count();


            $list['total_property_inquiry'] = PropertysInquiry::all()->count();
            $list['total_property_inquiry_pending'] = PropertysInquiry::where('status', '0')->get()->count();
            $list['total_property_inquiry_accept'] = PropertysInquiry::where('status', '1')->get()->count();
            $list['total_property_inquiry_in_progress'] = PropertysInquiry::where('status', '2')->get()->count();
            $list['total_property_inquiry_complete'] = PropertysInquiry::where('status', '3')->get()->count();
            $list['total_property_inquiry_cancle'] = PropertysInquiry::where('status', '4')->get()->count();


            $list['total_customer'] = Customer::all()->count();
            $payment_settings =
                Setting::select('type', 'data')->whereIn('type', ['apiKey', 'authDomain', 'projectId', 'storageBucket', 'messagingSenderId', 'appId', 'measurementId']);

            $result = $payment_settings->get();

            // dd(DB::getQueryLog());

            // if (count($result)) {

            $rows = array();
            $firebase_settings = array();



            $operate = '';

            $firebase_settings['apiKey'] = system_setting('apiKey');
            $firebase_settings['authDomain'] = system_setting('authDomain');
            $firebase_settings['projectId'] = system_setting('projectId');
            $firebase_settings['storageBucket'] = system_setting('storageBucket');
            $firebase_settings['messagingSenderId'] = system_setting('messagingSenderId');
            $firebase_settings['appId'] = system_setting('appId');
            $firebase_settings['measurementId'] = system_setting('measurementId');
            // }

            return view('home', compact('list', 'firebase_settings'));
        }
    }
    public function blank_dashboard()
    {


        return view('blank_home');
    }


    public function change_password()
    {

        return view('change_password.index');
    }
    public function changeprofile()
    {
        return view('change_profile.index');
    }

    public function check_password(Request $request)
    {
        $id = Auth::id();
        $oldpassword = $request->old_password;
        $user = DB::table('users')->where('id', $id)->first();
        return (password_verify($oldpassword, $user->password)) ? true : false;
    }
    // return Hash::check('admin123', '$2y$10$EqdAtOfZowAvBGInra6aXe6burYrC/c6mbgJ1DnqV/0Myo6sTT6Aq') ? true : false;


    public function store_password(Request $request)
    {

        $confPassword = $request->confPassword;
        $id = Auth::id();
        $role = Auth::user()->type;

        $users = User::find($id);
        // if ($role == 0) {
        //     $users->name  = $request->name;
        //     $users->email  = $request->email;
        // }

        if (isset($confPassword) && $confPassword != '') {
            $users->password = Hash::make($confPassword);
        }

        $users->update();
        return back()->with('success', 'Password Change Successfully');
    }
    function update_profile(Request $request)
    {
        $id = Auth::id();
        $role = Auth::user()->type;

        $users = User::find($id);
        if ($role == 0) {
            $users->name  = $request->name;
            $users->email  = $request->email;
        }
        $users->update();
        return back()->with('success', 'Profile Updated Successfully');
    }

    public function privacy_policy()
    {
        echo system_setting('privacy_policy');
    }


    public function firebase_messaging_settings(Request $request)
    {
        $file_path = public_path('firebase-messaging-sw.js');

        // Check if file exists
        if (File::exists($file_path)) {
            // Remove existing file
            File::delete($file_path);
        }

        // Move new file
        $request->file->move(public_path(), 'firebase-messaging-sw.js');
    }
}
