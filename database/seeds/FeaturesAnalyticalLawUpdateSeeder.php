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
      \"custom\" : \"Rhetorical Moves:\",
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
        {\"nostat\": \"<div class=\\\"alert alert-info\\\"><i class=\\\"fa fa-exclamation-circle\\\"></i> It looks like you are missing Question move, which highlights the critical insights in your essay. Try including linguistic cues to make this move clearer in your writing. Examples: However, the issue seems to be..., the study fails to consider, little research has been done..., ...raises various questions... <\/div>\"},
        {\"contrast\": \"<div class=\\\"alert alert-info\\\"><i class=\\\"fa fa-exclamation-circle\\\"></i> It looks like you are missing Contrast move, which highlights the critical insights in your essay. Try including linguistic cues to make this move clearer in your writing. Examples: However, the issue seems to be..., the study fails to consider, little research has been done..., ...raises various contrast... <\/div>\"},
        {\"emph\": \"<div class=\\\"alert alert-info\\\"><i class=\\\"fa fa-exclamation-circle\\\"></i> If there is a key idea you did like to emphasises in your essay try including linguistic cues to make this move clearer in your writing. Examples: It is important to note that ...., It makes a proper understanding important...\"}
      ],
      \"css\": [\"mtags\"],
      \"custom\" : \"Feedback\",
      \"tab\" :2,
      \"tabName\": \"Feedback\",
      \"tabEval\": \"dynamic\"
    }
  ]
}");

        $feature->save();
    }
}
