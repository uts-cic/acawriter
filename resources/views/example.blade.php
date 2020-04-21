@extends('layouts.app')

@section('content')
<main id="app" class="container">
    <h1>Examples</h1>

    <p>
        Here are some example pieces of writing to experiment with. They’re either Analytical or Reflective writing, and come from different disciplines.
        <br>
        You can edit the texts (nothing is saved), and see the <strong>automatic feedback</strong>. When you're ready, go to <a href="{{ url('/') }}">My documents</a> and create your own document.
    </p>

    <example-text></example-text>
</main>
@endsection
