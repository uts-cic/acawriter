@extends('layouts.app')

@section('content')
<div id="app" class="container-fluid">
	@if (isset($versions))
	<form action="{{ url('/admin/diffreport') }}" method="get">
		<div>
			<label>Version From:</label>
	    	<select id = "id" name = "id">
	    		@foreach($versions as $version)
	    			@if ($version->created_at == $data->draft_first->created_at)
	    			<option value ="{{$version->id}}" selected>{{$version->version}} ({{$version->created_at}})</option>
	    			@else
	    			<option value ="{{$version->id}}">{{$version->version}} ({{$version->created_at}})</option>
	    			@endif
	    		@endforeach
	    	</select>
	    	<label>Version To:</label>
	    	<select id = "id_to" name  = "id_to">
	    		@foreach($versions as $version)
		    		@if ($version->created_at == $data->draft_second->created_at)
		    		<option value = "{{$version->id}}" selected>{{$version->version}} ({{$version->created_at}})</option>
		    		@else
		    		<option value = "{{$version->id}}">{{$version->version}} ({{$version->created_at}})</option>
		    		@endif
	    		@endforeach
	    	</select>
	    	<button type="submit">Go</button>
	    </div>
	</form>
	<div align = 'center'>
		<form action="{{ url('/admin/diffreport') }}" method="get">
    			<button type = "submit"><- Prev</button>
    			<button type = "submit">Next -></button>
		</form>
	</div>
	@endif
    @if (isset($data->draft_first))
    <diff-report document="{{json_encode($data->draft_first)}}" document_compare="{{json_encode($data->draft_second)}}" :user-activity="userActivity"></diff-report>
    @else
    <diff-report document=""></diff-report>
    @endif

</div>
@endsection