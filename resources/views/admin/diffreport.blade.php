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

?>
@extends('layouts.app')

@section('content')
<div id="app">
    <div class="container">
        @include('admin.flash')
        <div class="row">
            <div class="col-md-12">
                <textarea rows="4", cols="54" style="resize:none, ">{{ $data->draft_first->text_input }}</textarea>
                <textarea rows="4", cols="54" style="resize:none, ">{{ $data->draft_second->text_input }}</textarea>
                <textarea rows="4", cols="54" style="resize:none, ">{{ $diff ?? '' }}</textarea>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<!-- API Documentation: https://github.com/google/diff-match-patch/wiki/API -->
    <script src="js/diff_match_patch.js"></script>
    var dmp = new diff_match_patch();
    var diff = dmp.diff_main($data->draft_first->text_input, $data->draft_second->text_input);
    dmp.diff_cleanupSemantic(diff);
    pretty_diff = diff_prettyHtml(diff)
@endsection

@section('footer')

@stop
