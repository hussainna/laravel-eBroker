@extends('layouts.main')

@section('title')
    Advertisment
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
    <section class="section">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <table class="table-light" aria-describedby="mydesc" class='table-striped' id="table_list"
                            data-toggle="table" data-url="{{ url('advertisement_list') }}" data-click-to-select="true"
                            data-side-pagination="server" data-pagination="true"
                            data-page-list="[5, 10, 20, 50, 100, 200,All]" data-search="true" data-search-align="right"
                            data-toolbar="#toolbar" data-show-columns="true" data-show-refresh="true"
                            data-fixed-columns="true" data-fixed-number="1" data-fixed-right-number="1"
                            data-trim-on-search="false" data-mobile-responsive="true" data-sort-name="id"
                            data-sort-order="desc" data-pagination-successively-size="3" data-query-params="queryParams">
                            <thead>
                                <tr>
                                    <th scope="col" data-field="id" data-halign="center" data-sortable="true">ID</th>
                                    <th scope="col" data-field="type" data-halign="center" data-sortable="false">
                                        Type</th>
                                    <th scope="col" data-field="image" data-halign="center" data-sortable="false">Image
                                    </th>
                                    {{-- <th scope="col" data-field="description" data-halign="center" data-sortable="false">
                                        Description
                                    </th> --}}
                                    <th scope="col" data-field="start_date" data-halign="center" data-sortable="false">
                                        Start Date</th>

                                    <th scope="col" data-field="end_date" data-halign="center" data-sortable="false">
                                        End Date</th>

                                    <th scope="col" data-field="user_name" data-halign="center" data-sortable="true">
                                        Customer Name</th>
                                    <th scope="col" data-field="user_contact" data-halign="center" data-visible="false"
                                        data-sortable="false">User Contact</th>
                                    <th scope="col" data-field="user_email" data-halign="center"data-visible="false"
                                        data-sortable="false">User Email</th>
                                    <th scope="col" data-field="is_enable" data-halign="center" data-sortable="false">
                                        Enable/Disable</th>
                                    <th scope="col" data-field="status" data-halign="center" data-sortable="false">Status
                                    </th>
                                    @if (has_permissions('update', 'property_inquiry'))
                                        <th scope="col" data-field="operate" data-halign="center" data-sortable="false">
                                            Action</th>
                                    @endif

                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>


        <!-- EDIT MODEL MODEL -->
        <div id="editModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h6 class="modal-title" id="myModalLabel1">Advertisement Status</h6>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">

                        <form action="{{ url('adv-status-update') }}" class="form-horizontal" enctype="multipart/form-data"
                            method="POST" data-parsley-validate>

                            {{ csrf_field() }}


                            <div class="row">



                                <div class="col-sm-12">



                                    <select name="edit_adv_status" id="edit_adv_status" class="chosen-select form-select"
                                        style="width: 100%">

                                        <option value='0'>approved</option>
                                        <option value='1'>pending</option>
                                        <option value='2'>rejected</option>

                                    </select>
                                    <input type="hidden" name="id" id="id">


                                </div>


                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary waves-effect"
                                    data-bs-dismiss="modal">Close</button>

                                <button type="submit" class="btn btn-primary waves-effect waves-light">Save</button>
                        </form>
                    </div>
                </div>
                <!-- /.modal-content -->
            </div>
            <!-- /.modal-dialog -->
        </div>
        <input type="hidden" id="customerid" value="{{ isset($_GET['customer']) ? $_GET['customer'] : '' }}">
    </section>
@endsection

@section('script')
    <script>
        $('#status').on('change', function() {
            $('#table_list').bootstrapTable('refresh');

        });

        $('#category').on('change', function() {
            $('#table_list').bootstrapTable('refresh');

        });
        $(document).ready(function() {
            var params = new window.URLSearchParams(window.location.search);
            if (params.get('status') != 'null') {
                $('#status').val(params.get('status')).trigger('change');
            }
        });

        function setValue(id) {

            $("#id").val(id);
            // $("#edit_category").val($("#" + id).parents('tr:first').find('td:nth-child(3)').text());
        }

        function queryParams(p) {

            return {
                sort: p.sort,
                order: p.order,
                offset: p.offset,
                limit: p.limit,
                search: p.search,
                status: $('#status').val(),
                category: $('#category').val(),
                customer_id: $('#customerid').val(),
            };
        }

        function disable(id) {
            $.ajax({
                url: "{{ route('advertisement.updateadvertisementstatus') }}",
                type: "POST",
                data: {
                    '_token': "{{ csrf_token() }}",
                    "id": id,
                    "is_enable": 0,
                },
                cache: false,
                success: function(result) {

                    if (result.error == false) {
                        Toastify({
                            text: 'Advertisement is Disable ',
                            duration: 6000,
                            close: !0,
                            backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)"
                        }).showToast();
                        $('#table_list').bootstrapTable('refresh');
                    } else {
                        Toastify({
                            text: result.message,
                            duration: 6000,
                            close: !0,
                            backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)"
                        }).showToast();
                        $('#table_list').bootstrapTable('refresh');
                    }

                },
                error: function(error) {

                }
            });
        }

        function active(id) {
            $.ajax({
                url: "{{ route('advertisement.updateadvertisementstatus') }}",
                type: "POST",
                data: {
                    '_token': "{{ csrf_token() }}",
                    "id": id,
                    "is_enable": 1,
                },
                cache: false,
                success: function(result) {

                    if (result.error == false) {
                        Toastify({
                            text: 'Advertisement is Enable',
                            duration: 6000,
                            close: !0,
                            backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)"
                        }).showToast();
                        $('#table_list').bootstrapTable('refresh');
                    } else {
                        Toastify({
                            text: result.message,
                            duration: 6000,
                            close: !0,
                            backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)"
                        }).showToast();
                        $('#table_list').bootstrapTable('refresh');
                    }
                },
                error: function(error) {

                }
            });
        }
    </script>
@endsection
