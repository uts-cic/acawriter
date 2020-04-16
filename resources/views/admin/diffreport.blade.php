@extends('layouts.app')

@section('content')
<div id="app" class="container-fluid">
	@if (isset($versions))
	<form action="{{ url('/admin/diffreport') }}" method="get">
		<div>
			<label>Version From:</label>
	    	<select id = "id" name = "id">
	    		@foreach($versions as $version)
	    		<option value ="{{$version->id}}">{{$version->version}} ({{$version->created_at}})</option>
	    		@endforeach
	    	</select>
	    	<label>Version To:</label>
	    	<select id = "id_to" name  = "id_to">
	    		@foreach($versions as $version)
	    		<option value = "{{$version->id}}">{{$version->version}} ({{$version->created_at}})</option>
	    		@endforeach
	    	</select>
	    	<button type="submit">Go</button>
	    </div>
	</form>
	@endif
    @if (isset($data->draft_first))
    <diff-report document="{{json_encode($data->draft_first)}}" document_compare="{{json_encode($data->draft_second)}}" :user-activity="userActivity"></diff-report>
    @else
    <diff-report document=""></diff-report>
    @endif

</div>
@endsection