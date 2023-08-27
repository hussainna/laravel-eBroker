@extends('layouts.main')

@section('title')
    Categories
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
    <link rel="stylesheet" href="https://bevacqua.github.io/dragula/dist/dragula.css" />
@endsection


@section('content')

    <section class="section">
        <div class="card">

            <div class="card-header">

                <div class="divider">
                    <div class="divider-text">
                        <h4>Create Category</h4>
                    </div>
                </div>
            </div>

            <div class="card-content">
                <div class="card-body">
                    <div class="row">
                        {{-- <div class="col-12"> --}}
                        {!! Form::open(['url' => route('categories.store'), 'data-parsley-validate', 'files' => true]) !!}
                        {{-- <div class="row mt-1"> --}}

                        <div class=" row">

                            <div class="col-sm-3 form-group mandatory">
                                {{ Form::label('category', 'Category', ['class' => 'form-label text-center']) }}

                                {{ Form::text('category', '', ['class' => 'form-control', 'placeholder' => 'Category', 'data-parsley-required' => 'true']) }}

                            </div>

                            <div class="col-sm-4 form-group mandatory">
                                {{ Form::label('type', 'Facilities', ['class' => 'form-label text-center']) }}

                                <select name="parameter_type[]" class="chosen-select form-control"
                                    id="select_parameter_type" multiple data-parsley-required='true'>


                                    @foreach ($parameters as $parameter)
                                        <option value={{ $parameter->id }}>{{ $parameter->name }}</option>
                                    @endforeach

                                </select>

                            </div>

                            <div class="col-sm-3 form-group mandatory">
                                {{ Form::label('image', 'Image', ['class' => 'form-label text-center']) }}

                                {{ Form::file('image', ['class' => 'form-control', 'data-parsley-required' => 'true']) }}
                                @if (count($errors) > 0)
                                    @foreach ($errors->all() as $error)
                                        <div class="alert alert-danger error-msg">{{ $error }}</div>
                                    @endforeach
                                @endif
                            </div>
                            <div class="col-sm-2 mt-4 justify-content-end">
                                {{ Form::submit('Save', ['class' => 'btn btn-primary me-1 mb-1']) }}
                            </div>
                        </div>


                        {!! Form::close() !!}

                        {{-- </div> --}}
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
                            data-toggle="table" data-url="{{ url('categoriesList') }}" data-click-to-select="true"
                            data-side-pagination="server" data-pagination="true"
                            data-page-list="[5, 10, 20, 50, 100, 200,All]" data-search="true" data-toolbar="#toolbar"
                            data-show-columns="true" data-show-refresh="true" data-fixed-columns="true"
                            data-fixed-number="1" data-fixed-right-number="1" data-trim-on-search="false"
                            data-mobile-responsive="true" data-sort-name="id" data-sort-order="desc"
                            data-pagination-successively-size="3" data-query-params="queryParams">
                            <thead>
                                <tr>
                                    <th scope="col" data-field="id" data-sortable="true">ID</th>
                                    <th scope="col" data-field="image" data-sortable="false">Image</th>
                                    <th scope="col" data-field="category" data-sortable="true">Category</th>
                                    <th scope="col" data-field="status" data-sortable="true">Status</th>
                                    <th scope="col" data-field="type" data-sortable="false">Type</th>
                                    <th scope="col" data-field="sequence" data-sortable="true">Sequence</th>
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
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title" id="myModalLabel1">Edit Categories</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <form action="{{ url('categories-update') }}" class="form-horizontal" enctype="multipart/form-data"
                        method="POST" data-parsley-validate>

                        {{ csrf_field() }}

                        <input type="hidden" id="old_image" name="old_image">
                        <input type="hidden" id="edit_id" name="edit_id">
                        <div class="row">
                            {{-- <div class="col-sm-12"> --}}



                            {{-- </div> --}}
                            <style>
                                * {
                                    margin: 0;
                                    padding: 0;
                                    box-sizing: border-box;
                                }

                                body {
                                    background-color: #fff;
                                }

                                .container {
                                    display: flex;
                                    flex-direction: row;
                                    flex-wrap: wrap;
                                    justify-content: center;
                                }

                                h1 {
                                    display: block;
                                    text-align: center;
                                    margin: 20px;
                                }

                                h2 {
                                    display: block;
                                    text-align: center;
                                    margin: 20px;
                                }

                                /*********************
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    *   BUTTONS CONFIG    *
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    **********************/

                                .btn1 {
                                    display: inline-block;
                                    height: 50px;
                                    width: 140px;
                                    color: #000;
                                    padding-top: 15px;
                                    text-align: center;
                                }

                                .btn1:link,
                                .btn1:visited {
                                    text-transform: uppercase;
                                    text-decoration: none;
                                }

                                /***********************
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    * Input Button Section  *
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    ************************/

                                .input-btn1 {
                                    display: inline-block;
                                    height: 50px;
                                    width: 140px;
                                    margin: 20px;
                                    text-transform: uppercase;
                                    outline: none;
                                }

                                .input-btn1-ghost {
                                    background-color: transparent;
                                    border: 1px #000;
                                    border-style: solid;
                                }

                                .input-btn1-ghost:hover {
                                    cursor: pointer;
                                    background-color: #2980b9;
                                    color: #fff;
                                    border-color: #fff;
                                    transition: .4s linear;
                                }

                                .input-btn1-ghost:active {
                                    background-color: #3498db;
                                    transition: .2s ease-out;
                                    color: #fff;
                                    border-color: #fff;
                                }

                                .input-btn1-ghost-rounded {
                                    border-radius: 5px;
                                }

                                .input-btn1-ghost-cicle {
                                    border-radius: 50px;
                                }

                                .input-btn1-ghost-dashed {
                                    background-color: transparent;
                                    border: 1px #000;
                                    border-style: dashed;
                                    height: 100px;
                                }

                                .input-btn1-ghost-dashed:hover {
                                    background-color: transparent;
                                    border: 2px #000;
                                    border-style: dashed;
                                    transition: .2s linear;
                                    cursor: pointer;
                                }


                                .input-btn1-ghost-dashed:active {
                                    background-color: #2980b9;
                                    border-color: #2980b9;
                                    color: #fff;
                                    transition: .1s ease;
                                }

                                @import url('https://fonts.googleapis.com/css?family=Nunito+Sans&display=swap');

                                :root {
                                    --primary-color: #087C7C;
                                    --bg-color: #dfe6e9;
                                    --text-color: black;
                                }

                                .body1 {
                                    background: var(--bg-color);
                                    height: 100vh;
                                    display: flex;
                                    justify-content: center;
                                    align-items: center;
                                    font-family: 'Nunito Sans', sans-serif;
                                }

                                .container1 {
                                    margin-left: 5%;
                                    width: 27%;
                                    background: white;
                                    cursor: pointer;
                                    border-radius: 15px;
                                    position: relative;
                                    padding: 8px 5px;
                                    color: black;
                                    border-color: #087C7C;
                                    border-style: double;
                                    text-align: center;
                                    align-content: center;
                                }

                                .container1::after {
                                    content: '';

                                    color: var(--text-color);

                                    background: var(--primary-color);
                                    border-radius: 15px;
                                    height: 100%;
                                    width: 100%;
                                    position: absolute;
                                    left: 0;
                                    top: 0;
                                    z-index: 0;
                                    clip-path: circle(10% at 0% 0%);
                                    transition: all .3s ease-in;

                                }

                                .content1 {
                                    position: relative;
                                    z-index: 1;
                                    transition: all .3s ease-in;
                                }

                                .container1:hover::after {

                                    clip-path: circle(100%);


                                }

                                .container1:hover .content {
                                    color: var(--text-color);
                                }
                            </style>

                            <div class="col-md-12 col-12 form-group">

                                {{ Form::label('image', 'Image', ['class' => 'col-sm-12 col-form-label']) }}
                                <input type="button" class="input-btn1 input-btn1-ghost-dashed bottomleft"
                                    value="+">
                                <input accept="image/*" name='edit_image' type='file' id="edit_image"
                                    style="display: none" />
                                <img id="blah" height="100" width="150" />


                                </i>


                                @if (count($errors) > 0)
                                    @foreach ($errors->all() as $error)
                                        <div class="alert alert-danger error-msg">{{ $error }}</div>
                                    @endforeach
                                @endif

                            </div>

                            <div class="col-md-12">
                                <div class="form-group mandatory">
                                    <label for="edit_category" class="form-label col-12">Category</label>
                                    <input type="text" id="edit_category" class="form-control col-12"
                                        placeholder="Name" name="edit_category" data-parsley-required="true">
                                </div>
                            </div>
                        </div>
                        <div class="row">

                            <div class="col-sm-12">
                                {{ Form::label('type', 'Type', ['class' => 'col-sm-12 col-form-label ']) }}



                                <div id="output"></div>

                                <select data-placeholder="Facilities" name="edit_parameter_type[]"
                                    id="edit_parameter_type" multiple class="chosen-select">
                                    @foreach ($parameters as $parameter)
                                        <option value={{ $parameter->id }} id='op'>{{ $parameter->name }}
                                        </option>
                                    @endforeach
                                </select>


                            </div>
                            {{ Form::label('image', 'Sequence', ['class' => 'col-sm-12 col-form-label ']) }}

                            <div class="col-sm- sequence">


                                <div id="par" class="row pt-3">

                                </div>
                                <input type="hidden" name="update_seq" id="update_seq">

                            </div>





                        </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Close</button>

                    <button type="submit" class="btn btn-primary waves-effect waves-light">Save</button>
                    </form>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- EDIT MODEL -->
@endsection

@section('script')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dragula/3.6.6/dragula.min.js"
        integrity="sha512-MrA7WH8h42LMq8GWxQGmWjrtalBjrfIzCQ+i2EZA26cZ7OBiBd/Uct5S3NP9IBqKx5b+MMNH1PhzTsk6J9nPQQ=="
        crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $('#add').hide();

        $('#add_parameter').click(function() {

            $('#add').slideToggle();



        });
        $('.container1').mouseover(function() {
            this.css("background-color", "yellow");
        });
        $('#add_parameter_value').click(function() {

            param = $('#parameter').val();




        });

        function queryParams(p) {
            return {
                sort: p.sort,
                order: p.order,
                offset: p.offset,
                limit: p.limit,
                search: p.search
            };
        }

        function disable(id) {
            $.ajax({
                url: "{{ route('customer.categoriesstatus') }}",
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
                            text: 'Category Deactive successfully',
                            duration: 6000,
                            close: !0,
                            backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)"
                        }).showToast();
                        $('#table_list').bootstrapTable('refresh');
                    } else {
                        Toastify({
                            text: "Something went wrong",
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
                url: "{{ route('customer.categoriesstatus') }}",
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
                            text: 'Category Active successfully',
                            duration: 6000,
                            close: !0,
                            backgroundColor: "linear-gradient(to right, #00b09b, #96c93d)"
                        }).showToast();
                        $('#table_list').bootstrapTable('refresh');
                    } else {
                        Toastify({
                            text: "Something went wrong",
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

        $('#btn_seq').click(function(e) {
            e.preventDefault();



            var sequence = [];
            $('.seq').each(function() {

                sequence.push($(this).attr('id'));

            });

            $('#update_seq').val(sequence.toString());

        });

        $('#edit_parameter_type').on('change', function(e) {
                e.preventDefault();

                $('#edit_parameter_type option:not(:selected)').each(function() {


                    $('#div_' + this.value).remove();


                    var sequence = [];
                    $('.seq').each(function() {


                        sequence.push($(this).attr('id'));

                    });
                    $('#update_seq').val(sequence.toString());
                });

                ids = $('#par > div').map((i, div) => div.id).get();
                console.log(ids);

                $('#par').html('');

                $("#edit_parameter_type option:selected").each(function() {


                    val_of_opt = this.value;
                    text_of_opt = this.text;


                    if (text_of_opt) {
                        $('#par').append($(

                            '<div class="container1 form-control col-sm -4 left1 mt-3">' +
                            ' <div class = "content1"> ' +
                            ' <div class="seq" id=' + val_of_opt + '> ' + text_of_opt + ' </div>' +
                            ' </div> ' +
                            ' </div>'

                        ));
                    }

                });

                var sequence = [];
                $('.seq').each(function() {


                    sequence.push($(this).attr('id'));


                });
                $('#update_seq').val(sequence.toString());

            }

        );




        function setValue(id) {


            $("#edit_id").val(id);
            $("#edit_category").val($("#" + id).parents('tr:first').find('td:nth-child(3)').text());
            $("#sequence").val($("#" + id).parents('tr:first').find('td:nth-child(6)').text());

            $("#old_image").val($("#" + id).data('oldimage'));
            $("#status").val($("#" + id).data('status')).trigger('change');
            src = ($("#" + id).parents('tr:first').find('td:nth-child(2)').find($('.image-popup-no-margins'))).attr('href');
            // console.log(src);
            $('#blah').attr('src', src);
            $('#edit_image').attr('src', src);
            var type = ($("#" + id).data('types')).toString();
            // var name = ($("#" + id).data('names')).toString();
            // console.log(name);
            var type_arr = type.split(',');
            // console.log(type);
            if (type != '') {
                $('#edit_parameter_type').val(type.split(',')).trigger('change');
            }

            $('#par').empty();
            str = '';

            val_arr = $("#edit_parameter_type").val();
            // arr1 = $("#edit_parameter_type :selected").val();
            arr1 = [];
            mapped_arr1 = [];
            $("#edit_parameter_type :selected").each(function(key, value) {


                var arr = type_arr;

                var mapped_arr = type_arr.map(function(val) {
                    return $.inArray(val, [val_arr]) ? val : "no";
                });


                mapped_arr1.push(mapped_arr);
                arr1.push(value.text);


                str += this.value + ',';


            });

            $.each(mapped_arr1[0], function(k, v) {
                console.log(v);
                text_op = ($('#edit_parameter_type option[value="' + v + '"]').text());
                $('#par').append($(

                    '<div class="container1 form-control col-sm-4 left1 mt-3">' +
                    ' <div class = "content1"> ' +
                    ' <div class="seq" id=' + v + '> ' + text_op + ' </div>' +
                    ' </div> ' +
                    ' </div>'

                ));

            });

            $(".chosen-select").val(str.split(',')).trigger('chosen:updated');

            var sequence = [];
            $('.seq').each(function() {


                sequence.push($(this).attr('id'));

            });

            $('#update_seq').val(sequence.toString());

            dragula(
                [document.querySelector('#par')].concat(
                    Array.from(document.querySelectorAll('.card col-sm-6 left1'))
                ), {


                }).on('drag', function() {
                $('#update_seq').val();

                var sequence = [];
                $('.seq').each(function() {

                    sequence.push($(this).attr('id'));



                });
                $('#update_seq').val(sequence.toString());

            });



        }
        var sequence = [];
        $('.seq').each(function() {

            sequence.push($(this).attr('id'));



        });

        $('#update_seq').val(sequence.toString());
        document.getElementById('output').innerHTML = location.search;
        $(".chosen-select").chosen();

        $('.bottomleft').click(function() {
            $('#edit_image').click();


        });
        edit_image.onchange = evt => {
            const [file] = edit_image.files
            if (file) {
                blah.src = URL.createObjectURL(file)

            }


        }
    </script>
@endsection
