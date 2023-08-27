@extends('layouts.main')

@section('title')
    Edit Article
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
                            <a href="{{ route('article.index') }}" id="subURL">View Article</a>
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
    <style>
        .bottomleft {
            position: absolute;
            bottom: 8px;
            left: 279px;
            font-size: 25px;
        }

        #image {
            display: none;
        }
    </style>
    <section class="section">
        <div class="card">
            {!! Form::open([
                'route' => ['article.update', $id],
                'method' => 'PATCH',
                'data-parsley-validate',
                'files' => true,
            ]) !!}
            <div class="card-body">

                <div class="row ">

                    <div class="col-md-6 col-12 form-group mandatory">

                        {{ Form::label('title', 'Title', ['class' => 'form-label col-12 ']) }}
                        {{ Form::text('title', $list->title, ['class' => 'form-control ', 'placeholder' => 'Title', 'data-parsley-required' => 'true', 'id' => 'title']) }}

                    </div>
                    <div class="col-md-6 col-12 form-group" style=" position:relative; display: inline-block">


                        <input accept="image/*" name='image' type='file'
                            id="image" />
                        <img id="blah" src="{{ $list->image }}" height="170" alt="your image" />
                        <i class="bottomleft fa fa-edit" style="color: white;background-color: #087C7C;">

                        </i>

                        @if (count($errors) > 0)
                            @foreach ($errors->all() as $error)
                                <div class="alert alert-danger error-msg">{{ $error }}</div>
                            @endforeach
                        @endif

                    </div>

                </div>
                <div class="row  mt-4">

                    <div class="row mt-4">





                    </div>
                    <div class="row mt-4">
                        <div class="col-md-12 col-12 form-group mandatory">
                            {{ Form::label('description', 'Description', ['class' => 'form-label col-12 ']) }}


                            {{ Form::textarea('description', $list->description, ['class' => 'form-control ', 'rows' => '2', 'id' => 'tinymce_editor', 'data-parsley-required' => 'true']) }}

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
    </section>
@endsection

@section('script')
    <script>
        $('.bottomleft').click(function() {
            $('#image').click();

        });

        image.onchange = evt => {

            var fileInput = document.getElementById('image');


            var filePath = fileInput.value;

            // Allowing file type
            var allowedExtensions = /(\.jpg|\.jpeg|\.png|\.svg)$/i;

            if (!allowedExtensions.exec(filePath)) {
                alert('Only .jpg, .jpeg, .png .svg format allow.');
                fileInput.value = '';
                return false;
            } else {
                const [file] = image.files
                if (file) {
                    blah.src = URL.createObjectURL(file)
                }
            }





        }
    </script>

    <script>
        function fileValidation() {
            // var fileInput = document.getElementById('image');


            // var filePath = fileInput.value;

            // // Allowing file type
            // var allowedExtensions = /(\.jpg|\.jpeg|\.png)$/i;

            // if (!allowedExtensions.exec(filePath)) {
            //     alert('Only .jpg, .jpeg, .png format allow.');
            //     fileInput.value = '';
            //     return false;
            // } else {

            // }
        }
    </script>
@endsection
