@extends('layouts.app')

@section('content')
<div id="app">
    @if (isset($data->document[0]))
    <doc-editor document="{{$data->document[0]}}" :user-activity="userActivity"></doc-editor>
    @else
    <doc-editor document=""></doc-editor>
    @endif

</div>
@endsection
