<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Storage;

class S3Controller extends Controller
{
    /**
     * show the view
     */
    public function docUpload()
    {
        return view('doc-upload');
    }

    public function processUpload(Request $request)
    {
        $this->validate($request, [
            'docs' => 'required',
            'docs.*' => 'mimes:doc,docx,rtf,txt|max:2048'
        ]);
        $S3images = array();

        //dd($request->docs);
        $i = 1;
        foreach ($request->docs as $list) {
            $fileName = time() . '_' . $i . '.' . $list->getClientOriginalExtension();
            $t = Storage::disk('s3')->put($fileName, file_get_contents($list), 'public');
            $S3images[] = Storage::disk('s3')->url($fileName);
            $i++;
        }

        return back()
            ->with('success', 'File(s) Uploaded successfully.')
            ->with('path', $S3images);
    }
}
