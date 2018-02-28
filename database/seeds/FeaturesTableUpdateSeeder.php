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
        $feature = Feature::find(1);
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
        {\"attitude\" : \"<span class=\\\"badge badge-pill badge-analytic\\\">P<\/span> A perspective or stance\"},
        {\"emph\" : \"<span class=\\\"badge badge-pill badge-analytic\\\">E<\/span> Emphasis to highlight key ideas\"},
        {\"contribution\" : \"<span class=\\\"badge badge-pill badge-analytic-green\\\">S<\/span> Summarises or signals the authors goals\"},
        {\"novstat\" : \"<span class=\\\"badge badge-pill badge-analytic\\\">N<\/span> Novelty improvements in ideas and methods\"},
        {\"contrast\" : \"<span class=\\\"badge badge-pill badge-analytic\\\">C</span> Contrast: Disagreement, Tension, Inconsistency\"},
        {\"tempstat\": \"<span class=\\\"badge badge-pill badge-analytic\\\">B</span> Background: generally accepted work\"},
        {\"surprise\": \"<span class=\\\"badge badge-pill badge-analytic\\\">S</span> Surprising\"},
        {\"nostat\": \"<span class=\\\"badge badge-pill badge-analytic\\\">Q</span> Question\"},
        {\"grow\" : \"<span class=\\\"badge badge-pill badge-analytic\\\">T<\/span> A trend or tendency related to ideas approaches and methods\"}
      ],
      \"css\": [\"P\",\"E\", \"T\", \"S\", \"N\", \"C\", \"B\", \"S\"]
    }
  ]
}");

      $feature->save();

    }
}