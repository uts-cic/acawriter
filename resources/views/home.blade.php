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
                <div class="card-header card-inverse card-primary">Status</div>
                <div class="card-body">
                    <div class="alert alert-info" role="alert">
                        <log-status :slogs="slogs"></log-status>
                    </div>

                        <tap-status :tap-health="tapHealth"></tap-status>

                </div>
            </div>
        </div>

        @if(in_array('admin', $data->roles))
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
        @endif

    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">My assignments</div>
                <div class="card-body"><autocomplete></autocomplete>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card">
                <div class="card-header">Notifications</div>
                <div class="card-body">
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Reports</div>
                <div class="card-body">
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
