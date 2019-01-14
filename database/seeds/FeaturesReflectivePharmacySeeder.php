<?php

use Illuminate\Database\Seeder;
use App\Feature;

class FeaturesReflectivePharmacySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $feature = new Feature();
        $feature->name = "Pharmacy";
        $feature->grammar="Reflective";
        $feature->id=8;
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
                    \"custom\" : \"more feedback\",
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
                    \"method\": \"expressionsFeedback\",
                    \"check\": {
                        \"tags\": [
                            \"epistemic\",
                            \"modal\",
                            \"affect\"
                        ],
                        \"conditions\" : []
                    },
                    \"message\": [
                        {\"epistemic\": \"<div class=\\\"alert alert-success\\\"><i class=\\\"fa fa-thumbs-up\\\"></i> Well done, you seem to have reflected on your beliefs\/learning\/knowledge. <\/div>\"},
                        {\"modal\": \"<div class=\\\"alert alert-success\\\"><i class=\\\"fa fa-thumbs-up\\\"></i> Well done, you seem to have incorporated a deeper reflection indicating self-critique. <\/div>\"},
                        {\"affect\": \"<div class=\\\"alert alert-success\\\"><i class=\\\"fa fa-thumbs-up\\\"></i> Well done, it appears that you have reflected on your feelings, thoughts or reactions. <\/div>\"},
                        {\"epistemic_m\": \"<div class=\\\"alert alert-info\\\"><i class=\\\"fa fa-exclamation-circle\\\"></i> You seem not to have reflected on your beliefs\/learning\/knowledge. If thats the case, then please think about this (e.g. including cultural, religious or family values\/assumptions). <\/div>\"},
                        {\"modal_m\": \"<div class=\\\"alert alert-info\\\"><i class=\\\"fa fa-exclamation-circle\\\"></i> You seem not to have incorporated a deeper reflection indicating self-critique. Consider how this could improve reflection on your strengths and weaknesses. <\/div>\"}
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
                            \"link2me\",
                            \"challenge\",
                            \"change\"
                        ],
                        \"conditions\" : []
                    },
                    \"message\": [
                        {\"link2me\": \"<div class=\\\"alert alert-success\\\"><i class=\\\"fa fa-thumbs-up\\\"></i> Well done, it appears that you have reflected in a deeper way about how your experiences connect with your professional development. <\/div>\"},
                        {\"challenge\": \"<div class=\\\"alert alert-success\\\"><i class=\\\"fa fa-thumbs-up\\\"></i> Well done, it appears that you’ve reported on something you found challenging. <\/div>\"},
                        {\"change\": \"<div class=\\\"alert alert-success\\\"><i class=\\\"fa fa-thumbs-up\\\"></i> Well done, it appears that you’ve reflected on how you would change/prepare for the future. Is there anything further to say about these new insights that have led to change <\/div>\"},
                        {\"link2me_m\": \"<div class=\\\"alert alert-info\\\"><i class=\\\"fa fa-exclamation-circle\\\"></i> You seem not to have reflected in a deeper way about your experiences. Consider applying your insights to how you can develop professionally. <\/div>\"},
                        {\"challenge_m\": \"<div class=\\\"alert alert-info\\\"><i class=\\\"fa fa-exclamation-circle\\\"></i> It appears that you haven’t commented on anything you found challenging. If you did find something challenging, please expand on this. <\/div>\"},
                        {\"change_m\": \"<div class=\\\"alert alert-info\\\"><i class=\\\"fa fa-exclamation-circle\\\"></i> It appears that you haven’t commented on what you would do differently should the same event occur in the future. Perhaps think about changes in perspectives\/strategies\/tools\/ideas\/behaviour and\/or approach. <\/div>\"}    
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
