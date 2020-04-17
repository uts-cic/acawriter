@extends('layouts.app')

@section('content')
<div class="container">

    @if ($errors->any())
        <div class="alert alert-danger alert-block">
            <button type="button" data-dismiss="alert" class="close">&times;</button>
            @foreach ($errors->all() as $error)
            <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <h1 class="font-weight-normal mt-5 mb-3">Welcome to AcaWriter!</h1>
    <p class="lead text-muted mt-3 mb-5">AcaWriter is a software tool that helps you develop your academic and reflective writing by providing you with automatic feedback. It is available to all UTS staff and students.</p>

    <!-- <p><strong>UTS</strong> isn’t here to tell you what to think, but to help you learn how to think. Similarly, <strong>AcaWriter</strong> won’t tell you what to write, but will help you learn how to say it in the most rigorous, effective way. Before you just jump in, please visit the <a href="https://www.uts.edu.au/acawriter" target="_blank">AcaWriter information website</a>. This will help maximise the impact that AcaWriter has on your academic and reflective writing. Once you are ready to use AcaWriter, please login below.
                <br>All enquiries, requests, bug reports, please submit using the <a href="{{ url('help') }}">contact form</a>.</p> -->
    <div class="row">
        <div class="col-md-6">
            <a href="https://www.uts.edu.au/acawriter" target="_blank" class="img-link">
                <img src="/images/acaWriter.jpg" alt="Visit the information website to find out more!" class="img-fluid mb-5">
            </a>
        </div>
        <div class="col-md-6">
            <p><strong>UTS</strong> isn’t here to tell you what to think, but to help you learn how to think. Similarly, <strong>AcaWriter</strong> won’t tell you what to write, but will help you learn how to say it in the most rigorous, effective way. Before you just jump in, please visit the <a href="https://www.uts.edu.au/acawriter" target="_blank">AcaWriter information website</a>. This will help maximise the impact that AcaWriter has on your academic and reflective writing. Once you are ready to use AcaWriter, please login below.
            <br>All enquiries, requests, bug reports, please submit using the <a href="{{ url('help') }}">contact form</a>.</p>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12 col-xs-12">
            @if (env('AAF_LINK', ''))
            <ul class="nav nav-tabs awa-tabs">
                <li class="nav-item">
                    <a href="#utslogin" data-toggle="tab" class="nav-link active">UTS Login</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="#otherlogin" data-toggle="tab">Non UTS login</a>
                </li>
            </ul>

            <div class="tab-content p-5">
                <div class="tab-pane fade active show" id="utslogin" role="tabpanel">
                    <a class="btn btn-lg btn-primary" href="{{ env('AAF_LINK', '') }}">UTS Staff & Student Login</a>
                </div>

                <div class="tab-pane fade" id="otherlogin" role="tabpanel">
                    <p>Non UTS and other authorised user login</p>
                    @include('auth.form')
                </div>
            </div>
            @else
            <div class="p-5">
                @include('auth.form')
            </div>
            @endif
        </div>
    </div>
</div>
@endsection
