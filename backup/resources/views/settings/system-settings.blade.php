@extends('layouts.main')

@section('title')
    System Settings
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

            <form class="form" action="{{ url('set_settings') }}" method="post">
                {{ csrf_field() }}

                <div class="card-body">
                    <div class="divider pt-3">
                        <h6 class="divider-text">Company Details</h6>
                    </div>
                    <div class="row mt-1">
                        <div class="card-body">


                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label text-center">Company Name</label>
                                <div class="col-sm-4">
                                    <input name="company_name" type="text" class="form-control"
                                        placeholder="Company Name"
                                        value="{{ system_setting('company_name') != '' ? system_setting('company_name') : '' }}"
                                        required="">
                                </div>

                                <label class="col-sm-2 col-form-label text-center">Website</label>
                                <div class="col-sm-4">
                                    <input required name="company_website" type="text" class="form-control"
                                        placeholder="Website"
                                        value="{{ system_setting('company_website') != '' ? system_setting('company_website') : '' }}">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label text-center">Email</label>
                                <div class="col-sm-4">
                                    <input name="company_email" type="email" class="form-control" placeholder="Email"
                                        value="{{ system_setting('company_email') != '' ? system_setting('company_email') : '' }}"
                                        required="">
                                </div>
                                <label class="col-sm-2 col-form-label text-center">Address</label>
                                <div class="col-sm-4">
                                    <textarea name="company_address" class="form-control" rows="3" placeholder="Enter Address">{{ system_setting('company_address') != '' ? system_setting('company_address') : '' }}</textarea>

                                </div>
                            </div>
                            <div class="form-group row">
                                <label class="col-sm-2 col-form-label text-center">Telephone 1</label>
                                <div class="col-sm-4">
                                    <input name="company_tel1" type="text" class="form-control" placeholder="Telephone 1"
                                        pattern="\d*"
                                        value="{{ system_setting('company_tel1') != '' ? system_setting('company_tel1') : '' }}"
                                        required="">

                                </div>
                                <label class="col-sm-2 col-form-label text-center">Telephone 2</label>
                                <div class="col-sm-4">
                                    <input name="company_tel2" type="text" class="form-control" placeholder="Telephone 2"
                                        pattern="\d*"
                                        value="{{ system_setting('company_tel2') != '' ? system_setting('company_tel2') : '' }}"
                                        required="">

                                </div>

                            </div>

                        </div>




                    </div>

                </div>
        </div>
        <div class="card">
            <div class="card-body">

                <div class="divider pt-3">
                    <h6 class="divider-text">Paypal Setting</h6>

                </div>
                <div class="form-group row mt-3">
                    <label class="col-sm-2 form-check-label text-center"
                        id='lbl_paypal'>{{ system_setting('paypal_gateway') != '' ? (system_setting('paypal_gateway') == 0 ? 'Disable' : 'Enable') : 'Disable' }}</label>
                    <div class="col-sm-2 col-md-4 col-xs-12 text-center">
                        <div class="form-check form-switch  text-center">

                            <input type="hidden" name="paypal_gateway" id="paypal_gateway"
                                value="{{ system_setting('paypal_gateway') != '' ? system_setting('paypal_gateway') : 0 }}">
                            <input class="form-check-input" type="checkbox" role="switch" class="switch-input"
                                name='op' {{ system_setting('paypal_gateway') == '1' ? 'checked' : '' }}
                                id="switch_paypal_gateway">
                            <label class="form-check-label" for="switch_paypal_gateway"></label>
                        </div>
                    </div>

                    <label class="col-sm-2 form-check-label text-center">Sandbox Mode</label>
                    <div class="col-sm-2 col-md-4 col-xs-12 text-center">
                        <div class="form-check form-switch  text-center">

                            <input type="hidden" name="sandbox_mode" id="sandbox_mode"
                                value="{{ system_setting('sandbox_mode') != '' ? system_setting('sandbox_mode') : 0 }}">
                            <input class="form-check-input" type="checkbox" role="switch"
                                {{ system_setting('sandbox_mode') == '1' ? 'checked' : '' }} id="switch_sandbox_mode">
                            <label class="form-check-label" for="switch_sandbox_mode"></label>
                        </div>
                    </div>
                    <br>
                    <label class="col-sm-2 form-check-label text-center mt-5">Paypal Business ID</label>
                    <div class="col-sm-4 mt-5">
                        <input name="paypal_business_id" type="text" class="form-control"
                            placeholder="Paypal Business ID"
                            value="{{ system_setting('paypal_business_id') != '' ? system_setting('paypal_business_id') : '' }}"
                            required="">
                    </div>

                    <label class="col-sm-2 form-check-label text-center mt-5">Paypal Webhook URL</label>
                    <div class="col-sm-4 mt-5">
                        <input name="paypal_webhook_url" type="text" class="form-control"
                            placeholder="Paypal Webhook URL"
                            value="{{ system_setting('paypal_webhook_url') != '' ? system_setting('paypal_webhook_url') : url('/webhook/paypal') }}"
                            required="">
                    </div>


                </div>
                <div class="divider pt-3">
                    <h6 class="divider-text">Razorpay Setting</h6>

                </div>
                <div class="form-group row mt-3">
                    <label class="col-sm-2 form-check-label text-center"
                        id='lbl_razorpay'>{{ system_setting('razorpay_gateway') != '' ? (system_setting('razorpay_gateway') == 0 ? 'Disable' : 'Enable') : 'Disable' }}</label>
                    <div class="col-sm-2 col-md-4 col-xs-12 text-center">
                        <div class="form-check form-switch  text-center">

                            <input type="hidden" name="razorpay_gateway" id="razorpay_gateway"
                                value="{{ system_setting('razorpay_gateway') != '' ? system_setting('razorpay_gateway') : 0 }}">
                            <input class="form-check-input" type="checkbox" role="switch" class="switch-input"
                                name='op' {{ system_setting('razorpay_gateway') == '1' ? 'checked' : '' }}
                                id="switch_razorpay_gateway">
                            <label class="form-check-label" for="switch_razorpay_gateway"></label>
                        </div>
                    </div>

                    <label class="col-sm-2 form-check-label text-center">Razorpay key</label>
                    <div class="col-sm-4">
                        <input name="razor_key" type="text" class="form-control" placeholder="Razorpay Key"
                            value="{{ system_setting('razor_key') != '' ? system_setting('razor_key') : '' }}"
                            required="">
                    </div>
                    <label class="col-sm-2 form-check-label text-center mt-5">Razorpay Secret</label>
                    <div class="col-sm-4 mt-5">
                        <input name="razor_secret" type="text" class="form-control" placeholder="Razorpay Secret"
                            value="{{ system_setting('razor_secret') != '' ? system_setting('razor_secret') : '' }}"
                            required="">
                    </div>
                    <label class="col-sm-2 form-check-label text-center mt-5">Razorpay Webhook URL</label>
                    <div class="col-sm-4 mt-5">
                        <input name="paypal_webhook_url" type="text" class="form-control"
                            placeholder="Razorpay Webhook URL"
                            value="{{ system_setting('paypal_webhook_url') != '' ? system_setting('paypal_webhook_url') : url('/webhook/razorpay') }}"
                            required="">
                    </div>

                </div>

                <div class="divider pt-3">
                    <h6 class="divider-text">Paystack Setting</h6>

                </div>
                <div class="form-group row mt-3">
                    <label class="col-sm-2 form-check-label text-center"
                        id='lbl_paystack'>{{ system_setting('paystack_gateway') != '' ? (system_setting('paystack_gateway') == 0 ? 'Disable' : 'Enable') : 'Disable' }}</label>
                    <div class="col-sm-2 col-md-4 col-xs-12 text-center">
                        <div class="form-check form-switch  text-center">

                            <input type="hidden" name="paystack_gateway" id="paystack_gateway"
                                value="{{ system_setting('paystack_gateway') != '' ? system_setting('paystack_gateway') : 0 }}">
                            <input class="form-check-input" type="checkbox" role="switch" class="switch-input"
                                name='op' {{ system_setting('paystack_gateway') == '1' ? 'checked' : '' }}
                                id="switch_paystack_gateway">
                            <label class="form-check-label" for="switch_paystack_gateway"></label>
                        </div>
                    </div>
                    <label class="col-sm-2 form-check-label text-center">Paystack Public key</label>
                    <div class="col-sm-4">
                        <input name="paystack_public_key" type="text" class="form-control"
                            placeholder="Paystack Public Key"
                            value="{{ system_setting('paystack_public_key') != '' ? system_setting('paystack_public_key') : '' }}"
                            required="">
                    </div>
                    <label class="col-sm-2 form-check-label text-center mt-5">Paystack Secret key</label>
                    <div class="col-sm-4 mt-5">
                        <input name="paystack_secret_key" type="text" class="form-control"
                            placeholder="Paystack Secret Key"
                            value="{{ system_setting('paystack_secret_key') != '' ? system_setting('paystack_secret_key') : '' }}"
                            required="">
                    </div>
                    <label class="col-sm-2 form-check-label text-center mt-5">Paystack Currency Symbol</label>

                    <div class="col-sm-4 mt-5">

                        <select name="paystack_currency" id="paystack_currency"
                            class="select2 form-select form-control-sm">





                            <option value="GHS" selected="{{ system_setting('paystack_currency') == 'GHS' ? 'true' : '' }}">
                                GHS</option>
                            <option value="NGN" selected="{{ system_setting('paystack_currency') == 'NGN' ? 'true' : '' }}">
                                NGN</option>
                            <option value="USD" selected="{{ system_setting('paystack_currency') == 'USD' ? 'true' : '' }}">
                                USD</option>
                            <option value="ZAR" selected="{{ system_setting('paystack_currency') == 'ZAR' ? 'true' : '' }}">
                                ZAR</option>


                        </select>
                    </div>
                    <label class="col-sm-2 form-check-label text-center mt-5">Paystack Webhook URL</label>
                    <div class="col-sm-4 mt-5">
                        <input name="paypal_webhook_url" type="text" class="form-control"
                            placeholder="Paystack Webhook URL"
                            value="{{ system_setting('paypal_webhook_url') != '' ? system_setting('paypal_webhook_url') : url('/webhook/paystack') }}"
                            required="">
                    </div>

                </div>


            </div>
        </div>
        <div class="card">
            <div class="card-body">

                <div class="divider pt-3">
                    <h6 class="divider-text">More Setting</h6>

                </div>
                <div class="form-group row mt-3">

                    <label class="col-sm-2 form-check-label text-center">Maintenance Mode</label>
                    <div class="col-sm-2 col-md-4 col-xs-12 text-center">
                        <div class="form-check form-switch  text-center">

                            <input type="hidden" name="maintenance_mode" id="maintenance_mode"
                                value="{{ system_setting('maintenance_mode') != '' ? system_setting('maintenance_mode') : 0 }}">
                            <input class="form-check-input" type="checkbox" role="switch"
                                {{ system_setting('maintenance_mode') == '1' ? 'checked' : '' }}
                                id="switch_maintenance_mode">
                            <label class="form-check-label" for="switch_maintenance_mode"></label>
                        </div>
                    </div>
                    <label class="col-sm-2 form-check-label text-center">Currency Symbol</label>
                    <div class="col-sm-4">
                        <input name="currency_symbol" type="text" class="form-control" placeholder="Currency Symbol"
                            value="{{ system_setting('currency_symbol') != '' ? system_setting('currency_symbol') : '' }}"
                            required="">
                    </div>

                </div>
                <div class="form-group row mt-3">
                    <label class="col-sm-2 form-check-label text-center">Default Language</label>
                    <div class="col-sm-4">


                        <select name="default_language" id="default_language"
                            class="select2 form-select form-control-sm">

                            @foreach ($languages as $row)
                                {{ $row }}
                                <option value="{{ $row->code }}"
                                    {{ system_setting('default_language') == $row->code ? 'selected' : '' }}>
                                    {{ $row->name }}
                                </option>
                            @endforeach
                        </select>

                    </div>
                    <label class="col-sm-2 form-check-label text-center">Number With Suffix</label>
                    <div class="col-sm-2 col-md-4 col-xs-12 text-center">
                        <div class="form-check form-switch  text-center">

                            <input type="hidden" name="number_with_suffix" id="number_with_suffix"
                                value="{{ system_setting('number_with_suffix') != '' ? system_setting('number_with_suffix') : 0 }}">
                            <input class="form-check-input" type="checkbox" role="switch"
                                {{ system_setting('number_with_suffix') == '1' ? 'checked' : '' }}
                                id="switch_number_with_suffix">
                            <label class="form-check-label" for="switch_number_with_suffix"></label>
                        </div>
                    </div>
                </div>
                <div class="form-group row mt-3">

                    <label class="col-sm-2 form-check-label text-center">Force Update</label>
                    <div class="col-sm-2 col-md-4 col-xs-12 text-center">
                        <div class="form-check form-switch  text-center">

                            <input type="hidden" name="force_update" id="force_update"
                                value="{{ system_setting('force_update') != '' ? system_setting('force_update') : 0 }}">
                            <input class="form-check-input" type="checkbox" role="switch"
                                {{ system_setting('force_update') == '1' ? 'checked' : '' }} id="switch_force_update">
                            <label class="form-check-label" for="switch_force_update"></label>
                        </div>
                    </div>
                    <label class="col-sm-2 form-check-label text-center">App Version</label>
                    <div class="col-sm-4">
                        <input name="app_version" type="text" class="form-control" placeholder="App Version"
                            value="{{ system_setting('app_version') != '' ? system_setting('app_version') : '' }}"
                            required="">
                    </div>

                </div>

            </div>
        </div>
        <div class="card">
            <div class="card-body">


                <div class="divider pt-3">
                    <h6 class="divider-text">Notification FCM Key</h6>

                </div>

                <div class="form-group row mt-3">

                    <label class="col-sm-2 form-check-label text-center">FCM Key</label>
                    <div class="col-sm-10 col-md-10 col-xs-12 text-center">
                        <textarea name="fcm_key" class="form-control" rows="3" placeholder="Fcm Key">{{ system_setting('fcm_key') != '' ? system_setting('fcm_key') : '' }}</textarea>

                    </div>


                </div>

                {{-- <div class="card-footer"> --}}
                <div class="col-12 d-flex justify-content-end">
                    <button type="submit" name="btnAdd" value="btnAdd" class="btn btn-primary me-1 mb-1">Save</button>
                </div>
                {{-- </div> --}}
                </form>
            </div>
        </div>



    </section>
@endsection

@section('script')
    <script type="text/javascript">
        const checkboxes = document.querySelectorAll('input[type=checkbox][role=switch][name=op]', );
        checkboxes.forEach((checkbox) => {
            checkbox.addEventListener('change', (event) => {
                if (event.target.checked) {
                    checkboxes.forEach((checkbox) => {
                        if (checkbox !== event.target) {
                            checkbox.checked = false;




                            $("#switch_paypal_gateway").is(':checked') ? $("#paypal_gateway").val(
                                    1) : $("#paypal_gateway")
                                .val(0);

                            $("#switch_paypal_gateway").is(':checked') ? $("#lbl_paypal").text(
                                    "Disable") : $("#lbl_paypal")
                                .text("Enable");
                            $("#switch_razorpay_gateway").is(':checked') ? $("#razorpay_gateway")
                                .val(1) : $("#razorpay_gateway")
                                .val(0);

                            $("#switch_razorpay_gateway").is(':checked') ? $("#lbl_razorpay").text(
                                    "Disable") : $("#lbl_razorpay")
                                .text("Enable");
                            $("#switch_paystack_gateway").is(':checked') ? $("#paystack_gateway")
                                .val(1) : $("#paystack_gateway")
                                .val(0);

                            $("#switch_paystack_gateway").is(':checked') ? $("#lbl_paystack").text(
                                    "Disable") : $("#lbl_paystack")
                                .text("Enable");

                        }
                    });
                }
            });
        });
        $("#switch_maintenance_mode").on('change', function() {
            $("#switch_maintenance_mode").is(':checked') ? $("#maintenance_mode").val(1) : $("#maintenance_mode")
                .val(0);
        });
        $("#switch_force_update").on('change', function() {
            $("#switch_force_update").is(':checked') ? $("#force_update").val(1) : $("#force_update")
                .val(0);
        });
        $("#switch_number_with_suffix").on('change', function() {
            $("#switch_number_with_suffix").is(':checked') ? $("#number_with_suffix").val(1) : $(
                    "#number_with_suffix")
                .val(0);
        });
        $("#switch_sandbox_mode").on('change', function() {
            $("#switch_sandbox_mode").is(':checked') ? $("#sandbox_mode").val(1) : $("#sandbox_mode")
                .val(0);
        });
        $("#switch_paypal_gateway").on('change', function() {

            $("#switch_paypal_gateway").is(':checked') ? $("#paypal_gateway").val(1) : $("#paypal_gateway")
                .val(0);

            $("#switch_paypal_gateway").is(':checked') ? $("#lbl_paypal").text("Disable") : $("#lbl_paypal")
                .text("Enable");
        });
        $("#switch_razorpay_gateway").on('change', function() {

            $("#switch_razorpay_gateway").is(':checked') ? $("#razorpay_gateway").val(1) : $("#razorpay_gateway")
                .val(0);

            $("#switch_razorpay_gateway").is(':checked') ? $("#lbl_razorpay").text("Disable") : $("#lbl_razorpay")
                .text("Enable");
        });
        $("#switch_paystack_gateway").on('change', function() {

            $("#switch_paystack_gateway").is(':checked') ? $("#paystack_gateway").val(1) : $("#paystack_gateway")
                .val(0);

            $("#switch_paystack_gateway").is(':checked') ? $("#lbl_paystack").text("Disable") : $("#lbl_paystack")
                .text("Enable");
        });
    </script>
@endsection
