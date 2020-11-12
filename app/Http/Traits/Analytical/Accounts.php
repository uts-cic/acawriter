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

namespace App\Http\Traits\Analytical;

trait Accounts
{
    /**
     * @param $tap
     * @param $rule --- accounting moves swap
     * @return array
     */
    public function amoves($tap, $rule, $extra = array())
    {
        $result = array();
        $check = $rule['check'];
        $tempo = 0;
        $tags = $check['tags'];
        $messages = $rule['message'];
        if (count($tags) == 0) {
            return $result;
        }

        foreach ($tap as $key => $data) {
            $setFeed = new \stdClass();
            $setFeed->str = $data->str;
            $setFeed->message = array();
            $setFeed->css = array();

            $temp_tap_raw_tags = array();

            $filtered = array();
            foreach ($data->raw_tags as $raw) {
                if ($raw == 'nostat') {
                    array_push($temp_tap_raw_tags, 'contrast');
                } else {
                    array_push($temp_tap_raw_tags, $raw);
                }
            }

            $filtered = array_unique($temp_tap_raw_tags);

            foreach ($tags as $tag) {

                if (count(preg_grep("[^" . $tag . "]", $filtered)) > 0) {
                    foreach ($messages as $msg) {
                        if (isset($msg[$tag])) {
                            $setFeed->message[$tag] = $msg[$tag];
                            if (!in_array($tag, $setFeed->css)) {
                                array_push($setFeed->css, $tag);
                            }
                        }
                    }
                }
            }

            $result[] = $setFeed;
        }

        return $result;
    }

    /**
     * @param $tap
     * @param $rule -- used for accounting rules
     * @return array
     */
    public function missingSwapTags($tap, $rule, $extra = array())
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

        if ($rule["tabEval"] === 'dynamic') {

            $temp = array();
            $temp_temp = array();
            foreach ($tap as $key => $data) {
                $temp_temp = array_merge($temp_temp, $data->raw_tags);
            }

            foreach ($temp_temp as $raw) {
                if ($raw == 'nostat' || $raw == 'emph') {
                    if ($raw == 'nostat') {
                        array_push($temp, 'contrast');
                    }
                    if ($raw == 'emph') {
                        array_push($temp, 'attitude');
                    }
                } else {
                    array_push($temp, $raw);
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

            //check for any conditional positive feedback - true iff all the tags are added
            if (count($conditions) > 0 && count($issues) == 0) {
                //will be positive in case of accounts
                $cond_value = $conditions[0];
                foreach ($messages as $msg) {
                    if (isset($msg[$cond_value])) array_push($issues, $msg[$cond_value]);
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

    /**
     * will positive feedback per existing tag
     * @param $tap
     * @param $rule -- used for accounting positive feedback
     * @return array
     */
    public function positiveFeed($tap, $rule, $extra = array())
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

        if ($rule["tabEval"] === 'dynamic') {

            $temp = array();
            $temp_temp = array();
            foreach ($tap as $key => $data) {
                $temp_temp = array_merge($temp_temp, $data->raw_tags);
            }

            foreach ($temp_temp as $raw) {
                if ($raw == 'nostat' || $raw == 'emph') {
                    if ($raw == 'nostat') {
                        array_push($temp, 'contrast');
                    }
                    if ($raw == 'emph') {
                        array_push($temp, 'attitude');
                    }
                } else {
                    array_push($temp, $raw);
                }
            }

            $monitor = array_unique($temp);
            $aggregate_tags = array();

            foreach ($tags as  $d) {
                if (in_array($d, $monitor)) {
                    foreach ($messages as $msg) {
                        if (isset($msg[$d])) array_push($aggregate_tags, $msg[$d]);
                    }
                }
            }
            if (count($aggregate_tags) > 0) {
                $issues[] = "<i class=\"fa fa-2x fa-check-circle-o text-success\"></i> Good job! AcaWriter spotted these key moves: " . implode(', ', $aggregate_tags);
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
