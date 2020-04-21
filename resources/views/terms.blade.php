@extends('layouts.app')

@section('content')
<main id="app" class="container">
    <div class="container-text">
        <h1>Terms of Use / Privacy Statement</h1>

        <h2 class="mt-5 mb-3">Purpose</h2>
        <p>AcaWriter is provided to support students and staff of UTS. Its purpose is to provide feedback on academic writing. It works by looking for specific hallmarks of good academic writing that signal that there is a significant connection being made, or an important development or contrast in ideas. This helps to demonstrate your critical thinking, skill in argument, or development as a reflective professional.</p>
        <p>To participate, you will need to register as a user of AcaWriter. Information collected will include name, email, course/subject identifies, documents uploaded for review and system activity logs.  Your uploaded documents and the reports from AcaWriter enable you to assess your academic writing progress overtime. Information collected through AcaWriter will also be used by UTS for quality improvement and planning purposes associated with the platform, and for associated research by the Connected Intelligence Centre. Aggregate data may be made available to other relevant UTS areas. Data collected from AcaWriter may also be used for research and statistical analysis, in either a de-identified form or in line with research ethics approval.</p>
        <p>Information will only be disclosed in an anonymised format, or with your express consent, or if required or permitted by law.</p>

        <h2 class="mt-5 mb-3">Data Management</h2>
        <p>Data held within the AcaWriter platform is stored in Australia with an external company under contract with UTS. Web Services, in accordance with UTS data management and privacy.</p>
        <p>Data will be retained to support your academic writing development and for research purposes. Ongoing retention beyond this will be managed in accordance with the general retention and disposal authorities issued under the NSW State Records Act. If you leave UTS, you may continue to use of the AcaWriter platform.</p>

        <h2 class="mt-5 mb-3">Managing your information</h2>
        <p>You can access the information relating to your academic writing. You can download your data in a pdf format at any time. </p>

        <h2 class="mt-5 mb-3">Unsubscribe</h2>
        <p>If you wish to withdraw from using AcaWriter, or if you wish to have your profile and data removed, please contact us using using the <a href="{{ url('help') }}">contact form</a>.</p>

        <h2 class="mt-5 mb-3">About privacy at UTS</h2>
        <p>This notice may be updated at any time and will be available when you log in. For further information about privacy and the University’s privacy contacts, see <a target="_blank" href="https://www.uts.edu.au/about/uts-governance/privacy-uts">privacy at UTS</a>.</p>
    </div>
</main>
@endsection
