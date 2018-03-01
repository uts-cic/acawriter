@extends('layouts.app')

@section('content')
<div class="container" id="app">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-default"><h2>Contact</h2> <small>Feel free to drop us a line if you have any questions.</small></div>
                <div class="card-block p-5">
                    <div class="row">
                        <div class="col-md-6">
                            <p class="card-text">
                                How well did AcaWriter do? We want to hear from you!
                            </p>
                            <form class="form-horizontal" method="POST" action="">
                                {{ csrf_field() }}

                                <div class="form-group">
                                    <label for="name" class="col-md-4 control-label">Name</label>
                                    <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>
                                </div>
                                <div class="form-group">
                                    <label for="email" class="col-md-4 control-label">E-Mail Address</label>
                                    <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required autofocus>
                                </div>

                                <div class="form-group">
                                    <label for="message" class="col-md-4 control-label">Comments</label>
                                    <textarea id="message" class="form-control" name="message" required></textarea>
                                </div>

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
                        <div class="col-md-6 bg-info">


                            <p class="card-text">
                                <strong><em>Academic questions:</em></strong><br /> Prof. Simon Buckingham Shum (CIC Director)<br />
                                <a href="mailto:Simon.BuckinghamShum@uts.edu.au">Simon.BuckinghamShum@uts.edu.au </a>
                                <br />
                                <hr />
                                <strong><em>Technical questions:</em></strong><br /> Radhikar Mogarkar (CIC Web Developer) <br />
                                <a href="mailto:RadhikaVijay.Mogarkar@uts.edu.au">RadhikaVijay.Mogarkar@uts.edu.au </a>
                            </p>
                        </div>
                    </div>

                </div>

            </div>
        </div>
    </div>
</div>
@endsection