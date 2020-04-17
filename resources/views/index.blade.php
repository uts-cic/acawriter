@extends('layouts.app')

@section('content')
<main id="app" class="container" data-ga-category="Home">
    @if ($hasDocuments)
        <button class="btn btn-outline-primary pull-right mt-5" type="button" data-toggle="collapse" data-target="#add" aria-expanded="false" aria-controls="add" data-ga-label="Add document"><i class="fa fa-plus-circle"></i> Add document</button>
        <h1 class="font-weight-normal mt-5 mb-5">My documents</h1>
    @else
    <div class="jumbotron mb-5">
        <h1>Welcome to AcaWriter!</h1>
        <p class="lead">AcaWriter is a website that gives you automated feedback on your draft writing.</p>
        <p class="lead mb-0">
            Make sure you’ve checked out the <a href="https://www.uts.edu.au/acawriter" target="_blank">information website</a> so you understand what it can (and can’t) do.
            <br>
            Feel free to explore <a href="{{ url('example') }}">examples of writing</a> that demonstrate how AcaWriter gives feedback.
            <br>
            To create a new document, choose one of the two options below.
        </p>
    </div>
    @endif

    @include('admin.flash')

    <div id="add" class="{{ $hasDocuments ? 'collapsible collapse' : '' }}">

        <div class="row">

            <!-- <div class="col-xl-4">
                <iframe width="560" height="315" src="https://www.youtube.com/embed/BeAIt_oVecQ" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen="allowfullscreen" class="mb-5" style="width: 100%;"></iframe>
            </div> -->

            <div class="col-xl-12">
                <ul class="nav nav-tabs awa-tabs">
                    <li class="nav-item">
                        <a href="#assignment" id="tab_assignment" data-toggle="tab" data-ga-label="Enter my assignment code" class="nav-link active show">Create using assignment code</a>
                    </li>
                    <li class="nav-item">
                        <a href="#new" id="tab_new" data-toggle="tab" data-ga-label="Create a new document" class="nav-link">Create a new document</a>
                    </li>
                </ul>

                <div class="tab-content mb-5" style="min-height: 272px;">
                    <div id="assignment" role="tabpanel" class="tab-pane fade active show">
                        <h5 style="margin-bottom: 20px;">Create a new document using assignment code</h5>
                        <div class="row">
                            <div class="col-lg-4">
                                <form-subscribe class="mb-4" csrf="{{ csrf_token() }}"></form-subscribe>
                            </div>
                            <div class="col-lg-8">
                                <p class="font-italic mb-0">What is my assignment code?..</p>
                                <p>An assignment code is usually provided by your lecturer/tutor, and takes you to the right version of AcaWriter for your assignment.</p>
                                <p>If you don't have an assignment code, then use the tab <a href="javascript:document.getElementById('tab_new').click()" data-ga-label="Create a new document [link]">Create a new document</a> where you’ll tell AcaWriter which kind of writing you’re working on.</p>
                                <iframe width="560" height="400" src="https://www.youtube.com/embed/BeAIt_oVecQ" frameborder="0" allow="accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture" allowfullscreen="allowfullscreen" style="width: 100%;"></iframe>
                            </div>
                        </div>
                    </div>

                    <div id="new" role="tabpanel" class="tab-pane fade">
                        <h5 class="mb-4">Create a new document</h5>
                        <div class="row">
                            <div class="col-lg-4">
                                <form-new class="mb-4" csrf="{{ csrf_token() }}"></form-new>
                            </div>
                            <div class="col-lg-8">
                                <p class="font-italic">Choose the right document type for your writing…</p>
                                <p>Feedback in <a href="https://www.uts.edu.au/research-and-teaching/teaching-and-research-integration/acawriter/students/what-kind-writing-0" target="_blank" data-ga-label="Analytical Essay/Report">Analytical Essay/Report</a> is tuned for essays and reports where you’re demonstrating your critical, analytical thinking, such as your ability to analyse or construct arguments. This includes literature reviews, scientific/technical reports and persuasive essays.</p>
                                <p>Feedback in <a href="https://www.uts.edu.au/research-and-teaching/teaching-and-research-integration/acawriter/students/what-kind-writing-1" target="_blank" data-ga-label="Reflective Journal/Report">Reflective Journal/Report</a> is tuned to writing in the first person about a learning experience (e.g. an internship; working as a team), the thoughts, feelings and emotions they provoked, and how you’re developing as a learner/professional.</p>
                                <p>Feedback in <a href="https://www.uts.edu.au/research-and-teaching/teaching-and-research-integration/acawriter/students/what-kind-writing-0" target="_blank" data-ga-label="Research Abstract/Intro">Research Abstract</a> is tuned for the Abstract to your research report/article. You can use this in addition to the above to clarify right from the start if you have explained the topic, stated the aim of the research, identified the issue you’re trying to solve and presented the findings.</p>
                                <p>Feedback in <a href="https://www.uts.edu.au/research-and-teaching/teaching-and-research-integration/acawriter/students/what-kind-writing-0" target="_blank" data-ga-label="Research Abstract/Intro">Research Introduction</a> is tuned for the introduction to your research report/article. You can use this in addition to the above to clarify right from the start how important the topic is, what problem you’ve identified, and what your contribution is.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @if ($hasDocuments)
    <documents id="documents" class="mb-5"></documents>
    @endif

</main>
@endsection
