@extends('layouts.main')

@section('title')
    Languages
@endsection

@section('page-title')
    {{-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" /> --}}

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

            <div class="card-header">

                <div class="divider">
                    <div class="divider-text">
                        <h4>Add Language</h4>
                    </div>
                </div>
            </div>

            <div class="card-content">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            {!! Form::open(['url' => route('language.store'), 'files' => true]) !!}
                            {{-- <div class="row mt-1"> --}}

                            <div class="form-group row">

                                {{ Form::label('LanguageName', 'Language Name', ['class' => 'col-sm-1 col-form-label text-center']) }}
                                <div class="col-sm-2">
                                    {{ Form::text('name', '', ['class' => 'form-control', 'placeholder' => 'language name', 'required' => true]) }}

                                </div>

                                {{ Form::label('LanguageCode', 'Language Code', ['class' => 'col-sm-2 col-form-label text-center']) }}
                                <div class="col-sm-2">
                                    {{ Form::text('code', '', ['class' => 'form-control', 'placeholder' => 'language code', 'required' => true]) }}

                                </div>

                                {{ Form::label('file', 'Language File', ['class' => 'col-sm-1 col-form-label text-center', 'accept' => '.json.*']) }}
                                <div class="col-sm-2">
                                    {{ Form::file('file', ['class' => 'form-control']) }}
                                    @if (count($errors) > 0)
                                        @foreach ($errors->all() as $error)
                                            <div class="alert alert-danger error-msg">{{ $error }}</div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>

                            {{-- <div class="card-footer"> --}}
                            <div class="col-sm-12 d-flex justify-content-end">
                                {{ Form::submit('Save', ['class' => 'btn btn-primary me-1 mb-1']) }}
                            </div>
                            {{-- </div> --}}
                            {{-- </div> --}}
                            {!! Form::close() !!}

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="section">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <table class="table-light" aria-describedby="mydesc" class='table-striped' id="table_list"
                            data-toggle="table" data-url="{{ url('language_list') }}" data-click-to-select="true"
                            data-side-pagination="server" data-pagination="true"
                            data-page-list="[5, 10, 20, 50, 100, 200,All]" data-search="true" data-toolbar="#toolbar"
                            data-show-columns="true" data-show-refresh="true" data-fixed-columns="true"
                            data-fixed-number="1" data-fixed-right-number="1" data-trim-on-search="false"
                            data-mobile-responsive="true" data-sort-name="id" data-sort-order="desc"
                            data-pagination-successively-size="3" data-query-params="queryParams">
                            <thead>
                                <tr>
                                    <th scope="col" data-field="id" data-sortable="true">ID</th>
                                    <th scope="col" data-field="name" data-sortable="false">Name</th>
                                    <th scope="col" data-field="code" data-sortable="true">Code</th>
                                    <th scope="col" data-field="file" data-sortable="true">File</th>


                                    <th scope="col" data-field="operate" data-sortable="false">Action</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <!-- EDIT MODEL MODEL -->
    <div id="editModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel1" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ url('language_update') }}" class="form-horizontal" enctype="multipart/form-data" method="POST"
                data-parsley-validate>
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="myModalLabel1">Edit Language</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">



                        {{ csrf_field() }}

                        <input type="hidden" id="old_image" name="old_image">
                        <input type="hidden" id="edit_id" name="edit_id">
                        <div class="row">
                            <div class="col-sm-12">

                                <div class="col-md-12 col-12">
                                    <div class="form-group mandatory">
                                        <label for="edit_language" class="form-label col-12">Language
                                            name</label>
                                        <input type="text" id="edit_language_name" class="form-control col-12"
                                            placeholder="Name" name="edit_language_name" data-parsley-required="true">
                                    </div>
                                </div>

                            </div>
                            <div class="col-sm-12">

                                <div class="col-md-12 col-12">
                                    <div class="form-group mandatory">
                                        <label for="edit_language" class="form-label col-12">Language
                                            Code</label>
                                        <input type="text" id="edit_language_code" class="form-control col-12"
                                            placeholder="Name" name="edit_language_code" data-parsley-required="true">
                                    </div>
                                </div>

                            </div>


                            <!--{{ Form::label('edit_json', 'Language File', ['class' => 'col-sm-12 col-form-label', 'accept' => '.json.*']) }}-->


                            <div class="col-sm-12">
                                 <div class="col-md-12 col-12">
                                      <div class="form-group mandatory">
                                <label for="edit_json" class="form-label col-12">Language
                                            File</label>
                                         <input type="file" id="edit_json" class="form-control col-12"
                                           name="edit_json" data-parsley-required="true">   
                                            
                                <!--{{ Form::file('edit_json', ['class' => 'form-control']) }}-->
                                @if (count($errors) > 0)
                                    @foreach ($errors->all() as $error)
                                        <div class="alert alert-danger error-msg">{{ $error }}</div>
                                    @endforeach
                                @endif
                                </div>
                                </div>
                            </div>


                        </div>


                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary waves-effect"
                            data-bs-dismiss="modal">Close</button>

                        <button type="submit" class="btn btn-primary waves-effect waves-light">Save</button>
                    </div>
            </form>

        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
    </div>
    <!-- EDIT MODEL -->
@endsection
@section('script')
    <script>
        function setValue(id) {

            $("#edit_id").val(id);
            $("#edit_language_name").val($("#" + id).parents('tr:first').find('td:nth-child(2)').text());
            $("#edit_language_code").val($("#" + id).parents('tr:first').find('td:nth-child(3)').text());

        }

        function queryParams(p) {

            return {
                sort: p.sort,
                order: p.order,
                offset: p.offset,
                limit: p.limit,
                search: p.search,

            };
        }
    </script>
@endsection
