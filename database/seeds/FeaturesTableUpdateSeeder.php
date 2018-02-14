<?php

use Illuminate\Database\Seeder;
use App\Feature;

class FeaturesTableUpdateSeeder extends Seeder
{
    /**
     * Run the database seeds.
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
          \"vis\",
          \"contribution\",
          \"nostat\",
          \"contrast\",
          \"tempstat\",
          \"surprise\"
        ]
      },
      \"message\": [
        {\"contribution\" : \"<span class=\\\"badge badge-pill badge-analytic-green\\\">S<\/span> Summarise the authors goals\"},
        {\"attitude\" : \"<span class=\\\"badge badge-pill badge-analytic\\\">P<\/span> A perspective or stance\"},
        {\"emph\" : \"<span class=\\\"badge badge-pill badge-analytic\\\">E<\/span> Emphasis or importance to ideas \"},
        {\"vis\" : \"<span class=\\\"badge badge-pill badge-analytic\\\">T<\/span> A trend or tendency related to ideas approaches and methods\"},
        {\"nostat\" : \"<span class=\\\"badge badge-pill badge-analytic\\\">N<\/span> Novelty improvements of ideas methods\"},
        {\"contrast\" : \"<span class=\\\"badge badge-pill badge-analytic\\\">C<\/span> Disagreement, Tension, Options, Inconsistency\"},
        {\"tempstat\": \"<span class=\\\"badge badge-pill badge-analytic\\\">B<\/span> Reference to generally accepted previous work\"},
        {\"surprise\": \"<span class=\\\"badge badge-pill badge-analytic\\\">S<\/span> Surprise\"}
      ],
      \"css\": [\"P\",\"E\", \"T\", \"S\", \"N\", \"C\", \"B\", \"S\"]
    }
  ]
}");

      $feature->save();

    }
}
