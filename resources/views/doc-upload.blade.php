@extends('layouts.app')

@section('content')
<div class="container" id="app">
    @if (count($errors) > 0)
    <div class="alert alert-danger">
        <strong>Whoops!</strong> There were some problems with your input.<br><br>
        <ul>
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif
    @if(session('success'))

    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif


    <form action="{{ url('doc-upload') }}" enctype="multipart/form-data" method="POST">
        {{ csrf_field() }}
        <div class="row">
            <div class="col-md-12">
                <input type="file" name="docs[]" multiple />
            </div>
            <div class="col-md-12">
                <button type="submit" class="btn btn-success">Upload</button>
            </div>
        </div>
    </form>
</div>
@endsection