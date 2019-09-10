@extends('layouts.generaldatabase')

@section('title', 'IFDM Statistics')

@section('content')


  <div class="row">
    <br>
    <br>
    <div id="statistics"></div>
  </div>

  <div class="row">
    <table>
      
    </table>
  </div>
  <br>
  <br>
  <br>

  <div class="row">
    <div name="table" id="table">

      <table class="table table-striped ">
        <tr>
          <th>User</th>
          <th>Company</th>
          <th>Online Time</th>
          <th></th>
        </tr>
        @foreach  ($timebyusers as $timebyuser)
          <tr>
            <td>
              {{ $timebyuser->name }}
            </td>
            <td>
              {{ $timebyuser->company }}
            </td>
            <td>{{ $timebyuser->time }}</td>
            <td><a href="{{ URL::route('UserStatistics.show', $timebyuser->id) }}">View Details</a></td>
          </tr>
        @endforeach
      </table>
    </div>
  </div>

  <div class="modal fade" id="highchartsmodal" role="dialog" aria-labelledby="highchartsmodal" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Online Time</h4>
            </div>
            <div class="modal-body">
                <center><div id="highcharts_div"></div></center>
              
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Ok</button>
            </div>
        </div>
    </div>
</div>

@endsection


@section('Scripts')

  @include('js/user_statistics')

@endsection


