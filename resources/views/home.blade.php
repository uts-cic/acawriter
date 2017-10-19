@extends('layouts.app')

@section('content')
<div class="container" id="app">
    <div class="row">
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @endif
                    <div class="alert alert-info" role="alert">You are logged in!</div>
                    <br />
                    <internet-connection :last-heart-beat-received-at="lastHeartBeatReceivedAt"></internet-connection>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Status</div>
                <div class="card-body">
                    <log-status :slogs="slogs"></log-status>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
