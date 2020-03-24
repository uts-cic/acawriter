@extends('layouts.app')

@section('content')
<main id="app" class="container-fluid">
    <doc-editor document="{{ $document }}" :user-activity="userActivity"></doc-editor>
</main>
@endsection
