<!DOCTYPE html>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="shortcut icon" href="{{ url('assets/images/logo/favicon.png') }}" type="image/x-icon">
    <title>@yield('title') || {{ config('app.name') }}</title>

    @include('layouts.include')
    @yield('css')
</head>

<body>
    <div id="app">
        @include('layouts.sidebar')

        <div id="main" class='layout-navbar'>
            @include('layouts.topbar')
            <div id="main-content">
                <div class="page-heading">

                    @yield('page-title')
                    @yield('content')
                </div>


                {{-- footer --}}
                {{-- <div style="position:relative;bottom:0;right:0;margin-right:3%"> --}}
                {{-- </div> --}}
            </div>

        </div>

        <div style="position: fixed; bottom: 0; right: 20;">

            @include('layouts.footer')
        </div>

    </div>

    @include('layouts.footer_script')
    @yield('js')
    @yield('script')
</body>

</html>
