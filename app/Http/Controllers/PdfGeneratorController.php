<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;


class PdfGeneratorController extends Controller
{

    public function pdfview (Request $request) {
        PDF::setOptions(['dpi' => 150]);
        $pdf = PDF::loadView('pdf.view');

        return $pdf->download('feedback.pdf');
    }

}
