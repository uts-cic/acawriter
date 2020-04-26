<?php

/**
 * Project: AcaWriter
 * Copyright (c) 2018 original University of Technology Sydney. Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * Contributor(s):
 * Developer UTS Connected Intelligence Centre
 *
 */

?>
@extends('layouts.app')

@section('content')
<main id="app" class="container">
    <div>
        @include('admin.flash')
        <hr>
        <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">Filter Documents</div>
                            <div class="card-body">
                                <form action="/admin/documents" method="GET">
                                    <div class="row">
                                        <div class="form-group col-sm-12 col-md-3">
                                            <input type="text"  class="form-control" name="email" placeholder="enter email" />
                                        </div>
                                        <div class="form-group col-sm-12 col-md-3">
                                            <label>From Date:</label>
                                            <input type="date"  class="form-control" value="2000-01-01" name="from_date"/>
                                            <input type="time"  class="form-control" value ="00:00:00" name="from_date_time"/>
                                        </div>
                                        <div class="form-group col-sm-12 col-md-3">
                                            <label>To Date:</label>
                                            <input type="date"  class="form-control" value="2050-01-01" name="to_date"/>
                                            <input type="time"  class="form-control" value ="00:00:00" name="to_date_time"/>
                                        </div><br/>
                                        <div class="form-group col-sm-12 col-md-9">
                                            <div class="btn-group" role="group" aria-label="Basic example">
                                                <button href="#" class="btn btn-primary" type="submit" name="action" value="show"><i class="fa fa-search" aria-hidden="true"></i> Documents</button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">Results</div>
                            <table class="table">
                                <thead class="thead-dark">
                                    <tr>
                                        <th scope="col">Document Name</th>
                                        <th scope="col">Document ID</th>
                                        <th scope="col">Document Type</th>
                                        <th scope="col">Updated at</th>
                                        <th scope="col">Student</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($documents))
                                    @foreach ($documents as $document)
                                    <tr>
                                        <td><a href="/admin/document/{{$document->id}}"><i class="fa fa-cogs"></i>{{ substr($document->name, 0, 10) }}</td>
                                        <td>{{ $document->id }}</td>
                                        <td>{{ $document->type }}</td>
                                        <td>{{ $document->updated_at}}</td>
                                        <td>{{ $document->user }}</td>
                                    </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>
                            <form role="form" method="GET" action="{{ url('/admin/diffreport/') }}">
                            <table class="table">
                                <thead class="thead-dark">
                                <tr>
                                    <th scope="col">User</th>
                                    <th scope="col">Document Name</th>
                                    <th scope="col">Document Id</th>
                                    <th score="col">Version (From)</th>
                                    <th score="col">Version (To)</th>
                                    <!-- <th scope="col">Text</th> -->
                                </tr>
                                </thead>
                                <tbody>
                                @if(isset($data->documents->drafts))
                                @foreach($data->documents->drafts as $document)
                                    <tr>
                                        <th scope="row">{{$document->user->users->name}}</th>
                                        <td>{{$document->document->name}}</td> 
                                        <td>{{$document->document_id}}</td> 
                                        <td>{{$document->version}} ({{$document->updated_at}}) {{Form::radio('id', $document->id)}}</td>
                                        <td>{{$document->version}} ({{$document->updated_at}}) {{Form::radio('id_to', $document->id)}}</td>
                                        <!-- <td><button type="submit" name="id" value="{{$document->id}}" class="btn-link">Link</button></td> -->
                                    </tr>
                                @endforeach
                                <td></td>
                                <td></td>
                                <td></td>
                                <td></td>
                                <td align='center'><button type="submit">Submit</button></td>
                                @endif
                                </tbody>
                            </table>
                        </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>
@endsection
