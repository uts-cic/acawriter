@extends('layouts.app')

@section('content')
<div id="app">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Add a new assignment</div>
                    <div class="card-body">
                        <form class="form" method="POST" action="/assignment">
                            {{ csrf_field() }}
                            <div class="row">
                                <div class="form-group col-md-4">
                                    <label for="assignment-name">Name</label>
                                    <input type="text"  class="form-control" id="assignment-name" name="name" placeholder="assignment name"/>
                                </div>
                                <div class="col-md-2">
                                    <label for="feedbackOpt">Feedback</label>
                                    <select class="form-control" id="feedbackOpt" v-model="feedbackOpt">
                                        <option value="">--Select--</option>
                                        <option value="feedback">Default</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label for="feedbackOpt">Grammar</label>
                                    <select class="form-control" id="grammar" v-model="grammar">
                                        <option value="">--Select--</option>
                                        <option value="reflective">Reflective</option>
                                        <option value="analytics">Analytics</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <label for="keywords">Keywords(comma separated)</label>
                                    <input class="form-control" id="keywords" v-model="keywords" placeholder="server, place"/>
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
                    <div class="card-header">Current Assignments</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6"><strong>Name</strong></div>
                            <div class="col-md-3"><strong>Code</strong></div>
                            <div class="col-md-3"><strong>Published</strong></div>
                        </div>
                        <hr />
                        @foreach($assignments as $assignment)
                            <div class="row">
                                <div class="col-md-6">{{$assignment->name}}</div>
                                <div class="col-md-3">{{$assignment->code}}</div>
                                <div class="col-md-3">{{$assignment->published==0?'No':'Yes'}}</div>
                            </div>
                        @endforeach

                    </div>
                </div>
            </div>
        </div>



    </div>


</div>
@endsection


@section('footer')

@stop