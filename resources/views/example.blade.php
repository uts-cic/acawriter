@extends('layouts.app')

@section('content')
<main class="container-fluid" id="app">
    <div class="subheader shadow">
        <div class="subheader-title"><h4>Examples</h4></div>
    </div>

    <p>
        Here are some example pieces of writing to experiment with. They’re either Analytical or Reflective writing, and come from different disciplines.
        <br>
        You can edit the texts (nothing is saved), and see the <strong>automatic feedback</strong>. When you're ready, go to <a href="{{ url('/') }}">My documents</a> and create your own document.
    </p>

    <example-text></example-text>
</main>
@endsection
