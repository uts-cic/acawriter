<?php

use Illuminate\Database\Seeder;
use App\Feature;

class FeaturesAnalyticalUpdateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        //
        $feature = Feature::find(5);
        $feature->rules= json_encode("{
  \"rules\": [
    {
      \"name\": \"moves1\",
      \"method\": \"moves\",
      \"check\": {
        \"tags\": [
          \"emph\",
          \"tempstat\"
        ]
      },
      \"message\": [
        {\"emph\" : \"<span class=\\\"badge badge-pill badge-analytic\\\">E<\/span> Studied extensively, received considerable attention\"},
        {\"tempstat\" : \"<span class=\\\"badge badge-pill badge-analytic-green\\\">B<\/span> Wide interest\"}
      ],
      \"css\": [\"E\",\"B\"],
      \"custom\" : \"move 1: Establishing a research territory\",
      \"tab\": 1,
      \"tabName\": \"Analytical Feedback\"
    },
    {
      \"name\": \"moves2\",
      \"method\": \"moves\",
      \"check\": {
        \"tags\": [
          \"contrast\",
          \"nostat\"
        ]
      },
      \"message\": [
        {\"contrast\" : \"<span class=\\\"badge badge-pill badge-analytic\\\">C<\/span> Disagreement, Tension, Options, Inconsistency\"},
        {\"nostat\" : \"<span class=\\\"badge badge-pill badge-analytic\\\">Q<\/span> Novelty improvements of ideas methods\"}
      ],
      \"css\": [\"C\", \"Q\"],
      \"custom\" : \"move 2: Establishing a Niche\",
      \"tab\": 1,
      \"tabName\": \"Analytical Feedback\"
    },
    {
      \"name\": \"moves3\",
      \"method\": \"moves\",
      \"check\": {
        \"tags\": [
          \"novstat\",
          \"contribution\"
        ]
      },
      \"message\": [
        {\"novstat\" : \"<span class=\\\"badge badge-pill badge-analytic\\\">N<\/span> Novelty improvements of ideas methods\"},
        {\"contribution\" : \"<span class=\\\"badge badge-pill badge-analytic-green\\\">S<\/span> Summarises/signals author’s goals\"}
      ],
      \"css\": [\"N\", \"S\"],
      \"custom\" : \"move 3: Occupying the Niche\",
      \"tab\": 1,
      \"tabName\": \"Analytical Feedback\"
    },
    {
      \"name\": \"alerts\",
      \"method\": \"staticFeed\",
      \"check\": {
        \"tags\": [
        ],
        \"conditions\" : [
        ]
      },
      \"message\": [
        {\"txt\" : \"<div class=\\\"alert alert-success\\\" role=\\\"alert\\\">Thank you for submitting your draft to AcaWriter.Quality writing comes from revision. Research shows that writing drafts and revising your text helps improve the quality of your writing.<\/div>\"},
        {\"txt\" : \"<div class=\\\"alert alert-danger\\\"><small>Remember AcaWriter is a machine – so it may not highlight all your moves correctly and could give you incorrect feedback. So, don’t be afraid to disagree with the feedback, if you believe you have included all three moves in the correct order.<\/small> <\/div>\"}
      ],
      \"css\": [\"msg1\", \"msg2\"],
      \"custom\" : \"more feedback\",
      \"tab\" :2,
      \"tabName\": \"Feedback\",
      \"tabEval\": \"static\"
    },
    {
      \"name\": \"customised\",
      \"method\": \"enforced\",
      \"check\": {
        \"tags\": [
          { \"1\" : [\"emph\", \"tempstat\"] },
          { \"2\" : [\"contrast\", \"nostat\"] },
          { \"3\" : [\"novstat\", \"contribution\"] }
        ],
        \"conditions\" : [
          {\"problem1\": \"2,1\"},
          {\"problem2\": \"3,1\"},
          {\"problem3\": \"3,2\"}
        ]
      },
      \"message\": [
        {\"problem1\" : \"You have indicated the research gap and(or) written about your research problem-Move 2 Establishing a nice (C or Q sentences) before explaining how your research topic is relevant and important which is Move 1 (E or B sentences). It’s better to give some background information on your research topic before jumping straight into your gap and research problem. Acawriter suggests moving Move 1 Establishing the research territory (E or B sentences) before Move 2 Establishing a nice (C or Q sentences). \"},
        {\"problem2\" : \"It seems you have stated how your research fills the gap and/or solves the research problem [Move 3 – Occupying the niche (S or N sentences)] before you have given background information on your research [Move 1 - Establishing the research territory (E or B sentences)]. It is more effective to state how your research fills the gap or solves the research problem at the end of your introduction, as this is an effective transition into the next section of your paper. \"},
        {\"problem3\" : \"It seems you have stated how your research fills the gap and/or solves the research problem [Move 3 – Occupying the niche (S or N sentences)] before you have indicated the gap and/or explained your research problem [Move 2 Establishing a nice (C or Q sentences)]. It is more effective to indicate the gap and explain the research problem before you state your solution and aim of your study.  Acawriter suggests putting Move 3 – Occupying the niche (S or N sentences) after Move 2 Establishing a nice (C or Q sentences).\"}
      ],
      \"css\": [\"N\", \"S\"],
      \"custom\" : \"Feedback\",
      \"tab\" :2,
      \"tabName\": \"Feedback\",
      \"tabEval\": \"dynamic\"      
    },
    {
      \"name\": \"faq\",
      \"method\": \"staticFeed\",
      \"check\": {
        \"tags\": [
        ],
        \"conditions\" : [
        ]
      },
      \"message\": [
        {\"txt\" : \"<div class=\\\"alert alert-dark\\\" role=\\\"alert\\\">Thank you for submitting your draft to AcaWriter.Quality writing comes from revision. <a href=\\\"http:\/\/utscic.edu.au\\\">Research<\/a> shows that writing drafts and revising your text helps improve the quality of your writing.<\/div>\"},
        {\"txt\" : \"<div class=\\\"alert alert-dark\\\"><small>Remember AcaWriter is a machine – so it may not highlight all your moves correctly and could give you incorrect feedback. So, don’t be afraid to disagree with the feedback, if you believe you have included all three moves in the correct order.<\/small> <\/div>\"}
      ],
      \"css\": [\"msg1\", \"msg2\"],
      \"custom\" : \"more feedback\",
      \"tab\" :3,
      \"tabName\": \"Resources\",
      \"tabEval\": \"static\"
    }
  ]
}");

        $feature->save();
    }
}
