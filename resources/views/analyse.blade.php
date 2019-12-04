@extends('layouts.app')

@section('content')
<main id="app" class="container-fluid">
    @if (isset($data->document[0]))
    <doc-editor document="{{$data->document[0]}}" :user-activity="userActivity"></doc-editor>
    @else
    <doc-editor document=""></doc-editor>
    @endif

</main>
@endsection
