@extends('layouts.basic')

@section('title', 'IFDM Database')

@section('content')

@foreach ($article->revisions as $revision)
  <div class="alert alert-warning no-margin">
    <caption>
      {{ $revision->created_at }} rekord <strong>{{ $revision->action }}</strong> przez: <strong>{{ $revision->user }}</strong>.
    </caption>
  </div>
   
  @if (count($revision->old))
    <table class="table">
      <thead>
        <tr>
          <th>Pole</th>
          <th>Stara wartość</th>
          <th>Nowa wartość</th>
        </tr>
      </thead>
     
        @foreach ($revision->old as $key => $v)
        <tr>
          <td>{{ $revision->label($key) }}</td>
          <td class="{{ $revision->isUpdated($key) ? ' danger' : '' }}">{{ $revision->old($key) }}</td>
          <td class="{{ $revision->isUpdated($key) ? ' success' : '' }}">{{ $revision->new($key) }} {{$article->identifiableNameC(1)->nombre}}</td>
        </tr>
        @endforeach
    </table>
  @endif
@endforeach

@endsection


@section('Scripts')

x =  {!! json_encode($article->identifiableNameC(1)->nombre) !!};


@endsection


