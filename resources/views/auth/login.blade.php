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

    <h1>Login</h1>
    <p class="lead">AcaWriter is available to all UTS staff and students.</p>
    <p class="mb-5"><a class="btn btn-primary" href="{{ env('AAF_LINK', '') }}">UTS Staff and Students Login</a></p>

    <h2>Authorised non-UTS users only</h2>
    <div>
        @include('auth.form')
    </div>
</main>
@endsection
