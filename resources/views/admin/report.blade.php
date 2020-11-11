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
         <div class="row">
            <div class="col-12 col-lg-6 col-xl-3 text-center">
                <div class="bg-primary p-2 text-center text-white"><strong>-</strong><br><small>Total users</small>
                </div>
            </div>
            <div class="col-12 col-lg-6 col-xl-3">
                <div class="bg-primary p-2 text-center text-white">
                    <strong>-</strong><br><small>Monthly New Users</small>
                </div>
            </div>
            <div class="col-12 col-lg-6 col-xl-3">
                <div class="bg-primary p-2 text-center text-white">
                    <strong>-</strong><br><small>Monthly User Activity</small>
                </div>
            </div>
            <div class="col-12 col-lg-6 col-xl-3">
                <div class="bg-primary p-2 text-center text-white">
                    <strong>-</strong><br><small>Monthly Feedback</small>
                </div>
            </div>
        </div>
        <hr>
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">Search by Assignment Code</div>
                            <div class="card-body">
                                <form action="/admin/reports" method="post" autocomplete="off">
                                    <div class="row">
                                        <div class="form-group col-sm-12 col-md-3">
                                            <input type="text"  class="form-control" name="assignment_code" placeholder="enter assignment code">
                                        </div>
                                        <div class="form-group col-sm-12 col-md-9">
                                            <div class="btn-group" role="group" aria-label="Basic example">
                                                <button href="#" class="btn btn-primary" type="submit" name="action" value="show"><i class="fa fa-search" aria-hidden="true"></i> Show Records</button>&nbsp; or &nbsp;
                                                <button href="#" class="btn btn-primary" type="submit" name="action" value="download_feed"><i class="fa fa-cloud-download" aria-hidden="true"></i> Download Feedback</button>&nbsp; or &nbsp;
                                                <button href="#" class="btn btn-primary" type="submit" name="action" value="download_text"><i class="fa fa-cloud-download" aria-hidden="true"></i> Download Text</button>
                                            </div>
                                        </div>

                                        {{ csrf_field() }}
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">Search Documents by Username</div>
                            <div class="card-body">
                                <form action="/admin/documentsByUser" method="post">
                                    <div class="row">
                                        <div class="form-group col-sm-12 col-md-3">
                                            <input type="text"  class="form-control" name="username" placeholder="enter username" />
                                        </div>
                                        <div class="form-group col-sm-12 col-md-9">
                                            <div class="btn-group" role="group" aria-label="Basic example">
                                                <button href="#" class="btn btn-primary" type="submit" name="action" value="show"><i class="fa fa-search" aria-hidden="true"></i> Users</button>
                                            </div>
                                        </div>

                                        {{ csrf_field() }}
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">Search Documents</div>
                            <div class="card-body">
                                <form action="/admin/diffDocuments" method="post">
                                    <div class="row">
                                        <div class="form-group col-sm-12 col-md-3">
                                            <input type="text"  class="form-control" name="document_id" placeholder="enter document id" />
                                        </div>
                                        <div class="form-group col-sm-12 col-md-9">
                                            <div class="btn-group" role="group" aria-label="Basic example">
                                                <button href="#" class="btn btn-primary" type="submit" name="action" value="show"><i class="fa fa-search" aria-hidden="true"></i> Documents</button>
                                            </div>
                                        </div>

                                        {{ csrf_field() }}
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">Results</div>
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th scope="col">Code</th>
                                        <th scope="col">Student</th>
                                        <th scope="col">Document Name</th>
                                        <th scope="col">Text</th>
                                        <th scope="col">Feedback</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($documents))
                                    @foreach ($documents as $document)
                                    <tr>
                                        <th scope="row">{{ $document->code }}</th>
                                        <td>{{ $document->email }}</td>
                                        <td>{{ $document->name }}</td>
                                        <td>
                                            <a href="/admin/download/csv/txt/{{ $document->docid }}/{{ $document->uid }}" class="text-nowrap"><i class="fa fa-download"></i> Auto ({{ $document->txtcount }})</a>
                                            <br>
                                            <a href="/admin/download/csv/maj/{{ $document->docid }}/{{ $document->uid }}" class="text-nowrap"><i class="fa fa-download"></i> Major ({{ $document->dcount }})</a>
                                        </td>
                                        <td>
                                            <a href="/admin/download/csv/feed/{{ $document->docid }}/{{ $document->uid }}" class="text-nowrap"><i class="fa fa-download"></i> Feed ({{ $document->dcount }})</a>
                                        </td>
                                    </tr>
                                    @endforeach
                                    @endif
                                </tbody>
                            </table>
                            <table class="table">
                                <thead class="thead-dark">
                                    <tr>
                                        <th scope="col">Document Name</th>
                                        <th scope="col">Document ID</th>
                                        <th scope="col">Student</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if (isset($user_documents))
                                    @foreach ($user_documents as $u_doc)
                                    <tr>
                                        <th scope="row">{{ $u_doc->name }}</th>
                                        <td>{{ $u_doc->id }}</td>
                                        <td>{{ $u_doc->user->name }}</td>
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
