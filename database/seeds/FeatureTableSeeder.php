<?php

use Illuminate\Database\Seeder;
use App\Feature;

class FeatureTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $feature_a = new Feature();
        $feature_a->name = "Default-Rule";
        $feature_a->grammar="Analytical";
        $feature_a->rules=json_encode("{
    \"rules\": [
      {
        \"name\": \"background\",
        \"check\": {
          \"paragraph\": \"3\",
          \"tag\":\"temporality\"
        },
        \"message\": [
          {\"background\" : \"Background information missing in first paragraph\"}
        ],
        \"css\": \"temporality\"
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
        \"css\": \"vocab\"
      },
      {
        \"name\": \"metrics\",
        \"check\": {
          \"sentenceWordCount\": \"35\"
        },
        \"message\": [
          {\"metrics\" :\"Sentence too long, might disengage the reader. Try breaking it into smaller sentences\"}
        ],
        \"css\": \"metrics\"
      },
      {
        \"name\": \"moves\",
        \"check\": {
          \"tags\": [
            \"attitude\",
            \"emph\",
            \"vis\",
            \"contribution\",
            \"novstat\",
            \"contrast\",
            \"tempstat\",
            \"surprise\"
          ]
        },
        \"message\": [
          {\"attitude\" : \"P\"},
          {\"emph\" : \"E\"},
          {\"vis\" : \"T\"},
          {\"contribution\" : \"S\"},
          {\"novstat\" : \"N\"},
          {\"contrast\" : \"C\"},
          {\"tempstat\": \"B\"}
        ],
        \"css\": [\"P\",\"E\", \"T\",\"N\", \"C\", \"B\"]
      }
    ]
}
        ");
        $feature_a->save();

        $feature_b = new Feature();
        $feature_b->grammar="Reflective";
        $feature_b->name = "Default-Rule";
        $feature_b->rules=json_encode("{
  \"rules\": [
    {
      \"name\": \"expression\",
      \"check\": {
        \"all\": [
          \"affect\",
          \"epistemic\",
          \"modal\"
        ]
      },
      \"message\": [
        {\"affect\" : \"Words associated with strong feelings\"},
        {\"epistemic\" : \"<u>Expressions indicating belief, learning, or knowledge.<\/u>\"},
        {\"modal\" : \"Expressions indicating self critique\"}
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
        {\"link2me\" : \"<b>Deeper reflection, personally applied.</b>\"},
        {\"change\": \"How new knowledge can lead to a change\"}
      ],
      \"css\": [\"context\",\"challenge\",\"link2me\", \"change\"]
    }

  ]
}
        ");

$feature_b->save();




    }
}
