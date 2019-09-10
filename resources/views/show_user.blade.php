@extends('layouts.userSidebar')

@section('title', 'IFDM User')


@section('content')

<h2>User: <small>{{ $user->name }}</small></h2>
</br>

<table class="table table-striped ">
    <tr>
        <td>
        <th>Name</th>
        </td>
        <td>
            {{ $user->name }}
        </td>
    </tr>

    <tr>
        <td>
        <th>Position</th>
        </td>
        <td>
            {{ $user->charge }}
        </td>
    </tr>

    <tr>
        <td>
        <th>Company</th>
        </td>
        @if ($user->company == 0)
        <td>
            UN
        </td>
        @elseif ($user->company == 1)
        <td>
            Equion
        </td>
        @elseif ($user->company == 2)
        <td>
            Ecopetrol
        </td>
        @elseif ($user->company == 3)
        <td>
            Hocol
        </td>
        @endif
    </tr>

    <tr>
        <td>
        <th>Profile</th>
        </td>
        @if ($user->office == 0)
        <td>
            System administrator
        </td>
        @elseif ($user->office == 1)
        <td>
            Company administrator
        </td>
        @elseif ($user->office == 2)
        <td>
            Engineer
        </td>
        @endif
    </tr>

    <tr>
        <td>
        <th>Gender</th>
        </td>
        @if ($user->gender == 0)
        <td>
            Male
        </td>
        @elseif ($user->gender == 1)
        <td>
            Female
        </td>
        @endif
    </tr>

    <tr>
        <td>
        <th>E-mail</th>
        </td>
        <td>
            {{ $user->email }}
        </td>
    </tr>
</table>

<div class="row">
    <ul class="pager">
        <li class="previous"><a href="{{ URL::route('UserC.index') }}"><span aria-hidden="true">&larr;</span> Return</a></li>
    </ul>
</div>


@endsection

