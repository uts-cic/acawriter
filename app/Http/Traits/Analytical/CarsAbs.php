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

namespace App\Http\Traits\Analytical;

trait CarsAbs
{

    public function percentFeedback($tap, $rule, $extra = array())
    {
        $issues = array();

        $check = $rule['check'];
        $tags = $check['tags'];
        if (count($tags) == 0) {
            return array();
        }

        $messages = $rule['message'];

        if ($rule["tabEval"] === 'dynamic') {
            $conditions = isset($check['conditions']) ? $check['conditions'] : array();
            $exclude = isset($check['exclude']) ? $check['exclude'] : array();

            $keys = $this->getConditionKeys($tap, $conditions);
            $excludeKeys = $this->getExcludeKeys($tap, $exclude);

            $temp = array();
            foreach ($tap as $key => $data) {
                if (isset($keys) && !in_array($key, $keys)) {
                    continue;
                }
                if (in_array($key, $excludeKeys)) {
                    continue;
                }
                $temp = array_merge($temp, $data->raw_tags);
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
            // else {
            //     foreach ($collect_tags as $d) {
            //         foreach ($messages as $msg) {
            //             $ref = $d . "_e";
            //             if (isset($msg[$ref])) array_push($issues, $msg[$ref]);
            //         }
            //     }
            // }

        }
        else {
            foreach ($messages as $key => $msg) {
                array_push($issues, $msg);
            }
        }

        // if ($rule['custom'] === 'Move 3') {
        //     print_r($rule);print_r($issues);die();
        // }

        return array($issues);
    }

    private function getConditionKeys($tap, $conditions)
    {
        $check_data = array();
        $slice_at = 0;
        $total = count($tap);
        //check the conditions array as well
        if (count($conditions) > 0) {
            foreach ($conditions as $condition) {
                $check_data = isset($condition['terms']) ? $condition['terms'][0] : null;
                $percent = isset($condition['per_chk']) ? $condition['per_chk'] / 100 : 0;
                $number = isset($condition['num_chk']) ? intval($condition['num_chk']) : 0;
                break;
            }
            if ($check_data === 'top') {
                if ($percent && $percent < 1) {
                    $tr = floor($total * $percent);
                    $slice_at = $tr ? $tr - 1 : 0;
                    return range(0, $slice_at);
                }
                if ($number > 0 && $number <= $total) {
                    return range(0, $number - 1);
                }
                if ($number < 0) {
                    return $total + $number > 0 ? range(0, $total + $number - 1) : array();
                }
            } elseif ($check_data === 'bottom') {
                if ($percent && $percent < 1) {
                    $tr = ceil($total * $percent);
                    $slice_at = $tr < $total ? $tr + 1 : $total - 1;
                    return range($slice_at, $total - 1);
                }
                if ($number > 0 && $number < $total) {
                    return range($total - $number, $total - 1);
                }
                if ($number < 0) {
                    return $total + $number > 0 ? range($number * -1, $total - 1) : array();
                }
            }
        }
        return null;
    }

    private function getExcludeKeys($tap, $exclude)
    {
        $excludeKeys = array();
        foreach ($exclude as $check) {
            $tags = $check['tags'];
            $conditions = isset($check['conditions']) ? $check['conditions'] : array();
            $keys = $this->getConditionKeys($tap, $conditions);

            foreach ($tap as $key => $data) {
                if (isset($keys) && !in_array($key, $keys)) {
                    continue;
                }

                if ($data->raw_tags && array_intersect($tags, $data->raw_tags)) {
                    $excludeKeys[] = $key;
                }
            }
        }
        return $excludeKeys;
    }
}
