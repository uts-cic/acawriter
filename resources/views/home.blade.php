@extends('layouts.app')

@section('content')
<div class="container" id="app">
    <div class="row">
        <div class="col-md-12">
            <h2>My Dashboard </h2>
            <small><em>AcaWriter</em> provides feedback on your analytical or reflective writing.</small>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
        <div class="card dashboard">
            <div class="card-header bg-dark text-white">
                <ul class="nav nav-tabs card-header-tabs">
                    <li class="nav-item">
                        <a class="nav-link active" href="#new" data-toggle="tab">Create a new document</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#assignment" data-toggle="tab">Enter my assignment code</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#document" data-toggle="tab">View my documents</a>
                    </li>
                </ul>
            </div>

            <div class="card-block">
                <div class="tab-content p-5">
                    <div class="tab-pane" id="document" role="tabpanel">
                        <h4 class="card-title">My Documents</h4>
                        <documents></documents>
                    </div>
                    <div class="tab-pane" id="assignment" role="tabpanel">
                        <h4>Enter your Assignment Code:</h4>
                        <p class="small">If your lecturer/tutor has given you an Assignment Code for using AcaWriter, please paste it here and a new document will be created.
                            The document’s name will default to the same name as the assignment, but you can change this if you want to, now or later. Ideally select a unique name for the document for easy self reference.</p>
                        <autocomplete></autocomplete>
                    </div>
                    <div class="tab-pane active" id="new" role="tabpanel">
                        <h4>Add a Document</h4>
                        <form class="form" method="POST" action="/document">
                            {{ csrf_field() }}
                        <div class="row">
                            <div class="col-md-6">
                                <input type="text" class="form-control" id="doc_title" name="docu_name" placeholder="document title" />
                            </div>
                            <div class="col-md-6">
                                <input type="radio" id="doc_grammar_ana" name="doc_grammar" checked="checked" value="1" /> Analytical Writing
                                <input type="radio" id="doc_grammar_ref" name="doc_grammar" value="2" /> Reflective Writing
                            </div>
                        </div>
                        <br />
                        <div class="row">
                            <div class="col-md-6">
                                <button type="submit" class="btn btn-dark">Add</button>
                            </div>
                            <div class="col-md-6">

                            </div>
                        </div>
                        </form>
                    </div>

                </div>

            </div>
        </div>

        </div>





        <!--    <div class="col-md-4">
                <div class="card text-center">
                    <div class="card-block">
                        <h3 class="card-title">Already have your document</h3>
                        <p class="card-text">click on the document and start analysis.</p>
                        <a href="#" class="btn btn-primary">Click here</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <i class="fa fa-3x fa-angle-double-right pull-left" aria-hidden="true"></i><p>Have an assignment code - add to my documents by entering the code & then start analysis</p>
            </div>
            <div class="col-md-4">
                <i class="fa fa-3x fa-question-circle pull-left" aria-hidden="true"></i><p>Alternatively create a new document and start analysis</p>
            </div> -->
    </div>


    <!-- <div class="row">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header"> <h5>Enter Assignment code</h5></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <autocomplete></autocomplete>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-header"> <h5>Add a Document</h5></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <input type="text" class="form-control" id="doc_title" placeholder="document title" />
                        </div>
                        <div class="col-md-6">
                            <input type="radio" id="doc_grammar_ana" name="doc_grammar" /> Analytical
                            <input type="radio" id="doc_grammar_ref" name="doc_grammar" /> Reflective
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <button type="submit" class="btn btn-dark">Add</button>
                        </div>
                        <div class="col-md-6">

                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>

    <div class="row">
        <div class="col-md-12">
            <div class="card ">
                <div class="card-header bg-dark text-white">My Documents</div>
                <div class="card-body">

                </div>
            </div>
        </div>
    </div>

    <br />
    @if(in_array('admin', $data->roles))
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-dark text-white">System Status</div>
                <div class="card-body">
                    <ul class="list-group">
                        @if (session('status'))
                        <li class="list-group-item list-group-item-success">
                            {{ session('status') }}
                        </li>
                        @endif
                        <li class="list-group-item list-group-item-info" role="alert">You are logged in!</li>
                        <internet-connection :last-heart-beat-received-at="lastHeartBeatReceivedAt"></internet-connection>
                        <tap-status :tap-health="tapHealth"></tap-status>
                        <log-status :slogs="slogs"></log-status>
                    </ul>
                </div>
            </div>
        </div>



        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-dark text-white">System Admin</div>
                <div class="card-body">
                    <a href="/admin/users" class="list-group-item list-group-item-action"><i class="fa fa-users"></i>  Manage Users</a>
                    <a href="#" class="list-group-item list-group-item-action"><i class="fa fa-cog"></i>  Manage Features</a>
                    <a href="/assignment" class="list-group-item list-group-item-action"><i class="fa fa-clone"></i>  Manage Assignments</a>
                </div>
            </div>
        </div>



        @elseif(in_array('staff', $data->roles))
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-dark text-white">System Status</div>
                    <div class="card-body">
                        <ul class="list-group">
                            @if (session('status'))
                            <li class="list-group-item list-group-item-success">
                                {{ session('status') }}
                            </li>
                            @endif
                            <li class="list-group-item list-group-item-info" role="alert">You are logged in!</li>
                            <internet-connection :last-heart-beat-received-at="lastHeartBeatReceivedAt"></internet-connection>
                            <tap-status :tap-health="tapHealth"></tap-status>
                        </ul>
                    </div>
                </div>
            </div>



            <div class="col-md-4">
                <div class="card">
                    <div class="card-header bg-dark text-white">Admin</div>
                    <div class="card-body">
                        <a href="/assignment" class="list-group-item list-group-item-action"><i class="fa fa-clone"></i>  Manage Assignments</a>
                    </div>
                </div>
            </div>
            @endif




    </div>

 -->
</div>
@endsection