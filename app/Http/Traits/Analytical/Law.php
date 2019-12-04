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

trait Law
{
    /**
     * @param $tap
     * @param $rule -- used for shibani's law abstract rules
     * @return array
     */
    public function missingTags($tap, $rule, $extra = array())
    {
        $result = array();
        $check = $rule['check'];
        $tempo = 0;
        $tags = $check['tags'];
        $messages = $rule['message'];
        if (count($tags) == 0) {
            return $result;
        }
        $monitor = array();
        $issues = array();

        if ($rule["tabEval"] === 'dynamic') {

            $temp = array();
            foreach ($tap as $key => $data) {
                $temp = array_merge($temp, $data->raw_tags);
            }

            $temp = array();
            $temp_temp = array();
            foreach ($tap as $key => $data) {
                $temp_temp = array_merge($temp_temp, $data->raw_tags);
            }

            /***
             * hacky stuff that violates the flow of rules - for Shibani....
             * if contrast and question present don't add error
             * else if neither of them present show  error
             * solution replace all contrast tags with question and check for question(nostat)
             * if present don't error else error!!!!
             ***/

            foreach ($temp_temp as $v) {
                if ($v == 'contrast') {
                    array_push($temp, 'nostat');
                } else {
                    array_push($temp, $v);
                }
            }

            $monitor = array_unique($temp);

            foreach ($tags as  $d) {
                if (!in_array($d, $monitor)) {
                    foreach ($messages as $msg) {
                        if (isset($msg[$d])) array_push($issues, $msg[$d]);
                    }
                }
            }
        } else {
            foreach ($messages as $key => $msg) {
                array_push($issues, $msg);
            }
        }

        array_push($result, $issues);
        return $result;
    }
}
