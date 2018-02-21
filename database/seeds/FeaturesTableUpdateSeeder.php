<?php

use Illuminate\Database\Seeder;
use App\Feature;

class FeaturesTableUpdateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * update Analytic rules
     * just update the id and attach the required rules
     *
     * @return void
     */
    public function run()
    {
        //
        $feature = Feature::find(4);
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
        {\"emph\" : \"<span class=\\\"badge badge-pill badge-analytic\\\">E</span> Studied extensively, received considerable attention\"},
        {\"tempstat\" : \"<span class=\\\"badge badge-pill badge-analytic\\\">B</span> Wide interest\"}
      ],
      \"css\": [\"E\",\"B\"],
      \"custom\" : \"Move 1: Establishing a research territory\"
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
        {\"contrast\" : \"<span class=\\\"badge badge-pill badge-analytic\\\">C</span> Disagreement, Tension, Options, Inconsistency\"},
        {\"nostat\" : \"<span class=\\\"badge badge-pill badge-analytic\\\">Q</span> Question\"}
      ],
      \"css\": [\"C\", \"Q\"],
      \"custom\" : \"Move 2: Establishing a Niche\"
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
        {\"novstat\" : \"<span class=\\\"badge badge-pill badge-analytic\\\">N</span> Novelty improvements of ideas methods\"},
        {\"contribution\" : \"<span class=\\\"badge badge-pill badge-analytic-green\\\">S</span> Summarises/signals author’s goals\"}
      ],
      \"css\": [\"N\", \"S\"],
      \"custom\" : \"Move 3: Occupying the Niche\"
    }
  ]
}");

      $feature->save();

    }
}