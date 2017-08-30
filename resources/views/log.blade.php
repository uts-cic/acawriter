@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Log</div>

                <div class="panel-body">
                    @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                    @endif

                    You are logged in!
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@section('footer')
<script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.0.3/socket.io.js"></script>
<script>
    var socket = io('http://127.0.0.1:3000');
    var logs =[];
    socket.on('operational-log:App\\Events\\OperationLog', function(data){
        // increase the power everytime we load test route
        logs.push(data.details);
        console.log(logs);
    });
</script>
@stop