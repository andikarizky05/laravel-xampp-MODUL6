<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\Employee;
use App\Models\Position;


class EmployeeController extends Controller
{

    public function index()
    {
        $pageTitle = 'Employee List';

        // ELOQUENT
        $employees = Employee::all();

        return view('employee.index', [
            'pageTitle' => $pageTitle,
            'employees' => $employees
        ]);
    }


    // RAW SQL QUERY
/*
        $employees = DB::select('
        select *, employees.id as employee_id, positions.name as position_name
        from employees
        left join positions on employees.position_id = positions.id
    ');

    return view('employee.index', [
        'pageTitle' => $pageTitle,
        'employees' => $employees
        ]);}
*/
    // QUERY BUILDER
    /*
    $employees = DB::table('employees')
                    ->leftJoin('positions', 'employees.position_id', '=', 'positions.id')
                    ->select('employees.*', 'positions.name as position_name', 'employees.id as employee_id')
                    ->get();

    return view('employee.index', [
        'pageTitle' => $pageTitle,
        'employees' => $employees
    ]);
}
*/


    public function create()
    {
        $pageTitle = 'Create Employee';

        // ELOQUENT
        $positions = Position::all();

        return view('employee.create', compact('pageTitle', 'positions'));
    }

    public function store(Request $request)
    {
        $messages = [
            'required' => ':Attribute harus diisi.',
            'email' => 'Isi :attribute dengan format yang benar',
            'numeric' => 'Isi :attribute dengan angka'
        ];

        $validator = Validator::make($request->all(), [
            'firstName' => 'required',
            'lastName' => 'required',
            'email' => 'required|email',
            'age' => 'required|numeric',
        ], $messages);

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        // ELOQUENT
        $employee = new Employee;
        $employee->firstname = $request->firstName;
        $employee->lastname = $request->lastName;
        $employee->email = $request->email;
        $employee->age = $request->age;
        $employee->position_id = $request->position;
        $employee->save();

        return redirect()->route('employees.index');
    }



    /* public function store(Request $request)
     {
         $messages = [
             'required' => ':Attribute harus diisi.',
             'email' => 'Isi :attribute dengan format yang benar',
             'numeric' => 'Isi :attribute dengan angka'
         ];

         $validator = Validator::make($request->all(), [
             'firstName' => 'required',
             'lastName' => 'required',
             'email' => 'required|email',
             'age' => 'required|numeric',
         ], $messages);

         if ($validator->fails()) {
             return redirect()->back()->withErrors($validator)->withInput();
         }


         // QUERY BUILDER
         DB::table('employees')->insert([
             'firstname' => $request->firstName,
             'lastname' => $request->lastName,
             'email' => $request->email,
             'age' => $request->age,
             'position_id' => $request->position,
         ]);

         return redirect()->route('employees.index');
     }
     */

    public function show(string $id)
    {
        $pageTitle = 'Employee Detail';
        // ELOQUENT
        $employee = Employee::find($id);
        return view('employee.show', compact('pageTitle', 'employee'));
    }

    // RAW SQL QUERY
    /*
    $employee = collect(DB::select('
        select *, employees.id as employee_id, positions.name as position_name
        from employees
        left join positions on employees.position_id = positions.id
        where employees.id = ?
    ', [$id]))->first();
    */
    // QUERY BUILDER
    /*
    $employee = DB::table('employees')
        ->leftJoin('positions', 'employees.position_id', '=', 'positions.id')
        ->select('employees.*', 'positions.name as position_name', 'employees.id as employee_id')
        ->where('employees.id', $id)
        ->first();


    return view('employee.show', compact('pageTitle', 'employee'));
}
*/
    public function edit(string $id)
    {
        $pageTitle = 'Edit Employee';
        // ELOQUENT
        $positions = Position::all();
        $employee = Employee::find($id);

        return view('employee.edit', compact(
            'pageTitle',
            'positions',
            'employee'
        ));
    }
    public function update(Request $request, string $id)
    {
        $messages = [
            'required' => ':Attribute harus diisi.',
            'email' => 'Isi :attribute dengan format yang benar',
            'numeric' => 'Isi :attribute dengan angka'
        ];
        $validator = Validator::make($request->all(), [
            'firstName' => 'required',
            'lastName' => 'required',
            'email' => 'required|email',
            'age' => 'required|numeric',
            'position' => 'required',
        ], $messages);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }
        // ELOQUENT
        $employee = Employee::find($id);
        $employee->firstname = $request->firstName;
        $employee->lastname = $request->lastName;
        $employee->email = $request->email;
        $employee->age = $request->age;
        $employee->position_id = $request->position;
        $employee->save();

        return redirect()->route('employees.index');
    }

    /*
        public function edit($id)
        {
            $employee = DB::table('employees')
                ->select('employees.*', 'positions.name as position_name')
                ->join('positions', 'employees.position_id', '=', 'positions.id')
                ->where('employees.id', $id)
                ->first();

            if (!$employee) {
                return redirect()->route('employees.index')->with('error', 'Employee not found.');
            }

            $pageTitle = 'Edit Employee';
            $positions = DB::table('positions')->get();

            return view('employee.edit', compact('employee', 'pageTitle', 'positions'));
        }

        public function update(Request $request, $id)
        {
            $validator = Validator::make($request->all(), [
                'firstName' => 'required',
                'lastName' => 'required',
                'email' => 'required|email',
                'age' => 'required|numeric',
                'position' => 'required',
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            // QUERY BUILDER
            DB::table('employees')
                ->where('id', $id)
                ->update([
                    'firstname' => $request->firstName,
                    'lastname' => $request->lastName,
                    'email' => $request->email,
                    'age' => $request->age,
                    'position_id' => $request->position,
                ]);

            return redirect()->route('employees.index')->with('success', 'Employee updated successfully');
        }
        */

        public function destroy(string $id)
        {
        // ELOQUENT
        Employee::find($id)->delete();

        return redirect()->route('employees.index');
        }
    }

    /*public function destroy(string $id)
    {
        // QUERY BUILDER
        DB::table('employees')
            ->where('id', $id)
            ->delete();

        return redirect()->route('employees.index');
    }


*/