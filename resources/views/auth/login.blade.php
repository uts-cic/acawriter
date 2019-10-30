@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h1>AcaWriter</h1>
            <h3 class="text-secondary">AcaWriter is a software tool that helps you develop your academic and reflective writing by providing you with automatic feedback. It is available to all UTS staff and students.</h3>
        </div>
    </div>
    <div class="row">
        <div class="col-md-7 col-xs-12">
            <a href="https://www.uts.edu.au/acawriter" target="_blank"><img src="/images/acaWriter.jpg" alt="more information on acawriter" /></a>
        </div>
        <div class="col-md-5 col-xs-12">
            <div class="alert alert-secondary p-4">
                <strong>UTS</strong> isn’t here to tell you what to think, but to help you learn how to think. Similarly, <strong>AcaWriter</strong> won’t tell you what to write, but will help you learn how to say it in the most rigorous, effective way. Before you just jump in, please visit the <a href="https://www.uts.edu.au/acawriter" target="_blank">AcaWriter information website</a>. This will help maximise the impact that AcaWriter has on your academic and reflective writing. Once you are ready to use AcaWriter, please login below.
                <br />All enquiries, requests, bug reports, please submit using the <a href="/page/contact">contact</a> form.
            </div>
        </div>
    </div>
    <br />
    <div class="row">
        <div class="col-md-12 col-xs-12 align-self-center">
            <div class="card">
                @if (env('AAF_LINK', ''))
                <div class="card-block">
                    <div class="card-header bg-dark text-white">
                        <ul class="nav nav-tabs card-header-tabs">
                            <li class="nav-item">
                                <a class="nav-link active" href="#utslogin" data-toggle="tab">UTS Login</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#otherlogin" data-toggle="tab">Non UTS login</a>
                            </li>
                        </ul>
                    </div>

                    <div class="card-block">
                        <div class="tab-content p-5">
                            <div class="tab-pane active" id="utslogin" role="tabpanel">
                                <div class="form-group">
                                    <a class="btn btn-lg btn-dark" href="{{env('AAF_LINK', '')}}"
                                    >UTS Staff & Student Login</a>
                                </div>
                            </div>

                            <div class="tab-pane" id="otherlogin" role="tabpanel">
                                <p>Non UTS and other authorised user login</p>
                                @include('auth.form')
                            </div>
                        </div>
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
    <br />
</div>
@endsection
