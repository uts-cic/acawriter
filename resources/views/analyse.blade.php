@extends('layouts.app')

@section('content')
<div id="app">
    <doc-editor assignment="{{$data->assignment_id[0]}}"></doc-editor>
</div>
@endsection


@section('footer')

@stop