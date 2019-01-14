<?php

use Illuminate\Database\Seeder;
use App\Feature;

class FeaturesReflectivePharmacyUpdateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $feature = Feature::find(8);
        $feature->rules= json_encode("{
            \"rules\": [
                {
                    \"name\": \"moves\",
                    \"check\": {
                        \"tags\": [
                            \"context\",
                            \"challenge\",
                            \"link2me\",
                            \"change\"
                        ]
                    },
                    \"message\": [
                        {\"context\" : \"Initial thoughts and feelings about a significant experience.\"},
                        {\"challenge\" : \"The challenge of new surprising or unfamiliar ideas, problems or learning experiences.\"},
                        {\"link2me\" : \"<span class=\\\"link2me\\\">Deeper reflection, personally applied.<\/span>\"},
                        {\"change\": \"How new knowledge can lead to a change\"}
                    ],
                    \"css\": [\"context\",\"challenge\",\"link2me\", \"change\"],
                    \"custom\" : \"\",
                    \"tab\" :1,
                    \"tabName\": \"Reflective Feedback\",
                    \"tabEval\": \"dynamic\"
                },
                {
                    \"name\": \"expression\",
                    \"check\": {
                        \"all\": [
                            \"epistemic\",
                            \"modal\",
                            \"affect\"
                        ],
                        \"affectVal\": {\"arousal\": 4.95, \"valence\":0, \"dominance\":0}
                    },
                    \"message\": [
                        {\"epistemic\" : \"<u>Expressions indicating belief, learning, or knowledge.</u>\"},
                        {\"modal\" : \"<span class=\\\"modall\\\">Expressions indicating self critique<\/span>\"},
                        {\"affect\" :\"<span class=\\\"affect\\\">Words associated with strong feelings<\/span>\"}
                    ],
                    \"css\": [\"epistemic\",\"modall\"]
                },
                {
                    \"name\": \"metrics\",
                    \"check\": {
                        \"sentenceWordCount\": \"35\"
                    },
                    \"message\": [
                        {\"metrics\" :\"Sentence too long, might disengage the reader. Try breaking it into smaller sentences\"}
                    ],
                    \"css\": [\"metrics\"]
                },
                {
                    \"name\": \"filterWords\",
                    \"check\": {
                        \"list\": [
                            {
                                \"words\": [\"preceptor\",\"supervisor\", \"hospital\", \"Community\", \"Pharmacy\", \"Placement\", \"site\", \"pharmacy\", \"pharmacist\",\"mentor\"], 
                                \"tags\" : [\"affect\"]
                            }
                        ]
                    },
                    \"message\": [],
                    \"css\": []
                },
                {
                    \"name\": \"customised\",
                    \"method\": \"paragraphFeedback\",
                    \"check\": {
                        \"tags\": [
                            \"context\",
                            \"change\"
                        ],
                        \"conditions\" : [
                            \"change_without_context\"
                        ]
                    },
                    \"message\": [
                        {\"context\": \"<span class=\\\"text-success\\\"> 1st Paragraph: It appears that you’ve acknowledged your first thoughts, feelings and\/or reactions to an incident, or learning task, within the first paragraph.<\/span>\"},
                        {\"context_m\": \"<span class=\\\"text-danger\\\"> 1st Paragraph: Perhaps consider introducing your first thoughts, feelings and\/or reactions to an incident, or learning task, within the first paragraph. AcaWriter couldn’t spot this. <\/span>\"},
                        {\"change\": \"<span class=\\\"text-success\\\"> 1st Paragraph: Well done, it appears that you’ve reflected on how you would change\/prepare for the future. Is there anything further to say about these new insights that have led to change. <\/span>\"},
                        {\"change_without_context\": \"<span class=\\\"text-danger\\\"> 1st Paragraph: While it appears that you’ve reported on how you would change\/prepare for the future, you dont seem to have described your thoughts, feelings and\/or reactions to an incident, or learning task. <\/span>\"}
                    ],
                    \"css\": [\"mtags\"],
                    \"custom\" : \"Feedback\",
                    \"tab\" :2,
                    \"tabName\": \"Feedback\",
                    \"tabEval\": \"dynamic\"
                },
                {
                    \"name\": \"customised\",
                    \"method\": \"expressionsFeedback\",
                    \"check\": {
                        \"tags\": [
                            \"epistemic\",
                            \"modal\",
                            \"affect\",
                            \"link2me\",
                            \"challenge\",
                            \"change\"
                        ],
                        \"conditions\" : [
                            \"challenge\",
                            \"change\"
                        ]
                    },
                    \"message\": [
                        {\"epistemic\": \"<span class=\\\"text-success\\\"> You have reflected on your beliefs\/learning\/knowledge. <\/span>\"},
                        {\"modal\": \"<span class=\\\"text-success\\\"> You seem to have incorporated a deeper reflection indicating self-critique. <\/span>\"},
                        {\"affect\": \"<span class=\\\"text-success\\\"> It appears that you have reflected on your feelings, thoughts or reactions. <\/span>\"},
                        {\"epistemic_m\": \"<span class=\\\"text-danger\\\"> You seem not to have reflected on your beliefs\/learning\/knowledge. If that’s the case, then please think about this (e.g. including cultural, religious or family values\/assumptions). <\/span>\"},
                        {\"modal_m\": \"<span class=\\\"text-danger\\\"> You seem not to have incorporated a deeper reflection indicating self-critique. Consider how this could improve reflection on your strengths and weaknesses. <\/span>\"},
                        {\"link2me\": \"<span class=\\\"text-success\\\"> It appears that you have reflected in a deeper way about how your experiences connect with your professional development. <\/span>\"},
                        {\"challenge\": \"<span class=\\\"text-success\\\"> It appears that you’ve reported on something you found challenging. <\/span>\"},
                        {\"change\": \"<span class=\\\"text-success\\\"> It appears that you’ve reflected on how you would change/prepare for the future. Is there anything further to say about these new insights that have led to change <\/span>\"},
                        {\"link2me_m\": \"<span class=\\\"text-danger\\\"> You seem not to have reflected in a deeper way about your experiences. Consider applying your insights to how you can develop professionally. <\/span>\"},
                        {\"challenge_m\": \"<span class=\\\"text-danger\\\"> It appears that you haven’t commented on anything you found challenging. If you did find something challenging, please expand on this. <\/span>\"},
                        {\"change_m\": \"<span class=\\\"text-danger\\\"> It appears that you haven’t commented on what you would do differently should the same event occur in the future. Perhaps think about changes in perspectives\/strategies\/tools\/ideas\/behaviour and\/or approach.<\/span>\"},
                        {\"double_challenge\": \"<span class=\\\"text-success\\\"> It appears that you may have expanded the detail on the challenge you faced. (#cnt)<\/span>\"},
                        {\"double_change\": \"<span class=\\\"text-success\\\"> It appears that you have expanded the detail on how you would change\/prepare for the future. (#cnt)<\/span>\"},
                        {\"order_tag_err\": \"<span class=\\\"text-danger\\\"> While it appears that you’ve reported on how you would change\/prepare for the future, you don’t seem to have reported first on what you found challenging. Perhaps you’ve reflected only on the positive aspects in your report?. <\/span>\"}
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
