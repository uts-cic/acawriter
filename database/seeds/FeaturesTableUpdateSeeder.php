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
          \"grow\",
          \"contribution\",
          \"nostat\",
          \"novstat\",
          \"contrast\",
          \"tempstat\",
          \"surprise\"
        ]
      },
      \"message\": [
        {\"contribution\" : \"<span class=\\\"badge badge-pill badge-analytic-green\\\">S<\/span> Summarises\/signals author\’s goals\"},
        {\"attitude\" : \"<span class=\\\"badge badge-pill badge-analytic\\\">P<\/span> Perspective or stance\"},
        {\"emph\" : \"<span class=\\\"badge badge-pill badge-analytic\\\">E<\/span> Emphasis or importance to ideas \"},
        {\"grow\" : \"<span class=\\\"badge badge-pill badge-analytic\\\">T<\/span> A trend or tendency related to ideas approaches and methods\"},
        {\"novstat\" : \"<span class=\\\"badge badge-pill badge-analytic\\\">N<\/span> Novelty improvements of ideas\/methods\"},
        {\"contrast\" : \"<span class=\\\"badge badge-pill badge-analytic\\\">C<\/span> Disagreement, Tension, Inconsistency\"},
        {\"tempstat\": \"<span class=\\\"badge badge-pill badge-analytic\\\">B<\/span> Generally accepted work\"},
        {\"surprise\": \"<span class=\\\"badge badge-pill badge-analytic\\\">S<\/span> Surprising\"},
        {\"nostat\": \"<span class=\\\"badge badge-pill badge-analytic\\\">Q<\/span> Question to be resolved\/missing knowledge\"},
      ],
      \"css\": [\"P\",\"E\", \"T\", \"S\", \"N\", \"C\", \"B\", \"S\"]
    }
  ]
}");

      $feature->save();

    }
}