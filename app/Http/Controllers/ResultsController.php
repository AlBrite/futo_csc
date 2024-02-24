<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Imports\ResultsImport;
use Maatwebsite\Excel\Facades\Excel;
use App\Models\Result;
use App\Http\Controllers\Controller;

class ResultsController extends Controller
{

    public function uploadExcel(Request $request)
    {
        $matcher = [
            'Reg No.' => 'reg_no',
            'LAB' => 'lab',
            'TEST' => 'test',
            'EXAM' => 'exam',
            'TOTAL' => 'score'
        ];

        $request->validate([
            "level" => "required",
            "semester" => "required",
            "course" => "required",
            "session" => "required"
        ]);
        $level = $request->level;
        $semester = $request->semester;
        $course = $request->course;
        $session = $request->session;


        $file = $request->file('result');
        
        
        $data = Excel::toArray([], $file);

        
        // Store the data in the database
        // For example:
        $n = 0;
        

        $results = [];

        $foundRow = false;
        $retrieveColumns = [];
        
        foreach ($data[0] as $rowNumber => $row) {


            foreach($row as $col) {
                if (array_key_exists($col, $matcher)) {
                    foreach($row as $n => $column) {
                        if (!array_key_exists($column, $matcher)) {
                            continue;
                        }
                        $retrieveColumns[$n] = $matcher[$column];
                    }
                    $foundRow = $rowNumber;
                    break;
                }
            }
        
        }

        if ($foundRow === false) {
            return redirect()->back()->with('error', 'Failed to scan results');
        }
        
        
        $results = array_splice($data[0], $foundRow + 2);

        
        $newResult = [];

        
        foreach($results as $result) {
            $ResultDB = new Result();
            foreach($retrieveColumns as $index => $retrieved) {
                $newResult[$retrieved] = $result[$index];
                $ResultDB->{$retrieved} = $result[$index];
                $ResultDB->level = $level;
                $ResultDB->semester = $semester;
                $ResultDB->course_id = $course;
                $ResultDB->session = $session; 
            }
            // Store into the database table Results 
            $ResultDB->save();
        }


            
        return redirect()->back()->with('success', count($results).' results uploaded and processed successfully');
    }

    public function import(Request $request) 
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls',
        ]);

        Excel::import(new ResultsImport, $request->file('file'));

        return redirect()->back()->with('success','Results imported successfully.');
    }

    public function save_results(Request $request) {
        $request->validate([
            'results' => 'required',
            'course_id' => 'required',
        ]);
        try {

            $results = $request->results;
            $course_id = $request->course_id;
            $extracts = ['exam', 'score', 'lab', 'reg_no'];

            foreach($results as $n => $result) {
                $queue = new Result();
                foreach($extracts as $extract) {
                    $queue->$extract = $result[$extract];
                }
                $queue->course_id = $course_id;
                $queue->uploaded_by = auth()->id();
                $queue->save();
            }
            
            return response([
                'message' => 'Results uploaded successfully'
            ]); 
        } catch(\Exception $e) {
            return response([
                'message' => $e->getMessage().'Failed to upload results'
            ], 401);
        }
    }
}
