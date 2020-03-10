@extends('layouts.app')

@section('content')
<div class="container" id="app">
    @include('admin.flash')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-default">
                    <h2>Contact</h2>
                    <small>Feel free to drop us a line if you have any questions.</small>
                </div>
                <div class="card-block p-5">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="card-text">
                                How well did AcaWriter do? We want to hear from you!
                            </p>

                            <form class="form-horizontal" method="POST" action="/contact" autocomplete="off">
                                {{ csrf_field() }}

                                <div class="form-group">
                                    <label for="name" class="col-md-4 control-label">Name</label>
                                    <input id="name" type="text" class="form-control" name="name" value="{{ $contact->name }}" required autofocus>
                                </div>
                                <div class="form-group">
                                    <label for="email" class="col-md-4 control-label">E-Mail Address</label>
                                    <input id="email" type="email" class="form-control" name="email" value="{{ $contact->email }}" required autofocus>
                                </div>

                                <div class="form-group">
                                    <label for="message" class="col-md-4 control-label">Comments</label>
                                    <textarea id="comment" class="form-control" name="comment" required></textarea>
                                </div>

                                @cannot('bypass-captcha')
                                <div class="form-group">
                                    <div class="captcha">
                                        <span>{!! captcha_img() !!}</span>
                                        <button type="button" class="btn btn-success"><i class="fa fa-refresh" id="refresh"></i></button>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <input id="captcha" type="text" class="form-control" placeholder="Enter Captcha" name="captcha">
                                </div>
                                @endcannot

                                <div class="form-group">
                                    <div class="row">
                                        <div class="col-md-12">
                                            <button type="submit" class="btn btn-dark">
                                                Send
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-6">


                            <p class="card-text">
                                <strong>General Enquiries and Technical Enquires </strong><br /><em>(including from academics wishing to use AcaWriter)</em><br /><br />
                                Email: <a href="mailto:cic@uts.edu.au">cic@uts.edu.au </a>

                            </p>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>
@endsection