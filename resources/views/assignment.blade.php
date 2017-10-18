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
                                <div class="form-group col-md-10">
                                    <label for="staticEmail2" class="sr-only">Assignment</label>
                                    <input type="text"  class="form-control" id="assignment-name" name="name" placeholder="assignment name"/>
                                </div>
                                <div class="col-md-2">
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