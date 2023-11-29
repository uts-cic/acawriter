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
        $features = array(
            array(
                'id' => 1,
                'name' => 'Standard',
                'grammar' => 'Analytical',
                'info' => 'Highlights sentences that appear to show hallmarks of good analytical academic writing. For <a href="https://www.uts.edu.au/research-and-teaching/teaching-and-research-integration/acawriter/educators/how-acawriter-being-5" target="_blank">more info</a>',
            ),
            array(
                'id' => 2,
                'name' => 'Standard',
                'grammar' => 'Reflective',
                'info' => 'Highlights sentences that appear to show hallmarks of good reflective writing. <a target="_blank" href="https://www.uts.edu.au/research-and-teaching/teaching-and-research-integration/acawriter/educators/how-acawriter-being-0">More Info</a>',
            ),
            array(
                'id' => 5,
                'name' => 'Research Introduction',
                'grammar' => 'Analytical',
                'info' => 'Highlights sentences that appear to show hallmarks of good academic writing specifically for Research Abstracts and Introductions. <a target="_blank" href="https://www.uts.edu.au/research-and-teaching/teaching-and-research-integration/acawriter/educators/how-acawriter-being-4">More info</a>',
            ),
            array(
                'id' => 6,
                'name' => 'Law Essay Feedback',
                'grammar' => 'Analytical',
                'info' => 'This genre highlights sentences where AcaWriter detects rhetorical moves in a law essay writing context. These rhetorical moves when used explicitly guide the reader with the flow of the text and are hallmarks of good academic writing. The genre also includes specific feedback on possible improvements that can be made in that essay writing context. <a target="_blank" href="https://www.uts.edu.au/research-and-teaching/teaching-and-research-integration/acawriter/educators/how-acawriter-being-3">More Info</a>',
            ),
            array(
                'id' => 7,
                'name' => 'Analytical Accounting',
                'grammar' => 'Analytical',
                'info' => 'Highlights sentences that appear to show the hallmarks of good academic writing for a UTS business report and provides specific feedback on possible improvements that can be made within this context. <a target="_blank" href="https://www.uts.edu.au/research-and-teaching/teaching-and-research-integration/acawriter/educators/how-acawriter-being-2">More info</a>',
            ),
            array(
                'id' => 8,
                'name' => 'Pharmacy',
                'grammar' => 'Reflective',
                'info' => 'Reflective writing is your response to experience, opinions, events or new information as well as your response to your thoughts and feelings. Reflective writing allows you explore your learning and to make meaning out of what you study. It also gives you the opportunity to gain self-knowledge, as well as better understanding what you are learning. <a target="_blank" href="https://www.uts.edu.au/research-and-teaching/teaching-and-research-integration/acawriter/educators/how-acawriter-being-0">More info</a>',
            ),
            array(
                'id' => 9,
                'name' => 'International Studies',
                'grammar' => 'Reflective',
                'info' => 'A deep reflection on your learning as well as an analysis of cultural aspects of the host culture and its relevance in the home cultures is required. The aim of a reflection is to make you sensitive to new experiences, what is confronting/ interesting/ different and how does this makes you rethink your assumptions. <a target="_blank" href="https://www.uts.edu.au/research-and-teaching/teaching-and-research-integration/acawriter/educators/how-acawriter-being-0">More info</a>',
            ),
            array(
                'id' => 10,
                'name' => 'Research Abstract',
                'grammar' => 'Analytical',
                'info' => 'Highlights sentences that appear to show hallmarks of good academic writing specifically for Research Abstracts. <a target="_blank" href="https://www.uts.edu.au/research-and-teaching/teaching-and-research-integration/acawriter/educators/how-acawriter-being-4">More Info</a>',
            ),
            array(
                'id' => 11,
                'name' => 'DCE',
                'grammar' => 'Reflective',
                'info' => '',
            ),
            array(
                'id' => 12,
                'name' => 'Critical Analysis Essay Feedback - 21129',
                'grammar' => 'Analytical',
                'info' => '',
            ),
            array(
                'id' => 13,
                'name' => 'Critical Analysis Essay Feedback - 21129 (2021 SPR)',
                'grammar' => 'Analytical',
                'info' => '',
            ),
            array(
                'id' => 14,
                'name' => 'Analytical [570002 Application Implementation with Microsoft Dynamics]',
                'grammar' => 'Analytical',
                'info' => '',
            ),
            array(
                'id' => 15,
                'name' => 'Reflective [570002 Application Implementation with Microsoft Dynamics]',
                'grammar' => 'Reflective',
                'info' => '',
            ),
        );

        foreach ($features as $data) {
            $rules = file_get_contents(__DIR__ . '/rules/feature_' . $data['id'] . '.json');
            if (!$rules) {
                continue;
            }

            // compress rules JSON string
            $rules = json_encode(json_decode($rules));

            $feature = Feature::find($data['id']);
            if (!$feature) {
                $feature = new Feature();
            }
            $feature->id = $data['id'];
            $feature->name = $data['name'];
            $feature->grammar = $data['grammar'];
            $feature->rules = json_encode($rules);
            $feature->info = $data['info'];
            $feature->save();
        }
    }
}
