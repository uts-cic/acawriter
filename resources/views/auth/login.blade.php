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
                                <form class="form-horizontal" method="POST" action="{{ route('login') }}">
                                    {{ csrf_field() }}

                                    <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                        <label for="email" class="col-md-4 control-label">E-Mail Address</label>


                                        <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>

                                        @if ($errors->has('email'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('email') }}</strong>
                                        </span>
                                        @endif

                                    </div>

                                    <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                        <label for="password" class="col-md-4 control-label">Password</label>


                                        <input id="password" type="password" class="form-control" name="password" required>

                                        @if ($errors->has('password'))
                                        <span class="help-block">
                                            <strong>{{ $errors->first('password') }}</strong>
                                        </span>
                                        @endif

                                    </div>

                                    <div class="form-group">

                                        <div class="checkbox">
                                            <label>
                                                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember Me
                                            </label>
                                        </div>

                                    </div>

                                    <div class="form-group">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <button type="submit" class="btn btn-dark">
                                                    Login
                                                </button>
                                            </div>
                                            <div class="col-md-6">
                                                <a class="btn btn-link" href="{{ route('password.request') }}">
                                                    Forgot Your Password?
                                                </a>
                                            </div>
                                        </div>
                                    </div>


                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br />
</div>
@endsection
