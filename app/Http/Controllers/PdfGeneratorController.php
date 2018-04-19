<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use App\Draft;


class PdfGeneratorController extends Controller
{

    public function pdfview ($ref= NULL) {
        PDF::setOptions(['dpi' => 150]);



        if($ref) {
            $draft = Draft::where('id',$ref)->get();
            $draft[0]->raw = json_decode($draft[0]->raw_response);
            // dd($draft[0]->raw);
            view()->share('draft', $draft[0]);
            $pdf = PDF::loadView('pdf.view');

            return $pdf->download('feedback.pdf');
        } else {
            return redirect()->back()->with('error','Error generating the Pdf, there are no drafts for this document');
        }
    }

}
