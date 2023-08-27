<?php

namespace App\Http\Controllers;

use App\Models\Chats;
use App\Models\Customer;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Type\Time;
use Tymon\JWTAuth\Claims\Custom;

class ChatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $chat = new Chats();
        $chat->sender_id = $request->sender_by;
        $chat->receiver_id = $request->receiver_id;
        $chat->message = $request->message;
        $chat->property_id = $request->property_id;
        $destinationPath = public_path('images') . config('global.CHAT_FILE');
        if (!is_dir($destinationPath)) {
            mkdir($destinationPath, 0777, true);
        }
        if ($request->hasFile('attachment')) {
            $attachment = $request->file('attachment');
            $imageName = microtime(true) . "." . $attachment->getClientOriginalExtension();
            $attachment->move($destinationPath, $imageName);
            $chat->file = $imageName;
        } else {
            $chat->file = '';
        }

        $chat->save();
$chat->save();
        $customer = Customer::select('fcm_id', 'name')->find($request->receiver_id);
        if ($customer) {
            $fcm_id = $customer->fcm_id;
            $username = $customer->name;
        }
        $user = User::select('fcm_id', 'name')->find($request->receiver_id);
        if ($user) {
            $fcm_id = $user->fcm_id;
            $username = $user->name;
        }
        $fcmMsg = array(
            'title' => 'Message',
            'message' => $request->message,
            'type' => 'chat',
            'body' => $request->message,
            'sender_id' => $request->sender_id,
            'receiver_id' => $request->receiver_id,
            'username' => $username,
            'file' => $chat->file != '' ? $chat->file : '',
            'audio' => $chat->audio,
            'date' => $chat->created_at,
            'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
            'sound' => 'default',
            // 'id' => $Property->id,
        );
        // echo($customer->fcm_id);
        send_push_notification([$fcm_id], $fcmMsg);

        $response['error'] = false;
        return response()->json($response);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function getChats()
    {
        $current_user = Auth::user()->id;

        $offset = 0;
        $limit = 10;
        $sort = 'id';
        $order = 'DESC';

        if (isset($_GET['offset'])) {
            $offset = $_GET['offset'];
        }

        if (isset($_GET['limit'])) {
            $limit = $_GET['limit'];
        }

        if (isset($_GET['sort'])) {
            $sort = $_GET['sort'];
        }

        if (isset($_GET['order'])) {
            $order = $_GET['order'];
        }

        DB::enableQueryLog();

        $sql =

            Chats::with(['sender', 'receiver'])->with('property', function ($q) use ($current_user) {
                $q->where('post_type', 1)->where('added_by', $current_user);
            })

            ->select(DB::raw('DISTINCT least(sender_id, receiver_id) as op_user,greatest(sender_id, receiver_id) as user'), 'property_id')
            ->where(function ($query) use ($current_user) {
                $query->where('sender_id', $current_user)
                    ->orWhere('receiver_id', $current_user);
            })
            ->orderBy('id', 'desc')
            ->groupBy('property_id')
            ->groupBy('sender_id')
            ->groupBy('receiver_id')

            ->where('property_id', $_GET['property_id']);



        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search = $_GET['search'];
            $sql->where('id', 'LIKE', "%$search%")->orwhere('name', 'LIKE', "%$search%");
        }


        $total = $sql->count();

        if (isset($_GET['limit'])) {
            $sql->skip($offset)->take($limit);
        }


        $res = $sql->get();


        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        $count = 1;


        $operate = '';
        foreach ($res as $key => $row) {

            $tempRow['id'] = $key + 1;
            $customer = Customer::find($row->user);
            if ($customer) {
                $tempRow['user'] = $customer->id;
                $tempRow['name'] = $customer->name;

            }



            $tempRow['property_id'] = $row->property_id;

            $operate = '<a  id="' . $row->id . '"  class="btn icon btn-primary  view-chat btn-sm rounded-pill"  data-bs-toggle="modal" data-bs-target="#chat_modal"  onclick="setallMessage(' . $row->property_id . ',' . $row->user . ');"" title="Chat"><i class="bi bi-chat"></i></a>&nbsp;&nbsp;';

            $tempRow['operate'] = $operate;

            $rows[] = $tempRow;
            $count++;
        }

        $bulkData['rows'] = $rows;
        return response()->json($bulkData);
    }
    public function getAllMessage(Request $request)
    {

        $property_id = $request->propert_id;
        $offset = $request->offset ? $request->offset : 0;
        $limit = $request->limit ? $request->limit : 10;


        $chat = Chats::with('sender')->with('receiver')->with('property')->select('id', 'sender_id', 'receiver_id', 'message', 'audio','property_id', 'file', 'created_at')->where('property_id', $request->property_id)
            ->where(function ($query) use ($request) {
                $query->where('sender_id', $_GET['client_id'])
                    ->orWhere('receiver_id', $_GET['client_id']);
            })->orderBy('id', 'DESC')->get();

        // dd(DB::getQueryLog());

        // dd($chat->toArray());
        $rows = array();
        $tempRow = array();
        $count = 1;
        foreach ($chat as $row) {
            // dd($row->toArray());
            $tempRow['message'] = $row->message;

            $current = Carbon::parse(date('Y/m/d h:i:s'), 'Asia/Kolkata');
            $test = Carbon::parse(($row->created_at), 'Asia/Kolkata');

            $tempRow['time_ago'] = $row->created_at->diffForHumans(now(), CarbonInterface::DIFF_RELATIVE_AUTO, true);

            $tempRow['attachment'] = $row->file;
            $tempRow['audio'] = $row->audio;
// print_r($row->audio);
            if ($row->receiver_id == Auth::user()->id) {

                $user = User::find($row->sender_id);

                $customer = Customer::find($row->sender_id);
                if ($customer) {
                    $name = $customer->name;
                    $profile = $customer->profile;
                    $profile = '';
                }
                if ($user && $row->property->post_type == 1) {
                    $name = $user->name;
                }
                $tempRow['sendeprofile'] = $profile;

                $tempRow['sender_type'] = 1;

                $tempRow['sendername'] = $name;
            }
            if ($row->sender_id  == Auth::user()->id) {
                // $tempRow['attachment'] = $row->file;

                // $tempRow['attachment'] = $row->file != '' ? "<img class='img-fluid'  alt='No image'  style='height: 150px;' src='" . $row->file . "'>" : '';


                $user = User::find($row->sender_id);

                $customer = Customer::find($row->sender_id);
                if ($customer) {
                    $name = $customer->name;
                    $profile = $customer->profile;
                }
                if ($user && $row->property->post_type == 1) {
                    $name = $user->name;
                    $profile = '';
                }
                // $tempRow['attachment'] = $row->file;

                $tempRow['ssendeprofile'] = $profile;
                $tempRow['ssendername'] = $name;

                $tempRow['sender_type'] = 0;
            }
            $rows[] = $tempRow;
            $count++;
        }

        $bulkData['rows'] = $rows;
        return response()->json($rows);
    }
}
