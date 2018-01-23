@extends('layouts.app')

@section('content')
<div id="app">
    @if (isset($data[0]))
    <ex-doc-editor ex="{{$data[0]}}"></ex-doc-editor>
    @else
    <ex-doc-editor ex=""></ex-doc-editor>
    @endif

</div>
@endsection
