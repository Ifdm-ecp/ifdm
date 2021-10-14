<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <meta charset="UTF-8">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    @include('layouts/modal_error')
    <title>Reset Password</title>
</head>

<body>
    <div class="row login-block mt-5">
        @include('auth/notifications')
        <div class="row">
            <div class="col-md-6">
                <img src="/images/Ifdm_new.png" class="img-fluid logo" alt="logo IFDM">
            </div>
            <div class="col-md-6">
                {!!Form::open(['route' => ['enviar_mail'], 'method' => 'POST'])!!}
                <div>

                    <center><h4><strong>Reset Password</strong></h4></center>
                    
                        <br>

                        <label for="email"><strong>Email</strong></label>
                        <input name="email" type="email" value="" placeholder="Email" id="email" />
                    
                    <div class="col-md-6"><input type="button" onclick="location.href='{{ url('auth/login') }}';" class="btn btn-danger" value="Cancel"></div>
                    <div class="col-md-6"><input type="submit" class="btn btn-primary" value="Reset Password"></div>
                </div>
                {!! Form::Close() !!}
            </div>
        </div>
    </div>
</body>
</html>

@include('js/modal_error')
@include('css/login')
