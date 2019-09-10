@extends('layouts.generaldatabase')

@section('title', 'User Information')

@section('content')
<div class="panel panel-default">
    <div class="panel-body"></br>
        @if (\Auth::User()->gender == '0')
        <center>   <img src="{!! asset('images/bgM.png') !!}" alt="..." class="img-circle" width="100" height="100"> </center> 
        @else
        <center>   <img src="{!! asset('images/bg.png') !!}" alt="..." class="img-circle" width="100" height="100"> </center> 
        @endif

        </br><hr>
        <center><h3>User Information</h3></center>

        <center>
            <table style="border-collapse: separate; border-spacing: 10px;" >
                <tr>
                    <td><h5><b>Name:</b></h5></td>
                    <td><?php echo \Auth::User()->name;?></td>
                </tr>

                <tr>
                    <td><h5><b>Position:</b></h5></td>
                    <td><?php echo \Auth::User()->charge;?></td>
                </tr>

                <tr>
                    <td><h5><b>Company:</b></h5></td>
                    @if (\Auth::User()->company == 0)
                    <td>
                        UN
                    </td>
                    @elseif (\Auth::User()->company == 1)
                    <td>
                        Equion
                    </td>
                    @elseif (\Auth::User()->company == 2)
                    <td>
                        Ecopetrol
                    </td>
                    @elseif (\Auth::User()->company == 3)
                    <td>
                        Hocol
                    </td>
                    @elseif (\Auth::User()->company == 4)
                    <td>
                        UIS
                    </td>
                    @endif
                </tr>

                <tr>
                    <td><h5><b>Profile:</b></h5></td>
                    @if (\Auth::User()->office == 0)
                    <td>
                        System administrator
                    </td>
                    @elseif (\Auth::User()->office == 1)
                    <td>
                        Company administrator
                    </td>
                    @elseif (\Auth::User()->office == 2)
                    <td>
                        Engineer
                    </td>
                    @endif
                </tr>
            </table>
        </center>
    </div>
</div>
@endsection


@section('Scripts')


@endsection


