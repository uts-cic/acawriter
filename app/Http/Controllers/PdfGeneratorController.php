<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade as PDF;
use Auth;
use App\Document;
use App\Draft;

class PdfGeneratorController extends Controller
{
    private $checks;

    public function __construct()
    {
        $this->middleware(['auth', 'can:export-pdf']);

        $this->checks = new \stdClass();
        $this->checks->reflective = new \stdClass();
        $this->checks->analytical = new \stdClass();

        //reflective def's
        $this->checks->reflective->icons = array(
            "context",
            "challenge",
            "change",
            "metrics"
        );

        // AI/2019-06-25: Removing affect analysis
        //affect tag validity criteria
        // $this->checks->reflective->affect_validity = array(
        //     "context",
        //     "challenge",
        //     "change"
        // );

        //inline text replacement tags
        $this->checks->reflective->inlineTxt = array(
            // AI/2019-06-25: Removing affect analysis
            // "affect"    => "affect",
            "epistemic" => "epistemic",
            "modal"     => "modall"
        );

        //analytical defs
        $this->checks->analytical->analytic_xlator = array(
            "metrics" => "metrics",
            "emph" =>  "E",
            "grow" =>  "T",
            "contrast" => "C",
            "contribution" => "S",
            "nostat" => "Q",
            "tempstat" => "B",
            "novstat" => "N",
            "surprise" => "S",
            "attitude" => "P"
        );
        $this->checks->analytical->moves_css = array(
            "moves1",
            "moves2",
            "moves3",
            "moves4"
        );
    }

    public function pdfview($code = NULL)
    {
        $user_id = Auth::user()->id;
        $document = Document::where('slug', '=', $code)
            ->where('user_id', $user_id)
            ->first();

        $id = $document ? Draft::where('document_id', $document->id)->get()->max('id') : null;

        if ($id) {
            $draft = Draft::with('feature')->find($id);
            $grammar = $draft->feature->grammar;
            $raw = json_decode($draft->raw_response);

            $pdfOut = new \stdClass();
            $pdfOut->raw = $raw;
            $pdfOut->annotated = $this->getAnnotation($raw, $grammar);
            $pdfOut->name = $document->name;
            $pdfOut->grammar = $grammar;
            $pdfOut->original = $draft->text_input;
            $pdfOut->created_at = $draft->created_at;

            view()->share('draft', $pdfOut);

            PDF::setOptions(['dpi' => 96, 'defaultFont' => 'arial']);

            switch (strtolower($grammar)) {
                case 'reflective':
                    $pdf = PDF::loadView('pdf.reflective');
                    break;
                case 'analytical':
                    $pdf = PDF::loadView('pdf.analytical');
                    break;
            }
        }

        if (!isset($pdf)) {
            return redirect('/analyse/' . $code)->withError('This document does not have any feedback associated with it.');
        }
        return $pdf->download('feedback.pdf');
    }


    private function getAnnotation($feed, $grammar)
    {
        $txt = '';

        switch (strtolower($grammar)) {
            case 'reflective':
                $inlineClass = "";
                $inlineText = "";

                foreach ($feed->final as $ref) {
                    $inlineText = $this->fabricateInlineText($ref);
                    if (count($ref->css) > 0) {
                        foreach ($ref->css as $style) {
                            if (in_array($style, $this->checks->reflective->icons)) {
                                $txt .= "<span class=\"std" . $style . " " . $style . "\"></span>";
                            }
                            $inlineClass = ($style === "link2me") ? "link2me" : "";
                        }
                    }
                    $txt .= "<span class=\"" . $inlineClass . "\">" . $inlineText . "</span>";
                }
                break;
            case 'analytical':
                $spanClass = '';
                foreach ($feed->final as $ann) {
                    $spanClass = $this->fabricateInlineClass($ann);
                    if (count($ann->css) > 0) {
                        foreach ($ann->css as $style) {
                            $legend = isset($this->checks->analytical->analytic_xlator[$style]) ? $this->checks->analytical->analytic_xlator[$style] : '';
                            if (in_array($style, array('contribution'))) $txt .= "<span class=\"badge badge-pill badge-analytic-green " . $style . "\">" . $legend . "</span>";
                            elseif (in_array($style, array('background', 'metrics'))) $txt .= "<span class=\"" . $style . "\"></span>";
                            else $txt .= "<span class=\"badge badge-pill badge-analytic " . $style . "\">" . $legend . "</span>";
                        }
                        // $txt .= "<span class=\".$spanClass\">" . $ann->str . "</span>";
                    }
                    $txt .= "<span class=\"" . $spanClass . "\">" . $ann->str . "</span>";
                }
                break;
        }

        return $txt;
    }


    private function fabricateInlineText($feed)
    {
        $html = '';

        if ($feed->str != "" && isset($feed->expression)) {
            $html = $feed->str;

            foreach ($this->checks->reflective->inlineTxt as $key => $value) {
                // AI/2019-06-25: Removing affect analysis
                // if (
                //     $key === 'affect' &&
                //     count($feed->expression->{$key}) > 0 &&
                //     count(array_intersect($this->checks->reflective->affect_validity, $feed->css)) > 0
                // ) {
                //     foreach ($feed->expression->{$key} as $word) {
                //         $html = str_replace($word->text, "<span class='std" . $value . " " . $value . "'>$word->text</span>", $html);
                //     }
                // } else {
                    foreach ($feed->expression->{$key} as $word) {
                        $html = str_replace($word->text, "<span class='std" . $value . " " . $value . "'>$word->text</span>", $html);
                    }
                // }
            }
        }
        return $html;
    }

    private function fabricateInlineClass($feed)
    {
        $name = '';
        $css = [];

        if (count($feed->css) == 0) {
            return $name;
        }

        foreach ($feed as $key => $value) {
            //loop to check if moves present in the array
            if (in_array($key, $this->checks->analytical->moves_css)) {
                if (count($feed->{$key}->css) > 0) $css[] = $key;
            }
        }

        if (in_array('moves4', $css)) {
            $name = "moves4";
        } elseif (in_array('moves3', $css)) {
            $name = "moves3";
        } elseif (in_array('moves2', $css)) {
            $name = "moves2";
        } elseif (in_array('moves1', $css)) {
            $name = "moves1";
        } else {
            if (count($feed->css) === 1 && in_array('metrics', $feed->css)) return '';
            elseif (in_array('contribution', $feed->css)) $name = 'ana_bg_green';
            else $name =  'ana_bg_yellow';
        }

        return $name;
    }
}
