<?php

use Illuminate\Database\Seeder;
use App\Feature;

class FeaturesAnalyticalLawUpdateSeeder extends Seeder
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
        $feature = Feature::find(6);
        $feature->rules= json_encode("{
  \"rules\": [
    {
       \"name\": \"moves\",
      \"check\": {
        \"tags\": [
          \"attitude\",
          \"emph\",
          \"contribution\",
          \"novstat\",
          \"contrast\",
          \"tempstat\",
          \"surprise\",
          \"nostat\",
          \"grow\"
        ]
      },
      \"message\": [
        {\"contribution\" : \"<span class=\\\"badge badge-pill badge-analytic-green\\\">S<\/span> Summarises or signals the authors goals\"},
        {\"attitude\" : \"<span class=\\\"badge badge-pill badge-analytic\\\">P<\/span> Perspective or stance\"},
        {\"emph\" : \"<span class=\\\"badge badge-pill badge-analytic\\\">E<\/span> Emphasis to highlight key ideas\"},
        {\"novstat\" : \"<span class=\\\"badge badge-pill badge-analytic\\\">N<\/span> Novel improvements in ideas\"},
        {\"contrast\" : \"<span class=\\\"badge badge-pill badge-analytic\\\">C</span> Contrasting idea, tension or critical insight\"},
        {\"tempstat\": \"<span class=\\\"badge badge-pill badge-analytic\\\">B</span> Background information and previous work\"},
        {\"surprise\": \"<span class=\\\"badge badge-pill badge-analytic\\\">S</span> Surprising or unexpected finding\"},
        {\"nostat\": \"<span class=\\\"badge badge-pill badge-analytic\\\">Q</span> Question or gap in previous knowledge\"},
        {\"grow\" : \"<span class=\\\"badge badge-pill badge-analytic\\\">T<\/span> Trend or tendency related to ideas\"}
      ],
      \"css\": [\"P\",\"E\", \"T\", \"S\", \"N\", \"C\", \"B\", \"S\"],
      \"custom\" : \"<span class=\\\"small\\\">The analytical report highlights salient rhetorical moves AcaWriter identified in your essay for reflection. For more specific feedback, go to the Feedback tab.<\/span><h5>Rhetorical Moves<\/h5>\",
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
        {\"txt\" : \"<div class=\\\"alert alert-success\\\"><small>The rhetorical moves highlighted by AcaWriter are used in good academic writing but use them with caution according to the context. Remember, AcaWriter does not really understand your writing, the way people do. You may have written beautifully crafted nonsense - that's for you to decide! Moreover, writing is complex, and AcaWriter will get it wrong sometimes. If you think it got it wrong, that's fine - now you're thinking about more than spelling, grammar, and plagiarism.<\/small> <\/div>\"}
      ],
      \"css\": [\"msg1\"],
      \"custom\" : \"more feedback\",
      \"tab\" :2,
      \"tabName\": \"Feedback\",
      \"tabEval\": \"static\"
    },
    {
      \"name\": \"customised\",
      \"method\": \"missingTags\",
      \"check\": {
        \"tags\": [
          \"contribution\",
          \"tempstat\",
          \"nostat\",
          \"contrast\",
          \"emph\"
        ],
        \"conditions\" : [
        ]
      },
      \"message\": [
        {\"contribution\": \"<div class=\\\"alert alert-info\\\"><i class=\\\"fa fa-exclamation-circle\\\"></i> It looks like you are missing a Summary move that highlights the purpose (thesis) statement of your essay and your essay plan. Try including linguistic cues to make this move clearer in your writing. Examples: This essay talks about.., In this essay, I analyse…, This essay consists of three parts… The first part talks about…, In conclusion,... <\/div>\"},
        {\"tempstat\": \"<div class=\\\"alert alert-info\\\"><i class=\\\"fa fa-exclamation-circle\\\"></i> It looks like you are missing a Background move in your text, which highlights background information and previous literature on the topic. Try including linguistic cues to make this move clearer in your writing. Examples: The past decade has seen ...., Recent studies indicate ... ,It is generally accepted that..., the concept has previously been thought to be... <\/div>\"},
        {\"nostat\": \"<div class=\\\"alert alert-info\\\"><i class=\\\"fa fa-exclamation-circle\\\"></i> It looks like you are missing Contrast\/Question move, which highlights the critical insights in your essay. Try including linguistic cues to make this move clearer in your writing. Examples: However, the issue seems to be..., the study fails to consider, little research has been done..., ...raises various questions... <\/div>\"},
        {\"emph\": \"<div class=\\\"alert alert-info\\\"><i class=\\\"fa fa-exclamation-circle\\\"></i> If there is a key idea you did like to emphasises in your essay try including linguistic cues to make this move clearer in your writing. Examples: It is important to note that ...., It makes a proper understanding important...\"}
      ],
      \"css\": [\"mtags\"],
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
         {\"txt\":\"A list of sample sentences with rhetorical moves, mapped to your essay assessment rubric is provided below:\"},
         {\"txt\" : \"<table class=\\\"table table-bordered\\\"><thead><tr class=\\\"table-secondary\\\"><th scope=\\\"col\\\">Sample Sentence<\/th><th scope=\\\"col\\\">Essay Assessment Rubric Element<\/th><th scope=\\\"col\\\">AcaWriter Move<\/th><\/tr><\/thead><tbody><tr><td scope=\\\"row\\\">The concept of good faith <strong>has previously been thought<\/strong> to be a work in progress in Australia.<\/td><td>Engagement with the law and scholarly literature<\/td><td>Background<\/td><\/tr><tr><td scope=\\\"row\\\"><strong>This article will trace<\/strong> the origins of good faith and its development in the common law. <strong>This essay contains three parts. The first part will talk about<\/strong> the origins of good faith.<\/td><td>Statement of thesis, Essay plan<\/td><td>Summary<\/td><\/tr><tr><td scope=\\\"row\\\"><strong>However<\/strong>, where the obligations are found in statute and they conflict with contractual obligations, <strong>it is important to note that<\/strong> the former must prevail.<\/td><td>Identification of relevant issues, Critical analysis and original insight<\/td><td>Contrast,Emphasis<\/td><\/tr><\/tbody><\/table>\"}
       ],
      \"css\": [\"msg1\", \"msg2\"],
      \"custom\" : \"more feedback\",
      \"tab\" :3,
      \"tabName\": \"Examples\",
      \"tabEval\": \"static\"
    }
  ]
}");

        $feature->save();
    }
}
