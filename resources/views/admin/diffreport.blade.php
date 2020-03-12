@extends('layouts.app')

@section('content')
<div id="app" class="container-fluid">
    @if (isset($data->draft_first))
    <diff-report document="{{$data->draft_first}}" :user-activity="userActivity"></diff-report>
    @else
    <diff-report document=""></diff-report>
    @endif

</div>
@endsection