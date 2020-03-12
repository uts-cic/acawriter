@extends('layouts.app')

@section('content')
<div id="app" class="container-fluid">
    @if (isset($data->draft_first))
    <diff-report document="{{json_encode($data->draft_first)}}" document_compare="{{json_encode($data->draft_second)}}" :user-activity="userActivity"></diff-report>
    @else
    <diff-report document=""></diff-report>
    @endif

</div>
@endsection