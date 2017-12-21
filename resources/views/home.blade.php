@extends('layouts.app')

@section('content')
<div class="container" id="app">


    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header"> <h5>Assignments</h5></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <autocomplete></autocomplete>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">Reports</div>
                <div class="card-body">
                    <table class="table">
                        <thead class="thead-dark">
                        <tr>
                            <th scope="col">Code</th>
                            <th scope="col">Title</th>
                            <th scope="col">Last Updated</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($data->assignments as $list)
                        <tr>
                            <th scope="row">{{$list->code}}</th>
                            <td><a href="/analyse/{{$list->code}}">{{$list->name}}</a></td>
                            <td><small>22/7/2017</small></td>
                        </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>
                <div class="card-body">
                    <ul class="list-group">
                        @if (session('status'))
                        <li class="list-group-item list-group-item-success">
                            {{ session('status') }}
                        </li>
                        @endif
                        <li class="list-group-item list-group-item-info" role="alert">You are logged in!</li>
                        <internet-connection :last-heart-beat-received-at="lastHeartBeatReceivedAt"></internet-connection>
                        <tap-status :tap-health="tapHealth"></tap-status>
                        <log-status :slogs="slogs"></log-status>
                    </ul>
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
                    <a href="/assignment" class="list-group-item list-group-item-action"><i class="fa fa-clone"></i>  Manage Assignments</a>
                </div>
            </div>
        </div>
        @endif

    </div>


</div>
@endsection