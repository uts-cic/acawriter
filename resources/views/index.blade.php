@extends('layouts.app')

@section('content')
<main class="container">

    @if ($errors->any())
        <div class="alert alert-danger alert-block">
            <button type="button" data-dismiss="alert" class="close">&times;</button>
            @foreach ($errors->all() as $error)
            <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <h1>Welcome to AcaWriter!</h1>
    <p class="lead">AcaWriter helps develop your academic and reflective writing by providing instant, automatic feedback on your drafts, 24/7.
        Automatic feedback is just the latest addition to the <a href="{{ url('help') }}">other UTS writing support services</a>.</p>

    <div class="tiles">
        <a class="tile tile-1 tile-bg tile-centered" href="https://www.uts.edu.au/acawriter" target="_blank">
            <div class="tile-content">
                <h3>Please visit the information website first.</h3>
                <p>(You won’t have used a tool like this before!)</p>
            </div>
        </a>
        <div class="tile tile-2 tile-bordered">
            <p><strong>UTS</strong> isn’t here to tell you what to think, but to help you learn how to think. Similarly, <strong>AcaWriter</strong> won’t tell you what to write, but will help you learn how to say it in the most rigorous, effective way.</p>
            <p>Before you just jump in, please visit the <a href="https://www.uts.edu.au/acawriter" target="_blank">AcaWriter information website</a>. This will help maximise the impact that AcaWriter has on your academic and reflective writing.</p>
            <p>AcaWriter is available to all UTS staff and students.</p>
            <a class="btn btn-primary" href="{{ url('auth') }}">Login to AcaWriter</a>
        </div>
    </div>
</main>
@endsection
