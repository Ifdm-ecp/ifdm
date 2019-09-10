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
            {!!Form::open(['route' => ['enviar_mail'], 'method' => 'POST'])!!}
            <div>
                @include('auth/notifications')
                <center style="border: gray 0px solid;margin-bottom: 0.5px;"><h4><strong>Reset Password</strong></h4></center>
                <div class="form-group" style="padding: 5px;">
                    <label for="email"><strong>Email</strong></label>
                    <input name="email" type="email" value="" placeholder="Email" id="email" />
                </div>
                <div class="col-md-6"><input type="button" onclick="location.href='{{ url('auth/login') }}';" class="btn btn-danger" value="Cancel"></div>
                <div class="col-md-6"><input type="submit" class="btn btn-primary" value="Reset Password"></div>
            </div>
            {!! Form::Close() !!}
        </div>
    </div>
</body>
</html>

@include('js/modal_error')
@include('css/login')
