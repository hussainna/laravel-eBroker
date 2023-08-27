@extends('layouts.main')

@section('title')
    {{ __('Property') }}
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
                            {{ Form::submit(__('Add Property'), ['class' => 'btn btn-primary']) }}
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
                            <option value="">{{ __('Select Category') }}</option>
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
                            <option value="">{{ __('Select Status') }} </option>
                            <option value="0">{{ __('InActive') }}</option>
                            <option value="1">{{ __('Active') }}</option>
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
                            data-trim-on-search="false" data-responsive="true" data-sort-name="id" data-sort-order="desc"
                            data-pagination-successively-size="3" data-query-params="queryParams">
                            <thead>
                                <tr>
                                    <th scope="col" data-field="id" data-align="center" data-sortable="true">
                                        {{ __('ID') }}</th>
                                    <th scope="col" data-field="added_by" data-align="center" data-sortable="false">
                                        {{ __('Client Name') }}</th>
                                    <th scope="col" data-field="mobile" data-align="center" data-sortable="false">
                                        {{ __('Mobile') }}
                                    </th>
                                    <th scope="col" data-field="client_address" data-align="center"
                                        data-sortable="false">{{ __('Client Address') }}</th>
                                    <th scope="col" data-field="title" data-align="center" data-sortable="false">Title
                                    </th>
                                    <th scope="col" data-field="address" data-align="center" data-sortable="false">
                                        {{ __('Address') }}</th>

                                    <th scope="col" data-field="category" data-align="center" data-sortable="true">
                                        {{ __('Category') }}</th>

                                    <th scope="col" data-field="status" data-align="center" data-sortable="false">
                                        {{ __('Status') }}</th>
                                    <th scope="col" data-field="title_image" data-align="center" data-sortable="false">
                                        {{ __('Image') }}</th>
                                    <th scope="col" data-field="3d_image" data-align="center" data-sortable="false">
                                        {{ __('3D Image') }}</th>
                                    <th scope="col" data-field="total_interested_users" data-align="center"
                                        data-sortable="false">
                                        {{ __('Total Interested Users') }}</th>

                                    <th scope="col" data-field="enble_disable" data-sortable="false" data-align="center"
                                        data-width="5%">
                                        {{ __('Enable/Disable') }}</th>
                                    @if (has_permissions('update', 'property_inquiry'))
                                        <th scope="col" data-field="operate" data-align="center" data-sortable="false">
                                            {{ __('Action') }}</th>
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

        function initMap() {
            var latitude = parseFloat($('#latitude').val());
            var longitude = parseFloat($('#longitude').val());
            var map = new google.maps.Map(document.getElementById('map'), {

                center: {
                    lat: latitude,
                    lng: longitude
                },
                zoom: 13
            });
            var marker = new google.maps.Marker({
                position: {
                    lat: latitude,
                    lng: longitude
                },
                map: map,
                draggable: true,
                title: 'Marker Title'
            });
            google.maps.event.addListener(marker, 'dragend', function(event) {
                var geocoder = new google.maps.Geocoder();
                geocoder.geocode({
                    'latLng': event.latLng
                }, function(results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        if (results[0]) {
                            var address_components = results[0].address_components;
                            var city, state, country, full_address;

                            for (var i = 0; i < address_components.length; i++) {
                                var types = address_components[i].types;
                                if (types.indexOf('locality') != -1) {
                                    city = address_components[i].long_name;
                                } else if (types.indexOf('administrative_area_level_1') != -1) {
                                    state = address_components[i].long_name;
                                } else if (types.indexOf('country') != -1) {
                                    country = address_components[i].long_name;
                                }
                            }

                            full_address = results[0].formatted_address;

                            // Do something with the city, state, country, and full address

                            $('#city').val(city);
                            $('#country').val(state);
                            $('#state').val(country);
                            $('#address').val(full_address);


                            $('#latitude').val(event.latLng.lat());
                            $('#longitude').val(event.latLng.lng());

                        } else {
                            console.log('No results found');
                        }
                    } else {
                        console.log('Geocoder failed due to: ' + status);
                    }
                });
            });

            var input = document.getElementById('searchInput');
            map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

            var autocomplete = new google.maps.places.Autocomplete(input);
            autocomplete.bindTo('bounds', map);

            var infowindow = new google.maps.InfoWindow();
            var marker = new google.maps.Marker({
                map: map,
                anchorPoint: new google.maps.Point(0, -29)
            });

            autocomplete.addListener('place_changed', function() {
                infowindow.close();
                marker.setVisible(false);
                var place = autocomplete.getPlace();
                if (!place.geometry) {
                    window.alert("Autocomplete's returned place contains no geometry");
                    return;
                }

                // If the place has a geometry, then present it on a map.
                if (place.geometry.viewport) {
                    map.fitBounds(place.geometry.viewport);
                } else {
                    map.setCenter(place.geometry.location);
                    map.setZoom(17);
                }
                marker.setIcon(({
                    url: place.icon,
                    size: new google.maps.Size(71, 71),
                    origin: new google.maps.Point(0, 0),
                    anchor: new google.maps.Point(17, 34),
                    scaledSize: new google.maps.Size(35, 35)
                }));
                marker.setPosition(place.geometry.location);
                marker.setVisible(true);

                var address = '';
                if (place.address_components) {
                    address = [
                        (place.address_components[0] && place.address_components[0].short_name || ''),
                        (place.address_components[1] && place.address_components[1].short_name || ''),
                        (place.address_components[2] && place.address_components[2].short_name || '')
                    ].join(' ');
                }

                infowindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
                infowindow.open(map, marker);

                // Location details
                for (var i = 0; i < place.address_components.length; i++) {
                    console.log(place);

                    if (place.address_components[i].types[0] == 'locality') {
                        $('#city').val(place.address_components[i].long_name);


                    }
                    if (place.address_components[i].types[0] == 'country') {
                        $('#country').val(place.address_components[i].long_name);


                    }
                    if (place.address_components[i].types[0] == 'administrative_area_level_1') {
                        console.log(place.address_components[i].long_name);
                        $('#state').val(place.address_components[i].long_name);


                    }
                }


                var latitude = place.geometry.location.lat();
                var longitude = place.geometry.location.lng();
                $('#address').val(place.formatted_address);


                $('#latitude').val(place.geometry.location.lat());
                $('#longitude').val(place.geometry.location.lng());
            });
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
                            text: "Something Went Wrong",
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
                            text: "Something Went Wrong",
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
