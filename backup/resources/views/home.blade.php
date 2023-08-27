@extends('layouts.main')

@section('title')
    Home
@endsection
@section('content')
    <section class="section">

        <div class="col-12 col-lg-12" style="margin-bottom: 15%">
            <div class="row">

                <div class="col-12 col-lg-3 col-md-3">
                    <a href="{{ url('customer') }}">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h6 class="font-semibold text-uppercase">Total Customer</h6>
                                        <label style="font-size: 40px"><b>{{ isset($list['total_customer']) ? $list['total_customer'] : 0 }}
                                            </b></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-12 col-lg-3 col-md-3">
                    <a href="{{ url('property') }}">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h6 class="font-semibold text-uppercase">Total Property</h6>
                                        <label style="font-size: 40px"><b>{{ isset($list['total_property']) ? $list['total_property'] : 0 }}
                                            </b></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-12 col-lg-3 col-md-3">
                    <a href="{{ url('property') . '?status=1' }}">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">

                                    <div class="col-md-12">
                                        <h6 class="font-semibold text-uppercase">Total Active Property</h6>
                                        <label style="font-size: 40px"><b>{{ isset($list['total_active_property']) ? $list['total_active_property'] : 0 }}
                                            </b></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-12 col-lg-3 col-md-3">
                    <a href="{{ url('property') . '?status=0' }}">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h6 class="font-semibold text-uppercase">Total Deactive Property</h6>
                                        <label style="font-size: 40px"><b>{{ isset($list['total_inactive_property']) ? $list['total_inactive_property'] : 0 }}
                                            </b></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

            </div>

            <div class="row">
                <div class="col-12 col-lg-3 col-md-3">
                    <a href="{{ url('property-inquiry') }}">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h6 class="font-semibold text-uppercase">Total Inquiry</h6>
                                        <label style="font-size: 40px"><b>{{ isset($list['total_property_inquiry']) ? $list['total_property_inquiry'] : 0 }}
                                            </b></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-12 col-lg-3 col-md-3">
                    <a href="{{ url('property-inquiry') . '/0' }}">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h6 class="font-semibold text-uppercase">Total Inquiry Pending</h6>
                                        <label style="font-size: 40px"><b>{{ isset($list['total_property_inquiry_pending']) ? $list['total_property_inquiry_pending'] : 0 }}
                                            </b></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-12 col-lg-3 col-md-3">
                    <a href="{{ url('property-inquiry') . '/1' }}">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h6 class="font-semibold text-uppercase">Total Inquiry Accept</h6>
                                        <label style="font-size: 40px"><b>{{ isset($list['total_property_inquiry_accept']) ? $list['total_property_inquiry_accept'] : 0 }}
                                            </b></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-12 col-lg-3 col-md-3">
                    <a href="{{ url('property-inquiry') . '/2' }}">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h6 class="font-semibold text-uppercase">Total Inquiry Complete</h6>
                                        <label style="font-size: 40px"><b>{{ isset($list['total_property_inquiry_complete']) ? $list['total_property_inquiry_complete'] : 0 }}
                                            </b></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>

                <div class="col-12 col-lg-3 col-md-3">
                    <a href="{{ url('property-inquiry') . '/3' }}">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h6 class="font-semibold text-uppercase">Total Inquiry Cancel</h6>
                                        <label style="font-size: 40px"><b>{{ isset($list['total_property_inquiry_cancle']) ? $list['total_property_inquiry_cancle'] : 0 }}
                                            </b></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
            </div>
            {{-- {{ print_r($firebase_settings) }} --}}
            <input type="hidden" name="apiKey" id="apiKey"
                value="{{ $firebase_settings['apiKey'] ? $firebase_settings['apiKey'] : '' }}">

            <input type="hidden" name="authDomain" id="authDomain"
                value="{{ $firebase_settings['authDomain'] ? $firebase_settings['authDomain'] : '' }}">

            <input type="hidden" name="projectId" id="projectId"
                value="{{ $firebase_settings['projectId'] ? $firebase_settings['projectId'] : '' }}">

            <input type="hidden" name="storageBucket" id="storageBucket"
                value="{{ $firebase_settings['storageBucket'] ? $firebase_settings['storageBucket'] : '' }}">

            <input type="hidden" name="messagingSenderId" id="messagingSenderId"
                value="{{ $firebase_settings['messagingSenderId'] ? $firebase_settings['messagingSenderId'] : '' }}">

            <input type="hidden" name="appId" id="appId"
                value="{{ $firebase_settings['appId'] ? $firebase_settings['appId'] : '' }}">

            <input type="hidden" name="measurementId" id="measurementId"
                value="{{ $firebase_settings['measurementId'] ? $firebase_settings['measurementId'] : '' }}">

        </div>
    </section>
@endsection
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
    $(document).ready(function() {
        var apiKey = $('#apiKey').val();
        console.log(apiKey);
        var authDomain = $('#authDomain').val();
        var projectId = $('#projectId').val();
        var storageBucket = $('#storageBucket').val();
        var messagingSenderId = $('#messagingSenderId').val();
        var measurementId = $('#measurementId').val();

    });
</script>
