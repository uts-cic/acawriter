@extends('layouts.app')

@section('content')
<div id="app" class="container-fluid">
	@if (isset($versions))
	<form action="{{ url('/admin/diffreport') }}" method="get">
		<div>
			<label>Version From:</label>
	    	<select id = "id" name = "id">
	    		@foreach($versions as $version)
	    			@if ($version->created_at == $data->draft_second->created_at)
	    			<option value ="{{$version->id}}" selected>{{$version->version}} ({{$version->created_at}})</option>
	    			@else
	    			<option value ="{{$version->id}}">{{$version->version}} ({{$version->created_at}})</option>
	    			@endif
	    		@endforeach
	    	</select>
	    	<label>Version To:</label>
	    	<select id = "id_to" name  = "id_to">
	    		@foreach($versions as $version)
		    		@if ($version->created_at == $data->draft_first->created_at)
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
			@foreach($versions as $key=>$version)
				@if ($version->created_at == $data->draft_second->created_at && $version->version == 1)
    			<button type = "submit" disabled><- Prev</button>
    			@elseif ($version->created_at == $data->draft_second->created_at)
    			<input hidden name="id" value="{{ $versions[$key+1]->id }}"></input>
    			<input hidden name="id_to" value="{{ $versions[$key]->id}}"></input>
    			<button type = "submit"><- Prev</button>
    			@endif
    			@if ($version->created_at == $data->draft_first->created_at && $version->version == count($versions))
    			<button type = "submit" disabled>Next -></button>
    			@elseif ($version->created_at == $data->draft_first->created_at)
    			<input hidden name="id" value="{{ $versions[$key]->id }}"></input>
    			<input hidden name="id_to" value="{{ $versions[$key-1]->id}}"></input>
    			<button type = "submit">Next -></button>
    			@endif
    		@endforeach
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