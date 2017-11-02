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
                    <div class="alert alert-info" role="alert">
                        <log-status :slogs="slogs"></log-status>
                    </div>
                    <div class="alert alert-info" role="alert">
                        TAP Status: <tap-health :tap-health="tapHealth"></tap-health>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">System Admin</div>
                <div class="card-body">
                    <a href="/admin/users" class="list-group-item list-group-item-action"><i class="fa fa-users"></i>  Manage Users</a>
                    <a href="#" class="list-group-item list-group-item-action"><i class="fa fa-cog"></i>  Manage Features</a>
                    <a href="#" class="list-group-item list-group-item-action"><i class="fa fa-clone"></i>  Manage Assignments</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
