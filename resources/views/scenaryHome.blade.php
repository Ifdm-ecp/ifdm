@extends('layouts.ProjectGeneral')

@section('title', 'IFDM Project')


@section('content')

<h2>Scenario List</h2>
</br>

<table class="table table-striped ">
    <tr>
        <th>Name</th>
        <th>Actions</th>
    </tr>
    @foreach  ($scenary as $scenarya)
    <tr>
        <td>{{ $scenarya->nombre }}</td>
        <td>
            <form class="form-inline">
                @if($scenarya->tipo == "IPR")
                    @if($scenarya->completo == 1)
                        <a href="{{ URL('IPR/result', array('id' => $scenarya->id)) }}" class="btn btn-info">View</a>
                    @else
                        <a href="{{ URL::route('IPR.edit', array('id' => $scenarya->id)) }}" class="btn btn-info">View</a>
                    @endif
                @elseif($scenarya->tipo == "Multiparametric")
                    @if($scenarya->completo == 1)
                        <a href="{{ URL::route('multiparametrico', array('id' => $scenarya->id)) }}" class="btn btn-info">View</a>
                    @else
                        <a href="{{ URL::route('ScenaryC.edit', $scenarya->id) }}" class="btn btn-info">View</a>
                    @endif
                @elseif($scenarya->tipo == "Drilling")
                    @if($scenarya->completo == 1)
                        <a href="{{ URL::route('drilling_results', array('id' => $scenarya->id)) }}" class="btn btn-info">View</a>
                    @else
                        <a href="{{ URL::route('ScenaryC.edit', $scenarya->id) }}" class="btn btn-info">View</a>
                    @endif
                @endif
            </form>
        </td>
    </tr>
    @endforeach
</table>

{!! str_replace('/?', '?', $scenary->render()) !!}  

@endsection

@section('Scripts')

@endsection