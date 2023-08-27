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
                                        <h6 class="font-semibold text-uppercase">{{ __('Total Customer') }}</h6>
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
                                        <h6 class="font-semibold text-uppercase">{{ __('Total Property') }}</h6>
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
                                        <h6 class="font-semibold text-uppercase">{{ __('Total Active Property') }}</h6>
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
                                        <h6 class="font-semibold text-uppercase">{{ __('Total Deactive Property') }}</h6>
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
                                        <h6 class="font-semibold text-uppercase">{{ __('Total Inquiry') }}</h6>
                                        <label style="font-size: 40px"><b>{{ isset($list['total_property_inquiry']) ? $list['total_property_inquiry'] : 0 }}
                                            </b></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                <div class="col-12 col-lg-3 col-md-3">
                    <a href="{{ url('categories') }}">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <h6 class="font-semibold text-uppercase">{{ __('Total Categories') }}</h6>
                                        <label style="font-size: 40px"><b>{{ isset($list['total_categories']) ? $list['total_categories'] : 0 }}
                                            </b></label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>





            </div>
            {{-- {{ print_r($firebase_settings) }} --}}


        </div>
    </section>
@endsection
