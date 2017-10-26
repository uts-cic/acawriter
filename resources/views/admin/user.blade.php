<?php
/**
 * Created by IntelliJ IDEA.
 * User: Developer CIC: Radhika Mogarkar
 * Date: 26/10/17
 * Time: 9:22 AM
 */
?>
@extends('layouts.app')

@section('content')
<div id="app">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">Manage Users</div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-3"><strong>Name</strong></div>
                            <div class="col-md-4"><strong>Email</strong></div>
                            <div class="col-md-4"><strong>Roles</strong></div>
                            <div class="col-md-1"><strong>Edit</strong></div>
                        </div>
                        <hr />
                        @foreach($data->users as $user)
                        <div class="row">
                            <div class="col-md-3">{{$user->name}}</div>
                            <div class="col-md-4">{{$user->email}}</div>
                            <div class="col-md-4">
                                @foreach($data->roles as $role)
                                {{$role->name}}
                                @if ($user->hasRole($role->name))
                                    <input type="checkbox" name="user_{{$role->name}}" checked />
                                @else
                                    <input type="checkbox" name="user_{{$role->name}}" />
                                @endif
                                @endforeach
                            </div>
                            <div class="col-md-1">
                                <a href="#" class="btn btn-success"><i class="fa fa-floppy-o" aria-hidden="true"></i></a>
                            </div>
                        </div>
                        @endforeach

                    </div>
                </div>
            </div>
        </div>



    </div>


</div>
@endsection


@section('footer')

@stop
