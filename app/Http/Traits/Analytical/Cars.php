<?php
/**
 * Project: AcaWriter
 *
 * Copyright(c)2018 original University of Technology Sydney.
 * Licensed under the Apache License, Version2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 *
 * See the License for the specific language governing permissions and limitations under the License.
 *
 *  Contributor(s):
 *  UTS Connected Intelligence Centre
 */

namespace App\Traits\Analytical;
use App\Services\Analyser;

trait Cars {

    /**
     * -- crafted this to cover sophies CARS rules, all the rules guided by features
     * @param $tap - all tags per specified calls
     * @param $rule - fulled from selected feature
     * @return array - returns missing tags
     *                         moves1, move2, move3 precedence orders and messages if not followed
     */

    public function enforced($tap, $rule) {
        $result = array();
        $check = $rule['check'];
        $tempo = 0;
        $tags = $check['tags'];
        $messages = $rule['message'];
        if(count($tags) == 0) {
            return $result;
        }
        $monitor = array();
        $issues = array();

        if($rule["tabEval"] === 'dynamic') {


            foreach ($tap as $key => $data) {
                $setFeed = new \stdClass();
                $setFeed->str = $data->str;
                $setFeed->message = array();
                $setFeed->css = array();
                $setFeed->interim = array();

                $temp = array();
                foreach ($tags as $it => $case) {
                    foreach ($case as $k => $tag) {
                        if (count(array_intersect($tag, $data->raw_tags)) > 0) {
                            $temp[] = $k;
                        }
                    }
                }

                if (count($temp) > 0) {
                    arsort($temp);
                    $sorted = array_unique($temp);
                    // print_r(current($sorted));
                    array_push($monitor, current($sorted));
                }
            }

            foreach ($monitor as $key => $d) {
                if (isset($monitor[$key + 1])) {
                    $pre = $monitor[$key];
                    $next = $monitor[$key + 1];
                    $idx = $pre.$next;
                    if ($pre > $next) {
                        foreach ($messages as $msg) {
                            if (isset($msg['problem' . $idx])) array_push($issues, $msg['problem' . $idx]);
                        }
                    }
                }
            }

            // print_r($issues);

            //check for missing moves
            $unique_moves = array_unique($monitor);
            //print_r($unique_moves);
            foreach (array(1, 2, 3) as $move) {
                if (!in_array($move, $unique_moves)) {
                    foreach ($messages as $msg) {
                        if (isset($msg['missing' . $move])) array_push($issues, $msg['missing' . $move]);
                    }
                }
            }


        } else {
            foreach($messages as $key => $msg) {
                array_push($issues, $msg);
            }
        }

        array_push($result, $issues);
        //print_r($result);
        return $result;
    }
}