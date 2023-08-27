@extends('layouts.main')

@section('title')
    Add Property
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
                            Add
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


            {!! Form::open(['route' => 'property.store', 'data-parsley-validate', 'id' => 'myForm', 'files' => true]) !!}
            <div class="card-body">

                <div class="row ">


                    <div class="col-md-6 col-12 form-group mandatory">

                        {{ Form::label('title', 'Title', ['class' => 'form-label col-12 ']) }}
                        {{ Form::text('title', '', ['class' => 'form-control ', 'placeholder' => 'Title', 'required' => 'true', 'id' => 'title']) }}

                    </div>

                    <div class="col-md-4 col-12  form-group  mandatory">
                        {{ Form::label('', 'Propery Type', ['class' => 'form-label col-12 ']) }}

                        <div class="control-label col-form-label form-check form-check-inline mb-2">

                            {{ Form::radio('property_type', 0, null, ['class' => 'form-check-input', 'id' => 'property_type', 'required' => true]) }}
                            {{ Form::label('property_type', 'Sell', ['class' => 'form-check-label']) }}


                        </div>
                        <div class="control-label col-form-label form-check form-check-inline mb-2">
                            {{ Form::radio('property_type', 1, null, ['class' => 'form-check-input', 'id' => 'property_type', 'required' => true, 'min' => '1']) }}
                            {{ Form::label('property_type', 'Rent', ['class' => 'form-check-label']) }}

                        </div>
                    </div>
                    <div class="col-md-2 form-group  mandatory">
                        {{ Form::label('price', 'Price', ['class' => 'form-label col-12 ']) }}
                        {{ Form::number('price', '', ['class' => 'form-control ', 'placeholder' => 'Price', 'required' => 'true', 'min' => '1', 'id' => 'price']) }}

                    </div>



                </div>
                <div class="row">
                    <div class="col-md-12 col-12 ">

                        <div class="divider">
                            <div class="divider-text">Address</div>
                        </div>
                        <div class="row mt-10">



                            <div class="col-md-2 col-12 form-group">
                                {{ Form::label('country', 'Country', ['class' => 'form-label col-12 ']) }}
                                <select name="country" id="country" class="select2 form-select form-control-sm">
                                    <option value=""> Select Option </option>
                                    @foreach ($country as $row)
                                        <option value="{{ $row }}"> {{ $row }} </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-2 col-12 form-group">
                                {{ Form::label('state', 'State', ['class' => 'form-label col-12 ']) }}
                                <select name="state" id="state" class="select2 form-select form-control-sm">
                                    <option value=""> Select Option </option>

                                </select>
                            </div>
                            <div class="col-md-2 col-12 form-group">
                                {{ Form::label('city', 'City', ['class' => 'form-label col-12 ']) }}
                                {{ Form::text('city', '', ['class' => 'form-control ', 'placeholder' => 'City', 'id' => 'city']) }}

                            </div>
                            <div class="col-md-3 form-group  mandatory">
                                {{ Form::label('latitude', 'Latitude', ['class' => 'form-label col-12 ']) }}
                                {{ Form::text('latitude', '', ['class' => 'form-control ', 'placeholder' => 'Latitude', 'required', 'id' => 'latitude']) }}

                            </div>
                            <div class="col-md-3 form-group  mandatory">
                                {{ Form::label('longitude', 'Longitude', ['class' => 'form-label col-12 ']) }}
                                {{ Form::text('longitude', '', ['class' => 'form-control ', 'placeholder' => 'Longitude', 'required' => 'true', 'id' => 'longitude']) }}

                            </div>
                        </div>
                        <div class="row">

                            <div class="col-md-6 col-12 form-group mandatory">
                                {{ Form::label('address', 'Client Address', ['class' => 'form-label col-12 ']) }}
                                {{ Form::textarea('client_address', '', ['class' => 'form-control ', 'placeholder' => 'Client Address', 'rows' => '2', 'id' => 'address', 'autocomplete' => 'off', 'required' => 'true']) }}
                            </div>

                            <div class="col-md-6 col-12 form-group mandatory">
                                {{ Form::label('address', 'Address', ['class' => 'form-label col-12 ']) }}
                                {{ Form::textarea('address', '', ['class' => 'form-control ', 'placeholder' => 'Address', 'rows' => '2', 'id' => 'address', 'autocomplete' => 'off', 'required' => 'true']) }}
                            </div>


                        </div>
                        <hr>
                    </div>
                </div>


                <div class="row  mt-4">



                    <div class="col-md-12 col-12 form-group">
                        {{ Form::label('category', 'Category', ['class' => 'form-label col-12 ']) }}
                        <select name="category" class="select2 form-select form-control-sm" data-parsley-minSelect='1'
                            id="category" required>
                            <option value="      "> Select Option </option>
                            @foreach ($category as $row)
                                <option value="{{ $row->id }}" data-parametertypes='{{ $row->parameter_types }}'>
                                    {{ $row->category }}
                                </option>
                            @endforeach
                        </select>
                    </div>






                    <div class="divider">
                        <div class="divider-text unit">Facilities</div>
                    </div>

                    <div class="row  mt-4" id="parameters">
                        {{ Form::hidden('category_count[]', $category, ['id' => 'category_count']) }}
                        {{ Form::hidden('parameter_count[]', $parameters, ['id' => 'parameter_count']) }}
                        {{ Form::hidden('parameter_add', '', ['id' => 'parameter_add']) }}


                        <div id="parameter_type" name=parameter_type class="row">

                        </div>
                    </div>
                    <hr class=" mt-5">


                    <div class="row mt-4">

                        <div class="col-md-6 col-12 form-group">

                            {{ Form::label('title_image', 'Image', ['class' => 'col-12 form-label ']) }}
                            {{-- <div class="row"> --}}


                            {{ Form::file('title_image', ['class' => 'form-control', 'id' => 'input_title_img']) }}
                            @if (count($errors) > 0)
                                @foreach ($errors->all() as $error)
                                    <div class="alert alert-danger error-msg">{{ $error }}</div>
                                @endforeach
                            @endif

                        </div>
                        <div class="col-md-6 col-12 form-group">

                            {{ Form::label('3d_image', '3D Image', ['class' => 'col-12 form-label']) }}

                            {{ Form::file('3d_image', ['class' => 'form-control', 'id' => 'input_3d_img']) }}
                            @if (count($errors) > 0)
                                @foreach ($errors->all() as $error)
                                    <div class="alert alert-danger error-msg">{{ $error }}</div>
                                @endforeach
                            @endif

                        </div>




                    </div>
                    <div class="row mt-4">
                        <div class="col-md-6 col-12 form-group" id='preview_title_img'>
                        </div>
                        <div class="col-md-6 col-12 form-group" id='preview_3d_img'>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 col-12 form-group">

                            {{ Form::label('gallery_images', 'Gallery Images', ['class' => 'form-label col-12 ']) }}
                            <input type="file" id="file-input" class="form-control" name='gallery_images[]' multiple>

                            {{-- {{ Form::file('gallery_images[]', ['class' => 'form-control', 'multiple' => true]) }} --}}
                            @if (count($errors) > 0)
                                @foreach ($errors->all() as $error)
                                    <div class="alert alert-danger error-msg">{{ $error }}</div>
                                @endforeach
                            @endif

                        </div>
                        <div class="col-md-6 col-12 form-group">

                            {{ Form::label('video_link', 'Video Link', ['class' => 'form-label col-12 ']) }}

                            {{ Form::text('video_link', '', ['class' => 'form-control ', 'placeholder' => 'video link', 'id' => 'video_link']) }}


                        </div>
                    </div>
                    <div class="row" id="preview"></div>



                    <div class="row mt-4">
                        <div class="col-md-12 col-12 form-group mandatory">
                            {{ Form::label('description', 'Description', ['class' => 'form-label col-12 ']) }}

                            {{ Form::textarea('description', '', ['class' => 'form-control ', 'rows' => '10', 'id' => '', 'required' => 'true']) }}

                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <div class="col-12 d-flex justify-content-end">

                        {{ Form::submit('Save', ['class' => 'btn btn-primary me-1 mb-1']) }}
                    </div>

                </div>


                {!! Form::close() !!}
            </div>
            <style>
                #preview {
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



    </section>
@endsection

@section('script')
    <script>
        jQuery(document).ready(function() {
            ImgUpload();
        });


        $(document).ready(function() {
            $('#file-input').on('change', function() {
                // Get the selected files
                var files = $(this)[0].files;
                // Loop through each selected file
                for (var i = 0; i < files.length; i++) {
                    // Create a new FileReader instance
                    var reader = new FileReader();
                    // Set the onload function to generate a preview
                    reader.onload = function(e) {
                        $('#preview').append('<div class="preview-item col-md-2"><img src="' + e.target
                            .result +
                            '"  style="height: 165px; width:100%"><button class="remove-btn">X</button></div>'
                        );
                    }
                    // Read the selected file as a data URL
                    reader.readAsDataURL(files[i]);
                }
            });
            $('#input_title_img').on('change', function() {
                // Get the selected files
                var files = $(this)[0].files;
                // Loop through each selected file
                for (var i = 0; i < files.length; i++) {
                    // Create a new FileReader instance
                    var reader = new FileReader();
                    // Set the onload function to generate a preview
                    reader.onload = function(e) {
                        $('#preview_title_img').append('<div class="preview-item col-md-2"><img src="' +
                            e.target
                            .result +
                            '"  style="height: 165px; width:100%"><button class="remove-btn">X</button></div>'
                        );
                    }
                    // Read the selected file as a data URL
                    reader.readAsDataURL(files[i]);
                }
            });
            $('#input_3d_img').on('change', function() {
                // Get the selected files
                var files = $(this)[0].files;
                // Loop through each selected file
                for (var i = 0; i < files.length; i++) {
                    // Create a new FileReader instance
                    var reader = new FileReader();
                    // Set the onload function to generate a preview
                    reader.onload = function(e) {
                        $('#preview_3d_img').append('<div class="preview-item col-md-2"><img src="' +
                            e.target
                            .result +
                            '"  style="height: 165px; width:100%"><button class="remove-btn">X</button></div>'
                        );
                    }
                    // Read the selected file as a data URL
                    reader.readAsDataURL(files[i]);
                }
            });
            // Remove button click event
            $(document).on('click', '.remove-btn', function() {
                $(this).parent().remove();
            });
        });

        $(document).ready(function() {
            $('.parsley-error filled,.parsley-required').attr("aria-hidden", "true");
            $('.parsley-error filled,.parsley-required').hide();

            // your code that uses .rules() function
        });

        $('#unit_type').change(function() {
            $('.unit').empty();
            $('.unit').append('Unit Type (' + $('#unit_type :selected').text() + ')');

        });
        $(function() {


            $("#category").change(function() {
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


            $("#country").change(function() {
                var country = $(this).val();

                $.ajax({
                    type: "GET",
                    url: "{{ route('property.getStatesByCountry') }}",
                    dataType: 'json',
                    data: {
                        country: country
                    },

                    success: function(response) {
                        $('#state').empty();
                        if (response.error == false) {
                            $.each(response.data, function(i, item) {
                                $('#state').append($('<option>', {
                                    value: item,
                                    text: item

                                }));
                            });
                        } else {
                            $('#state').empty();
                        }
                    }
                });

            });
        });
    </script>
    <script></script>

    <style>
        .error-message {
            color: red;
            margin-top: 5px;
            font-size: 15px;
        }
    </style>
@endsection
