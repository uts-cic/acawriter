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
                                    <label for="assignment-name">Title</label>
                                    <input type="text"  class="form-control" id="assignment-name" name="name" placeholder="assignment name"/>
                                </div>
                                <div class="col-md-6">
                                    <label for="feedbackOpt">Genre</label> <a href="/example"><i class="fa fa-question-circle"></i></a>
                                    <select class="form-control" id="grammar" name="grammar">
                                        @foreach($features as $key => $value)
                                            <optgroup label="{{$key}}">
                                                @foreach($value as $feature)
                                                    <option value="{{$feature->id}}">{{$feature->name}}</option>
                                                @endforeach
                                            </optgroup>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-10">
                                    <label for="keywords">Short Description <small>(if applicable)</small></label>
                                    <input class="form-control" id="keywords" type="text" name="keywords" placeholder="short description"/>
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
                    <div class="card-header bg-dark text-white">Current assignments</div>
                    <div class="card-body">
                       <assignment-list assignments="{{($assignments)}}"></assignment-list>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection