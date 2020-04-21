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
    <p class="lead">Non UTS and other authorised users only</div>
    <div>
        @include('auth.form')
    </div>
</main>
@endsection
