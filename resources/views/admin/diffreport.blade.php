@extends('layouts.app')

@section('content')
<div id="app" class="container-fluid">
    @if (isset($data->draft_first->raw_response))
    <diff-report document="{{$data->draft_first->text_input}}" :user-activity="userActivity"></diff-report>
    @else
    <diff-report document=""></diff-report>
    @endif

</div>
@endsection
