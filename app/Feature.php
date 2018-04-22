<?php
/**
 * Copyright (c) 2018 original UTS CIC. Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software distributed under the License
 * is distributed on an "AS IS" BASIS, WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND,
 * either express or implied. See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * Contributors:
 * UTS Connected Intelligence Centre
 */

namespace App;

use Illuminate\Database\Eloquent\Model;

class Feature extends Model
{
    //
    public function assignments() {
        return $this->hasMany('App\Assignment');
    }

    public function drafts() {
        return $this->hasMany('App\Draft');
    }

    public function examples() {
        return $this->hasMany('App\Example');
    }


}
