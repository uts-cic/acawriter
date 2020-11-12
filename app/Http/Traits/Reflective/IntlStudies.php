<?php

/**
 * Project: AcaWriter
 * Copyright (c) 2019 original University of Technology Sydney. Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * Contributor(s):
 * Developer UTS Connected Intelligence Centre
 *
 */

/*
 * Adding only Intl specific function call here, only lastparafeed is additional hence adding it here
 * all other function calls still getting used from Pharmacy Trait
 */

namespace App\Http\Traits\Reflective;

trait IntlStudies
{
    /**
     * @param $tap
     * @param $rule -- used for pharmacy rules paragraph level feedback
     * @return array
     */
    public function paragraphLastFeedback($tap, $rule, $extra = array())
    {
        $result = array();
        $check = $rule['check'];
        $tempo = 0;
        $tags = $check['tags'];
        $messages = $rule['message'];
        $conditions = $check['conditions'];
        $startOf = 0; //start index (from the end) to check change tag

        if (count($tags) == 0) {
            return $result;
        }
        $monitor = array();
        $issues = array();

        if (count($extra) > 0) {
            foreach ($extra as $key => $more) {
                if ($key == 'expression') {
                    $expression = $more;
                }
            }
        }

        if ($rule["tabEval"] === 'dynamic') {

            $temp = array();
            $check_data = array();
            $para_count = 0;

            // print_r($conditions);
            //check the conditions array as well
            if (count($conditions) > 0) {
                foreach ($conditions as $condition) {
                    if (isset($condition['terms'])) {
                        $check_data = $condition['terms'];
                    }
                    if (isset($condition['para_chk'])) {
                        $para_count = $condition['para_chk'];
                    }
                }
            }
            if ($para_count > 0) {
                // slice at number of para's,
                // get text to check upto that number into an array
                $lastIds = array();
                foreach ($tap as $idx => $scan) {
                    if (strstr($scan->str, '[&][&]')) {
                        $lastIds[] = $idx;
                    }
                }
                if (count($lastIds) > 0) {
                    $len = count($lastIds);
                    if ($len > 1 && $len - 2 > 0) {
                        $startOf = $lastIds[$len - 2];
                    }
                }

                foreach ($tap as $key => $data) {
                    if ($key >= $startOf) {
                        $temp = array_merge($temp, $data->raw_tags);
                    }
                }

                $monitor = array_unique($temp);

                foreach ($tags as $d) {
                    if (!in_array($d, $monitor)) {
                        // this is not set to check if change is absent in Intl studies as of now
                        foreach ($messages as $msg) {
                            $ref = $d . '_m';
                            if (isset($msg[$ref])) array_push($issues, $msg[$ref]);
                        }
                    } else {
                        foreach ($messages as $msg) {
                            if (isset($msg[$d])) array_push($issues, $msg[$d]);
                        }
                    }
                }
            }
        } else {
            foreach ($messages as $key => $msg) {
                array_push($issues, $msg);
            }
        }

        array_push($result, $issues);
        //print_r($result);
        return $result;
    }
}
