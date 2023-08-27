<?php

namespace App\Http\Controllers;

use App\Models\Advertisement;
use App\Models\Notifications;
use Illuminate\Http\Request;

class AdvertisementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //

        return view('advertisement.index');
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        //
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

        // \DB::enableQueryLog(); // Enable query log
        $sql = Advertisement::with('customer')->orderBy($sort, $order);

        if (isset($_GET['search']) && !empty($_GET['search'])) {
            $search = $_GET['search'];
            $sql = $sql->where('id', 'LIKE', "%$search%")->orwhere('title', 'LIKE', "%$search%")->orwhere('description', 'LIKE', "%$search%");
        }

        $total = $sql->count();

        if (isset($_GET['limit'])) {
            $sql->skip($offset)->take($limit);
        }
        $res = $sql->get();
        // dd(\DB::getQueryLog());

        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $tempRow = array();
        $count = 1;
        $status = '';


        $operate = '';
        foreach ($res as $row) {

            // if (has_permissions('update', 'property')) {
            // $operate = '<a  href="' . route('advertisement.edit', $row->id) . '"  class="btn icon btn-primary btn-sm rounded-pill mt-2" title="Edit"><i class="fa fa-edit"></i></a>';
            // }
            $operate = '<a  id="' . $row->id . '"  class="btn icon btn-primary btn-sm rounded-pill" data-status="' . $row->status . '" data-oldimage="' . $row->image . '" data-types="' . $row->id . '" data-bs-toggle="modal" data-bs-target="#editModal"  onclick="setValue(this.id);" title="Edit"><i class="fa fa-edit"></i></a>&nbsp;&nbsp;';

            // if (has_permissions('delete', 'property')) {
            //     // $operate .= '&nbsp;<a href="' . route('property.destroy', $row->id) . '" onclick="return confirmationDelete(event);" class="btn icon btn-danger btn-sm rounded-pill mt-2" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark" title="Delete"><i class="bi bi-trash"></i></a>';

            //     $operate .= '&nbsp;<a href="' . route('article.destroy', $row->id) . '" onclick="return confirmationDelete(event);" class="btn icon btn-danger btn-sm rounded-pill mt-2" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-dark" title="Delete"><i class="bi bi-trash"></i></a>';
            // }
            if ($row->is_enable == '0') {
                $operate .=   '&nbsp;<a id="' . $row->id . '" class="btn btn-primary btn-sm rounded-pill" onclick="return active(this.id);" title="Enable"><i class="bi bi-eye-fill"></i></a>';
            } else {
                $operate .=   '&nbsp;<a id="' . $row->id . '" class="btn btn-danger btn-sm rounded-pill" onclick="return disable(this.id);" title="Disable"><i class="bi bi-eye-slash-fill"></i></a>';
            }
            $tempRow['id'] = $row->id;
            $tempRow['title'] = $row->title;
            $tempRow['type'] = $row->type;
            $tempRow['image'] = ($row->image != '') ? '<a class="image-popup-no-margins" href="' . url('images') . config('global.ADVERTISEMENT_IMAGE_PATH')  . $row->image . '"><img class="rounded avatar-md shadow img-fluid" alt="" src="' . url('images') . config('global.ADVERTISEMENT_IMAGE_PATH')  . $row->image . '" width="55"></a>' : '';
            $tempRow['start_date'] = $row->start_date;
            $tempRow['end_date'] = $row->end_date;
            $tempRow['user_name'] = $row->customer->name;
            $tempRow['user_contact'] = $row->user_contact;
            $tempRow['is_enable'] = ($row->is_enable == '0') ? '<span class="badge rounded-pill bg-danger">Disable</span>' : '<span class="badge rounded-pill bg-success">Enable</span>';
            $tempRow['user_email'] = $row->user_email;
            if ($row->status == 0) {
                $status = 'Approved';
            }
            if ($row->status == 1) {
                $status = 'Pending';
            }
            if ($row->status == 2) {
                $status = 'Rejected';
            }
            $tempRow['status'] = $status;

            $tempRow['operate'] = $operate;
            $rows[] = $tempRow;
            $count++;
        }

        $bulkData['rows'] = $rows;
        return response()->json($bulkData);
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
    public function update(Request $request)
    {
        if (!has_permissions('update', 'property')) {
            $response['error'] = true;
            $response['message'] = PERMISSION_ERROR_MSG;
            return response()->json($response);
        } else {
            Advertisement::find($request->id)->update(['status' => $request->edit_adv_status]);

            $adv = Advertisement::with('customer')->find($request->id);
            // dd($adv);
            $status = $adv->status;
            if ($adv->customer->fcm_id != '' && $adv->customer->notification == 1) {
                if ($status == '0') {
                    $status_text  = 'Approved';
                } else if ($status == '1') {
                    $status_text  = 'Pending';
                } else if ($status == '2') {
                    $status_text  = 'Rejected';
                }
                //START :: Send Notification To Customer
                $fcm_ids = array();
                $fcm_ids[] = $adv->customer->fcm_id;
                if (!empty($fcm_ids)) {
                    $registrationIDs = array_filter($fcm_ids);
                    $fcmMsg = array(
                        'title' => 'Advertisement Request',
                        'message' => 'Advertisement Request Is ' . $status_text,
                        'type' => 'advertisement_request',
                        'body' => 'Advertisement Request Is ' . $status_text,
                        'click_action' => 'FLUTTER_NOTIFICATION_CLICK',
                        'sound' => 'default',
                        'id' => $adv->id,
                    );
                    send_push_notification($registrationIDs, $fcmMsg);
                }
                //END ::  Send Notification To Customer

                Notifications::create([
                    'title' => 'Property Inquiry Updated',
                    'message' => 'Your Advertisement Request is ' . $status_text,
                    'image' => '',
                    'type' => '1',
                    'send_type' => '0',
                    'customers_id' => $adv->customer->id,
                    'propertys_id' => $adv->id
                ]);
            }

            return back()->with('success', 'Property status update Successfully');
        }
    }

    public function updateStatus(Request $request)
    {
        if (!has_permissions('update', 'property')) {
            $response['error'] = true;
            $response['message'] = PERMISSION_ERROR_MSG;
            return response()->json($response);
        } else {
            Advertisement::where('id', $request->id)->update(['is_enable' => $request->is_enable]);

            $response['error'] = false;
            return response()->json($response);
        }
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
}
