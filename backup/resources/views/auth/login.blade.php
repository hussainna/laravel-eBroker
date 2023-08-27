<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="{{ url('assets/images/logo/logo.png') }}" type="image/x-icon">
    <title>Login</title>
    <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ url('assets/css/main/app.css') }}">
    <link rel="stylesheet" href=" {{ url('assets/css/pages/auth.css') }}">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>


<body>
    <div id="auth">

        <div class="row justify-content-center align-items-center login-box">
            <div class="col-lg-4 col-12 card">
                <div id="auth-center">

                    <div class="auth-logo justify-content-center align-items-center d-flex">


                        <a href="{{ url('') }}"><img src="{{ url('assets/images/logo/logo.png') }}"
                                alt="Logo"></a>
                    </div>

                    <div class="pt-4">
                        <form method="POST" action="{{ route('login') }}">
                            {{-- {{ csrf_field() }} --}}
                            @csrf





                            <div class="form-group position-relative has-icon-left mb-4">


                                <input id="email" type="email" placeholder="Email"
                                    class="form-control @error('email') is-invalid @enderror" name="email"
                                    value="{{ old('email') }}" required autocomplete="email" autofocus>

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                                <div class="form-control-icon">
                                    <i class="bi bi-person"></i>
                                </div>
                            </div>
                            <style>
                                .form-group.has-icon-right .form-control-icon.icon-right {
                                    right: 0;
                                    left: auto;
                                }

                                .has-icon-right .form-control {
                                    padding-right: 3.5rem !important;
                                }
                            </style>


                            <div class="form-group position-relative has-icon-left has-icon-right mb-4" id="pwd">
                                <input id="password" type="password" placeholder="Password"
                                    class="form-control @error('password') is-invalid @enderror" name="password"
                                    required autocomplete="current-password">
                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                                <div class="form-control-icon icon-left">
                                    <i class="bi bi-shield-lock"></i>
                                </div>
                                <div class="form-control-icon icon-right">
                                    <i class="bi bi-eye" id='toggle_pass'></i>
                                </div>
                            </div>

                            <button class="btn btn-primary btn-block btn-sm shadow-lg mt-5">Log in</button>
                        </form>
                    </div>
                </div>
            </div>

        </div>

    </div>
    <script>
        $('#pwd').click(function() {
            console.log('click');
            $('#password').focus();
        });
        $("#toggle_pass").click(function() {

            $(this).toggleClass("bi bi-eye bi-eye-slash");
            var input = $('#password');
            if (input.attr("type") == "password") {
                input.attr("type", "text");

            } else {
                input.attr("type", "password");
            }
        });
    </script>
</body>

</html>
