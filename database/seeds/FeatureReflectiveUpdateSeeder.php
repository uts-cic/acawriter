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
          \"modal\"
        ]
      },
      \"message\": [
        {\"epistemic\" : \"<u>Expressions indicating belief, learning, or knowledge.</u>\"},
        {\"modal\" : \"<span class=\\\"modall\\\">Expressions indicating self critique<\/span>\"}
      ],
      \"css\": [\"epistemic\",\"affect\",\"modall\"]
    },
    {
      \"name\": \"vocab\",
      \"check\": {
        \"words\": [
          \"server\",
          \"study\",
          \"force\"
        ]
      },
      \"message\": [
        {\"vocab\" :\"One or more keywords missing\"}
      ],
      \"css\": [\"vocab\"]
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
