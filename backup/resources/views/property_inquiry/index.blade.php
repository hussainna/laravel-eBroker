@extends('layouts.main')

@section('title')
    Property Inquiry
@endsection

@section('page-title')
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h4>@yield('title')</h4>

            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">

            </div>
        </div>
    </div>
@endsection


@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <section class="section">
        <div class="card">

            <div class="card-body">
                <div class="row justify-content-center" id="toolbar">


                    <div class="col-sm-12">

                        {{-- {{ Form::label('filter_status', 'Status', ['class' => 'form-label col-12 text-center']) }} --}}
                        {{-- <select id="filter_status" class="form-select form-control-sm">
                            <option value="">Select Status </option>
                            <option value="0">Pending</option>
                            <option value="1">Accept</option>
                            <option value="2">Complete</option>
                            <option value="3">Cancel</option>
                        </select> --}}

                        {!! Form::select(
                            'filter_status',
                            ['0' => 'Pending', '1' => 'Accept', '2' => 'Complate', '3' => 'Cancel'],
                            $status,
                            ['class' => 'form-select form-control-sm', 'id' => 'filter_status', 'placeholder' => 'Select Status'],
                        ) !!}
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <table class="table-light" aria-describedby="mydesc" class='table-striped' id="table_list"
                            data-toggle="table" data-url="{{ url('getPropertyInquiryList') }}" data-click-to-select="true"
                            data-side-pagination="server" data-pagination="true"
                            data-page-list="[5, 10, 20, 50, 100, 200,All]" data-search="true" data-toolbar="#toolbar"
                            data-show-columns="true" data-show-refresh="true" data-fixed-columns="true"
                            data-fixed-number="1" data-fixed-right-number="1" data-trim-on-search="false"
                            data-mobile-responsive="true" data-sort-name="id" data-sort-order="desc"
                            data-pagination-successively-size="3" data-query-params="queryParams">
                            <thead>
                                <tr>
                                    <th scope="col" data-field="id" data-halign="center" data-sortable="true">ID</th>
                                    <th scope="col" data-field="title" data-halign="center" data-sortable="false">Title
                                    </th>
                                    <th scope="col" data-field="property_owner" data-halign="center"
                                        data-sortable="false">Owner Name</th>
                                    <th scope="col" data-field="property_mobile" data-halign="center"
                                        data-sortable="false">Owner Mobile</th>
                                    <th scope="col" data-field="name" data-halign="center" data-sortable="false">Inquiry
                                        By</th>


                                    <th scope="col" data-field="location" data-halign="center" data-sortable="false">
                                        Location</th>
                                    <th scope="col" data-field="email" data-halign="center" data-sortable="false">Email
                                    </th>
                                    <th scope="col" data-field="mobile" data-halign="center" data-sortable="false">Mobile
                                    </th>





                                    <th scope="col" data-field="inquiry_created" data-halign="center"
                                        data-sortable="false">Enquiries Posted
                                    </th>

                                    <th scope="col" data-field="chat" data-halign="center" data-sortable="false"
                                        data-events="actionEvents">
                                        Chat
                                    </th>
                                    @if (has_permissions('update', 'property') || has_permissions('delete', 'property'))
                                        <th scope="col" data-field="operate" data-events="actionEvents"
                                            data-sortable="false">Action</th>
                                    @endif
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>


        </div>
    </section>


    <!-- EDIT MODEL MODEL -->
    <div class="modal modal-sticky" id="editModal" role="dialog" data-backdrop="false">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <!--begin::Card-->
                <div class="card card-custom">
                    <!--begin::Header-->
                    <div class="card-header align-items-center px-4 py-3">
                        <div class="text-left flex-grow-1">
                            <!--begin::Dropdown Menu-->
                            <div class="dropdown dropdown-inline">


                            </div>
                            <!--end::Dropdown Menu-->
                        </div>
                        <div class="text-center flex-grow-1">
                            <div class="text-dark-75 font-weight-bold font-size-h5">Enquiries</div>

                        </div>
                        <div class="text-right flex-grow-1">
                            <button type="button" class="btn btn-clean btn-sm btn-icon btn-icon-md" data-dismiss="modal">
                                <i class="ki ki-close icon-1x"></i>
                            </button>
                        </div>
                    </div>
                    <!--end::Header-->
                    <!--begin::Body-->
                    <div class="card-body">
                        <!--begin::Scroll-->
                        <div class="table-responsive">

                            <table class="table-light" aria-describedby="mydesc" class='table-striped' id="table_list1"
                                data-toggle="table" data-url="{{ url('getChatList') }}" data-click-to-select="true"
                                data-side-pagination="server" data-pagination="true"
                                data-page-list="[5, 10, 20, 50, 100, 200,All]" data-search="true" data-toolbar="#toolbar"
                                data-show-columns="true" data-show-refresh="true" data-fixed-columns="true"
                                data-fixed-number="1" data-fixed-right-number="1" data-trim-on-search="false"
                                data-mobile-responsive="true" data-sort-name="id" data-sort-order="desc"
                                data-pagination-successively-size="3" data-query-params="chatsqueryParams">
                                <thead>
                                    <tr>
                                        <th scope="col" data-field="id" data-halign="center" data-sortable="true">ID
                                        </th>
                                        <th scope="col" data-field="name" data-halign="center" data-sortable="true">
                                            User
                                        </th>
                                        <th scope="col" data-field="operate" data-events="actionEvents"
                                            data-sortable="false">Action</th>


                                    </tr>
                                </thead>
                            </table>

                        </div>
                        <!--end::Scroll-->
                    </div>
                    <!--end::Body-->

                </div>
                <!--end::Card-->
            </div>
        </div>
    </div>
    <div class="modal modal-sticky modal-sticky-bottom-center" id="chat_modal" role="dialog" data-backdrop="false">

        <div class="modal-dialog modal-xl" role="document">
            <div class="modal-content">
                <!--begin::Card-->
                <div class="card card-custom">
                    <!--begin::Header-->
                    <div class="card-header align-items-center px-4 py-3">

                        <div class="text-center flex-grow-1">
                            <div class="text-dark-75 font-weight-bold font-size-h5 text-center">Enquiries Chat</div>

                        </div>
                        <div class="text-right flex-grow-1">
                            <button type="button" class="btn btn-clean btn-sm btn-icon btn-icon-md"
                                data-dismiss="modal">
                                <i class="ki ki-close icon-1x"></i>
                            </button>
                        </div>
                    </div>
                    <!--end::Header-->
                    <!--begin::Body-->
                    <div class="card-body">
                        <div class="row">
                            <label class="col-sm-12 col-form-label text-center issueTitle"></label>
                        </div>
                        <hr>
                        <hr>
                        <!--begin::Scroll-->
                        <div id="myscroll" style="overflow-y: scroll;height: 400px">
                            <!--begin::Messages-->

                            <div class="messages" id="chat">

                            </div>
                            <!--end::Messages-->
                        </div>
                        <!--end::Scroll-->
                    </div>
                    <!--end::Body-->
                    <!--begin::Footer-->
                    <div class="card-footer align-items-center">
                        <form action="" method="POST">
                            @csrf
                            <input type="hidden" name="prop_id" id="prop_id">
                            <input type="hidden" name="receiver_id" id=receiver_id>
                            <input type="hidden" name="sender_id" id="sender_id" value={{ Auth::user()->id }}>

                            <!--begin::Compose-->
                            <textarea class="form-control form-control-solid border-0 p-0" data-emoji-picker="true" rows="2"
                                placeholder="Type a message" id="Onmessage" required></textarea>
                            <div class="d-flex align-items-center justify-content-between mt-5">
                                <div class="mr-3">
                                    <div class="custom-file">
                                        <input type="file" name="attachment" class="custom-file-input"
                                            id="Homeattachment" />

                                    </div>
                                </div>

                                <div>
                                    <button type="button"
                                        class="btn btn-primary btn-md text-uppercase font-weight-bold chat-send py-2 px-6"
                                        onclick="OnsendMessage();">Send</button>
                                </div>
                            </div>
                        </form>
                        <!--begin::Compose-->
                    </div>
                    <!--end::Footer-->

                </div>
                <!--end::Card-->
            </div>
        </div>
    </div>
    <!--end::Chat Panel-->




    <!-- VIEW PROPERTY MODEL -->
    <div id="ViewPropertyModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="myModalLabel1">View Property</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <div class="row ">
                        <div class="col-md-6 col-12 ">
                            <div class="col-md-12 col-12 form-group">
                                <label for="title" class="form-label col-12 text-center">Title</label>
                                <input class="form-control " placeholder="Title" id="title" readonly type="text">
                            </div>
                        </div>

                        <div class="col-md-6 col-12 ">
                            <div class="row ">
                                <div class="col-md-3 col-12 form-group">
                                    <label for="category" class="form-label col-12 text-center">Category</label>
                                    <input class="form-control " placeholder="Category" id="category" readonly
                                        type="text">
                                </div>
                                <div class="col-md-3 col-12 form-group">
                                    <label for="city" class="form-label col-12 text-center">City</label>
                                    <input class="form-control " placeholder="City" readonly="true" id="city"
                                        type="text">

                                </div>

                                <div class="col-md-3 col-12 form-group">
                                    <label for="country" class="form-label col-12 text-center">Country</label>
                                    <input class="form-control " placeholder="Country" id="country" readonly
                                        type="text">
                                </div>

                                <div class="col-md-3 col-12 form-group">
                                    <label for="state" class="form-label col-12 text-center">State</label>
                                    <input class="form-control " placeholder="State" id="state" readonly
                                        type="text">
                                </div>
                            </div>
                        </div>


                    </div>


                    <div class="row ">
                        <div class="col-md-6 col-12">
                            <div class="row">
                                <div class="col-md-6 col-12 form-group">
                                    <label for="property_type" class="form-label col-12 text-center">Property Type</label>
                                    <input class="form-control " placeholder="Property Type" id="property_type" readonly
                                        type="text">
                                </div>
                                <div class="col-md-6 col-12 form-group">
                                    <label for="price" class="form-label col-12 text-center">Price</label>
                                    <input class="form-control " placeholder="Price" id="price" readonly
                                        type="text">
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 col-12">
                            <div class="row">
                                <div class="col-md-4 col-12 form-group">
                                    <label for="unit_type" class="form-label col-12 text-center">Unit Type</label>
                                    <input class="form-control " placeholder="Property Type" id="unit_type" readonly
                                        type="text">
                                </div>
                                <div class="col-md-4 col-12 form-group">
                                    <label for="latitude" class="form-label col-12 text-center">Latitude</label>
                                    <input class="form-control " placeholder="Latitude" id="latitude" readonly
                                        type="text">
                                </div>
                                <div class="col-md-4 col-12 form-group">
                                    <label for="longitude" class="form-label col-12 text-center">Longitude</label>
                                    <input class="form-control " placeholder="Longitude" id="longitude" readonly
                                        type="text">
                                </div>
                            </div>
                        </div>
                    </div>



                    <div class="row ">
                        <div class="col-md-12 col-12">
                            <div class="row">
                                <div class="col-md-6 col-12 form-group">
                                    <label for="client_address" class="form-label col-12 text-center">Client
                                        Address</label>
                                    <textarea class="form-control " placeholder="Client Address" rows="2" id="client_address" autocomplete="off"
                                        cols="50" readonly></textarea>
                                </div>
                                <div class="col-md-6 col-12 form-group">
                                    <label for="address" class="form-label col-12 text-center">Address</label>
                                    <textarea class="form-control " placeholder="Address" rows="2" id="address" autocomplete="off"
                                        cols="50" readonly></textarea>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="row ">
                        <div class="col-md-12 col-12">
                            <label for="description" class="form-label col-12 text-center">Description</label>
                            <p id="description"></p>
                        </div>

                    </div>
                </div>

            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- VIEW PROPERTY MODEL -->
@endsection

@section('script')
    <script>
        var clipboard = new ClipboardJS('.CopyLocation');

        clipboard.on('success', function(e) {
            Toastify({
                text: 'Copied',
                duration: 1000,
                close: !0,
                backgroundColor: "#000000"
            }).showToast()
            e.clearSelection();
        });
    </script>
    <script>
        $('#filter_status').on('change', function() {
            $('#table_list').bootstrapTable('refresh');

        })
        $(document).ready(function() {
            var params = new window.URLSearchParams(window.location.search);

            if (params.get('status') != 'null') {
                $('#status').val(params.get('status')).trigger('change');
            }
        });

        function queryParams(p) {
            return {
                sort: p.sort,
                order: p.order,
                offset: p.offset,
                limit: p.limit,
                search: p.search,
                status: $('#filter_status').val(),
            };
        }

        function chatsqueryParams(p) {
            return {
                sort: p.sort,
                order: p.order,
                offset: p.offset,
                limit: p.limit,
                search: p.search,
                status: $('#filter_status').val(),
                property_id: $('#prop_id').val(),
            };
        }

        function setValue(id) {
            //$('#editUserForm').attr('action', ($('#editUserForm').attr('action') +'/edit/'+id));
            $("#edit_id").val(id);
            $("#status").val($("#" + id).data('status')).trigger('change');

        }

        function setValues(id) {
            console.log('click');
            $('#prop_id').val(id);

            $('#table_list1').bootstrapTable('refresh');
        }

        function changeStatus() {
            var id = $("#edit_id").val();
            var status = $("#status").val();

            $.ajax({
                url: "{{ route('property-inquiry.updateStatus') }}",
                type: "POST",
                data: {
                    '_token': "{{ csrf_token() }}",
                    "id": id,
                    "status": status,
                },
                cache: false,
                success: function(result) {

                    if (result.error == false) {
                        Toastify({
                            text: 'Inquiry Status Change successfully',
                            duration: 6000,
                            close: !0,
                            backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)"
                        }).showToast();
                        $('#table_list').bootstrapTable('refresh');
                        $("#edit_id").val('');
                        $("#status").val(0).trigger('change');
                        $('#editModal').modal('toggle');
                    } else {
                        Toastify({
                            text: result.message,
                            duration: 6000,
                            close: !0,
                            backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)"
                        }).showToast();
                        $('#table_list').bootstrapTable('refresh');
                    }

                }

            });
        }
    </script>

    <script>
        window.actionEvents = {
            'click .view-property': function(e, value, row, index) {
                $("#title").val(row.title);
                $("#category").val(row.category);
                $("#state").val(row.state);
                $("#city").val(row.city);
                $("#country").val(row.country);
                $("#state").val(row.state);
                $("#property_type").val(row.property_type);
                $("#price").val(row.price);
                $("#unit_type").val(row.unitType);
                $("#latitude").val(row.latitude);
                $("#longitude").val(row.longitude);
                $("#client_address").val(row.client_address);
                $("#address").val(row.address);
                $("#description").html(row.description);


                var strArray = row.category_parameter_types.split(",");
                for (var i = 0; i < strArray.length; i++) {
                    if (parseInt(strArray[i]) == 1) {
                        $(".div_carpet_area").attr("style", "display:block");
                        $("#carpet_area").val(row.carpet_area);
                    }


                }
            },
            'click .view-chat': function(e, value, row, index) {
                console.log(row);
                $("#prop_id").val(row.property_id);
                $('#receiver_id').val(row.user);
                $('#property_id').val(row.property_id);


            }


        };

        function OnsendMessage() {

            var sender_by = $('#sender_id').val();
            var receive_by = $('#receiver_id').val();
            console.log($('#receiver_id').val());

            var message = $("#Onmessage").val();
            var attachment = $('#Homeattachment')[0].files;
            $('.progress').show();
            var fd = new FormData();

            console.log(attachment);

            fd.append('attachment', attachment[0]);
            fd.append('receiver_id', receive_by);
            fd.append('message', message);
            fd.append('property_id', $('#prop_id').val());
            fd.append('sender_type', 0);
            fd.append('sender_by', sender_by);
            console.log("success");
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.ajax({
                xhr: function() {
                    var xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener("progress", function(evt) {
                        if (evt.lengthComputable) {
                            var percentComplete = ((evt.loaded / evt.total) * 100);
                            $(".progress-bar").width(percentComplete + '%');
                            $(".progress-bar").html(percentComplete + '%');
                        }
                    }, false);
                    return xhr;
                },
                url: 'store_chat',
                enctype: 'multipart/form-data',
                type: "POST",
                dataType: 'json',
                data: fd,
                processData: false,
                contentType: false,
                async: true,
                cache: false,

                success: function(data) {
                    console.log(data.notificatio);
                    if (data.error == false) {

                        $('#Homeattachment').val('');
                        $('.custom-file-label').html('');
                        $("#Onmessage").val("");
                        console.log("success");

                        getAllMessage(0, 10);
                    }
                }
            });
        }

        function getAllMessage(offset, limit) {
            $.ajax({
                url: 'getAllMessage',
                type: "GET",
                dataType: 'json',
                data: {
                    property_id: $('#prop_id').val(),
                    client_id: $('#receiver_id').val(),
                    offset: offset,
                    limit: limit
                },
                async: true,
                cache: false,
                success: function(data) {

                    if (data != '') {
                        var html = '';
                        $("#chat").empty();


                        $.each(data.reverse(), function(i, item) {
                            console.log(item);

                            if (item.attachment == "") {
                                file = "";
                            } else {
                                file = '<img alt="Pic" src="' + item.attachment +
                                    '" style="height: 216px;width: 216px;"/><br>'
                            }
                            if (item.audio == "") {
                                audio = "";
                            } else {

                                audio = '<audio controls>' +
                                    '<source src="' + item.audio + '" type="audio/ogg">' +
                                    '<source src="' + item.audio + '" type="audio/mpeg">' +
                                    'Your browser does not support the audio element.' +
                                    '</audio>';

                            }

                            if (item.sender_type == '0') {
                                html += '<div class="d-flex flex-column mb-5 align-items-end">' +
                                    '<div class="d-flex align-items-center">' +
                                    '<div class="mt-2 rounded p-3 bg-light-primary text-dark-50 font-weight-bold font-size-sm  max-w-400px">' +
                                    audio + file + item.message +
                                    '</div>' +

                                    '</div>' +
                                    '<div>' +
                                    '<span  class="text-dark-75 text-hover-primary font-weight-bold font-size-sm">' +
                                    audio + item.ssendername + '</span>' +
                                    '&nbsp;<span class="text-muted font-size-sm">' + item.time_ago +
                                    ' </span></div></div>';


                            } else {
                                html += '<div class="d-flex flex-column mb-5 align-items-start">' +
                                    ' <div class="d-flex align-items-center">' +

                                    ' <div>' +
                                    '<div class="mt-2 rounded p-3 bg-light-success text-dark-50 font-weight-bold font-size-sm text-left max-w-400px">' +
                                    audio + file + item.message + '</div>' +
                                    '    <span  class="text-dark-75 text-hover-primary font-weight-bold font-size-sm">' +
                                    item.sendername + '</span>' +
                                    '   <span class="text-muted font-size-sm"> ' + item.time_ago +
                                    '</span>' +
                                    '</div>' +
                                    ' </div>' +
                                    '</div>';

                            }

                        });

                        $("#chat").html(html);

                    } else {
                        // $("#Onmessage").html("");
                        $("#chat").html("");
                        $("#chat").append("No message");

                    }
                }
            });
        }

        function setallMessage(id, c_id) {
            $("#chat").html("");
            $(".issueTitle").html("");
            console.log("client:" + c_id);
            // var issue = atob($('#' + id).data('issue'));
            property_id = id;
            client_id = c_id;

            $.ajax({
                url: 'getAllMessage',
                type: "GET",
                dataType: 'json',
                data: {
                    property_id: property_id,
                    client_id: c_id,
                    offset: 0,
                    limit: 10
                },
                async: true,
                cache: false,
                success: function(data) {

                    if (data != '') {
                        var html = '';
                        $("#chat").empty();


                        $.each(data.reverse(), function(i, item) {
                            console.log(item);

                            if (item.attachment == "") {
                                file = "";
                            } else {
                                file = '<img alt="Pic" src="' + item.attachment +
                                    '"style="height: 216px;width: 216px;"/><br>'
                            }
                            if (item.audio == "") {
                                audio = "";
                            } else {

                                audio = '<audio controls>' +
                                    '<source src="' + item.audio + '" type="audio/ogg">' +
                                    '<source src="' + item.audio + '" type="audio/mpeg">' +
                                    'Your browser does not support the audio element.' +
                                    '</audio>';

                            }

                            if (item.sender_type == '0') {
                                html += '<div class="d-flex flex-column mb-5 align-items-end">' +
                                    '<div class="d-flex align-items-center">' +
                                    '<div class="mt-2 rounded p-3 bg-light-primary text-dark-50 font-weight-bold font-size-sm  max-w-400px">' +
                                    audio + file + item.message +
                                    '</div>' +
                                    '</div>' +
                                    '<div>' +
                                    '<span  class="text-dark-75 text-hover-primary font-weight-bold font-size-sm">' +
                                    item.ssendername + '</span>' +
                                    '&nbsp;<span class="text-muted font-size-sm">' + item.time_ago +
                                    ' </span></div></div>';

                            } else {
                                html += '<div class="d-flex flex-column mb-5 align-items-start">' +
                                    ' <div class="d-flex align-items-center">' +
                                    ' <div>' +
                                    '<div class="mt-2 rounded p-3 bg-light-success text-dark-50 font-weight-bold font-size-sm text-left max-w-400px">' +
                                    audio + file + item.message + '</div>' +
                                    '    <span  class="text-dark-75 text-hover-primary font-weight-bold font-size-sm">' +
                                    item.sendername + '</span>' +
                                    '   <span class="text-muted font-size-sm"> ' + item.time_ago +
                                    '</span>' +
                                    '</div>' +
                                    ' </div>' +
                                    '</div>';

                            }

                        });

                        $("#chat").html(html);
                        $('#myscroll').animate({
                            scrollTop: $('#myscroll').get(0).scrollHeight
                        }, 1500);
                    } else {
                        // $("#Onmessage").html("");
                        $("#chat").html("");
                        $("#chat").append("No message");

                    }
                }
            });
        }
    </script>
    <script type="text/javascript">
        messaging.onMessage(function(payload) {
            console.log(payload);

            if (payload.data.file == "") {
                file = "";
            } else {
                file = '<img alt="Pic" src="' + payload.data.file +
                    '" /><br>'
            }
            if (payload.data.audio == "") {
                audio = "";
            } else {
                audio = '<audio controls>' +
                    '<source src="' + payload.data.audio + '" type="audio/ogg">' +
                    '<source src="' + payload.data.audio + '" type="audio/mpeg">' +
                    'Your browser does not support the audio element.' +
                    '</audio>';


            }

            if (payload.data.type == 'chat') {

                html1 = '<div class="d-flex flex-column mb-5 align-items-start">' +
                    ' <div class="d-flex align-items-center">' +
                    '    <div class="symbol symbol-circle symbol-40 mr-3">' +
                    '       <img alt="Pic" src="' + payload.data.profile + '" />' +
                    '  </div>' +
                    ' <div>' +
                    '<div class="mt-2 rounded p-3 bg-light-success text-dark-50 font-weight-bold font-size-sm text-left max-w-400px">' +

                    audio + file + payload.data.message + '</div>' +
                    '    <span  class="text-dark-75 text-hover-primary font-weight-bold font-size-sm">' + payload
                    .data.username + '</span>' +
                    '   <span class="text-muted font-size-sm"> ' + payload.data.time_ago + '</span>' +
                    '</div>' +
                    ' </div>' +
                    '</div>';
                $("#chat").append(html1);
                $('#myscroll').animate({
                    scrollTop: $('#myscroll').get(0).scrollHeight
                }, 1500);
            }

        });
    </script>
@endsection
