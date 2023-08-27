@extends('layouts.main')

@section('title')
    Add Package
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
                            <a href="{{ route('article.index') }}" id="subURL">View Packages</a>
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
            {!! Form::open(['route' => 'package.store', 'data-parsley-validate', 'files' => true]) !!}
            <div class="card-body">

                <div class="row ">

                    <div class="col-md-6 col-12 form-group mandatory">

                        {{ Form::label('name', 'name', ['class' => 'form-label col-12 text-center']) }}
                        {{ Form::text('name', '', ['class' => 'form-control ', 'placeholder' => 'Package Name', 'data-parsley-required' => 'true', 'id' => 'name']) }}

                    </div>
                    <div class="col-md-6 col-12 form-group mandatory">

                        {{ Form::label('name', 'name', ['class' => 'form-label col-12 text-center']) }}
                        {{ Form::text('name', '', ['class' => 'form-control ', 'placeholder' => 'Package Name', 'data-parsley-required' => 'true', 'id' => 'name']) }}

                    </div>
                    <div class="col-md-6 col-12 form-group mandatory">

                        {{ Form::label('name', 'name', ['class' => 'form-label col-12 text-center']) }}
                        {{ Form::text('name', '', ['class' => 'form-control ', 'placeholder' => 'Package Name', 'data-parsley-required' => 'true', 'id' => 'name']) }}

                    </div>



                </div>

                <div class="card-footer">
                    <div class="col-12 d-flex justify-content-end">

                        {{ Form::submit('Save', ['class' => 'btn btn-primary me-1 mb-1']) }}
                    </div>

                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </section>
@endsection

@section('script')
    <script>
        $(function() {
            // $('#parameters').append($('<div id="1">abcs</div>'));

            $("#category").change(function() {
                $('#parameter_type').empty();
                console.log($('#parameter_type').children('#ABC').text());

                var parameter_types = $(this).find(':selected').data('parametertypes');


                console.log($('#parameter_count').val());
                parameter_data = $.parseJSON($('#parameter_count').val());

                arr = [];
                data = $.parseJSON($('#category_count').val());
                $.each(data, function(key, value) {
                    if (value.parameter_types == parameter_types) {

                        $.each(parameter_data, function(key, name) {

                            $.each((parameter_types + ',').split(","), function(key,
                                value) {
                                if (value == name.id) {
                                    console.log(name.name);


                                    $('#parameter_type').append($(



                                        '<div class="form-group mandatory col-md-3"><label for="' +
                                        name.name +
                                        '" class="form-label text-center col-12">' +
                                        name.name +
                                        '</label><input class="form-control" data-parsley-required="true" type="text" id="' +
                                        name.id + '" name="' + name.id +
                                        '"></div>'


                                    ));
                                }
                            })
                        })
                    }

                })


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
@endsection
