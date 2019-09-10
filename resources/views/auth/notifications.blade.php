@if(Session::has('notificacion__')) 
	{!! Session::get('notificacion__') !!} 
	{{ Session::forget('notificacion__') }}
@endif