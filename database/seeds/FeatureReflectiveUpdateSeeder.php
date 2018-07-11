<?php

use Illuminate\Database\Seeder;
use App\Feature;

class FeatureReflectiveUpdateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $feature = Feature::find(2);
        $feature->rules= json_encode("{
  \"rules\": [
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
            \"words\" : 
                [\"preceptor\",\"supervisor\", \"hospital\", \"Community\", \"Pharmacy\", \"Placement\", \"site\", \"pharmacy\", \"pharmacist\",\"mentor\"], 
            \"tags\" : 
                [\"affect\"]
            }
        ]
      },
      \"message\": [],
      \"css\": []
    },
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
      \"css\": [\"context\",\"challenge\",\"link2me\", \"change\"]
    }

  ]
}");
        $feature->save();
    }
}
