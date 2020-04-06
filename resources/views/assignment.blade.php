@extends('layouts.app')

@section('content')
<main id="app" class="container">

    <h1 class="font-weight-normal mt-5 mb-5">Assignments</h1>

    <div class="jumbotron mb-5">
        <div class="row">
            <div class="col-md-6">
                <h4>Introduction to assignment management</h4>
                <p class="p-2">
                    <ol>
                        <li> Give your assignment a title and short description.</li>
                        <li> Choose the genre type from the drop-down menu.</li>
                        <li> Click blue "Create assignment" button.</li>
                        <li> It generates an unique Assignment Code and is listed in the table below.</li>
                        <li> Please use this assignment code to share it with students for them to subscribe and use AcaWriter.</li>
                    </ol>
                </p>
            </div>
            <div class="col-md-6">
                <iframe width="560" height="315" src="https://www.youtube.com/embed/kwAP264Rnak" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen="allowfullscreen" class="" style="width: 100%;"></iframe>
            </div>
        </div>
    </div>

    @include('admin.flash')

    <div id="add" class="mb-5">
        <div class="card mb-5 bg-light shadow-sm">
            <div class="card-header">
                <h2 class="my-0">Create a new assignment</h2>
            </div>
            <div class="card-body">
                <form class="form" method="POST" id="add_assignment" action="/assignment/create" autocomplete="off">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for="assignment-name">Assignment name</label>
                            <input type="text"  class="form-control" id="assignment-name" name="name">
                        </div>
                        <div class="form-group col-md-8">
                            <label for="keywords">Short description <small>(optional)</small></label>
                            <input class="form-control" id="keywords" type="text" name="keywords" placeholder="">
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-4">
                            <label for="feedbackOpt">Genre</label> <a href="{{ url('example') }}"><i class="fa fa-question-circle"></i></a>
                            <select class="form-control" id="grammar" name="grammar">
                                @foreach ($features as $grammar => $featureGroup)
                                <optgroup label ="{{ $grammar }}">
                                    @foreach ($featureGroup as $feature)
                                    <option value="{{ $feature->id }}">{{ $feature->name }}</option>
                                    @endforeach
                                </optgroup>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group col-md-8">
                            <label for="featureInfo">Information about the genre</label>
                            <br>
                            @foreach ($features as $featureGroup)
                                @foreach ($featureGroup as $feature)
                                    <div class="feature_info" data-index="{{ $feature->id }}"><small>{!! $feature->info !!}</small></div>
                                @endforeach
                            @endforeach
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Create assignment</button>
                </form>
                @if ($errors->any())
                <div class="col-md-12">
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <assignment-list assignments="{{ $assignments }}" class="mb-5"></assignment-list>

</main>
@endsection
