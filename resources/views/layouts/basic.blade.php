@extends('layouts.general')

@section('title', 'Add data')


@section('sidebar')
  <div class="list-group">
    <a class="list-group-item active">Add Data</a>
    {!! link_to('AddDataC', 'Basin',array('class' => 'list-group-item'))!!}
    {!! link_to('AddDataC', 'Field', array('class' => 'list-group-item')) !!}
    {!! link_to('AddFormationC', 'Formation',array('class' => 'list-group-item')) !!}
    {!! link_to('AddFormationWC', 'Well',array('class' => 'list-group-item')) !!}
    {!! link_to('AddFormationWellC', 'Producing Interval',array('class' => 'list-group-item')) !!}
    {!! link_to('AddMeasurementC', 'Damage Variables',array('class' => 'list-group-item')) !!}
    {!! link_to('filtration_function', 'Filtration Function',array('class' => 'list-group-item')) !!}
    <a href="{{route('formation-mineralogy.create')}}" class="list-group-item">Formation Mineralogy</a>
    <a href="{{route('pvt-global.create')}}" class="list-group-item">PVT Library</a>
  </div>
 @endsection

