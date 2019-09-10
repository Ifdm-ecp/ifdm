@extends('layouts.ProjectGeneral')

@section('title', 'Tutorial')

@section('content')
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            {!! Form::model($data, ['route' => ['manual.update', $data->id], 'method' => 'PATCH'])!!}
                <div class="form-group {{$errors->has('tittle') ? 'has-error' : ''}}">
                    {!! Form::label('tittle', 'Tittle') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                    {!! Form::text('tittle', null, ['class'=>'form-control']) !!}
                </div>
                <div class="form-group">
                    {!! Form::label('body', 'Article') !!}{!! Form::label('*', '*', array('class' => 'red')) !!}
                    {!! Form::textarea('body', null, ['class' => 'body form-control', 'rows' => 10, 'id' => 'editor']) !!}
                </div>
                <div class="form-group">
                    <input type="submit" value="Save" class="btn btn-primary pull-right">
                </div>
            {!!Form::close()!!}
        </div>
    </div>
    <!-- /.row -->
@endsection


@section('Scripts')
<script src="https://cdn.ckeditor.com/4.11.1/standard/ckeditor.js">
</script><script type="text/javascript">
    CKEDITOR.replace('body');
</script>


@endsection    