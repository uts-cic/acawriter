@extends('layouts.app')

@section('content')
<div id="app">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @include('admin.flash')
                <div class="card">
                    <div class="card-header bg-dark text-white">Add a new assignment</div>
                    <div class="card-body">
                        <form class="form" method="POST" action="/assignment">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="form-group col-md-6">
                                    <label for="assignment-name">1. Title</label>
                                    <input type="text"  class="form-control" id="assignment-name" name="name" placeholder="assignment name"/>
                                </div>
                                <div class="col-md-6">
                                    <label for="feedbackOpt">2. Grammar</label>
                                    <select class="form-control" id="grammar" name="grammar">
                                        <option value="">--Select--</option>
                                        @foreach($features as $feature)
                                            <option value="{{$feature->id}}">{{$feature->grammar}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-8">
                                    <label for="keywords">Expected Keywords(comma separated) <small>optional</small></label>
                                    <input class="form-control" id="keywords" type="text" name="keywords" placeholder="server, place"/>
                                </div>
                                <div class="col-md-2"> <br />
                                    <button type="submit" class="btn btn-primary">Add</button>
                                </div>
                            </div>
                        </form>
                        @if (count($errors))
                        <div class="col-md-12">
                            <div class="alert alert-danger">
                                <ul>
                                    @foreach($errors->all() as $error)
                                    <li>{{$error}}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header bg-dark text-white">Current Assignments</div>
                    <div class="card-body">
                        <table class="table">
                            <thead class="thead-dark">
                            <tr>
                                <th scope="col">Code</th>
                                <th scope="col">Title</th>
                                <th scope="col">Grammar</th>
                                <th scope="col">Keywords</th>
                                <th scope="col">Actions</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($assignments as $assignment)
                            <tr>
                                <th scope="row">{{$assignment->code}}</th>
                                <td>{{$assignment->name}}</td>
                                <td>Analytical</td>
                                <td><small>{{$assignment->keywords}}</small></td>
                                <td><a href="#"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a> &nbsp; <a href="#"><i class="fa fa-trash-o" aria-hidden="true"></i></a></td>
                            </tr>
                            @endforeach
                    </div>
                </div>
            </div>
        </div>



    </div>


</div>
@endsection