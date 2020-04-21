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
        <div class="tile tile-1 tile-bg tile-centered">
            <div class="field-item">
                <h3>Get started!</h3>
                <p>AcaWriter is available to all UTS staff and students.</p>
                <p><a class="btn btn-primary" href="{{ env('AAF_LINK', '') }}">Login to AcaWriter</a></p>
            </div>
        </div>
        <div class="tile tile-2 tile-bordered">
            <p><strong>UTS</strong> isn’t here to tell you what to think, but to help you learn how to think.</p>
            <p>Similarly, <strong>AcaWriter</strong> won’t tell you what to write, but will help you learn how to say it in the most rigorous, effective way.</p>
            <p>Before you just jump in, please visit the <a href="https://www.uts.edu.au/acawriter" target="_blank">AcaWriter information website</a>. This will help maximise the impact that AcaWriter has on your academic and reflective writing. Once you are ready, please <a href="{{ env('AAF_LINK', '') }}">login to AcaWriter</a>.</p>
            <p>To get technical support, or if you  have any enquiries, please visit <a href="{{ url('help') }}">Help &amp; Support</a>.</p>
        </div>
    </div>
</main>
@endsection
