<?php

namespace App\Http\Controllers\Admin\hr;

use App\Http\Controllers\Controller;
use App\Models\hr\employee\Employee;
use Illuminate\Http\Request;

class MainHrController extends Controller
{
    // Fetch records
    public function getEmployees_2(Request $request)
    {
        $search = $request->search;

        if ($search == '') {
            $employees = Employee::orderby('name', 'asc')->select('id', 'name')->limit(5)->get();
        } else {
            $employees = Employee::orderby('name', 'asc')->select('id', 'name')->where('name', 'like', '%' . $search . '%')->limit(5)->get();
        }

        $response = array();
        foreach ($employees as $employee) {
            $response[] = array(
                "id" => $employee->id,
                "text" => $employee->name
            );
        }
        return response()->json($response);
    }

    public function getEmployees(Request $request)
    {
        $search = $request->input('search');
        $selectedId = $request->input('selectedId');
        $page = $request->input('page', 1);
        $limit = 10;
        $offset = ($page - 1) * $limit;

        $query = Employee::select('id', 'name');

        if (!empty($search)) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        $data = $query->limit($limit)
            ->offset($offset)
            ->get();
        foreach ($data as $item) {
            // Set 'selected' to true or false based on some condition (example: $item->id === $selectedId)
            $item->selected = $item->id === $selectedId; // Adjust the condition as per your logic
        }
        return response()->json(array('data'=>$data,'total'=>Employee::count()));
    }
}
