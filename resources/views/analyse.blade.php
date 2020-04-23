@extends('layouts.app')

@section('content')
<style>.container { max-width: 100%; padding: 0 30px; }</style>
<main id="app" class="container">
    <doc-editor document="{{ $document }}" :user-activity="userActivity"></doc-editor>
</main>
@endsection
