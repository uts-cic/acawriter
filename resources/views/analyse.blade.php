@extends('layouts.app')

@section('content')
<div id="app">
    @if ($data->assignment[0])
    <doc-editor assignment="{{$data->assignment[0]}}"></doc-editor>
    @else
    <doc-editor assignment="{{$data->assignment_id[0]}}"></doc-editor>
    @endif

</div>
@endsection


@section('footer')

@stop