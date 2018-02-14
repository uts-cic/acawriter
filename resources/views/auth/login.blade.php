@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="mx-auto col-md-6 col-xs-12 align-self-center">
            <div class="card">

                <div class="card-block">
                    <div class="card-header bg-dark text-white">
                        <ul class="nav nav-tabs card-header-tabs">
                            <li class="nav-item">
                                <a class="nav-link active" href="#utslogin" data-toggle="tab">UTS Staff Login</a>
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
                                    <p>UTS staff & students can login using the link below: <br />
                                    </p>
                                    <a class="btn btn-lg btn-dark" href="{{env('AAF_LINK', '')}}"
                                    >UTS Login</a>
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
</div>
@endsection
