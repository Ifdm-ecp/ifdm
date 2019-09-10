<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    <meta charset="UTF-8">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    @include('layouts/modal_error')
    <title>Reset Password</title>
</head>

<body>
    <div class="row login-block mt-5">
        <div class="col-md-6">
            <img src="/images/Ifdm_new.png" class="img-fluid logo" alt="logo IFDM">
        </div>
        <div class="col-md-6">
            {!!Form::open(['route' => ['setp'], 'method' => 'POST'])!!}
            <div class="row">
                <center style="border: gray 0px solid;margin-bottom: 0.5px;"><h4><strong>Change Password</strong></h4></center>
                <hr>
                @include('auth/notifications')
                <div class="col-md-6">
                    <input type="hidden" name="token" id="token" value="{{ $token }}">
                    <input type="hidden" name="email" id="email" value="{{ $email }}">
                    <div class="form-group {{$errors->has('Password') ? 'has-error' : ''}}">
                        {!! Form::label('password', 'Password') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                        {!! Form::password('password', ['placeholder' => 'Password', 'class' =>'form-control', 'id' => 'password']) !!}
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group {{$errors->has('PasswordC') ? 'has-error' : ''}}">
                        {!! Form::label('password_confirmation', 'Confirm Password') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                        {!! Form::password('password_confirmation', ['placeholder' => 'Confirm Password', 'class' =>'form-control', 'id' => 'password_confirmation']) !!}
                    </div>
                </div>
            </div>
            <div class="col-md-6"><input type="button" onclick="location.href='{{ url('auth/login') }}';" class="btn btn-danger" value="Cancel"></div>
            <div class="col-md-6"><input type="submit" class="btn btn-primary" value="Change Password"></div>
            {!! Form::Close() !!}
        </div>
    </div>
</body>
</html>

@include('js/modal_error')
@include('css/login')
