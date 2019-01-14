<?php

use Illuminate\Database\Seeder;
use App\Feature;

class FeaturesAnalyticalCars extends Seeder
{
    /**
     * Run the database seeds. this is to add Cars profile (most updated version)
     *
     * @return void
     */
    public function run()
    {
        //
        $feature_a = new Feature();
        $feature_a->id = 5;
        $feature_a->name = "CARS";
        $feature_a->grammar="Analytical";
        $feature_a->rules= json_encode("{
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
        {\"emph\" : \"<span class=\\\"badge badge-pill badge-analytic\\\">E<\/span>Emphasis of a significant or an important idea \"},
        {\"tempstat\" : \"<span class=\\\"badge badge-pill badge-analytic\\\">B<\/span>Background information and reviewing previous work\"}
      ],
      \"css\": [\"E\",\"B\"],
      \"custom\" : \"Move 1: Establishing a research territory\",
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
        {\"contrast\" : \"<span class=\\\"badge badge-pill badge-analytic\\\">C<\/span>Contrasting idea, tension, disagreement or critical insight \"},
        {\"nostat\" : \"<span class=\\\"badge badge-pill badge-analytic\\\">Q<\/span>Question or gap in previous knowledge\"}
      ],
      \"css\": [\"C\", \"Q\"],
      \"custom\" : \"Move 2: Establishing a Niche\",
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
        {\"novstat\" : \"<span class=\\\"badge badge-pill badge-analytic\\\">N<\/span>Novelty and value of your research \"},
        {\"contribution\" : \"<span class=\\\"badge badge-pill badge-analytic-green\\\">S<\/span>Summary of the author’s goal or nature of the research, or structure of the paper\"}
      ],
      \"css\": [\"N\", \"S\"],
      \"custom\" : \"Move 3: Occupying the Niche\",
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
        {\"problem21\" : \"<div class=\\\"alert alert-info\\\" role=\\\"alert\\\"><i class=\\\"fa fa-exclamation-circle\\\"></i> You have indicated the research gap or written about your research problem [Move 2 Establishing a niche (C or Q sentences)] before explaining how your research topic is relevant and important [Move 1 (E or B sentences)]. It’s better to give some background information on your research topic before jumping straight into your gap and research problem. Go back and check if Move 1 Establishing the research territory (E or B sentences) is before Move 2 Establishing a niche (C or Q sentences). <\/div>\"},
        {\"problem31\" : \"<div class=\\\"alert alert-info\\\" role=\\\"alert\\\"><i class=\\\"fa fa-exclamation-circle\\\"></i> It seems you have stated how your research fills the gap or solves the research problem [Move 3 – Occupying the niche (S or N sentences)] before you have given background information on your research [Move 1 - Establishing the research territory (E or B sentences)]. It is more effective to state how your research fills the gap or solves the research problem at the end of your introduction, as this is an effective transition into the next section of your paper. <\/div>\"},
        {\"problem32\" : \"<div class=\\\"alert alert-info\\\" role=\\\"alert\\\"><i class=\\\"fa fa-exclamation-circle\\\"></i> It seems you have explained how your research fills the gap or solves the research problem [Move 3 – Occupying the niche (S or N sentences)] before you have indicated the gap or explained your research problem [Move 2 Establishing a niche (C or Q sentences)]. It is more effective to indicate the gap and explain the research problem before you state your solution and aim of your study.  Go back and revise your text so that Move 3 – Occupying the niche (S or N sentences) is after Move 2 Establishing a niche (C or Q sentences).<\/div>\"},
        {\"missing1\" : \"<div class=\\\"alert alert-info\\\" role=\\\"alert\\\"><i class=\\\"fa fa-exclamation-circle\\\"></i> It looks like you are missing Move 1 – Establishing a research territory (E or B sentences). Here you should show how your research topic is relevant and important by introducing & reviewing previous research on your topic. For example, recent research indicates that the effects of climate change have…. (for more examples head to the resources tab)<\/div>\"},
        {\"missing2\" : \"<div class=\\\"alert alert-info\\\" role=\\\"alert\\\"><i class=\\\"fa fa-exclamation-circle\\\"></i> It looks like you are missing Move 2 – Establishing a niche (C or Q sentences). Here you should indicate the gap and state the research problem, by explaining how previous research is incomplete or that there are aspects of the research topic that still needs investigating. This can be done by using sentences like these: However, these studies have failed to recognise that…., Limited research exists on……, Despite earlier studies the effects of x remains unclear. (for more examples head to the resources tab) <\/div>\"},
        {\"missing3\" : \"<div class=\\\"alert alert-info\\\" role=\\\"alert\\\"><i class=\\\"fa fa-exclamation-circle\\\"></i> It looks like you are missing Move 3 – Occupying the niche (S or N sentences). Here you should state how your research fills the gap or solves the research problem mentioned in Move 2. You can do this by stating the aim and purpose of your research. For example, this goal of this study, this research shows that.., the purpose of this investigation….(for more examples head to the resources tab)<\/div>\"}
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
        {\"txt\" : \"<div class=\\\"alert alert-dark\\\" role=\\\"alert\\\">An effective way to introduce your research is by using the Creating a Research Space (C.A.R.S) framework developed by linguist John Swales (1990). Swales (1990) analysed journal articles from a variety of disciplines and found that researchers follow a particular organisational pattern of moves when writing the introduction. The <strong>CARS<\/strong> moves are as follows:<br \/><br \/><i class=\\\"fa fa-angle-double-right\\\"><\/i><strong>Establish a research territory<\/strong> to show how the research area is important and worth investigating, by introducing and reviewing previous research <br \/><i class=\\\"fa fa-angle-double-right\\\"><\/i><strong>Establish a niche<\/strong> by indicating a gap in previous research or raising questions about it<br \/><i class=\\\"fa fa-angle-double-right\\\"><\/i><strong>Occupy the niche <\/strong> by stating how one’s own research seeks to close\/fill the gap. <br \/><br \/>Following CARS will help set the scene of your research and will make it easier for others to understand your research.<br \/>For more information on how to apply CARS in your research writing please <a target=\\\"_blank\\\" href=\\\"http:\/\/heta.io\/resources\/wawa-improving-research-abstracts-intros\/?target=resources\\\">click<\/a> here.<\/div>\"}
       ],
      \"css\": [\"msg1\", \"msg2\"],
      \"custom\" : \"more feedback\",
      \"tab\" :3,
      \"tabName\": \"Resources\",
      \"tabEval\": \"static\"
    }
  ]
}");

        $feature_a->save();

    }
}
