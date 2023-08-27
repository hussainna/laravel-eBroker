@extends('layouts.main')
{{-- {{ dd($state) }} --}}

@section('title')
    Edit Property
@endsection

@section('page-title')
    <div class="page-title">


        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h4>@yield('title')</h4>

            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item">
                            <a href="{{ route('property.index') }}" id="subURL">View Property</a>
                        </li>
                        <li class="breadcrumb-item active" aria-current="page">
                            Edit
                        </li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>
@endsection


@section('content')
    <section class="section">
        <div class="card">


            {!! Form::open([
                'route' => ['property.update', $id],
                'method' => 'PATCH',
                'data-parsley-validate',
                'files' => true,
            ]) !!}
            <div class="card-body">

                <div class="row ">

                    {{-- {{ dd($list) }} --}}

                    <div class="col-md-6 col-12 form-group mandatory">

                        {{ Form::label('title', 'Title', ['class' => 'form-label col-12']) }}
                        {{ Form::text('title', isset($list->title) ? $list->title : '', ['class' => 'form-control ', 'placeholder' => 'Title', 'data-parsley-required' => 'true', 'id' => 'title']) }}

                    </div>
                    <div class="col-md-4 form-group  mandatory">
                        {{ Form::label('', 'Propery Type', ['class' => 'form-label col-12']) }}

                        <div class="control-label col-form-label form-check form-check-inline mb-2">
                            <input type="radio" name="property_type" value="0"
                                {{ isset($list->propery_type) && $list->propery_type == 0 ? 'checked' : '' }}
                                class="form-check-input" id="property_type">
                            {{ Form::label('property_type', 'Sell', ['class' => 'form-check-label']) }}
                        </div>
                        <div class="control-label col-form-label form-check form-check-inline mb-2">

                            <input type="radio" name="property_type" value="1"
                                {{ isset($list->propery_type) && $list->propery_type == 1 ? 'checked' : '' }}
                                class="form-check-input" id="property_type">
                            {{ Form::label('property_type', 'Rent', ['class' => 'form-check-label']) }}
                        </div>
                    </div>
                    <div class="col-md-2 form-group  mandatory">
                        {{ Form::label('price', 'Price', ['class' => 'form-label col-12']) }}
                        {{ Form::text('price', isset($list->price) ? $list->price : '', ['class' => 'form-control ', 'placeholder' => 'Price', 'min' => '1', 'data-parsley-required' => 'true', 'id' => 'title']) }}

                    </div>

                </div>
                <div class="row">


                    <div class="col-md-12 col-12 ">

                        <div class="divider">
                            <div class="divider-text">Address</div>
                        </div>
                        <div class="row mt-10">





                            <div class="col-md-2 col-12 form-group">
                                {{ Form::label('country', 'Country', ['class' => 'form-label col-12']) }}
                                <select name="country" id="country" class="select2 form-select form-control-sm">
                                    <option value=""> Select Option </option>
                                    @foreach ($country as $row)
                                        <option value="{{ $row }}"
                                            {{ strval($list->country) == strval($row) ? ' selected=selected' : '' }}>
                                            {{ $row }} </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-2 col-12 form-group">
                                {{ Form::label('state', 'State', ['class' => 'form-label col-12']) }}
                                <select name="state" id="state" class="select2 form-select form-control-sm">
                                    <option value=""> Select Option </option>
                                    {{ $list->state }}
                                    @foreach ($state as $row)
                                        <option value="{{ $row }}"
                                            {{ strval($list->state) == strval($row) ? ' selected=selected' : '' }}>
                                            {{ $row }} </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3 col-12 form-group">
                                {{ Form::label('City', 'City', ['class' => 'form-label col-12']) }}
                                {{ Form::text('city', isset($list->city) ? $list->city : '', ['class' => 'form-control ', 'placeholder' => 'City', 'id' => 'district']) }}

                            </div>
                            <div class="col-md-2 form-group  mandatory">
                                {{ Form::label('latitude', 'Latitude', ['class' => 'form-label col-12']) }}
                                {{ Form::text('latitude', isset($list->latitude) ? $list->latitude : '', ['class' => 'form-control ', 'placeholder' => 'Latitude', 'data-parsley-required' => 'true', 'id' => 'latitude']) }}

                            </div>
                            <div class="col-md-2 form-group  mandatory">
                                {{ Form::label('longitude', 'Longitude', ['class' => 'form-label col-12']) }}
                                {{ Form::text('longitude', isset($list->longitude) ? $list->longitude : '', ['class' => 'form-control ', 'placeholder' => 'Longitude', 'data-parsley-required' => 'true', 'id' => 'longitude']) }}

                            </div>

                            <div class="row mt-4">

                                <div class="col-md-6 col-12 form-group mandatory">
                                    {{ Form::label('address', 'Client Address', ['class' => 'form-label col-12']) }}
                                    {{ Form::textarea('client_address', isset($list->client_address) ? $list->client_address : '', ['class' => 'form-control', 'placeholder' => 'Client Address', 'rows' => '2', 'id' => 'address', 'autocomplete' => 'off', 'data-parsley-required' => 'true']) }}
                                </div>

                                <div class="col-md-6 col-12 form-group mandatory">
                                    {{ Form::label('address', 'Address', ['class' => 'form-label col-12']) }}
                                    {{ Form::textarea('address', isset($list->address) ? $list->address : '', ['class' => 'form-control ', 'placeholder' => 'Address', 'rows' => '2', 'id' => 'address', 'autocomplete' => 'off', 'data-parsley-required' => 'true']) }}
                                </div>


                            </div>
                        </div>
                        <hr>
                    </div>
                </div>





                <div class="row  mt-4">


                    <div class="col-md-12 col-12 form-group">
                        {{ Form::label('category', 'Category', ['class' => 'form-label col-12']) }}
                        <select name="category" class="select2 form-select form-control-sm" data-parsley-minSelect='1'
                            id="category" required='true'>
                            <option value=""> Select Option </option>
                            @foreach ($category as $row)
                                <option value="{{ $row->id }}"
                                    {{ $list->category_id == $row->id ? ' selected=selected' : '' }}
                                    data-parametertypes='{{ $row->parameter_types }}'> {{ $row->category }}
                                </option>
                            @endforeach
                            <option value=""></option>

                        </select>

                    </div>



                    {{-- <div class="col-md-6 col-12 form-group mandatory">
                            {{ Form::label('unit_type', 'Unit Type', ['class' => 'form-label col-12']) }}

                            <select name="unit_type" class="select2 form-select form-control-sm" data-parsley-minSelect='1'
                                id="unit_type" required='true'>
                                <option value=""> Select Option </option>
                                @foreach ($unittype as $key => $value)
                                    <option value="{{ $key }}"
                                        {{ $list->unit_type == $key ? ' selected=selected' : '' }}>
                                        {{ $value }} </option>
                                @endforeach
                            </select>
                        </div> --}}





                    <div class="divider">
                        <div class="divider-text unit">Facilities</div>
                    </div>

                    <div class="row  mt-4" id="parameters">
                        {{ Form::hidden('category_count[]', $category, ['id' => 'category_count']) }}
                        {{ Form::hidden('parameter_count[]', $parameters, ['id' => 'parameter_count']) }}
                        {{ Form::hidden('parameter_add', '', ['id' => 'parameter_add']) }}
                        <div id="parameter_type" name=parameter_type class="row">
                            {{-- {{ print_r($parameters->toarray()) }} --}}
                            @foreach ($parameters as $res)
                                {{-- {{ print_r($res->name) }} --}}
                                {{-- {{ print_r($par_arr) }} --}}

                                @foreach ($par_arr as $key => $arr)
                                    {{-- {{ print_r($key) }} --}}


                                    @if ($key == $res->name)
                                        <div class="col-md-2 form-group  mandatory">


                                            {{ Form::label($res->name, $res->name, ['class' => 'form-label col-12']) }}

                                            @if ($res->type_of_parameter == 'dropdown')
                                                <select name="unit_type" class="select2 form-select form-control-sm"
                                                    data-parsley-minSelect='1' id="unit_type" required='true'
                                                    name={{ $res->id }}>

                                                    <option value=""> Select Option </option>
                                                    @foreach ($res->type_values as $key => $value)
                                                        <option value="{{ $key }}"
                                                            {{ $arr == $value ? ' selected=selected' : '' }}>
                                                            {{ $value }} </option>
                                                    @endforeach
                                                </select>
                                            @endif


                                            @if ($res->type_of_parameter == 'radiobutton')
                                                @foreach ($res->type_values as $key => $value)
                                                    {{-- {{ $arr }} --}}
                                                    <input type="radio" name="{{ $res->id }}" id=""
                                                        value={{ $value }} class="form-check-input"
                                                        {{ $value == $value ? 'checked' : '' }}>
                                                    {{ $value }}
                                                @endforeach
                                                </select>
                                            @endif
                                            @if ($res->type_of_parameter == 'number')
                                                <input type="number" name="{{ $res->id }}" id=""
                                                    class="form-control" value="{{ $arr }}">

                                                </select>
                                            @endif
                                            @if ($res->type_of_parameter == 'textbox')
                                                <input type="text" name="{{ $res->id }}" id=""
                                                    class="form-control" value="{{ $arr }}">

                                                </select>
                                            @endif
                                            @if ($res->type_of_parameter == 'textarea')
                                                <textarea name="{{ $res->id }}" id="" cols="10" rows="10" value="{{ $arr }}"></textarea>
                                            @endif
                                            @if ($res->type_of_parameter == 'checkbox')
                                                @foreach ($res->type_values as $key => $value)
                                                    {{-- {{ print_r($arr) }} --}}
                                                    <input type="checkbox" name="{{ $res->id . '[]' }}" id=""
                                                        class="form-check-input" value={{ $value }}
                                                        {{ in_array($value, $arr) ? 'checked' : '' }}>
                                                    {{ $value }}
                                                @endforeach
                                                </select>
                                            @endif

                                            @if ($res->type_of_parameter == 'file')
                                                <a href="{{ url('') . config('global.IMG_PATH') . config('global.PARAMETER_IMG_PATH') . '/' . $arr }}"
                                                    class="text-center col-12" style="text-align: center"> Click
                                                    here to View</a> OR
                                                <input type="hidden" name="{{ $res->id }}"
                                                    value="{{ $arr }}">
                                                <input type="file" class='form-control' name="{{ $res->id }}"
                                                    id='edit_param_img'>
                                            @endif

                                            {{-- {{ Form::text($res->id, $arr, ['data-parsley-required' => 'true', 'id' => $res->id, 'class' => 'form-control mt-3']) }} --}}
                                        </div>
                                    @endif
                                    {{-- @break --}}
                                @endforeach
                            @endforeach


                        </div>





                    </div>
                    <hr class=" mt-5">



                    <div class="row mt-4">

                        <div class="col-md-6 col-12 form-group">

                            {{ Form::label('title_image', 'Title Image', ['class' => 'col-12 form-label']) }}

                            {{ Form::file('title_image', ['class' => 'form-control', 'id' => 'title_img_input']) }}
                            @if (count($errors) > 0)
                                @foreach ($errors->all() as $error)
                                    <div class="alert alert-danger error-msg">{{ $error }}</div>
                                @endforeach
                            @endif
                            <div class="col-sm-2 preview-item mt-5 title_img" data-kt-image-input="true"
                                id='{{ $row->id }}'>
                                @if ($list->title_image != '')
                                    <img class="rounded  shadow img-fluid" alt="" src="{{ $list->title_image }}"
                                        style="height: 165px; width:100%">
                                    {{-- </a> --}}

                                    <button class="remove-btn">X</button>
                                @endif
                            </div>

                            <input type="hidden" value="{{ $list->title_image }}" name="old_title_image">
                            <input type="hidden" name="title_image_length" id=title_image_length>

                        </div>



                        <div class="col-md-6 col-12 form-group">

                            {{ Form::label('3d_image', '3D Image', ['class' => 'col-12 form-label']) }}

                            {{ Form::file('3d_image', ['class' => 'form-control', 'id' => '3d_img_input']) }}

                            <div class="col-sm-2 preview-item mt-5 3d_img" data-kt-image-input="true"
                                id='{{ $row->id }}'>
                                @if ($list->threeD_image != '')
                                    <img class="rounded  shadow img-fluid" alt=""
                                        src="{{ $list->threeD_image }}" style="height: 165px; width:100%">
                                    {{-- </a> --}}

                                    <button class="remove-btn">X</button>
                                @endif
                                <input type="hidden" value="{{ $list->threeD_image }}" name="old_3d_image">
                                <input type="hidden" name="_3d_image_length" id=_3d_image_length>

                            </div>
                        </div>









                        <div class="col-md-6 col-12 form-group">

                            {{ Form::label('gallery_images', 'Gallery Images', ['class' => 'form-label col-12']) }}

                            {{ Form::file('gallery_images[]', ['class' => 'form-control', 'multiple' => true, 'id' => 'img_input']) }}
                            @if (count($errors) > 0)
                                @foreach ($errors->all() as $error)
                                    <div class="alert alert-danger error-msg">{{ $error }}</div>
                                @endforeach
                            @endif

                        </div>
                        <div class="col-md-6 col-12 form-group">

                            {{ Form::label('video_link', 'Video Link', ['class' => 'form-label col-12']) }}

                            {{ Form::text('video_link', isset($list->video_link) ? $list->video_link : '', ['class' => 'form-control ', 'placeholder' => 'video link', 'id' => 'video_link']) }}


                        </div>

                    </div>
                    <style>
                        .imgs {
                            display: flex;
                            flex-wrap: wrap;
                        }

                        .preview-item {
                            position: relative;
                            margin: 10px;
                        }

                        .remove-btn {
                            position: absolute;
                            top: 5px;
                            right: 5px;
                            background-color: #F44336;
                            color: white;
                            border: none;
                            border-radius: 50%;
                            padding: 5px;
                            font-size: 12px;
                            cursor: pointer;
                        }

                        .remove-btn:hover {
                            background-color: #FF6666;
                        }

                        img {
                            max-width: 100%;
                            height: auto;
                            object-fit: fill;
                        }
                    </style>

                    <div class="row mt-4">
                        {{-- <div class="col-md-3 col-12 form-group"> --}}

                        <div class="row imgs preview">
                            <?php $i = 0; ?>
                            @if (!empty($list->gallery))
                                @foreach ($list->gallery as $row)
                                    <div class="col-sm-2 preview-item mt-5" data-kt-image-input="true"
                                        id='{{ $row->id }}'>

                                        <img class="rounded  shadow img-fluid" alt=""
                                            src="{{ url('') . config('global.IMG_PATH') . config('global.PROPERTY_GALLERY_IMG_PATH') . $list->id . '/' . $row->image }}"
                                            style="height: 165px; width:100%">
                                        {{-- </a> --}}

                                        <button class="remove-btn 3d" data-rowid="{{ $row->id }}">X</button>
                                    </div>




                                    <?php $i++; ?>
                                @endforeach
                            @endif
                        </div>

                    </div>

                    <div class="row
                                            mt-4">





                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12 col-12 form-group mandatory">
                            {{ Form::label('description', 'Description', ['class' => 'form-label col-12']) }}


                            {{ Form::textarea('description', isset($list->description) ? $list->description : '', ['class' => 'form-control ', 'rows' => '10', 'data-parsley-required' => 'true']) }}

                        </div>
                    </div>








                    <div class="card-footer">
                        <div class="col-12 d-flex justify-content-end">

                            {{ Form::submit('Save', ['class' => 'btn btn-primary me-1 mb-1']) }}
                        </div>

                    </div>
                </div>
            </div>


        </div>
        {!! Form::close() !!}

    </section>
@endsection

@section('script')
    <script>
        $(document).on('click', '.remove-btn', function(e) {
            e.preventDefault();


            $(this).parent().remove();
            var title_image = $('.title_img').children();
            var _3d_image = $('.3d_img').children();

            if (title_image.length == 0) {
                console.log("in");

                $('#title_image_length').val(0);

            }
            if (_3d_image.length == 0) {
                console.log("in");

                $('#_3d_image_length').val(0);

            }
        });
        $('#img_input').on('change', function() {
            // Get the selected files
            var files = $(this)[0].files;
            // Loop through each selected file
            for (var i = 0; i < files.length; i++) {
                // Create a new FileReader instance
                var reader = new FileReader();
                // Set the onload function to generate a preview
                reader.onload = function(e) {
                    $('.imgs').append('<div class="col-sm-2 preview-item mt-5"><img src="' +
                        e.target
                        .result +
                        '" style="height: 165px; width:100%"><button class="remove-btn">X</button></div>');
                }
                // Read the selected file as a data URL
                reader.readAsDataURL(files[i]);
            }
        });
        $('#title_img_input').on('change', function() {
            // Get the selected files
            var files = $(this)[0].files;
            // Loop through each selected file
            for (var i = 0; i < files.length; i++) {
                // Create a new FileReader instance
                var reader = new FileReader();
                // Set the onload function to generate a preview
                reader.onload = function(e) {
                    $('.title_img').empty();
                    $('.title_img').append('<div class="col-sm-12 preview-item mt-5"><img src="' +
                        e.target
                        .result +
                        '" style="height: 165px; width:100%"><button class="remove-btn">X</button></div>');
                }
                // Read the selected file as a data URL
                reader.readAsDataURL(files[i]);
            }
        });

        $('#3d_img_input').on('change', function() {
            // Get the selected files
            // $('.3d_img').empty();

            var files = $(this)[0].files;
            // Loop through each selected file
            for (var i = 0; i < files.length; i++) {
                // Create a new FileReader instance
                var reader = new FileReader();
                // Set the onload function to generate a preview
                reader.onload = function(e) {
                    $('.3d_img').empty();
                    $('.3d_img').append('<div class="col-sm-12 preview-item mt-5"><img src="' +
                        e.target
                        .result +
                        '" style="height: 165px; width:100%"><button class="remove-btn" >X</button></div>');
                }
                // Read the selected file as a data URL
                reader.readAsDataURL(files[i]);
            }
        });
        $('#unit_type').change(function() {
            $('.unit').empty();
            $('.unit').append('Unit Type (' + $('#unit_type :selected').text() + ')');

        });
        $(function() {
            $("#category").change(function(e) {
                e.preventDefault();
                $('#parameter_type').empty();

                $('.parsley-error filled,.parsley-required').attr("aria-hidden", "true");

                $('#parameter_type').empty();

                var parameter_types = $(this).find(':selected').data('parametertypes');
                var ids = $(this).find(':selected').val();


                parameter_data = $.parseJSON($('#parameter_count').val());

                arr = [];
                data = $.parseJSON($('#category_count').val());

                data_arr = (parameter_types + ',').split(",");

                $.each(data, function(key, value) {

                    if (value.parameter_types == parameter_types && value.id == ids) {

                        $.each(data_arr, function(keys,
                            val) {
                            $.each(parameter_data, function(key, name) {


                                if (val == name.id) {



                                    if (name.type_of_parameter == "dropdown") {


                                        $('#parameter_type').append(

                                            '<div class="form-group mandatory col-md-3"><label for="' +
                                            name.name +
                                            '" class="form-label col-6 ">' +
                                            name.name +
                                            '</label>' +
                                            '<select id="mySelect" name="' +
                                            name.id +
                                            '" class" ="select2 form-select form-control-sm"><option>choose option</option></select></div>'
                                        );

                                        arr = (name.type_values);
                                        $.each(name.type_values,
                                            function(key, val) {
                                                $('#mySelect').append($(
                                                    '<option>', {
                                                        value: val,
                                                        text: val
                                                    }));
                                            });

                                    }


                                    if (name.type_of_parameter == "checkbox") {




                                        $('#parameter_type').append(

                                            '<div class="form-group mandatory col-md-3 chk1"><label for="' +
                                            name.name +
                                            '" class="form-label col-12 ">' +
                                            name.name +
                                            '</label></div>');

                                        $.each(name.type_values,
                                            function(key, val) {
                                                $('.chk1').append($(
                                                    '<input name="' +
                                                    name.id +
                                                    '[]" type="checkbox" value="' +
                                                    val +
                                                    '" class="form-check-input ml-5"/><label class="form-label  col-2">' +
                                                    val +
                                                    '</label>'));
                                            });
                                    }

                                    if (name.type_of_parameter == "radiobutton" &&
                                        val == name.id) {



                                        $('#parameter_type').append(

                                            '<div class="form-group mandatory col-md-3 chk"><label for="' +
                                            name.name +
                                            '" class="form-label col-12 ">' +
                                            name.name +
                                            '</label></div>');

                                        $.each(name.type_values,
                                            function(key, val) {
                                                $('.chk').append($(
                                                    '<input name="' +
                                                    name.id +
                                                    '" type="radio" value="' +
                                                    val +
                                                    '" class="form-check-input ml-5"/><label class="form-label  col-2">' +
                                                    val +
                                                    '</label>'));
                                            });
                                    }

                                    if (name.type_of_parameter == "textbox" &&
                                        val == name.id) {

                                        $('#parameter_type').append($(



                                            '<div class="form-group mandatory col-md-3"><label for="' +
                                            name.name +
                                            '" class="form-label  col-12">' +
                                            name.name +
                                            '</label><input class="form-control" required type="text" id="' +
                                            name.id + '" name="' + name
                                            .id +
                                            '"></div>'


                                        ));
                                    }
                                    if (name.type_of_parameter == "textarea" &&
                                        val == name.id) {

                                        $('#parameter_type').append($(



                                            '<div class="form-group mandatory col-md-3"><label for="' +
                                            name.name +
                                            '" class="form-label  col-12">' +
                                            name.name +
                                            '</label><textarea class="form-control" required type="text" id="' +
                                            name.id + '" name="' + name
                                            .id +
                                            '"></div>'


                                        ));
                                    }
                                    if (name.type_of_parameter == "file" && val ==
                                        name.id) {

                                        $('#parameter_type').append($(



                                            '<div class="form-group mandatory col-md-3"><label for="' +
                                            name.name +
                                            '" class="form-label  col-12">' +
                                            name.name +
                                            '</label><input class="form-control" required type="file" id="' +
                                            name.id + '" name="' + name
                                            .id +
                                            '"></div>'


                                        ));
                                    }
                                    if (name.type_of_parameter == "number" && val ==
                                        name.id) {

                                        $('#parameter_type').append($(



                                            '<div class="form-group mandatory col-md-3"><label for="' +
                                            name.name +
                                            '" class="form-label  col-12">' +
                                            name.name +
                                            '</label><input class="form-control" required type="number" id="' +
                                            name.id + '" name="' + name
                                            .id +
                                            '"></div>'


                                        ));
                                    }

                                }
                            })
                        })
                    }

                })

                function validateForm(event) {
                    event.preventDefault();

                    let form = document.getElementById("myForm");
                    let inputs = form.querySelectorAll("[required]");
                    let isFormValid = true;

                    inputs.forEach(function(input) {
                        if (!input.value) {
                            isFormValid = false;
                            $('.parsley-error filled').hide();
                            $('.parsley-required').hide();
                            $('.parsley-required').attr('aria-hidden', 'false');

                            let errorMessage = document.createElement("div");
                            errorMessage.classList.add("error-message");
                            errorMessage.innerText = (input.placeholder != '' || input
                                    .placeholder) ? input.placeholder + ' is required' :
                                'This Field is required.';
                            input.parentNode.insertBefore(errorMessage, input.nextSibling);
                        }
                    });

                    if (isFormValid) {
                        form.submit();
                    }
                }

                let myForm = document.getElementById("myForm");
                myForm.addEventListener("submit", validateForm);

            });

        });
    </script>


    <script>
        $(".3d").click(function(e) {
            e.preventDefault();

            var id = $(this).data('rowid');
            console.log(id);
            Swal.fire({
                title: 'Are You Sure Want to Remove This Image',
                icon: 'error',
                showDenyButton: true,

                confirmButtonText: 'Yes',
                denyCanceButtonText: `No`,
            }).then((result) => {
                /* Read more about isConfirmed, isDenied below */
                if (result.isConfirmed) {
                    $.ajax({
                        url: "{{ route('property.removeGalleryImage') }}",

                        type: "POST",
                        data: {
                            '_token': "{{ csrf_token() }}",
                            "id": id
                        },
                        success: function(response) {

                            if (response.error == false) {
                                $(this).parent().remove();

                                Toastify({
                                    text: 'Image Delete Successful',
                                    duration: 6000,
                                    close: !0,
                                    backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)"
                                }).showToast();
                                $("#" + id).html('');
                            } else if (response.error == true) {
                                Toastify({
                                    text: 'Something Wrong !!!',
                                    duration: 6000,
                                    close: !0,
                                    backgroundColor: '#dc3545' //"linear-gradient(to right, #dc3545, #96c93d)"
                                }).showToast()
                            }
                        },
                        error: function(xhr) {}
                    });
                }
            })


        });
    </script>
@endsection
