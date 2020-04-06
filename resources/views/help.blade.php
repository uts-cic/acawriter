@extends('layouts.app')

@section('content')
<main id="app" class="container">
    @include('admin.flash')

    <h1 class="font-weight-normal mt-5 mb-3">Help & Support</h1>
    <p class="lead text-muted mt-3 mb-5">AcaWriter is developed and maintained by the UTS Connected Intelligence Centre (CIC).<br>Feel free to drop us a line if you have any questions.</p>

    <div class="card-deck">
        <div class="card mb-5 bg-light shadow-sm">
            <div class="card-header">
                <h2 class="my-0">Send us feedback</h2>
            </div>
            <div class="card-body p-4">
                <p>How well did AcaWriter do? We want to hear from you!</p>

                <form method="POST" action="/help" autocomplete="off">
                    {{ csrf_field() }}

                    <div class="form-group">
                        <label for="name">Name</label>
                        <input id="name" type="text" class="form-control" name="name" value="{{ $contact->name }}" required autofocus>
                    </div>
                    <div class="form-group">
                        <label for="email">Email address</label>
                        <input id="email" type="email" class="form-control" name="email" value="{{ $contact->email }}" required autofocus>
                    </div>

                    <div class="form-group">
                        <label for="message">Comments</label>
                        <textarea id="comment" class="form-control" name="comment" required></textarea>
                    </div>

                    @cannot('bypass-captcha')
                    <div class="form-group">
                        <div class="captcha">
                            <span>{!! captcha_img() !!}</span>
                            <button type="button" class="btn btn-success"><i class="fa fa-refresh" id="refresh"></i></button>
                        </div>
                    </div>

                    <div class="form-group">
                        <input id="captcha" type="text" class="form-control" placeholder="Enter Captcha" name="captcha">
                    </div>
                    @endcannot

                    <button type="submit" class="btn btn-outline-secondary">Send feedback</button>
                </form>
            </div>
        </div>

        <div class="card mb-5 bg-light shadow-sm">
            <div class="card-header">
                <h2 class="my-0">Technical support</h2>
            </div>
            <div class="card-body p-4">
                <p>UTS staff and students may get in touch with our technical team by submitting a request in Service Connect.</p>
                <p>To help us solve your issue in a timely manner, please provide us with:</p>
                <ul>
                    <li>Detailed description of the issue</li>
                    <li>Your document name and/or assignment code</li>
                    <li>URL of the page where the issue is occuring</li>
                    <li>Screenshots (if applicable)</li>
                </ul>
                <a class="btn btn-outline-secondary mt-3" href="https://uts.service-now.com/serviceconnect?id=sc_cat_item&sys_id=1ca488bddbcf8010e56c1c0e0496199c&sysparm_category=5c2864a6db178050e56c1c0e04961954" title="Service Connect - AcaWriter Support">Get technical support</a>
            </div>
        </div>

        <div class="card mb-5 bg-light shadow-sm">
            <div class="card-header">
                <h2 class="my-0">Other UTS writing support</h2>
            </div>
            <div class="card-body p-4">
                <p>AcaWriter is part of the rich support for academic writing UTS offers you, so we encourage you visit these:</p>
                <p><strong>The Library has online resources</strong> to help with</p>
                <ul class="list-inline">
                    <li class="list-inline-item"><a href="https://www.lib.uts.edu.au/help/academic-language" title="UTS:Library - Academic Language">Academic Language</a></li>
                    <li class="list-inline-item"><a href="https://www.lib.uts.edu.au/help/english-language" title="UTS:Library - English Language">English Language</a></li>
                </ul>
                <p><strong>Visit HELPS</strong> to discuss your writing with someone friendly, who can give you feedback on an assignment:</p>
                <ul class="list-inline">
                    <li class="list-inline-item"><a href="https://www.uts.edu.au/current-students/support/helps/writing-support" title="HELPS - Writing Support">Writing Support</a></li>
                    <li class="list-inline-item"><a href="https://www.uts.edu.au/current-students/support/helps/writenow-writing" title="HELPS - WriteNow! Writing support sessions">Writing Sessions</a></li>
                    <li class="list-inline-item"><a href="https://www.uts.edu.au/current-students/support/helps/assignment-writing-assistance" title="HELPS - Assignment writing assistance">Assignment Writing</a></li>
                </ul>
            </div>
        </div>

    </div>
</main>
@endsection