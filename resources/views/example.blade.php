@extends('layouts.app')

@section('content')
<div class="container" id="app">
    <div class="row">
        <div class="col-md-12">
            <h1>Example Texts</h1>
            <small>Here are some example pieces of writing to experiment with. They’re either Analytical or Reflective writing, and come from different disciplines. You can edit the texts (nothing is saved), and see the <strong>automatic feedback</strong>. When you're ready, go to <a href="/home">My Dashboard</a> and create your own document.</small>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-block">
                    <example-text></example-text>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection