<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade as PDF;
use App\Draft;


class PdfGeneratorController extends Controller
{
    private $checks;

    public function __construct()
    {
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

    public function pdfview($ref = NULL)
    {
        PDF::setOptions(['dpi' => 96, 'defaultFont' => 'arial']);

        if ($ref) {
            $encoded_data = json_decode($ref);
            $document_id = $encoded_data->id / 123456; //document Id
            $id = Draft::where('document_id', $document_id)->get()->max('id');

            if (!$id) {
                return redirect()->back()->with('error', 'This document does not have any feedback associated with it.');
            }

            $draft = Draft::where('id', $id)->get();
            $draft[0]->raw = json_decode($draft[0]->raw_response);
            // dd($draft[0]->raw);
            $pdfOut = new \stdClass();
            $pdfOut->raw = $draft[0]->raw;
            $pdfOut->annotated = $this->getAnnotation($draft[0]->raw, $encoded_data->grammar);
            $pdfOut->name = $encoded_data->name;
            $pdfOut->grammar = strtoupper($encoded_data->grammar);
            $pdfOut->original = $draft[0]->text_input;
            $pdfOut->created_at = $draft[0]->created_at;

            //dd($pdfOut);

            view()->share('draft', $pdfOut);
            if ($encoded_data->grammar == 'analytical') {
                $pdf = PDF::loadView('pdf.analytical');
            }
            if ($encoded_data->grammar == 'reflective') {
                $pdf = PDF::loadView('pdf.reflective');
            }
            return $pdf->download('feedback.pdf');
        } else {
            return redirect()->back()->with('error', 'Error generating the Pdf, there are no drafts for this document');
        }
    }


    private function getAnnotation($feed, $grammar)
    {
        $txt = '';

        switch ($grammar) {
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
