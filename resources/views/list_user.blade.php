@extends('layouts.userSidebar')
@section('title', 'IFDM User')

@section('content')
@include('layouts/modal_error')

<h2>User List</h2>
</br>

<table id="table_users" class="table table-striped ">
   <thead>
      <tr>
         <th>Name</th>
         <th>Profile</th>
         <th>E-mail</th>
         <th>Actions</th>
      </tr>
   </thead>
</table>

<div class="modal fade" id="confirmDelete" role="dialog" aria-labelledby="confirmDeleteLabel" aria-hidden="true">
   <div class="modal-dialog">
      <div class="modal-content">
         <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            <h4 class="modal-title">Confirmation</h4>
         </div>
         <div class="modal-body">
            <p>Do You Want To Delete This User?</p>
         </div>
         <div class="modal-footer">
            {!! Form::open(['method' => 'DELETE', 'url' => ['user/destroy'], 'id' => 'formDelete']) !!}
                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger" id="confirm">Ok</button>
            {!! Form::close() !!}
         </div>
      </div>
   </div>
</div>
@endsection


@section('Scripts')
@include('js/list_user')
@include('js/modal_error')
<script>
   $(document).ready(function() {
      var table = $('#table_users').DataTable({
         "processing": true,
         // "serverSide": true,
         ajax: "{{ url('getUsersTable') }}"
      });
   });
</script>
@endsection