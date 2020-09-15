@extends('layouts.general')

@section('title', 'Add data')


@section('sidebar')
  <div class="list-group">
    <a class="list-group-item active">Data Management</a>
    {!! link_to('listBasinC', 'Basin',array('class' => 'list-group-item'))!!}
    {!! link_to('listFieldC', 'Field', array('class' => 'list-group-item')) !!}
    {!! link_to('listFormationC', 'Formation',array('class' => 'list-group-item')) !!}
    {!! link_to('listWellC', 'Well',array('class' => 'list-group-item')) !!}
    {!! link_to('listIntervalC', 'Producing Interval',array('class' => 'list-group-item')) !!}
    {!! link_to('EditMeasurementC', 'Damage variables',array('class' => 'list-group-item')) !!}
    {!! link_to('DeleteProject', 'Project',array('class' => 'list-group-item')) !!}
    {!! link_to('filtration_function_list', 'Filtration Function',array('class' => 'list-group-item')) !!}
    <a href="{{route('formation-mineralogy.index')}}" class="list-group-item">Formation Mineralogy</a>
    <a href="{{route('pvt-global.index')}}" class="list-group-item">PVT Library</a>
  </div>
 @endsection


