<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <meta charset="UTF-8">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    @include('layouts/modal_error')
    <title>Login</title>
</head>

<body>
    <div class="row login-block mt-5">
        @include('auth/notifications')
        <div class="row">
            <div class="col-md-6">
                <img src="/images/Ifdm_new.png" class="img-fluid logo" alt="logo IFDM">
            </div>
            <div class="col-md-6">
                {!!Form::open(['route' => ['entrar'], 'method' => 'POST'])!!}
                <div >
                    <input name="name" type="text" value="" placeholder="Username" id="name" />
                    <input name="password" type="password" value="" placeholder="Password" id="password" />
                    <a href="{{ url('reset_password') }}"><small>Forgot password?</small></a>
                    <hr>
                    <button>Submit</button>
                </div>
                {!! Form::Close() !!}
            </div>
        </div>
    </div>
</body>
</html>

@include('js/modal_error')
@include('css/login')
