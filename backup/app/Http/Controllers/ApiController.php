<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use App\Models\Article;
use App\Models\AssignParameters;
use App\Models\Category;
use App\Models\Customer;
use App\Models\Favourite;

use App\Models\InterestedUser;
use App\Models\Language;
use App\Models\Notifications;
use App\Models\Package;
use App\Models\parameter;
use App\Models\Property;
use App\Models\PropertyImages;
use App\Models\PropertysInquiry;
use App\Models\Setting;
use App\Models\Slider;
use App\Models\Type;

use App\Models\User;
use App\Models\Chats;
use Carbon\CarbonInterface;
use App\Models\UserPurchasedPackage;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

use Intervention\Image\ImageManagerStatic as Image;

use Illuminate\Support\Str;
use kornrunner\Blurhash\Blurhash;
use App\Libraries\Paypal;
use App\Models\Payments;
use App\Libraries\Paypal_pro;
// use PayPal_Pro as GlobalPayPal_Pro;
use Tymon\JWTAuth\Claims\Issuer;

class ApiController extends Controller
{
    function update_subscription()
    {
        $data = UserPurchasedPackage::where('user_id', Auth::id())->where('end_date', Carbon::now());
        if ($data) {
            $Customer = Customer::find(Auth::id());
            $Customer->subscription = 0;
            $Customer->update();
        }
    }
    //* START :: get_system_settings   *//
    public function get_system_settings(Request $request)
    {


        $result = '';

        $result =  Setting::select('type', 'data')->get();


        if (!empty($result)) {
            $response['error'] = false;
            $response['message'] = "Data Fetch Successfully";
            $response['data'] = $result;
        } else {
            $response['error'] = false;
            $response['message'] = "No data found!";
            $response['data'] = [];
        }

        if (isset($request->user_id)) {

            $data = UserPurchasedPackage::where('modal_id', $request->user_id)->where('end_date', date('d'))->where('end_date', '!=', NULL)->get();


            $customer = Customer::select('id')->where('subscription', 1)->with('user_purchased_package.package')->find($request->user_id);


            if ($customer) {
                if (count($data)) {

                    $customer->subscription = 0;
                    $customer->update();
                }

                $response['subscription'] = true;
                $response['package'] = $customer;
            } else {
                $response['subscription'] = false;
            }
            // $language = Language::select('code', 'name')->get();
            // $response['languages'] = $language;
        }
        $language = Language::select('code', 'name')->get();
        $response['languages'] = $language;
        return response()->json($response);
    }
    //* END :: Get System Setting   *//


    //* START :: user_signup   *//
    public function user_signup(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required',
            'firebase_id' => 'required',

        ]);

        if (!$validator->fails()) {
            $type = $request->type;
            $firebase_id = $request->firebase_id;

            $user = Customer::where('firebase_id', $firebase_id)->where('logintype', $type)->get();
            if ($user->isEmpty()) {
                $saveCustomer = new Customer();
                $saveCustomer->name = isset($request->name) ? $request->name : '';
                $saveCustomer->email = isset($request->email) ? $request->email : '';
                $saveCustomer->mobile = isset($request->mobile) ? $request->mobile : '';
                // $saveCustomer->profile = isset($request->profile) ? $request->profile : '';
                $saveCustomer->fcm_id = isset($request->fcm_id) ? $request->fcm_id : '';
                $saveCustomer->logintype = isset($request->type) ? $request->type : '';
                $saveCustomer->address = isset($request->address) ? $request->address : '';
                $saveCustomer->firebase_id = isset($request->firebase_id) ? $request->firebase_id : '';
                $saveCustomer->isActive = '1';


                $destinationPath = public_path('images') . config('global.USER_IMG_PATH');
                if (!is_dir($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }
                // image upload


                if ($request->hasFile('profile')) {
                    // dd('in');
                    $profile = $request->file('profile');
                    $imageName = microtime(true) . "." . $profile->getClientOriginalExtension();
                    $profile->move($destinationPath, $imageName);
                    $saveCustomer->profile = $imageName;
                } else {
                    $saveCustomer->profile = '';
                }



                $saveCustomer->save();
                $start_date =  Carbon::now();

                $user_package = new UserPurchasedPackage();
                $user_package->modal()->associate($saveCustomer);
                $user_package->package_id = 1;
                $user_package->start_date = $start_date;
                $user_package->end_date =  Carbon::now()->addDays(30);
                $user_package->save();

                $saveCustomer->subscription = 1;
                $saveCustomer->update();

                $response['error'] = false;
                $response['message'] = 'User Register Successfully';

                $credentials = Customer::find($saveCustomer->id);
                $token = JWTAuth::fromUser($credentials);
                try {
                    if (!$token) {
                        $response['error'] = true;
                        $response['message'] = 'Login credentials are invalid.';
                    } else {
                        $credentials->api_token = $token;
                        $credentials->update();
                    }
                } catch (JWTException $e) {
                    $response['error'] = true;
                    $response['message'] = 'Could not create token.';
                }


                // $user2 = new Customer();
                // $user2 = $user2::find($credentials->id);
                // $user2->api_token = $token;
                // $user2->update();

                $response['token'] = $token;
                $response['data'] = $credentials;
            } else {
                $credentials = Customer::where('firebase_id', $firebase_id)->where('logintype', $type)->first();
                try {
                    $token = JWTAuth::fromUser($credentials);
                    if (!$token) {
                        $response['error'] = true;
                        $response['message'] = 'Login credentials are invalid.';
                    } else {
                        $credentials->api_token = $token;
                        $credentials->update();
                    }
                } catch (JWTException $e) {
                    $response['error'] = true;
                    $response['message'] = 'Could not create token.';
                }


                $response['error'] = false;
                $response['message'] = 'Login Successfully';
                $response['token'] = $token;
                $response['data'] = $credentials;
            }
        } else {
            $response['error'] = true;
            $response['message'] = 'Please fill all data and Submit';
        }
        return response()->json($response);
    }
    //* END :: user_signup   *//


    //* START :: get_slider   *//
    public function get_slider(Request $request)
    {
        $tempRow = array();
        $slider = Slider::select('id', 'image', 'sequence', 'category_id', 'propertys_id')->orderBy('sequence', 'ASC')->get();
        if (!$slider->isEmpty()) {
            foreach ($slider as $row) {
                if (filter_var($row->image, FILTER_VALIDATE_URL) === false) {
                    $property_img = Property::select('title_image')->find($row->propertys_id);
                    // dd($property_img);
                    $tempRow['image'] = ($row->image != '') ? url('') . config('global.IMG_PATH') . config('global.SLIDER_IMG_PATH') . $row->image : $property_img->title_image;
                } else {
                    echo "in";
                    $property_img = Property::select('title_image')->find($slider->propertys_id);
                    // $row->image =
                    $tempRow['image'] = $property_img->title_image;
                }
                $tempRow['id'] = $row->id;
                // $tempRow['image'] = $row->image;
                $tempRow['sequence'] = $row->sequence;
                $tempRow['category_id'] = $row->category_id;
                $tempRow['propertys_id'] = $row->propertys_id;



                $promoted = Slider::where('propertys_id', $row->propertys_id)->first();
                // print_r($promoted);

                if ($promoted) {
                    $tempRow['promoted'] = true;
                } else {
                    $tempRow['promoted'] = false;
                }
                $rows[] = $tempRow;
            }


            $response['error'] = false;
            $response['message'] = "Data Fetch Successfully";
            $response['data'] = $rows;
        } else {
            $response['error'] = false;
            $response['message'] = "No data found!";
            $response['data'] = [];
        }
        return response()->json($response);
    }

    //* END :: get_slider   *//


    //* START :: get_categories   *//
    public function get_categories(Request $request)
    {
        $offset = isset($request->offset) ? $request->offset : 0;
        $limit = isset($request->limit) ? $request->limit : 10;

        $categories = Category::select('id', 'category', 'image', 'parameter_types')->where('status', '1');

        if (isset($request->search) && !empty($request->search)) {
            $search = $request->search;
            $categories->where('category', 'LIKE', "%$search%");
        }

        if (isset($request->id) && !empty($request->id)) {
            $id = $request->id;
            $categories->where('id', '=', $id);
        }

        $total = $categories->get()->count();
        $result = $categories->orderBy('sequence', 'ASC')->skip($offset)->take($limit)->get();




        if (!$result->isEmpty()) {
            $response['error'] = false;
            $response['message'] = "Data Fetch Successfully";
            foreach ($result as $row) {


                $row->parameter_types = parameterTypesByCategory($row->id);
            }

            $response['total'] = $total;
            $response['data'] = $result;
        } else {
            $response['error'] = false;
            $response['message'] = "No data found!";
            $response['data'] = [];
        }
        return response()->json($response);
    }
    //* END :: get_slider   *//







    //* START :: update_profile   *//
    public function update_profile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'userid' => 'required',
        ]);


        if (!$validator->fails()) {
            $id = $request->userid;

            $customer =  Customer::find($id);

            if (!empty($customer)) {
                if (isset($request->name)) {
                    $customer->name = ($request->name) ? $request->name : '';
                }
                if (isset($request->email)) {
                    $customer->email = ($request->email) ? $request->email : '';
                }
                if (isset($request->mobile)) {
                    $customer->mobile = ($request->mobile) ? $request->mobile : '';
                }

                if (isset($request->fcm_id)) {
                    $customer->fcm_id = ($request->fcm_id) ? $request->fcm_id : '';
                }
                if (isset($request->address)) {
                    $customer->address = ($request->address) ? $request->address : '';
                }
                if (isset($request->address)) {
                    $customer->address = ($request->address) ? $request->address : '';
                }

                if (isset($request->firebase_id)) {
                    $customer->firebase_id = ($request->firebase_id) ? $request->firebase_id : '';
                }
                if (isset($request->notification)) {
                    $customer->notification = $request->notification;
                }

                $destinationPath = public_path('images') . config('global.USER_IMG_PATH');
                if (!is_dir($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }
                // image upload


                if ($request->hasFile('profile')) {
                    // dd('in');
                    $old_image = $customer->profile;

                    $profile = $request->file('profile');
                    $imageName = microtime(true) . "." . $profile->getClientOriginalExtension();
                    if ($profile->move($destinationPath, $imageName)) {
                        $customer->profile = $imageName;
                        if ($old_image != '') {
                            if (file_exists(public_path('images') . config('global.USER_IMG_PATH') . $old_image)) {
                                unlink(public_path('images') . config('global.USER_IMG_PATH') . $old_image);
                            }
                        }
                    }
                }
                $customer->update();


                $response['error'] = false;
                $response['data'] = $customer;
            } else {
                $response['error'] = false;
                $response['message'] = "No data found!";
                $response['data'] = [];
            }
        } else {
            $response['error'] = true;
            $response['message'] = "Please fill all data and Submit";
        }

        return response()->json($response);
    }
    //* END :: update_profile   *//


    //* START :: get_user_by_id   *//
    public function get_user_by_id(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'userid' => 'required',
        ]);

        if (!$validator->fails()) {
            $id = $request->userid;

            $customer =  Customer::find($id);
            if (!empty($customer)) {

                $response['error'] = false;
                $response['data'] = $customer;
            } else {
                $response['error'] = false;
                $response['message'] = "No data found!";
                $response['data'] = [];
            }
        } else {
            $response['error'] = true;
            $response['message'] = "Please fill all data and Submit";
        }

        return response()->json($response);
    }
    //* END :: get_user_by_id   *//


    //* START :: get_property   *//
    public function get_property(Request $request)
    {
        $offset = isset($request->offset) ? $request->offset : 0;
        $limit = isset($request->limit) ? $request->limit : 10;
        $payload = JWTAuth::getPayload($this->bearerToken($request));
        $current_user = ($payload['customer_id']);
        DB::enableQueryLog();
        $property = Property::with('customer')->with('user')->with('category:id,category,image')->with('favourite')->with('assignparameter.parameter')->with('interested_users');


        $property_type = $request->property_type;  //0 : Buy 1:Rent
        $max_price = $request->max_price;
        $min_price = $request->min_price;
        $top_rated = $request->top_rated;

        $userid = $request->userid;
        $posted_since = $request->posted_since;
        $category_id = $request->category_id;
        $id = $request->id;
        $country = $request->country;
        $state = $request->state;
        $city = $request->city;

        $furnished = $request->furnished;
        $parameter_id = $request->parameter_id;
        if (isset($parameter_id)) {

            $property = $property->whereHas('assignparameter', function ($q) use ($parameter_id) {
                $q->where('parameter_id', $parameter_id);
            });
        }
        if (isset($userid)) {
            $property = $property->where('added_by', $userid);
        } else {
            $property = $property->Where('status', 1);
        }


        if (isset($max_price) && isset($min_price)) {
            $property = $property->whereBetween('price', [$min_price, $max_price]);
        }
        if (isset($property_type)) {
            if ($property_type == 0 ||  $property_type == 2) {
                $property = $property->whereIn('propery_type', [0,2]);
            }
            if ($property_type == 1 ||  $property_type == 3) {
                $property = $property->whereIn('propery_type',[1,3]);
            }
        }

        if (isset($posted_since)) {
            // 0: last_week   1: yesterday
            if ($posted_since == 0) {
                $property = $property->whereBetween(
                    'created_at',
                    [Carbon::now()->subWeek()->startOfWeek(), Carbon::now()->subWeek()->endOfWeek()]
                );
            }
            if ($posted_since == 1) {
                $property =  $property->whereDate('created_at', Carbon::yesterday());
            }
        }

        if (isset($category_id)) {
            $property = $property->where('category_id', $category_id);
        }
        if (isset($id)) {
            $property = $property->where('id', $id);
        }
        if (isset($country)) {
            $property = $property->where('country', $country);
        }
        if (isset($state)) {
            $property = $property->where('state', $state);
        }
        if (isset($city)) {
            $property = $property->where('city', $city);
        }
        if (isset($furnished)) {
            $property = $property->where('furnished', $furnished);
        }
        if (isset($request->promoted)) {
            $adv = Advertisement::select('property_id')->get();

            $ad_arr = [];
            foreach ($adv as $ad) {

                array_push($ad_arr, $ad->property_id);
            }

            $property = $property->whereIn('id', $ad_arr);
        } else {

            $response['error'] = false;
            $response['message'] = "No data found!";
            $response['data'] = [];
        }
        if (isset($request->users_promoted)) {
            $adv = Advertisement::select('property_id')->where('customer_id', $current_user)->get();

            $ad_arr = [];
            foreach ($adv as $ad) {

                array_push($ad_arr, $ad->property_id);
            }
            $property = $property->whereIn('id', $ad_arr);
        } else {

            $response['error'] = false;
            $response['message'] = "No data found!";
            $response['data'] = [];
        }
        if (isset($request->promoted)) {



            if (!($property->Has('advertisement'))) {
                $response['error'] = false;
                $response['message'] = "No data found!";
                $response['data'] = [];
                return ($response);
            }

            $property = $property->with('advertisement');
        }

        if (isset($request->search) && !empty($request->search)) {
            $search = $request->search;

            $property = $property->where(function ($query) use ($search) {
                $query->where('title', 'LIKE', "%$search%")->orwhere('address', 'LIKE', "%$search%")->orwhereHas('category', function ($query1) use ($search) {
                    $query1->where('category', 'LIKE', "%$search%");
                });
            });
        }

        $total = $property->get()->count();

        if (isset($top_rated) && $top_rated == 1) {

            $property = $property->orderBy('total_click', 'DESC');
        } else {
            $property = $property->orderBy('id', 'DESC');
        }



        $result = $property->skip($offset)->take($limit)->get();

        $query = DB::getQueryLog();


        // $rows = array();
        // $tempRow = array();
        // //return $result;
        // $count = 1;
        if (!$result->isEmpty()) {
            $property_details
                = get_property_details($result, $current_user);


            $response['error'] = false;
            $response['message'] = "Data Fetch Successfully";
            $response['total'] = $total;
            $response['data'] = $property_details;
        } else {

            $response['error'] = false;
            $response['message'] = "No data found!";
            $response['data'] = [];
        }
        return ($response);
    }
    //* END :: get_property   *//



    //* START :: post_property   *//
    public function post_property(Request $request)
    {



        $validator = Validator::make($request->all(), [
            'package_id' => 'required',
            'title_image' => 'required|file|max:3000|mimes:jpeg,png,jpg',



        ]);

        if (!$validator->fails()) {
            $payload = JWTAuth::getPayload($this->bearerToken($request));
            $current_user = ($payload['customer_id']);


            $package = UserPurchasedPackage::where('modal_id', $current_user)->with(['package' => function ($q) {
                $q->select('id', 'property_limit', 'advertisement_limit')->where('property_limit', '!=', NULL);
            }])->first();


            $arr = 0;

            $prop_count = 0;
            if (!($package)) {
                $response['error'] = false;
                $response['message'] = 'Package not found';
                return response()->json($response);
            } else {

                if (!$package->package) {

                    $response['error'] = false;
                    $response['message'] = 'Package not found for add property';
                    return response()->json($response);
                }
                $prop_count = $package->package->property_limit;

                $arr = $package->id;



                $propeerty_limit = Property::where('added_by', $current_user)->where('package_id', $request->package_id)->get();


                if (($package->used_limit_for_property) < ($prop_count) || $prop_count == 0) {

                    $validator = Validator::make($request->all(), [
                        'userid' => 'required',
                        'category_id' => 'required'
                    ]);



                    $destinationPath = public_path('images') . config('global.PROPERTY_TITLE_IMG_PATH');
                    if (!is_dir($destinationPath)) {
                        mkdir($destinationPath, 0777, true);
                    }
                    $Saveproperty = new Property();
                    $Saveproperty->category_id = $request->category_id;

                    $Saveproperty->title = $request->title;
                    $Saveproperty->description = $request->description;
                    $Saveproperty->address = $request->address;
                    $Saveproperty->client_address = (isset($request->client_address)) ? $request->client_address : '';

                    $Saveproperty->propery_type = $request->property_type;
                    $Saveproperty->price = $request->price;

                    $Saveproperty->country = (isset($request->country)) ? $request->country : '';
                    $Saveproperty->state = (isset($request->state)) ? $request->state : '';
                    $Saveproperty->city = (isset($request->city)) ? $request->city : '';
                    $Saveproperty->latitude = (isset($request->latitude)) ? $request->latitude : '';
                    $Saveproperty->longitude = (isset($request->longitude)) ? $request->longitude : '';

                    $Saveproperty->added_by = $current_user;
                    $Saveproperty->status = (isset($request->status)) ? $request->status : 0;
                    $Saveproperty->video_link = (isset($request->video_link)) ? $request->video_link : "";

                    $Saveproperty->package_id = $request->package_id;
                    if ($request->hasFile('title_image')) {


                        $profile = $request->file('title_image');
                        $imageName = microtime(true) . "." . $profile->getClientOriginalExtension();
                        $profile->move($destinationPath, $imageName);
                        $Saveproperty->title_image = $imageName;
                    } else {
                        $Saveproperty->title_image  = '';
                    }

                    // threeD_image
                    if ($request->hasFile('threeD_image')) {
                        $destinationPath = public_path('images') . config('global.3D_IMG_PATH');
                        if (!is_dir($destinationPath)) {
                            mkdir($destinationPath, 0777, true);
                        }
                        // $Saveproperty->threeD_image_hash = get_hash($request->file('threeD_image'));
                        $profile = $request->file('threeD_image');
                        $imageName = microtime(true) . "." . $profile->getClientOriginalExtension();
                        $profile->move($destinationPath, $imageName);
                        $Saveproperty->threeD_image = $imageName;
                    } else {
                        $Saveproperty->threeD_image  = '';
                    }
                    // print_r(json_encode($request->parameters));
                    $Saveproperty->save();
                    $package->used_limit_for_property
                        =  $package->used_limit_for_property + 1;
                    $package->save();
                    $destinationPathforparam = public_path('images') . config('global.PARAMETER_IMAGE_PATH');
                    if (!is_dir($destinationPath)) {
                        mkdir($destinationPath, 0777, true);
                    }


                    if ($request->parameters) {
                        foreach ($request->parameters as $key => $parameter) {

                            // dd($parameter['value']);
                            $AssignParameters = new AssignParameters();

                            $AssignParameters->modal()->associate($Saveproperty);

                            $AssignParameters->parameter_id = $parameter['parameter_id'];
                            // function isUrl($value)
                            // {
                            //     return preg_match('/^https?:\/\/[^\s]+$/i', $value);
                            // }

                            if ($request->hasFile('parameters.' . $key . '.value')) {

                                $profile = $request->file('parameters.' . $key . '.value');
                                $imageName = microtime(true) . "." . $profile->getClientOriginalExtension();
                                $profile->move($destinationPathforparam, $imageName);
                                $AssignParameters->value = $imageName;
                            } else if (filter_var($parameter['value'], FILTER_VALIDATE_URL)) {


                                $ch = curl_init($parameter['value']);
                                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                $fileContents = curl_exec($ch);
                                curl_close($ch);

                                $filename
                                    = microtime(true) . basename($parameter['value']);

                                file_put_contents($destinationPathforparam . '/' . $filename, $fileContents);
                                $AssignParameters->value = $filename;
                            } else {
                                $AssignParameters->value = $parameter['value'];
                            }

                            $AssignParameters->save();
                        }
                    }

                    /// START :: UPLOAD GALLERY IMAGE

                    $FolderPath = public_path('images') . config('global.PROPERTY_GALLERY_IMG_PATH');
                    if (!is_dir($FolderPath)) {
                        mkdir($FolderPath, 0777, true);
                    }


                    $destinationPath = public_path('images') . config('global.PROPERTY_GALLERY_IMG_PATH') . "/" . $Saveproperty->id;
                    if (!is_dir($destinationPath)) {
                        mkdir($destinationPath, 0777, true);
                    }
                    if ($request->hasfile('gallery_images')) {


                        foreach ($request->file('gallery_images') as $file) {


                            $name = time() . rand(1, 100) . '.' . $file->extension();
                            $file->move($destinationPath, $name);

                            $gallary_image = new PropertyImages();
                            $gallary_image->image = $name;
                            $gallary_image->propertys_id = $Saveproperty->id;

                            $gallary_image->save();
                        }
                    }

                    /// END :: UPLOAD GALLERY IMAGE

                    $result = Property::with('customer')->with('user')->with('category:id,category,image')->with('favourite')->with('assignparameter.parameter')->with('interested_users')->where('id', $Saveproperty->id)->get();
                    $property_details = get_property_details($result);

                    $response['error'] = false;
                    $response['message'] = 'Property Post Succssfully';
                    $response['data'] = $property_details;
                } else {
                    $response['error'] = false;
                    $response['message'] = 'Package Limit is over';
                }
            }
        } else {
            $response['error'] = true;
            $response['message'] = $validator->errors()->first();
        }
        return response()->json($response);
    }
    //* END :: post_property   *//



    //* START :: update_post_property   *//
    /// This api use for update and delete  property
    public function update_post_property(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'action_type' => 'required'
        ]);

        if (!$validator->fails()) {
            $id = $request->id;
            $action_type = $request->action_type;
            $property = Property::find($id);
            if (($property)) {
                // 0: Update 1: Delete
                if ($action_type == 0) {
                    $destinationPath = public_path('images') . config('global.PROPERTY_TITLE_IMG_PATH');
                    if (!is_dir($destinationPath)) {
                        mkdir($destinationPath, 0777, true);
                    }

                    if (isset($request->category_id)) {
                        $property->category_id = $request->category_id;
                    }

                    if (isset($request->title)) {
                        $property->title = $request->title;
                    }

                    if (isset($request->description)) {
                        $property->description = $request->description;
                    }

                    if (isset($request->address)) {
                        $property->address = $request->address;
                    }

                    if (isset($request->client_address)) {
                        $property->client_address = $request->client_address;
                    }

                    if (isset($request->propery_type)) {
                        $property->propery_type = $request->propery_type;
                    }

                    if (isset($request->price)) {
                        $property->price = $request->price;
                    }


                    if (isset($request->country)) {
                        $property->country = $request->country;
                    }
                    if (isset($request->state)) {
                        $property->state = $request->state;
                    }
                    if (isset($request->city)) {
                        $property->city = $request->city;
                    }
                    if (isset($request->status)) {
                        $property->status = $request->status;
                    }
                    if (isset($request->latitude)) {
                        $property->latitude = $request->latitude;
                    }
                    if (isset($request->longitude)) {
                        $property->longitude = $request->longitude;
                    }


                    if ($request->hasFile('title_image')) {
                        $profile = $request->file('title_image');
                        $imageName = microtime(true) . "." . $profile->getClientOriginalExtension();
                        $profile->move($destinationPath, $imageName);


                        if ($property->title_image != '') {
                            if (file_exists(public_path('images') . config('global.PROPERTY_TITLE_IMG_PATH') .  $property->title_image)) {
                                unlink(public_path('images') . config('global.PROPERTY_TITLE_IMG_PATH') . $property->title_image);
                            }
                        }
                        $property->title_image = $imageName;
                    }
                    if ($request->parameters) {
                        $destinationPathforparam = public_path('images') . config('global.PARAMETER_IMAGE_PATH');
                        if (!is_dir($destinationPath)) {
                            mkdir($destinationPath, 0777, true);
                        }
                        // print_r($request->parameters);

                        foreach ($request->parameters as $key => $parameter) {
                            // print_r($parameter);
                            // echo $property->id;
                            // return false;
                            $AssignParameters = AssignParameters::where('modal_id', $property->id)->where('parameter_id', $parameter['parameter_id'])->pluck('id');
                            // echo $AssignParameters[0] . 'idddd';
                            $update_data = AssignParameters::find($AssignParameters[0]);
                            if ($update_data) {
                                // print_r($update_data->toArray());
                                // $AssignParameters->modal()->associate($property);

                                // $AssignParameters->parameter_id = $parameter['parameter_id'];

                                if ($request->hasFile('parameters.' . $key . '.value')) {

                                    $profile = $request->file('parameters.' . $key . '.value');
                                    $imageName = microtime(true) . "." . $profile->getClientOriginalExtension();
                                    $profile->move($destinationPathforparam, $imageName);
                                    $update_data->value = $imageName;
                                }
                                // if (isUrl($parameter['value'])) {
                                else if (filter_var($parameter['value'], FILTER_VALIDATE_URL)) {
                                    // dd('stop');


                                    $ch = curl_init($parameter['value']);
                                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                                    $fileContents = curl_exec($ch);
                                    curl_close($ch);

                                    // $fileContents = file_get_contents($parameter['value']);
                                    $filename
                                        = microtime(true) . basename($parameter['value']);
                                    // dd($filename);
                                    file_put_contents($destinationPathforparam . '/' . $filename, $fileContents);
                                    $update_data->value = $filename;
                                } else {
                                    $update_data->value = $parameter['value'];
                                }


                                $update_data->save();
                            }
                        }

                        // $AssignParameters->save();
                    }

                    $property->update();
                    $update_property = Property::with('customer')->with('user')->with('category:id,category,image')->with('favourite')->with('assignparameter.parameter')->with('interested_users')->where('id', $request->id)->get();


                    /// START :: UPLOAD GALLERY IMAGE

                    $FolderPath = public_path('images') . config('global.PROPERTY_GALLERY_IMG_PATH');
                    if (!is_dir($FolderPath)) {
                        mkdir($FolderPath, 0777, true);
                    }

                    $destinationPath = public_path('images') . config('global.PROPERTY_GALLERY_IMG_PATH') . "/" . $property->id;
                    if (!is_dir($destinationPath)) {
                        mkdir($destinationPath, 0777, true);
                    }
                    if ($request->hasfile('gallery_images')) {
                        foreach ($request->file('gallery_images') as $file) {
                            $name = time() . rand(1, 100) . '.' . $file->extension();
                            $file->move($destinationPath, $name);

                            PropertyImages::create([
                                'image' => $name,
                                'propertys_id' => $property->id,


                            ]);
                        }
                    }

                    /// END :: UPLOAD GALLERY IMAGE
                    $payload = JWTAuth::getPayload($this->bearerToken($request));
                    $current_user = ($payload['customer_id']);
                    $property_details = get_property_details($update_property, $current_user);
                    $response['error'] = false;
                    $response['message'] = 'Property Update Succssfully';
                    $response['data'] = $property_details;
                } elseif ($action_type == 1) {
                    if ($property->delete()) {
                        if ($property->title_image != '') {
                            if (file_exists(public_path('images') . config('global.PROPERTY_TITLE_IMG_PATH') . $property->title_image)) {
                                unlink(public_path('images') . config('global.PROPERTY_TITLE_IMG_PATH') . $property->title_image);
                            }
                        }
                        foreach ($property->gallery as $row) {
                            if (PropertyImages::where('id', $row->id)->delete()) {
                                if ($row->image_url != '') {
                                    if (file_exists(public_path('images') . config('global.PROPERTY_GALLERY_IMG_PATH') . $property->id . "/" . $row->image)) {
                                        unlink(public_path('images') . config('global.PROPERTY_GALLERY_IMG_PATH') . $property->id . "/" . $row->image);
                                    }
                                }
                            }
                        }
                        rmdir(public_path('images') . config('global.PROPERTY_GALLERY_IMG_PATH') . $property->id);

                        Notifications::where('propertys_id', $id)->delete();


                        $slider = Slider::where('propertys_id', $id)->get();

                        foreach ($slider as $row) {
                            $image = $row->image;

                            if (Slider::where('id', $row->id)->delete()) {
                                if (file_exists(public_path('images') . config('global.SLIDER_IMG_PATH') . $image)) {
                                    unlink(public_path('images') . config('global.SLIDER_IMG_PATH') . $image);
                                }
                            }
                        }

                        $response['error'] = false;
                        $response['message'] =  'Delete Successfully';
                    } else {
                        $response['error'] = true;
                        $response['message'] = 'something wrong';
                    }
                }
            } else {
                $response['error'] = true;
                $response['message'] = 'No Data Found';
            }
        } else {
            $response['error'] = true;
            $response['message'] = "Please fill all data and Submit";
        }

        return response()->json($response);
    }
    //* END :: update_post_property   *//


    //* START :: remove_post_images   *//
    public function remove_post_images(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required'
        ]);

        if (!$validator->fails()) {
            $id = $request->id;
            $getImage = PropertyImages::where('id', $id)->first();
            $image = $getImage->image;
            $propertys_id =  $getImage->propertys_id;

            if (PropertyImages::where('id', $id)->delete()) {
                if (file_exists(public_path('images') . config('global.PROPERTY_GALLERY_IMG_PATH') . $propertys_id . "/" . $image)) {
                    unlink(public_path('images') . config('global.PROPERTY_GALLERY_IMG_PATH') . $propertys_id . "/" . $image);
                }
                $response['error'] = false;
            } else {
                $response['error'] = true;
            }

            $countImage = PropertyImages::where('propertys_id', $propertys_id)->get();
            if ($countImage->count() == 0) {
                rmdir(public_path('images') . config('global.PROPERTY_GALLERY_IMG_PATH') . $propertys_id);
            }

            $response['error'] = false;
            $response['message'] = 'Property Post Succssfully';
        } else {
            $response['error'] = true;
            $response['message'] = "Please fill all data and Submit";
        }

        return response()->json($response);
    }
    //* END :: remove_post_images   *//



    //* START :: set_property_inquiry   *//
    public function set_property_inquiry(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'action_type' => 'required',

        ]);

        if (!$validator->fails()) {
            $action_type = $request->action_type;  ////0: add   1:update


            if ($action_type == 0) {
                //add inquiry
                $validator = Validator::make($request->all(), [
                    'property_id' => 'required',

                ]);
                $payload = JWTAuth::getPayload($this->bearerToken($request));
                $current_user = ($payload['customer_id']);
                if (!$validator->fails()) {
                    $PropertysInquiry = PropertysInquiry::where('propertys_id', $request->property_id)->where('customers_id', $current_user)->first();
                    if (empty($PropertysInquiry)) {
                        PropertysInquiry::create([
                            'propertys_id' => $request->property_id,
                            'customers_id' => $current_user,
                            'status'  => '0'
                        ]);
                        $response['error'] = false;
                        $response['message'] = 'Inquiry Send Succssfully';
                    } else {
                        $response['error'] = true;
                        $response['message'] = 'Request Already Submitted';
                    }
                } else {
                    $response['error'] = true;
                    $response['message'] = "Please fill all data and Submit";
                }
            } elseif ($action_type == 1) {
                //update inquiry
                $validator = Validator::make($request->all(), [
                    'id' => 'required',
                    'status' => 'required',

                ]);

                if (!$validator->fails()) {
                    $id = $request->id;
                    $propertyInquiry = PropertysInquiry::find($id);
                    $propertyInquiry->status = $request->status;
                    $propertyInquiry->update();


                    $response['error'] = false;
                    $response['message'] = 'Inquiry Update Succssfully';
                } else {
                    $response['error'] = true;
                    $response['message'] = "Please fill all data and Submit";
                }
            }
        } else {
            $response['error'] = true;
            $response['message'] = "Please fill all data and Submit";
        }



        return response()->json($response);
    }
    //* END :: set_property_inquiry   *//




    //* START :: get_notification_list   *//
    public function get_notification_list(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'userid' => 'required',
        ]);

        if (!$validator->fails()) {
            $id = $request->userid;

            $Notifications =  Notifications::whereRaw("FIND_IN_SET($id,customers_id)")->orwhere('send_type', '1')->orderBy('id', 'ASC')->get();


            if (!$Notifications->isEmpty()) {
                for ($i = 0; $i < count($Notifications); $i++) {
                    $Notifications[$i]->created = $Notifications[$i]->created_at->diffForHumans();
                    $Notifications[$i]->image  = ($Notifications[$i]->image != '') ? url('') . config('global.IMG_PATH') . config('global.NOTIFICATION_IMG_PATH') . $Notifications[$i]->image : '';
                }
                $response['error'] = false;
                $response['data'] = $Notifications;
            } else {
                $response['error'] = false;
                $response['message'] = "No data found!";
                $response['data'] = [];
            }
        } else {
            $response['error'] = true;
            $response['message'] = "Please fill all data and Submit";
        }

        return response()->json($response);
    }
    //* END :: get_notification_list   *//



    //* START :: get_property_inquiry   *//
    public function get_property_inquiry(Request $request)
    {



        $offset = isset($request->offset) ? $request->offset : 0;
        $limit = isset($request->limit) ? $request->limit : 10;


        $payload = JWTAuth::getPayload($this->bearerToken($request));
        $current_user = ($payload['customer_id']);
        $propertyInquiry = PropertysInquiry::with('property')->where('customers_id', $current_user);
        // dd($propertyInquiry->toArray());
        $total = $propertyInquiry->get()->count();
        $result = $propertyInquiry->orderBy('id', 'ASC')->skip($offset)->take($limit)->get();


        $rows = array();
        $tempRow = array();
        $count = 1;

        if (!$result->isEmpty()) {

            foreach ($result as $key => $row) {
                // print_r($row->toArray());
                $tempRow['id'] = $row->id;
                $tempRow['propertys_id'] = $row->propertys_id;
                $tempRow['customers_id'] = $row->customers_id;
                $tempRow['status'] = $row->status;
                $tempRow['created_at'] = $row->created_at;
                $tempRow['property']['id'] = $row['property']->id;
                $tempRow['property']['title'] = $row['property']->title;
                $tempRow['property']['price'] = $row['property']->price;
                $tempRow['property']['category'] = $row['property']->category;



                $tempRow['property']['description'] = $row['property']->description;
                $tempRow['property']['address'] = $row['property']->address;
                $tempRow['property']['client_address'] = $row['property']->client_address;
                $tempRow['property']['propery_type'] = ($row['property']->propery_type == '0') ? 'Sell' : 'Rent';
                $tempRow['property']['title_image'] = $row['property']->title_image;

                $tempRow['property']['threeD_image'] = $row['property']->threeD_image;

                $tempRow['property']['post_created'] = $row['property']->created_at->diffForHumans();
                $tempRow['property']['gallery'] = $row['property']->gallery;
                $tempRow['property']['total_view'] = $row['property']->total_click;
                $tempRow['property']['status'] = $row['property']->status;
                $tempRow['property']['state'] = $row['property']->state;
                $tempRow['property']['city'] = $row['property']->city;
                $tempRow['property']['country'] = $row['property']->country;
                $tempRow['property']['latitude'] = $row['property']->latitude;
                $tempRow['property']['longitude'] = $row['property']->longitude;
                $tempRow['property']['added_by'] = $row['property']->added_by;
                foreach ($row->property->assignParameter as $key => $res) {


                    $tempRow['property']["parameters"][$key] = $res->parameter;

                    $tempRow['property']["parameters"][$key]["value"] = $res->value;
                }


                $rows[] = $tempRow;
                // $parameters[] = $arr;
                $count++;
            }

            $response['error'] = false;
            $response['message'] = "Data Fetch Successfully";
            $response['total'] = $total;
            $response['data'] = $rows;
        } else {
            $response['error'] = false;
            $response['message'] = "No data found!";
            $response['data'] = [];
        }


        return response()->json($response);
    }
    //* END :: get_property_inquiry   *//



    //* START :: set_property_total_click   *//
    public function set_property_total_click(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'property_id' => 'required',

        ]);

        if (!$validator->fails()) {
            $property_id = $request->property_id;


            $Property = Property::find($property_id);
            $Property->increment('total_click');

            $response['error'] = false;
            $response['message'] = 'Update Succssfully';
        } else {
            $response['error'] = true;
            $response['message'] = "Please fill all data and Submit";
        }

        return response()->json($response);
    }
    //* END :: set_property_total_click   *//


    //* START :: delete_user   *//
    public function delete_user(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'userid' => 'required',

        ]);

        if (!$validator->fails()) {
            $userid = $request->userid;

            Customer::find($userid)->delete();
            Property::where('added_by', $userid)->delete();
            PropertysInquiry::where('customers_id', $userid)->delete();

            $response['error'] = false;
            $response['message'] = 'Delete Succssfully';
        } else {
            $response['error'] = true;
            $response['message'] = "Please fill all data and Submit";
        }

        return response()->json($response);
    }
    //* END :: delete_user   *//
    public function bearerToken($request)
    {
        $header = $request->header('Authorization', '');
        if (Str::startsWith($header, 'Bearer ')) {
            return Str::substr($header, 7);
        }
    }
    //*START :: add favoutite *//
    public function add_favourite(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'type' => 'required',
            'property_id' => 'required',


        ]);

        if (!$validator->fails()) {
            //add favourite
            $payload = JWTAuth::getPayload($this->bearerToken($request));
            $current_user = ($payload['customer_id']);
            if ($request->type == 1) {


                $fav_prop = Favourite::where('user_id', $current_user)->where('property_id', $request->property_id)->get();

                if (count($fav_prop) > 0) {
                    $response['error'] = false;
                    $response['message'] = "Property already add to favourite";
                    return response()->json($response);
                }
                $favourite = new Favourite();
                $favourite->user_id = $current_user;
                $favourite->property_id = $request->property_id;
                $favourite->save();
                $response['error'] = false;
                $response['message'] = "Property add to Favourite add successfully";
            }
            //delete favourite
            if ($request->type == 0) {
                Favourite::where('property_id', $request->property_id)->where('user_id', $current_user)->delete();

                $response['error'] = false;
                $response['message'] = "Property remove from Favourite  successfully";
            }
        } else {
            $response['error'] = true;
            $response['message'] = $validator->errors()->first();
        }


        return response()->json($response);
    }
    //*END :: add favoutite *//
    //*START :: delete favoutite *//
    // public function delete_favourite(Request $request)
    // {
    //     $payload = JWTAuth::getPayload($this->bearerToken($request));
    //     $current_user = ($payload['customer_id']);
    //     $validator = Validator::make($request->all(), [

    //         'property_id' => 'required',

    //     ]);
    //     if (!$validator->fails()) {
    //         Favourite::where('property_id', $request->property_id)->where('user_id', $current_user)->delete();

    //         $response['error'] = false;
    //         $response['message'] = "Property remove from Favourite  successfully";
    //     } else {
    //         $response['error'] = true;
    //         $response['message'] = "Please fill all data and Submit";
    //     }
    //     return response()->json($response);
    // }
    public function get_articles(Request $request)
    {
        $offset = isset($request->offset) ? $request->offset : 0;
        $limit = isset($request->limit) ? $request->limit : 10;

        $article = Article::select('id', 'image', 'title', 'description');

        $total = $article->get()->count();
        $result = $article->orderBy('id', 'ASC')->skip($offset)->take($limit)->get();
        if (!$result->isEmpty()) {
            foreach ($article as $row) {

                if (filter_var($row->image, FILTER_VALIDATE_URL) === false) {
                    // echo "in";
                    $row->image = ($row->image != '') ? url('') . config('global.IMG_PATH') . config('global.ARTICLE_IMG_PATH') . $row->image : '';
                } else {
                    // dd("in else");

                    $row->image = $row->image;
                }
            }
            $response['error'] = false;
            $response['message'] = "Data Fetch Successfully";
            $response['total'] = $total;
            $response['data'] = $result;
        } else {
            $response['error'] = false;
            $response['message'] = "No data found!";
            $response['data'] = [];
        }
        return response()->json($response);
    }
    public function store_advertisement(Request $request)
    {
        // dd($request->toArray());
        $validator = Validator::make($request->all(), [
            'type' => 'required',


            'property_id' => 'required',
            'package_id' => 'required',



        ]);
        if (!$validator->fails()) {


            $payload = JWTAuth::getPayload($this->bearerToken($request));
            $current_user = ($payload['customer_id']);


            $userpackage = UserPurchasedPackage::where('modal_id', $current_user)->with(['package' => function ($q) {
                $q->select('id', 'property_limit', 'advertisement_limit')->where('advertisement_limit', '!=', NULL);
            }])->first();


            $arr = 0;

            $prop_count = 0;
            if (!($userpackage)) {
                $response['error'] = false;
                $response['message'] = 'Package not found';
                return response()->json($response);
            } else {

                if (!$userpackage->package) {

                    $response['error'] = false;
                    $response['message'] = 'Package not found for add property';
                    return response()->json($response);
                }
                $advertisement_count = $userpackage->package->advertisement_limit;

                $arr = $userpackage->id;



                $advertisement_limit = Advertisement::where('customer_id', $current_user)->where('package_id', $request->package_id)->get();


                if ($userpackage->used_limit_for_advertisement < ($advertisement_count) || $advertisement_count == 0) {

                    $payload = JWTAuth::getPayload($this->bearerToken($request));
                    $current_user = ($payload['customer_id']);

                    $package = Package::where('advertisement_limit', '!=', NULL)->find($request->package_id);

                    $adv = new Advertisement();

                    $adv->start_date = Carbon::now();
                    if ($package) {
                        if (isset($request->end_date)) {
                            $adv->end_date = $request->end_date;
                        } else {
                            $adv->end_date = Carbon::now()->addDays($package->duration);
                        }


                        $adv->package_id = $package->id;

                        $adv->type = $request->type;
                        $adv->property_id = $request->property_id;
                        $adv->customer_id = $current_user;
                        $adv->is_enable = true;
                        $adv->status = 0;

                        $destinationPath = public_path('images') . config('global.ADVERTISEMENT_IMAGE_PATH');
                        if (!is_dir($destinationPath)) {
                            mkdir($destinationPath, 0777, true);
                        }

                        if ($request->type == 'Slider') {




                            $destinationPath_slider = public_path('images') . config('global.SLIDER_IMG_PATH');

                            if (!is_dir($destinationPath_slider)) {
                                mkdir($destinationPath_slider, 0777, true);
                            }
                            $slider = new Slider();

                            if ($request->hasFile('image')) {


                                $file = $request->file('image');


                                $name = time() . rand(1, 100) . '.' . $file->extension();

                                $file->move($destinationPath_slider, $name);
                                $sliderimageName = microtime(true) . "." . $file->getClientOriginalExtension();


                                $slider->image = $sliderimageName;
                            } else {
                                $slider->image = '';
                            }
                            $slider->category_id = isset($request->category_id) ? $request->category_id : 0;
                            $slider->propertys_id = $request->property_id;
                            $slider->save();
                        }
                        $result = Property::with('customer')->with('user')->with('category:id,category,image')->with('favourite')->with('assignparameter.parameter')->with('interested_users')->where('id', $request->property_id)->get();
                        $property_details = get_property_details($result);

                        $adv->image = "";
                        $adv->save();
                        $userpackage->used_limit_for_advertisement =  $userpackage->used_limit_for_advertisement + 1;

                        $userpackage->save();
                        $response['error'] = false;
                        $response['message'] = "Advertisement add successfully";
                        $response['data'] = $property_details;
                    } else {
                        $response['error'] = false;
                        $response['message'] = "Package not found";
                        return response()->json($response);
                    }
                } else {
                    $response['error'] = false;
                    $response['message'] = "Package Limit is over";
                    return response()->json($response);
                }
            }
        } else {
            $response['error'] = true;
            $response['message'] = $validator->errors()->first();
        }
        return response()->json($response);
    }
    public function get_advertisement(Request $request)
    {
        // $validator = Validator::make($request->all(), [
        //     'start_date' => 'required',
        //     'end_date' => 'required',

        // ]);

        // if (!$validator->fails()) {



        $offset = isset($request->offset) ? $request->offset : 0;
        $limit = isset($request->limit) ? $request->limit : 10;

        $article = Article::select('id', 'image', 'title', 'description');


        $date = date('Y-m-d');
        DB::enableQueryLog();
        $adv = Advertisement::select('id', 'image', 'category_id', 'property_id', 'type', 'customer_id', 'is_enable', 'status')->with('customer:id,name')->where('end_date', '>', $date);
        if (isset($request->customer_id)) {
            $adv->where('customer_id', $request->customer_id);
        }

        $total = $adv->get()->count();
        $result = $adv->orderBy('id', 'ASC')->skip($offset)->take($limit)->get();


        if (!$result->isEmpty()) {
            foreach ($adv as $row) {
                if (filter_var($row->image, FILTER_VALIDATE_URL) === false) {
                    $row->image = ($row->image != '') ? url('') . config('global.IMG_PATH') . config('global.ADVERTISEMENT_IMAGE_PATH') . $row->image : '';
                } else {
                    $row->image = $row->image;
                }
            }
            $response['error'] = false;
            $response['message'] = "Data Fetch Successfully";
            $response['data'] = $result;
        } else {
            $response['error'] = false;
            $response['message'] = "No data found!";
            $response['data'] = [];
        }

        // else {
        //     $response['error'] = true;
        //     $response['message'] = $validator->errors()->first();
        // }
        return response()->json($response);
    }
    public function get_package(Request $request)
    {
        // $validator = Validator::make($request->all(), [
        //     'start_date' => 'required',
        //     'end_date' => 'required',

        // ]);

        // if (!$validator->fails()) {
        $date = date('Y-m-d');
        DB::enableQueryLog();
        $package = Package::where('status', 1)->orderBy('id', 'ASC')->where('name', '!=', 'Trial Package')->get();
        // dd(DB::getQueryLog());
        if (!$package->isEmpty()) {

            $response['error'] = false;
            $response['message'] = "Data Fetch Successfully";
            $response['data'] = $package;
        } else {
            $response['error'] = false;
            $response['message'] = "No data found!";
            $response['data'] = [];
        }
        // else {
        //     $response['error'] = true;
        //     $response['message'] = "Please fill all data and Submit";
        // }
        return response()->json($response);
    }
    public function user_purchase_package(Request $request)
    {


        $start_date =  Carbon::now();



        $validator = Validator::make($request->all(), [

            'package_id' => 'required',

        ]);

        if (!$validator->fails()) {
            $payload = JWTAuth::getPayload($this->bearerToken($request));
            $current_user = ($payload['customer_id']);
            if (isset($request->flag)) {
                $user_exists = UserPurchasedPackage::where('modal_id', $current_user)->get();
                if ($user_exists) {
                    UserPurchasedPackage::where('modal_id', $current_user)->delete();
                }
            }

            $package = Package::find($request->package_id);
            $user = Customer::find($current_user);
            $data_exists = UserPurchasedPackage::where('modal_id', $current_user)->get();
            if (count($data_exists) == 0 && $package) {
                $user_package = new UserPurchasedPackage();
                $user_package->modal()->associate($user);
                $user_package->package_id = $request->package_id;
                $user_package->start_date = $start_date;
                $user_package->end_date = $package->duratio != 0 ? Carbon::now()->addDays($package->duration) : NULL;
                $user_package->save();

                $user->subscription = 1;
                $user->update();

                $response['error'] = false;
                $response['message'] = "purchased package  add successfully";
            } else {
                $response['error'] = false;
                $response['message'] = "data already exists or package not found or add flag for add new package";
            }
        } else {
            $response['error'] = true;
            $response['message'] = "Please fill all data and Submit";
        }
        return response()->json($response);
    }
    public function get_favourite_property(Request $request)
    {



        $offset = isset($request->offset) ? $request->offset : 0;
        $limit = isset($request->limit) ? $request->limit : 25;


        $payload = JWTAuth::getPayload($this->bearerToken($request));
        $current_user = ($payload['customer_id']);
        \DB::enableQueryLog(); // Enable query log


        $favourite = Favourite::where('user_id', $current_user)->select('property_id')->get();
        // dd($favourite);
        $arr = array();
        foreach ($favourite as $p) {
            $arr[] =  $p->property_id;
        }

        $property_details = Property::whereIn('id', $arr)->with('category:id,category,image')->with('assignparameter.parameter');



        $result = $property_details->orderBy('id', 'ASC')->skip($offset)->take($limit)->get();
        // dd(\DB::getQueryLog());
        // $favourite = Favourite::where('property_id', $row->id)->where('user_id', $current_user)->get();
        // if (count($favourite) != 0) {
        //     $tempRow['is_favourite'] = 1;
        // } else {
        //     $tempRow['is_favourite'] = 0;
        // }

        $total = $result->count();

        if (!$result->isEmpty()) {

            $response['error'] = false;
            $response['message'] = "Data Fetch Successfully";
            $response['data'] = $result;
            $response['total'] = $total;
        } else {
            $response['error'] = false;
            $response['message'] = "No data found!";
            $response['data'] = [];
        }
        return response()->json($response);
    }
    public function delete_advertisement(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',

        ]);

        if (!$validator->fails()) {
            $adv = Advertisement::find($request->id);
            if (!$adv) {
                $response['error'] = false;
                $response['message'] = "Data not found";
            } else {

                $adv->delete();
                $response['error'] = false;
                $response['message'] = "Advertisement Deleted successfully";
            }
        } else {
            $response['error'] = true;
            $response['message'] = $validator->errors()->first();
        }
        return response()->json($response);
    }
    public function interested_users(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'property_id' => 'required',
            'type' => 'required'


        ]);
        if (!$validator->fails()) {

            $payload = JWTAuth::getPayload($this->bearerToken($request));
            $current_user = ($payload['customer_id']);

            $interested_user = InterestedUser::where('customer_id', $current_user)->where('property_id', $request->property_id);

            if ($request->type == 1) {

                if (count($interested_user->get()) > 0) {
                    $response['error'] = false;
                    $response['message'] = "already added to interested users ";
                } else {
                    $interested_user = new InterestedUser();


                    $interested_user->property_id = $request->property_id;
                    $interested_user->customer_id = $current_user;
                    $interested_user->save();
                    $response['error'] = false;
                    $response['message'] = "Interested Users added successfully";
                }
            }
            if ($request->type == 0) {

                if (count($interested_user->get()) == 0) {
                    $response['error'] = false;
                    $response['message'] = "No data found to delete";
                } else {
                    $interested_user->delete();

                    $response['error'] = false;
                    $response['message'] = "Interested Users removed  successfully";
                }
            }
        } else {
            $response['error'] = true;
            $response['message'] = $validator->errors()->first();
        }
        return response()->json($response);
    }
    public function delete_inquiry(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'id' => 'required',

        ]);

        if (!$validator->fails()) {
            $adv = PropertysInquiry::where('status', 0)->find($request->id);
            if (!$adv) {
                $response['error'] = false;
                $response['message'] = "Data not found";
            } else {

                $adv->delete();
                $response['error'] = false;
                $response['message'] = "Property inquiry Deleted successfully";
            }
        } else {
            $response['error'] = true;
            $response['message'] = $validator->errors()->first();
        }
        return response()->json($response);
    }
    public function user_interested_property(Request $request)
    {



        $offset = isset($request->offset) ? $request->offset : 0;
        $limit = isset($request->limit) ? $request->limit : 25;


        $payload = JWTAuth::getPayload($this->bearerToken($request));
        $current_user = ($payload['customer_id']);
        \DB::enableQueryLog(); // Enable query log


        $favourite = InterestedUser::where('customer_id', $current_user)->select('property_id')->get();
        // dd($favourite);
        $arr = array();
        foreach ($favourite as $p) {
            $arr[] =  $p->property_id;
        }

        $property_details = Property::whereIn('id', $arr)->with('category:id,category')->with('assignparameter.parameter');



        $result = $property_details->orderBy('id', 'ASC')->skip($offset)->take($limit)->get();
        // dd(\DB::getQueryLog());

        $total = $result->count();

        if (!$result->isEmpty()) {
            foreach ($property_details as $row) {
                if (filter_var($row->image, FILTER_VALIDATE_URL) === false) {
                    $row->image = ($row->image != '') ? url('') . config('global.IMG_PATH') . config('global.PROPERTY_TITLE_IMG_PATH') . $row->image : '';
                } else {
                    $row->image = $row->image;
                }
            }
            $response['error'] = false;
            $response['message'] = "Data Fetch Successfully";
            $response['data'] = $result;
            $response['total'] = $total;
        } else {
            $response['error'] = false;
            $response['message'] = "No data found!";
            $response['data'] = [];
        }
        return response()->json($response);
    }
    public function get_limits(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required',

        ]);
        if (!$validator->fails()) {
            $payload = JWTAuth::getPayload($this->bearerToken($request));
            $current_user = ($payload['customer_id']);
            $package = UserPurchasedPackage::where('modal_id', $current_user)->where('package_id', $request->id)->with(['package' => function ($q) {
                $q->select('id', 'property_limit', 'advertisement_limit');
            }])->first();
            if (!$package) {
                $response['error'] = true;
                $response['message'] = "package not found";
                return response()->json($response);
            }
            $arr = 0;
            $adv_count = 0;
            $prop_count = 0;
            // foreach ($package as $p) {

            ($adv_count = $package->package->advertisement_limit == 0 ? "Unlimited" : $package->package->advertisement_limit);
            ($prop_count = $package->package->property_limit == 0 ? "Unlimited" : $package->package->property_limit);

            ($arr = $package->id);
            // }

            $advertisement_limit = Advertisement::where('customer_id', $current_user)->where('package_id', $request->id)->get();
            // DB::enableQueryLog();

            $propeerty_limit = Property::where('added_by', $current_user)->where('package_id', $request->id)->get();


            $response['total_limit_of_advertisement'] = ($adv_count);
            $response['total_limit_of_property'] = ($prop_count);


            $response['used_limit_of_advertisement'] = $package->used_limit_for_advertisement;
            $response['used_limit_of_property'] = $package->used_limit_for_property;
        } else {
            $response['error'] = true;
            $response['message'] = $validator->errors()->first();
        }
        return response()->json($response);
    }
    public function get_languages(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'language_code' => 'required',

        ]);
        if (!$validator->fails()) {

            DB::enableQueryLog();

            $language = Language::where('code', $request->language_code);

            $result = $language->get();

            //  dd(DB::getQueryLog());

            if ($result) {
                $response['error'] = false;
                $response['message'] = "Data Fetch Successfully";



                $response['data'] = $result;
            } else {
                $response['error'] = false;
                $response['message'] = "No data found!";
                $response['data'] = [];
            }
        } else {
            $response['error'] = true;
            $response['message'] = $validator->errors()->first();
        }
        return response()->json($response);
    }
    public function get_payment_details(Request $request)
    {
        $payload = JWTAuth::getPayload($this->bearerToken($request));
        $current_user = ($payload['customer_id']);

        $payment = Payments::where('customer_id', $current_user);

        $result = $payment->get();

        //  dd(DB::getQueryLog());

        if (count($result)) {
            $response['error'] = false;
            $response['message'] = "Data Fetch Successfully";



            $response['data'] = $result;
        } else {
            $response['error'] = false;
            $response['message'] = "No data found!";
            $response['data'] = [];
        }
        return response()->json($response);
    }



    public function paypal(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'package_id' => 'required',
            'amount' => 'required'

        ]);
        if (!$validator->fails()) {
            $payload = JWTAuth::getPayload($this->bearerToken($request));
            $current_user = ($payload['customer_id']);
            $paypal = new Paypal();
            // url('') . config('global.IMG_PATH')
            $returnURL = url('api/app_payment_status');
            $cancelURL = url('api/app_payment_status');
            $notifyURL = url('webhook/paypal');
            // $package_id = $request->package_id;
            $package_id = $request->package_id;
            // Get product data from the database

            // Get current user ID from the session
            $paypal->add_field('return', $returnURL);
            $paypal->add_field('cancel_return', $cancelURL);
            $paypal->add_field('notify_url', $notifyURL);
            $custom_data = $package_id . ',' . $current_user;

            // // Add fields to paypal form


            $paypal->add_field('item_name', "package");
            $paypal->add_field('custom_id', json_encode($custom_data));

            $paypal->add_field('custom', ($custom_data));

            $paypal->add_field('amount', $request->amount);

            // Render paypal form
            $paypal->paypal_auto_form();
        } else {
            $response['error'] = true;
            $response['message'] = "Please fill all data and Submit";
        }
    }
    public function app_payment_status(Request $request)
    {

        $paypalInfo = $request->all();

        if (!empty($paypalInfo) && isset($_GET['st']) && strtolower($_GET['st']) == "completed") {
            // // $payments=Payments::where('transaction_id',$paypalInfo['txn_id'])->get();
            // // if(count($payments)==0){
            //  $custom_data=explode(',',$paypalInfo['custom']);
            //     $package_id=$custom_data[0];
            //     $user_id=$custom_data[1];
            //     $payment = new Payments();
            //     $payment->transaction_id = $paypalInfo['txn_id'];
            //     $payment->amount = ($paypalInfo['payment_gross']);
            //     $payment->package_id = $package_id;
            //     $payment->customer_id = $user_id;
            //     $payment->status = 2;
            //     $payment->payment_gateway = "paypal";
            //     $payment->save();
            // // }
            $response['error'] = false;
            $response['message'] = "Your Purchase Package Activate Within 10 Minutes ";
            $response['data'] = $paypalInfo['txn_id'];
        } elseif (!empty($paypalInfo) && isset($_GET['st']) && strtolower($_GET['st']) == "authorized") {

            $response['error'] = false;
            $response['message'] = "Your payment has been Authorized successfully. We will capture your transaction within 30 minutes, once we process your order. After successful capture Ads wil be credited automatically.";
            $response['data'] = $paypalInfo;
        } else {
            $response['error'] = true;
            $response['message'] = "Payment Cancelled / Declined ";
            $response['data'] = (isset($_GET)) ? $paypalInfo : "";
        }
        // print_r(json_encode($response));
        return (response()->json($response));
    }
    public function get_payment_settings(Request $request)
    {


        $payment_settings =
            Setting::select('type', 'data')->whereIn('type', ['paypal_business_id', 'sandbox_mode', 'paypal_gateway', 'razor_key', 'razor_secret', 'razorpay_gateway', 'paystack_public_key', 'paystack_secret_key', 'paystack_currency', 'paystack_gateway']);

        $result = $payment_settings->get();

        //  dd(DB::getQueryLog());

        if (count($result)) {
            $response['error'] = false;
            $response['message'] = "Data Fetch Successfully";
            $response['data'] =



                $response['data'] = $result;
        } else {
            $response['error'] = false;
            $response['message'] = "No data found!";
            $response['data'] = [];
        }
        return (response()->json($response));
    }
    public function send_message(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'sender_id' => 'required',
            'receiver_id' => 'required',
            'message' => 'required',
            'property_id' => 'required',



        ]);
        if (!$validator->fails()) {

            $chat = new Chats();
            $chat->sender_id = $request->sender_id;
            $chat->receiver_id = $request->receiver_id;
            $chat->property_id = $request->property_id;
            $chat->message = $request->message;



            $destinationPath = public_path('images') . config('global.CHAT_FILE');
            if (!is_dir($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }
            // image upload


            if ($request->hasFile('file')) {
                // dd('in');
                $file = $request->file('file');
                $fileName = microtime(true) . "." . $file->getClientOriginalExtension();
                $file->move($destinationPath, $fileName);
                $chat->file = $fileName;
            } else {
                $chat->file = '';
            }

            $audiodestinationPath = public_path('images') . config('global.CHAT_AUDIO');
            if (!is_dir($audiodestinationPath)) {
                mkdir($audiodestinationPath, 0777, true);
            }
            if ($request->hasFile('audio')) {
                // dd('in');
                $file = $request->file('audio');
                $fileName = microtime(true) . "." . $file->getClientOriginalExtension();
                $file->move($audiodestinationPath, $fileName);
                $chat->audio = $fileName;
            } else {
                $chat->audio = '';
            }
            $chat->save();
            $customer = Customer::select('fcm_id', 'name')->find($request->receiver_id);

            if ($customer) {
                $fcm_id = $customer->fcm_id;
            }
            $user = User::select('fcm_id', 'name')->find($request->receiver_id);
            if ($user) {
                $fcm_id = $user->fcm_id;
            };
            $customer = Customer::select('fcm_id', 'name')->find($request->sender_id);

            if ($customer) {

                $username = $customer->name;
            }
            $user = User::select('fcm_id', 'name')->find($request->sender_id);
            if ($user) {

                $username = $user->name;
            };
            $fcmMsg = array(
                'title' => 'Message',
                'message' => $request->message,
                'type' => 'chat',
                'body' => $request->message,
                'sender_id' => $request->sender_id,
                'receiver_id' => $request->receiver_id,
                'file' => $chat->file,
                'username' => $username,
                'audio' => $chat->audio,
                'date' => $chat->created_at,
                'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                'sound' => 'default',
                'time_ago' => $chat->created_at->diffForHumans(now(), CarbonInterface::DIFF_RELATIVE_AUTO, true),
                // 'id' => $Property->id,
            );
            // echo($customer->fcm_id);
            $send = send_push_notification([$fcm_id], $fcmMsg);
            $response['error'] = false;
            $response['message'] = "Data Store Successfully";
            $response['data'] = $send;
            // $chat->sender_id = $request->sender_id;
        } else {
            $response['error'] = true;
            $response['message'] = "Please fill all data and Submit";
        }
        return (response()->json($response));
    }
    public function get_messages(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'property_id' => 'required'



        ]);
        if (!$validator->fails()) {
            $payload = JWTAuth::getPayload($this->bearerToken($request));
            $current_user = ($payload['customer_id']);
            // dd($current_user);

            $tempRow = array();
            $perPage = $request->per_page ? $request->per_page : 15; // Number of results to display per page
            $page = $request->page ?? 1; // Get the current page from the query string, or default to 1
            $chat = Chats::where('property_id', $request->property_id)
                ->where(function ($query) use ($request) {
                    $query->where('sender_id', $request->user_id)
                        ->orWhere('receiver_id', $request->user_id);
                })
                ->Where(function ($query) use ($current_user) {
                    $query->where('sender_id', $current_user)
                        ->orWhere('receiver_id', $current_user);
                })
                ->orderBy('created_at', 'DESC')
                //  ->get();
                ->paginate($perPage, ['*'], 'page', $page);

            // You can then pass the $chat object to your view to display the paginated results.



            // dd($chat->toArray());
            if ($chat) {

                $response['error'] = false;
                $response['message'] = "Data Fetch Successfully";
                $response['total_page'] = $chat->lastPage();
                $response['data'] = $chat;
            } else {
                $response['error'] = false;
                $response['message'] = "No data found!";
                $response['data'] = [];
            }
        } else {
            $response['error'] = true;
            $response['message'] = "Please fill all data and Submit";
        }
        return response()->json($response);
    }

    public function get_chats(Request $request)
    {
        $payload = JWTAuth::getPayload($this->bearerToken($request));
        $current_user = ($payload['customer_id']);
        $perPage = $request->per_page ? $request->per_page : 15; // Number of results to display per page
        $page = $request->page ?? 1;

        $chat = Chats::with(['sender', 'receiver'])->with('property')
            ->select('id', 'sender_id', 'receiver_id', 'property_id', 'created_at')
            ->where('sender_id', $current_user)
            ->orWhere('receiver_id', $current_user)
            ->orderBy('id', 'desc')
            ->groupBy('property_id')
            ->paginate($perPage, ['*'], 'page', $page);

        if (!$chat->isEmpty()) {

            $rows = array();

            $count = 1;

            $response['total_page'] = $chat->lastPage();

            foreach ($chat as $key => $row) {
                $tempRow = array();
                $tempRow['property_id'] = $row->property_id;
                $tempRow['title'] = $row->property->title;
                $tempRow['title_image'] = $row->property->title_image;
                $tempRow['date'] = $row->created_at;
                $tempRow['property_id'] = $row->property_id;
                if (!$row->receiver || !$row->sender) {
                    $user =
                        user::where('id', $row->sender_id)->orWhere('id', $row->receiver_id)->select('id')->first();

                    $tempRow['user_id'] = $user->id;
                    $tempRow['name'] = "Admin";
                    $tempRow['profile'] = '';

                    // $tempRow['fcm_id'] = $row->receiver->fcm_id;
                } else {
                    if ($row->sender->id == $current_user) {

                        $tempRow['user_id'] = $row->receiver->id;
                        $tempRow['name'] = $row->receiver->name;
                        $tempRow['profile'] = $row->receiver->profile;
                        $tempRow['firebase_id'] = $row->receiver->firebase_id;
                        $tempRow['fcm_id'] = $row->receiver->fcm_id;
                    }
                    if ($row->receiver->id == $current_user) {
                        $tempRow['user_id'] = $row->sender->id;
                        $tempRow['name'] = $row->sender->name;

                        $tempRow['profile'] = $row->sender->profile;
                        $tempRow['firebase_id'] = $row->sender->firebase_id;
                        $tempRow['fcm_id'] = $row->sender->fcm_id;
                    }
                }
                $rows[] = $tempRow;
                // $parameters[] = $arr;
                $count++;
            }


            $response['error'] = false;
            $response['message'] = "Data Fetch Successfully";
            $response['data'] = $rows;
        } else {
            $response['error'] = false;
            $response['message'] = "No data found!";
            $response['data'] = [];
        }
        return response()->json($response);
    }
     public function update_property_status(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required',
            'property_id' => 'required'



        ]);
        if (!$validator->fails()) {
            $property = Property::find($request->property_id);
            $property->propery_type = $request->status;
            $property->save();
            $response['error'] = false;
            $response['message'] = "Data updated Successfully";
        } else {
            $response['error'] = true;
            $response['message'] = "Please fill all data and Submit";
        }
        return response()->json($response);
    }
}
