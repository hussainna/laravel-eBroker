<?php

use App\Models\Advertisement;
use App\Models\Customer;
use App\Models\Favourite;
use App\Models\InterestedUser;
use App\Models\parameter;
use App\Models\PropertysInquiry;
use App\Models\Setting;
use App\Models\UserPurchasedPackage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use kornrunner\Blurhash\Blurhash;
use Intervention\Image\ImageManagerStatic as Image;


if (!function_exists('system_setting')) {

    function system_setting($type)
    {

        $db = Setting::where('type', $type)->first();
        return (isset($db)) ? $db->data : '';
    }
}

function form_submit($data = '', $value = '', $extra = '')
{
    $defaults = array(
        'type' => 'submit',
        'name' => is_array($data) ? '' : $data,
        'value' => $value
    );

    return '<input ' . _parse_form_attributes($data, $defaults) . _attributes_to_string($extra) . " />\n";
}
function _parse_form_attributes($attributes, $default)
{
    if (is_array($attributes)) {
        foreach ($default as $key => $val) {
            if (isset($attributes[$key])) {
                $default[$key] = $attributes[$key];
                unset($attributes[$key]);
            }
        }

        if (count($attributes) > 0) {
            $default = array_merge($default, $attributes);
        }
    }

    $att = '';

    foreach ($default as $key => $val) {
        if ($key === 'value') {
            $val = ($val);
        } elseif ($key === 'name' && !strlen($default['name'])) {
            continue;
        }

        $att .= $key . '="' . $val . '" ';
    }

    return $att;
}


// ------------------------------------------------------------------------

if (!function_exists('_attributes_to_string')) {
    /**
     * Attributes To String
     *
     * Helper function used by some of the form helpers
     *
     * @param	mixed
     * @return	string
     */
    function _attributes_to_string($attributes)
    {
        if (empty($attributes)) {
            return '';
        }

        if (is_object($attributes)) {
            $attributes = (array) $attributes;
        }

        if (is_array($attributes)) {
            $atts = '';

            foreach ($attributes as $key => $val) {
                $atts .= ' ' . $key . '="' . $val . '"';
            }

            return $atts;
        }

        if (is_string($attributes)) {
            return ' ' . $attributes;
        }

        return FALSE;
    }
}

if (!function_exists('send_push_notification')) {
    //send Notification
    function send_push_notification($registrationIDs = array(), $fcmMsg  = '')
    {

        $get_fcm_key = DB::table('settings')->select('data')->where('type', 'fcm_key')->first();


        $fcm_key = $get_fcm_key->data;

        $registrationIDs_chunks = array_chunk($registrationIDs, 1000);
        foreach ($registrationIDs_chunks as $registrationIDs) {

            $fcmFields = array(
                // 'to' => $singleID,
                'registration_ids' => $registrationIDs, // expects an array of ids
                'priority' => 'high',
                // 'notification' => $fcmMsg,
                'data' => $fcmMsg
            );
            //print_r(json_encode($fcmFields));
            $headers = array(
                'Authorization: key=' . $fcm_key,
                'Content-Type: application/json'
            );

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send');
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fcmFields));
            $get_result = curl_exec($ch);
            curl_close($ch);
            $result = json_decode($get_result, 1);
            // dd($get_result);
            return $result;
        }
    }
}

if (!function_exists('get_countries_from_json')) {
    function get_countries_from_json()
    {
        $country =  json_decode(file_get_contents(public_path('json') . "/cities.json"), true);

        $tempRow = array();
        foreach ($country['countries'] as $row) {
            $tempRow[] = $row['country'];
        }
        return $tempRow;
    }
}

if (!function_exists('get_states_from_json')) {
    function get_states_from_json($country)
    {


        $state =  json_decode(file_get_contents(public_path('json') . "/cities.json"), true);

        $tempRow = array();
        foreach ($state['countries'] as $row) {
            // echo $row;
            if ($row['country'] == $country) {
                $tempRow = $row['states'];
            }
        }

        return $tempRow;
    }
}


if (!function_exists('parameterTypesByCategory')) {
    function parameterTypesByCategory($category_id)
    {


        $parameter_types = DB::table('categories')->select('parameter_types')->where('categories.id', $category_id)->first();

        $tempRow = array();

        $parameterTypes = explode(',', $parameter_types->parameter_types);

        foreach ($parameterTypes as $key => $row) {
            $par_name = parameter::find($row);
            $tempRow['parameters'][$key] = $par_name;

            // $tempRow['parameters'][$key]['type'] = $par_name->type_of_parameter;
        }
        return  $tempRow;
    }
}
function update_subscription()
{
    $data = UserPurchasedPackage::where('user_id', Auth::id())->where('end_date', Carbon::now());
    if ($data) {
        $Customer = Customer::find(Auth::id());
        $Customer->subscription = 0;
        $Customer->update();
    }
}
function get_hash($img)
{

    $image_make = Image::make($img);
    $width = $image_make->width();
    $height = $image_make->height();

    $pixels = [];
    for ($y = 0; $y < $height; ++$y) {
        $row = [];
        for ($x = 0; $x < $width; ++$x) {
            $colors = $image_make->pickColor($x, $y);

            $row[] = [$colors[0], $colors[1], $colors[2]];
        }
        $pixels[] = $row;
    }

    $components_x = 4;
    $components_y = 3;
    $hash =  Blurhash::encode($pixels, $components_x, $components_y);
    //  "ll";
    return $hash;
}
if (!function_exists('form_hidden')) {
    /**
     * Hidden Input Field
     *
     * Generates hidden fields. You can pass a simple key/value string or
     * an associative array with multiple values.
     *
     * @param	mixed	$name		Field name
     * @param	string	$value		Field value
     * @param	bool	$recursing
     * @return	string
     */
    function form_hidden($name, $value = '', $recursing = FALSE)
    {
        static $form;

        if ($recursing === FALSE) {
            $form = "\n";
        }

        if (is_array($name)) {
            foreach ($name as $key => $val) {
                form_hidden($key, $val, TRUE);
            }

            return $form;
        }

        if (!is_array($value)) {
            $form .= '<input type="hidden" name="' . $name . '" value="' . ($value) . "\" />\n";
        } else {
            foreach ($value as $k => $v) {
                $k = is_int($k) ? '' : $k;
                form_hidden($name . '[' . $k . ']', $v, TRUE);
            }
        }

        return $form;
    }
}
if (!function_exists('form_close')) {
    /**
     * Form Close Tag
     *
     * @param	string
     * @return	string
     */
    function form_close($extra = '')
    {
        return '</form>' . $extra;
    }
}
function get_property_details($result, $current_user = NULL)
{
    $rows = array();
    $tempRow = array();
    //return $result;
    $count = 1;
    // if (!$result->isEmpty()) {
    foreach ($result as $row) {
        // print_r($row->customer->toArray());

       $customer = $row->customer->first();
        $user = $row->user->first();

        if ($customer) {
            $tempRow['customer_name'] = $customer->name;
            $tempRow['email'] = $customer->email;
            $tempRow['mobile'] = $customer->mobile;

            $tempRow['profile'] = $row->customer[0]->profile;
        } else if ($user && $row->post_type == 0) {

            $tempRow['customer_name'] = $user->name;
            $tempRow['email'] = $user->email;
            $tempRow['mobile'] = $user->mobile;
           
        }

        $tempRow['id'] = $row->id;
        $tempRow['title'] = $row->title;
        $tempRow['price'] = $row->price;
        $tempRow['category'] = $row->category;

        $tempRow['description'] = $row->description;
        $tempRow['address'] = $row->address;
        $tempRow['client_address'] = $row->client_address;
        if (($row->propery_type == '0')) {
            $property_type = "Sell";
        }
        if (($row->propery_type == '1')) {
            $property_type = "Rent";
        }
        if (($row->propery_type == '2')) {
            $property_type = "Sold";
        }
        if (($row->propery_type == '3')) {
            $property_type = "Rented";
        }
        $tempRow['propery_type'] = $property_type;


        $tempRow['title_image'] = $row->title_image;

        $tempRow['title_image_hash'] = $row->title_image_hash != '' ? $row->title_image_hash : '';
        $tempRow['threeD_image'] = $row->threeD_image;

        $tempRow['post_created'] = $row->created_at->diffForHumans();
        $tempRow['gallery'] = $row->gallery;
        $tempRow['total_view'] = $row->total_click;
        $tempRow['status'] = $row->status;
        $tempRow['state'] = $row->state;
        $tempRow['city'] = $row->city;
        $tempRow['country'] = $row->country;
        $tempRow['latitude'] = $row->latitude;
        $tempRow['longitude'] = $row->longitude;
        $tempRow['added_by'] = $row->added_by;
        $tempRow['video_link'] = $row->video_link;

        $inquiry = PropertysInquiry::where('customers_id', $current_user)->where('propertys_id', $row->id)->where('status', 2)->first();

        if ($inquiry) {
            $tempRow['inquiry'] = true;
        } else {
            $tempRow['inquiry'] = false;
        }
        $promoted = Advertisement::where('property_id', $row->id)->first();

        if ($promoted) {
            $tempRow['promoted'] = true;
        } else {
            $tempRow['promoted'] = false;
        }
        $interested_users = array();
        $favourite_users = array();
        $s = '';
        foreach ($row->favourite as $favourite_user) {

            if ($favourite_user->property_id == $row->id) {

                array_push($favourite_users, $favourite_user->user_id);
                $s .= $favourite_user->user_id . ',';
            }
        }

        foreach ($row->interested_users as $interested_user) {

            if ($interested_user->property_id == $row->id) {

                array_push($interested_users, $interested_user->customer_id);
                $s .= $interested_user->user_id . ',';
            }
        }


        $favourite = Favourite::where('property_id', $row->id)->where('user_id', $current_user)->get();

        $interest = InterestedUser::where('property_id', $row->id)->where('customer_id', $current_user)->get();


        if (count($favourite) != 0) {
            $tempRow['is_favourite'] = 1;
        } else {
            $tempRow['is_favourite'] = 0;
        }

        if (count($interest) != 0) {
            $tempRow['is_interested'] = 1;
        } else {
            $tempRow['is_interested'] = 0;
        }
        $tempRow['favourite_users'] = $favourite_users;
        $tempRow['interested_users'] = $interested_users;

        $tempRow['total_interested_users'] = count($interested_users);
        $tempRow['total_favourite_users'] = count($favourite_users);


        $arr = [];
        $arr1 = [];
        // print_r($row->assignparameter->toArray());
        if ($row->advertisement) {
            $tempRow['advertisement'] = $row->advertisement;
        }
        foreach ($row->assignparameter as $key => $res) {
            // dd($res->parameter->id);
            if ($res->parameter) {
                $tempRow['parameters'][$key]['id'] = $res->parameter->id;
                $tempRow['parameters'][$key]['name'] = $res->parameter->name;

                $tempRow['parameters'][$key]['type_of_parameter'] = $res->parameter->type_of_parameter;
                $tempRow['parameters'][$key]['type_values'] = $res->parameter->type_values;
                $tempRow['parameters'][$key]['image'] = $res->parameter->image;
                $tempRow['parameters'][$key]["value"] = $res->value;
            }
        }
        // $tempRow['parameters'] = $row->assignparameter;
        $rows[] = $tempRow;
        $parameters[] = $arr;
        $count++;
    }
    return $rows;
}
