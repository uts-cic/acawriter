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

use Illuminate\Database\Seeder;
use App\Feature;

class FeaturesAnalyticalAccountingUpdate extends Seeder
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
        $feature = Feature::find(7);
        $feature->rules= json_encode("{
  \"rules\": [
    {
       \"name\": \"amoves\",
      \"check\": {
        \"tags\": [
          \"attitude\",
          \"emph\",
          \"contribution\",
          \"novstat\",
          \"contrast\",
          \"tempstat\",
          \"nostat\"
        ]
      },
      \"message\": [
        {\"contribution\" : \"<span class=\\\"badge badge-pill badge-analytic-green\\\">S<\/span> Summarises or signals the authors goals\"},
        {\"attitude\" : \"<span class=\\\"badge badge-pill badge-analytic\\\">P<\/span> Perspective or stance\"},
        {\"novstat\" : \"<span class=\\\"badge badge-pill badge-analytic\\\">N<\/span> Novel improvements in ideas\"},
        {\"emph\" : \"<span class=\\\"badge badge-pill badge-analytic\\\">E<\/span>Emphasis of a significant or an important idea \"},
        {\"contrast\" : \"<span class=\\\"badge badge-pill badge-analytic\\\">C</span> Contrasting idea, tension or critical insight\"},
        {\"tempstat\": \"<span class=\\\"badge badge-pill badge-analytic\\\">B</span> Background information and previous work\"}
      ],
      \"css\": [\"P\", \"S\", \"N\", \"C\", \"B\"],
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
      \"method\": \"positiveFeed\",
      \"check\": {
        \"tags\": [
          \"contribution\",
          \"tempstat\",
          \"attitude\",
          \"contrast\"
        ],
        \"conditions\" : [
        ]
      },
      \"message\":[
        {\"contribution\":\"Summary\"},
        {\"tempstat\":\"Background\"},
        {\"attitude\":\"Perspective\"},
        {\"contrast\":\"Contrast\"}
      ],
      \"css\": [\"mtags\"],
      \"custom\" : \"Feedback\",
      \"tab\" :2,
      \"tabName\": \"Feedback\",
      \"tabEval\": \"dynamic\"
    },
    {
      \"name\": \"customised\",
      \"method\": \"missingSwapTags\",
      \"check\": {
        \"tags\": [
          \"contribution\",
          \"tempstat\",
          \"attitude\",
          \"contrast\"
        ],
        \"conditions\" : [
        ]
      },
      \"message\": [
        {\"contribution\": \"<div class=\\\"alert alert-info small\\\"><i class=\\\"fa fa-exclamation-triangle text-danger\\\"></i> It looks like you are missing a Summary move that defines the goal or summary of your report and its sections. Try including linguistic cues to make this move clearer in your writing like: This report defines… , the report first examines.. then.., this section explains... Note that you should use past tense in the executive summary section where you explain your results. <\/div>\"},
        {\"tempstat\": \"<div class=\\\"alert alert-info small\\\"><i class=\\\"fa fa-exclamation-triangle text-danger\\\"></i>  It looks like you are missing a Background move in your text, which highlights previous work on the topic. Some linguistic cues that exemplify background are: Previous market analysis demonstrate that…, …is widely recognised as …  , It is generally accepted that...Also, make sure that you provide relevant contextual information on the organisation.<\/div>\"},
        {\"attitude\": \"<div class=\\\"alert alert-info small\\\"><i class=\\\"fa fa-exclamation-triangle text-danger\\\"></i> It looks like you are missing Perspective and Emphasis moves, which highlight your attitude about an idea in text. Try including linguistic cues to make this move clearer in your writing. Examples: The key factor is that…, They highlight the focus on .., …is a critical aspect of….., Academic theory holds that… <\/div>\"},
        {\"contrast\": \"<div class=\\\"alert alert-info small\\\"><i class=\\\"fa fa fa-exclamation-triangle text-danger\\\"></i> It looks like you are missing a Contrast move which highlights disagreement, issues, or alternatives. Try including linguistic cues to make this move clearer in your writing like: Although it is the case… , One challenge is..., However, this problem.. <\/div>\"}
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
         {\"txt\":\"Here are certain rhetorical moves you can look for in your report and example sentences for your reference.<br \/><br \/><u>Organisational analysis<\/u><br \/> Where does your report provide contextual information about the organisation’s objectives, strategy, structure and activities?<br \/><u>Defining performance<\/u><br \/>Where does your report provide your perspective <span class=\\\"badge badge-pill badge-analytic\\\">P<\/span> about how to define performance or success for the organisation? Where does your report provide emphasis <span class=\\\"badge badge-pill badge-analytic\\\">E<\/span> to highlight the most important aspects of performance for the organisation?<br \/><u>Justification of your definition of performance<\/u><br \/>Where does your report provide convincing, persuasive justifications for your definition of performance by proposing novel <span class=\\\"badge badge-pill badge-analytic\\\">N<\/span> or critical insights, contrasting ideas or tension <span class=\\\"badge badge-pill badge-analytic\\\">C<\/span>? Where does your report justify your definition of performance with reference to prior work or background <span class=\\\"badge badge-pill badge-analytic\\\">B<\/span>?<br \/><u>Written communication<\/u><br \/>Where in your report do you use appropriate summary statements <span class=\\\"badge badge-pill badge-analytic-green\\\">S<\/span> to signal the content, sequence and goals of the report?<br \/>\"},
         {\"txt\" : \"<table class=\\\"table table-bordered\\\"><thead><tr class=\\\"table-secondary\\\"><th scope=\\\"col\\\">Acawriter Move<\/th><th scope=\\\"col\\\">Sample Sentences<\/th><\/tr><\/thead><tbody><tr><td scope=\\\"row\\\">Summary<\/td><td><span class=\\\"badge badge-pill badge-analytic-green\\\">S<\/span> This report defines performance for Nike Inc as a whole from the three main perspectives of economic, social and environmental performance. <br \/> <span class=\\\"badge badge-pill badge-analytic-green\\\">S<\/span> This section will explain how sustainability can result in the achievement of organisational objectives and contribute to company success. <\/td><\/tr><tr><td scope=\\\"row\\\">Perspective, Emphasis<\/td><td><span class=\\\"badge badge-pill badge-analytic\\\">P<\/span> Importantly, research has suggested a link between stronger brand perception and customer loyalty.<br \/><span class=\\\"badge badge-pill badge-analytic\\\">E<\/span> Therefore, to effectively measure performance for the Canadian Head Office of Lululemon it is essential to consider how the transformational self-improvement ethos of the company is achieved by analysing non-traditional metrics.<\/td><\/tr><tr><td scope=\\\"row\\\">Background<\/td><td><span class=\\\"badge badge-pill badge-analytic\\\">B<\/span> Previous market analysis shows that Nike is the world’s largest supplier of athletic shoes, equipment, and apparel. <br \/><span class=\\\"badge badge-pill badge-analytic\\\">B<\/span> Such an evaluation by a global organization has been observed previously.<\/td><\/tr><tr><td scope=\\\"row\\\">Contrasting ideas and Issues<\/td><td><span class=\\\"badge badge-pill badge-analytic\\\">C<\/span> These requirements maintain product quality as unethically produced garments could be of lower quality, damage its reputation and ultimately contradict the companys objective.<br \/><span class=\\\"badge badge-pill badge-analytic\\\">C<\/span> While the companys corporate mission is to maintain its market position as a leading brand for an active and mindful lifestyle, a holistic approach to defining performance is necessary given that Lululemons strength lies in its premium image<\/td><\/tr><tr><td scope=\\\"row\\\">Novelty<\/td><td><span class=\\\"badge badge-pill badge-analytic\\\">N<\/span> This closer connection with their customers allows Nike to create new ideas and convert them into products quicker while also being in touch with their demands. <br \/><span class=\\\"badge badge-pill badge-analytic\\\">N<\/span> Nike believes that through investing in employees and communities, they can inspire while also creating a new method of growth (Nike 2018c). <\/td><\/tr><\/tbody><\/table>\"}
       ],
      \"css\": [\"msg1\", \"msg2\"],
      \"custom\" : \"more feedback\",
      \"tab\" :3,
      \"tabName\": \"Tips\",
      \"tabEval\": \"static\"
    }
  ]
}");

        $feature->save();
    }
}