@extends('layouts.app')

@section('content')
<div id="app" class="container-fluid">
	@if (isset($versions))
	<form action="{{ url('/admin/diffreport') }}" method="post">
		<div>
			<label>Version From:</label>
	    	<select id = "id" name = "id">
	    		@foreach($versions as $version)
	    		<option value ="{{$version->id}}">{{$version->version}} ({{$version->created_at}})</option>
	    		@endforeach
	    	</select>
	    	<label>Version To:</label>
	    	<select id = "version">
	    		@foreach($versions as $version)
	    		<option value = "{{$version->created_at}}">{{$version->version}} ({{$version->created_at}})</option>
	    		@endforeach
	    	</select>
	    	<button type="submit">Go</button>
	    </div>
	    {{ csrf_field() }}
	</form>
	@endif
    @if (isset($data->draft_first))
    <diff-report document="{{json_encode($data->draft_first)}}" document_compare="{{json_encode($data->draft_second)}}" :user-activity="userActivity"></diff-report>
    @else
    <diff-report document=""></diff-report>
    @endif

</div>
@endsection