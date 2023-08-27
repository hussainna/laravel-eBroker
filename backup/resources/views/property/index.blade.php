@extends('layouts.main')

@section('title')
    Property
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
            @if (has_permissions('create', 'property'))
                <div class="card-header">

                    <div class="row ">
                        <div class="col-12 col-xs-12 d-flex justify-content-end">

                            {!! Form::open(['route' => 'property.create']) !!}
                            {{ method_field('get') }}
                            {{ Form::submit('Add Property', ['class' => 'btn btn-primary']) }}
                            {!! Form::close() !!}
                        </div>

                    </div>
                </div>
            @endif

            <hr>
            <div class="card-body">

                <div class="row " id="toolbar">

                    <div class="col-sm-6">

                        {{-- {{ Form::label('category', 'Category', ['class' => 'form-label col-12 text-center']) }} --}}
                        <select class="form-select form-control-sm" id="category">
                            <option value="">Select Category</option>
                            @if (isset($category))
                                @foreach ($category as $row)
                                    <option value="{{ $row->id }}">{{ $row->category }} </option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="col-sm-6">

                        {{-- {{ Form::label('status', 'Status', ['class' => 'form-label col-12 text-center']) }} --}}
                        <select id="status" class="form-select form-control-sm">
                            <option value="">Select Status </option>
                            <option value="0">InActive</option>
                            <option value="1">Active</option>
                        </select>
                    </div>

                </div>

                <div class="row">
                    <div class="col-12">
                        <table class="table-light" aria-describedby="mydesc" class='table-striped' id="table_list"
                            data-toggle="table" data-url="{{ url('getPropertyList') }}" data-click-to-select="true"
                            data-side-pagination="server" data-pagination="true"
                            data-page-list="[5, 10, 20, 50, 100, 200,All]" data-search="true" data-search-align="right"
                            data-toolbar="#toolbar" data-show-columns="true" data-show-refresh="true"
                            data-fixed-columns="true" data-fixed-number="1" data-fixed-right-number="1"
                            data-trim-on-search="false" data-mobile-responsive="true" data-sort-name="id"
                            data-sort-order="desc" data-pagination-successively-size="3" data-query-params="queryParams">
                            <thead>
                                <tr>
                                    <th scope="col" data-field="id" data-halign="center" data-sortable="true">ID</th>
                                    <th scope="col" data-field="added_by" data-halign="center" data-sortable="false">
                                        Client Name</th>
                                    <th scope="col" data-field="mobile" data-halign="center" data-sortable="false">Mobile
                                    </th>
                                    <th scope="col" data-field="client_address" data-halign="center"
                                        data-sortable="false">Client Address</th>
                                    <th scope="col" data-field="title" data-halign="center" data-sortable="false">Title
                                    </th>
                                    <th scope="col" data-field="address" data-halign="center" data-sortable="false">
                                        Address</th>

                                    <th scope="col" data-field="category" data-halign="center" data-sortable="true">
                                        Category</th>
                                    <th scope="col" data-field="type" data-halign="center" data-visible="false"
                                        data-sortable="false">Type</th>
                                    <th scope="col" data-field="parameters" data-halign="center"data-visible="false"
                                        data-sortable="false">Parameters</th>



                                    <th scope="col" data-field="status" data-halign="center" data-sortable="false">
                                        Status</th>
                                    <th scope="col" data-field="title_image" data-halign="center" data-sortable="false">
                                        Image</th>
                                    <th scope="col" data-field="3d_image" data-halign="center" data-sortable="false">
                                        3D Image</th>
                                    @if (has_permissions('update', 'property_inquiry'))
                                        <th scope="col" data-field="operate" data-halign="center"
                                            data-sortable="false">
                                            Action</th>
                                    @endif

                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>


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
                url: "{{ route('property.updatepropertystatus') }}",
                type: "POST",
                data: {
                    '_token': "{{ csrf_token() }}",
                    "id": id,
                    "status": 0,
                },
                cache: false,
                success: function(result) {

                    if (result.error == false) {
                        Toastify({
                            text: 'Property Deactive successfully',
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
                url: "{{ route('property.updatepropertystatus') }}",
                type: "POST",
                data: {
                    '_token': "{{ csrf_token() }}",
                    "id": id,
                    "status": 1,
                },
                cache: false,
                success: function(result) {

                    if (result.error == false) {
                        Toastify({
                            text: 'Property Active successfully',
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
