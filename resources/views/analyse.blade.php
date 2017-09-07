@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="panel panel-default">
                <div class="panel-heading">Text Analyser</div>
                <div class="panel-body">
                    <div id="app">
                        <doc-editor></doc-editor>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading">Your specs</div>
                <div class="panel-body">

                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@section('footer')

@stop