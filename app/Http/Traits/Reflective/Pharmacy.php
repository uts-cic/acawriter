<?php

/**
 * Project: AcaWriter
 * Copyright (c) 2018 original University of Technology Sydney. Licensed under the Apache License, Version 2.0 (the "License");
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

namespace App\Http\Traits\Reflective;

trait Pharmacy
{

    /**
     * @param $tap
     * @param $rule -- used for pharmacy rules
     * @return array
     */
    public function expressionsFeedback($tap, $rule, $extra = array())
    {
        $result = array();
        $check = $rule['check'];
        $tempo = 0;
        $tags = $check['tags'];
        $messages = $rule['message'];
        $conditions = $check['conditions'];
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
            $t = array();
            if (isset($expression)) {
                foreach ($expression as $data) {
                    foreach ($data as $exp) {
                        // AI/2019-06-25: Removing affect analysis
                        // if (count($exp->affect) > 0) {
                        //     $t[] = 'affect';
                        // }
                        if (count($exp->epistemic) > 0) {
                            $t[] = 'epistemic';
                        }
                        if (count($exp->modal) > 0) {
                            $t[] = 'modal';
                        }
                    }
                }
                $temp = array_merge($temp, $t);
            }

            foreach ($tap as $key => $data) {
                $temp = array_merge($temp, $data->raw_tags);
            }

            $monitor = array_unique($temp);

            foreach ($tags as  $d) {
                if (!in_array($d, $monitor)) {
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
        } else {
            foreach ($messages as $key => $msg) {
                array_push($issues, $msg);
            }
        }

        //now check for the double tags
        if (count($conditions) > 0) {
            foreach ($conditions as $checkTag) {
                $cnt = 0;
                $cnt = $this->checkDoubles($tap, $checkTag);
                if ($cnt > 0) {
                    foreach ($messages as $msg) {
                        $dt = 'double_' . $checkTag;
                        if (isset($msg[$dt])) {
                            $h = str_replace('#cnt', $cnt, $msg[$dt]);
                            array_push($issues, $h);
                        }
                    }
                }
            }
        }

        array_push($result, $issues);
        //print_r($result);
        return $result;
    }


    /**
     * @param $tap
     * @param $rule -- used for pharmacy rules paragraph level feedback
     * @return array
     */
    public function paragraphFeedback($tap, $rule, $extra = array())
    {
        $result = array();
        $check = $rule['check'];
        $tempo = 0;
        $tags = $check['tags'];
        $messages = $rule['message'];
        $conditions = $check['conditions'];
        $cutoff = 0;

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
            //print_r($para_count);
            if (count($check_data) > 0 && $para_count > 0) {
                // slice at number of para's,
                // get text to check upto that number into an array

                foreach ($tap as $idx => $scan) {
                    if (strstr($scan->str, '[&][&]')) {
                        $para_count--;

                        if ($para_count == 0) {
                            $cutoff = $idx - 1;
                            break;
                        }
                    }
                }

                foreach ($tap as $key => $data) {
                    if ($key > $cutoff) {
                        break;
                    }
                    $temp = array_merge($temp, $data->raw_tags);
                }

                $monitor = array_unique($temp);

                foreach ($tags as $d) {
                    if (count(preg_grep("/" . $d . "/m", $monitor)) === 0) {
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

                foreach ($check_data as $term) {
                    if ($term == 'change_without_context') {
                        if (count(preg_grep("/change/m", $monitor)) > 0 && count(preg_grep("/context/m", $monitor)) === 0) {
                            foreach ($messages as $msg) {
                                if (isset($msg[$term])) array_push($issues, $msg[$term]);
                            }
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


    /**
     * @param $tap
     * @param $tag - to check
     * @return int
     */
    private function checkDoubles($tap, $tag)
    {
        $cnt = 0;
        $sequence = array();
        //this function gets consecutive strings that have challenge/change

        foreach ($tap as $key => $data) {
            if (count(preg_grep("[^" . $tag . "]", $data->raw_tags)) > 0) $sequence[] = $key;
        }

        for ($i = 0; $i <= count($sequence); $i++) {
            if (($i + 1) < count($sequence)) {
                if ($sequence[$i + 1] - $sequence[$i] == 1) $cnt++;
            }
        }

        return $cnt;
    }
}
