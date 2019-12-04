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

/**
 * Created by IntelliJ IDEA.
 * User: Developer CIC
 * Date: 2019-03-05
 * Time: 15:34
 */

namespace App\Traits\Analytical;

trait CarsAbs
{

    public function percentFeedback($tap, $rule, $extra = array())
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

        if ($rule["tabEval"] === 'dynamic') {
            $temp = array();
            $check_data = array();
            $slice_at = 0;
            $total = count($tap);

            //check the conditions array as well
            if (count($conditions) > 0) {
                foreach ($conditions as $condition) {
                    if (isset($condition['terms'])) {
                        $check_data = $condition['terms'];
                    }
                    if (isset($condition['per_chk'])) {
                        $percent = $condition['per_chk'];
                    }
                }

                if ($check_data[0] === 'top') {
                    //picks top percent of sentences
                    $tr = round($total * $percent / 100);
                    $slice_at = $tr <= 1 ? 0 : $tr - 1;

                    foreach ($tap as $key => $data) {
                        if ($key <= $slice_at) {
                            $temp = array_merge($temp, $data->raw_tags);
                        }
                    }
                } elseif ($check_data[0] === 'bottom') {
                    $tr = (round($total * $percent / 100));
                    $tg = $tr <= 1 ? 1 : $tr - 1;
                    $slice_at = $total - $tg;

                    foreach ($tap as $key => $data) {
                        if ($key >= $slice_at) {
                            $temp = array_merge($temp, $data->raw_tags);
                        }
                    }
                } elseif ($check_data[0] === 'middle_range') {

                    $up = round($total * $check_data[1] / 100);
                    $top = $up <= 1 ? 0 : $up - 1;

                    $dw = (round($total * $check_data[1] / 100));
                    $dwr = $dw <= 1 ? 1 : $dw - 1;
                    $bottom = $total - $dwr;

                    foreach ($tap as $key => $data) {
                        if ($key > $top && $key < $bottom) {
                            $temp = array_merge($temp, $data->raw_tags);
                        }
                    }
                }


                $monitor = array_unique($temp);

                $collect_tags = array();
                foreach ($tags as $d) {
                    //now check if either of the tags exist
                    if (in_array($d, $monitor)) {
                        array_push($collect_tags, $d);
                    }
                }
                $cnt = count($collect_tags);
                //add appropriate messages based on the counts if they exist
                if ($check_data[0] === 'middle_range') {
                    if ($cnt === 0) {
                        foreach ($messages as $msg) {
                            $ref = 'tag_m';
                            if (isset($msg[$ref])) array_push($issues, $msg[$ref]);
                        }
                    } else {
                        foreach ($messages as $msg) {
                            $ref = 'exists';
                            if (isset($msg[$ref])) array_push($issues, $msg[$ref]);
                        }
                    }
                } else {
                    if ($cnt === 0) {
                        foreach ($messages as $msg) {
                            $ref = 'tag_m';
                            if (isset($msg[$ref])) array_push($issues, $msg[$ref]);
                        }
                    } elseif ($cnt >= 2) {
                        foreach ($messages as $msg) {
                            $ref = 'exists';
                            if (isset($msg[$ref])) array_push($issues, $msg[$ref]);
                        }
                    } else {
                        foreach ($collect_tags as $d) {
                            foreach ($messages as $msg) {
                                $ref = $d . "_e";
                                if (isset($msg[$ref])) array_push($issues, $msg[$ref]);
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
        return $result;
    }
}
