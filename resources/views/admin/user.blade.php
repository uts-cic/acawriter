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
        @include('admin.flash')
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
                        <form action="/admin/users" method="post">
                        <div class="row">
                            <div class="col-md-3">{{$user->name}}
                                <input type="hidden" name="user_id" value="{{$user->id}}" />
                            </div>
                            <div class="col-md-4">{{$user->email}}</div>
                            <div class="col-md-4">
                                @foreach($data->roles as $role)
                                {{$role->name}}
                                @if ($user->hasRole($role->name))
                                    <input type="checkbox" name="roles[]" checked value="{{$role->id}}" />
                                @else
                                    <input type="checkbox" name="roles[]" value="{{$role->id}}" />
                                @endif
                                @endforeach
                            </div>
                            {{ csrf_field() }}
                            <div class="col-md-1">
                                <button href="#" class="btn btn-success" type="submit"><i class="fa fa-floppy-o" aria-hidden="true"></i></button>
                            </div>
                        </div>
                        </form>
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
