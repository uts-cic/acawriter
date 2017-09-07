@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="panel panel-default">
                <div class="panel-heading">Log</div>

                <div class="panel-body">
                    @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                    @endif

                    You are logged in!
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel panel-default">
            <div class="panel-heading">Log</div>
                <div class="panel-body">
                    <div id="app">
                        <log-status :slogs="slogs"></log-status>
                     </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@section('footer')

@stop