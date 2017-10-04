@extends('layouts.app')

@section('content')
<div class="container" id="app">
    <div class="row">
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading">Dashboard</div>

                <div class="panel-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    You are logged in!<br />
                    <internet-connection :last-heart-beat-received-at="lastHeartBeatReceivedAt"></internet-connection>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="panel panel-default">
                <div class="panel-heading">Status</div>
                <div class="panel-body">
                    <log-status :slogs="slogs"></log-status>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
