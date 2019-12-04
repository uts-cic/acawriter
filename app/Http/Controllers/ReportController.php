<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class ReportController extends Controller
{
    //
    private $ui;

    public function __construct()
    {
        $this->middleware('auth');
        $this->ui = new \stdClass();
        $this->ui->general = new \stdClass();
        $this->ui->documents = new \stdClass();
    }

    public function index()
    {
        //$this->ui->general->total_users = User::all()->count();
        //$this->ui->general->m_new_users = User::whereRaw('created_at between  date_trunc(\'month\', current_date) and date_trunc(\'day\', current_date + INTERVAL \'1 day\' )')->get()->count();
        //$this->ui->general->m_feedback = Draft::whereRaw('created_at between  date_trunc(\'month\', current_date) and date_trunc(\'day\', current_date + INTERVAL \'1 day\' )')->get()->count();
        //$this->ui->general->m_user_activity = Activity::whereRaw('created_at between  date_trunc(\'month\', current_date) and date_trunc(\'day\', current_date + INTERVAL \'1 day\' )')->get()->count();
        return view('admin.report', ['data' => $this->ui]);
    }

    public function fetchDocs(Request $request)
    {

        if (!isset($request->assignment_code)) {
            return redirect()->back()->with('error', 'Please enter an assignment code');
        }
        //$this->ui->general->total_users = User::all()->count();

        //$this->ui->general->m_new_users = User::whereRaw('created_at between  date_trunc(\'month\', current_date) and date_trunc(\'day\', current_date + INTERVAL \'1 day\' )')->get()->count();
        //$this->ui->general->m_feedback = Draft::whereRaw('created_at between  date_trunc(\'month\', current_date) and date_trunc(\'day\', current_date + INTERVAL \'1 day\' )')->get()->count();
        //$this->ui->general->m_user_activity = Activity::whereRaw('created_at between  date_trunc(\'month\', current_date) and date_trunc(\'day\', current_date + INTERVAL \'1 day\' )')->get()->count();

        switch ($request->input('action')) {
            case 'show':
                $this->ui->documents->list = DB::table('documents')
                    ->select(DB::raw('
                                    (select count(id) from drafts drf where drf.document_id= documents.id) as dCount,
                                    documents.id as docId,
                                    users.id as uId,
                                    (select count(id) from text_drafts td where td.document_id= documents.id) as txtCount,
                                    users.email,
                                    documents.name,
                                    assignments.code
                                '))
                    ->join('assignments', 'assignments.id', '=', 'documents.assignment_id')
                    ->leftJoin('drafts', 'drafts.document_id', '=', 'documents.id')
                    ->leftJoin('text_drafts', 'text_drafts.document_id', '=', 'documents.id')
                    ->join('users', 'users.id', '=', 'drafts.user_id')
                    ->where('assignments.code', '=', $request->assignment_code)
                    ->groupBy('documents.id', 'assignments.code', 'users.id')
                    ->get();

                // echo DB::table('documents')
                //     ->select(DB::raw('
                //                     (select count(id) from drafts drf where drf.document_id= documents.id) as dCount,
                //                     documents.id as docId,
                //                     users.id as uId,
                //                     (select count(id) from text_drafts td where td.document_id= documents.id) as txtCount,
                //                     users.email,
                //                     documents.name,
                //                     assignments.code
                //                 '))
                //     ->join('assignments', 'assignments.id', '=', 'documents.assignment_id')
                //     ->join('drafts', 'drafts.document_id', '=', 'documents.id')
                //     ->join('text_drafts', 'text_drafts.document_id', '=', 'documents.id')
                //     ->join('users', 'users.id', '=', 'drafts.user_id')
                //     ->where('assignments.code', '=', $request->assignment_code)
                //     ->groupBy('documents.id', 'assignments.code', 'users.id')
                //     ->toSql();

                return view('admin.report', ['data' => $this->ui]);
                break;

            case 'download_feed':
                // download all feedback with the code
                $dump = array();
                $help = new \stdClass();
                $help->code = $request->assignment_code;
                $help->which = 'feed';
                $data = $this->getExportData($help);
                if (count($data) === 0) return redirect()->back()->with('error', 'No records found');
                $filename = 'feedback_dump_all';
                $columns = array('email', 'original', 'feedback', 'created_at');

                //make the data ready for csv aka convert them to array
                foreach ($data as $row) {
                    $dump[] = array($row->email, $row->text_input, $row->raw_response, $row->created_at);
                }
                return $this->downloadCSV($filename, $dump, $columns);
                break;

            case 'download_text':
                $dump = array();
                $help = new \stdClass();
                $help->code = $request->assignment_code;
                $help->which = 'txt';
                $data = $this->getExportData($help);
                if (count($data) === 0) return redirect()->back()->with('error', 'No records found');
                $filename = 'text_drafts_dump_all';
                $columns = array('email', 'original', 'created_at');

                //make the data ready for csv aka convert them to array
                foreach ($data as $row) {
                    $dump[] = array($row->email, $row->text_input, $row->created_at);
                }
                return $this->downloadCSV($filename, $dump, $columns);
                break;
        }
    }

    /**
     * @param Request $request
     * $request->type -- always csv (for now)
     * $request->what -- feedback or text
     * $request->did -- document_id (applicable when export is per student)
     * $request->uid -- users_id (applicable when export is per student)
     * @return csv download or any other export type as defined
     */
    public function export(Request $request)
    {
        $filename = '';
        if (!isset($docId) || !isset($uid)) {
            if (isset($request->type)) {
                if ($request->type === 'csv') {
                    $dump = array();
                    $columns = array();
                    $data = $this->getExportData($request);
                    if ($request->what === 'feed') {
                        $filename = 'feedback_dump_student';
                        $columns = array('email', 'original', 'feedback', 'created_at');

                        //make the data ready for csv aka convert them to array
                        foreach ($data as $row) {
                            $dump[] = array($row->email, $row->text_input, $row->raw_response, $row->created_at);
                        }
                    } elseif ($request->what === 'txt') {
                        $filename = 'text_drafts_dump_student';
                        $columns = array('email', 'original', 'created_at');

                        //make the data ready for csv aka convert them to array
                        foreach ($data as $row) {
                            $dump[] = array($row->email, $row->text_input, $row->created_at);
                        }
                    }

                    return $this->downloadCSV($filename, $dump, $columns);
                }
            }
        }
    }

    private function downloadCSV($filename, $data, $columns)
    {
        $callback = function () use ($data, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns, "\t");

            foreach ($data as $row) {
                fputcsv($file, $row, "\t");
            }
            fclose($file);
        };

        $headers = [
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0', 'Content-type' => 'text/csv', 'Content-Disposition' => 'attachment; filename=' . $filename . '.csv', 'Expires' => '0', 'Pragma' => 'public'
        ];

        return Response::stream($callback, 200, $headers);
    }


    /**
     * @param $data
     *      optional $data->did - student level
     *      optional $data->uid - student level
     *      optional $data->code - for all that have this code
     * @return array
     *
     */
    private function getExportData($data)
    {
        $list = array();
        if (isset($data->did) && isset($data->uid)) {
            $docId = $data->did;
            $uid = $data->uid;
            if ($data->what === 'feed') {
                $list = DB::table('drafts')
                    ->select('users.email', 'drafts.text_input', 'drafts.raw_response', 'drafts.created_at')
                    ->join('users', 'users.id', '=', 'drafts.user_id')
                    ->where('drafts.document_id', '=', $docId)
                    ->where('drafts.user_id', '=', $uid)
                    ->get();
            } elseif ($data->what === 'txt') {
                $list = DB::table('text_drafts')
                    ->select('users.email', 'text_drafts.text_input', 'text_drafts.created_at')
                    ->join('users', 'users.id', '=', 'text_drafts.user_id')
                    ->where('text_drafts.document_id', '=', $docId)
                    ->where('text_drafts.user_id', '=', $uid)
                    ->get();
            }
        } elseif (isset($data->code)) {
            if ($data->which === 'feed') {
                $list = DB::table('drafts')
                    ->select('users.email', 'drafts.text_input', 'drafts.raw_response', 'drafts.created_at')
                    ->join('users', 'drafts.user_id', '=', 'users.id')
                    ->join('documents', 'documents.id', '=', 'drafts.document_id')
                    ->join('assignments', 'assignments.id', '=', 'documents.assignment_id')
                    ->where('assignments.code', '=', $data->code)
                    ->get();
            } elseif ($data->which === 'txt') {
                $list = DB::table('text_drafts')
                    ->select('users.email', 'text_drafts.text_input', 'text_drafts.created_at')
                    ->join('users', 'text_drafts.user_id', '=', 'users.id')
                    ->join('documents', 'documents.id', '=', 'text_drafts.document_id')
                    ->join('assignments', 'assignments.id', '=', 'documents.assignment_id')
                    ->where('assignments.code', '=', $data->code)
                    ->get();
            }
        }
        return $list;
    }
}
