<?php

namespace App\Http\Controllers;

use App\Exports\SalaryExport;
use App\Models\Salary;
use App\Models\File;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Maatwebsite\Excel\Facades\Excel;

class StatisticController extends Controller
{
    public function index(Request $request)
    {
        $param = $request->all();
        // Query get user and salary current month
        $timeNow = Carbon::now();
        $salaries = Salary::with(
            [
                'user',
                'user.files' => function ($fileQuery) {
                    return $fileQuery->where('status', File::STATUS_DONE);
                }
            ]
        )
            ->where(function ($query) use ($param, $timeNow) {
                // Return current month's salary
                if (!isset($param['date'])) {
                    return $query->whereMonth('month', $timeNow);
                }
                // Return selected month's salary
                $selectedMonth = Carbon::create($param['date']);
                return $query->whereMonth('month', $selectedMonth);
            })
            ->orderBy('id', 'DESC')
            ->get();
        foreach ($salaries as $salary) {
            $salary->txt_status = Salary::CONVERT_PAID_STATUS[$salary->status];
        }
        return view('admin.statistic.index', compact('salaries'));
    }

    public function paid(Request $request, $id)
    {
        $salary = Salary::find($id);
        $salary->status = Salary::STATUS_PAID;
        $salary->update();
        return redirect()->back();
    }

    public function export(Request $request)
    {
        $timeNow = Carbon::now();
        return Excel::download(new SalaryExport($timeNow), '給料.xlsx');
    }
}
