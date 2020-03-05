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
<div id="app">
    <div class="container">
        @include('admin.flash')
        <hr />
        <div class="row">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header">Search Documents</div>
                            <div class="card-body">
                                <form action="/admin/documents" method="post">
                                    <div class="row">
                                        <div class="form-group col-sm-12 col-md-3">
                                            <input type="text"  class="form-control" name="document_id" placeholder="enter document id" />
                                        </div>
                                        <div class="form-group col-sm-12 col-md-9">
                                            <div class="btn-group" role="group" aria-label="Basic example">
                                                <button href="#" class="btn btn-primary" type="submit" name="action" value="show"><i class="fa fa-search" aria-hidden="true"></i> Show Documents</button>&nbsp; or &nbsp;
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
                            <table class="table">
                                <thead class="thead-dark">
                                <tr>
                                    <th scope="col">Document Id</th>
                                    <th scope="col">Text</th>
                                    <th scope="col">Last Updated Date</th>

                                </tr>
                                </thead>
                                <tbody>
                                @if(isset($data->documents->list))
                                @foreach($data->documents->list as $document)
                                    <tr>
                                        <th scope="row">{{$document->code}}</th>
                                        <td>{{$document->email}}</td>
                                        <td>{{$document->name}}</td>
                                        <td><a href="/admin/download/csv/txt/{{$document->docid}}/{{$document->uid}}"><i class="fa fa-download"></i> Text ({{$document->txtcount}})</td>
                                        <td><a href="/admin/download/csv/feed/{{$document->docid}}/{{$document->uid}}"><i class="fa fa-download"></i> Feed ({{$document->dcount}})</td>
                                    </tr>
                                @endforeach
                                @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection


@section('footer')

@stop
