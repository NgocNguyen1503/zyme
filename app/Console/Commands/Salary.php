<?php

namespace App\Console\Commands;

use App\Models\File;
use App\Models\Salary as ModelsSalary;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class Salary extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:salary';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Calculate salary by month
        $timeNow = Carbon::now();
        $currentSalary = DB::table('salary')
            ->whereMonth('month', $timeNow)->get();
        $editors = User::with([
            'files' => function ($fileQuery) use ($timeNow) {
                return $fileQuery
                    ->where('status', File::STATUS_DONE);
            },
            'salary' => function ($salaryQuery) use ($timeNow) {
                return $salaryQuery->whereMonth('month', $timeNow);
            }
        ])->get();
        $arrSalary = [];
        foreach ($editors as $editor) {
            $tmpSalary = count($editor->files) * ModelsSalary::BASE_SALARY;
            // Check salary for month
            if (count($editor->salary) > 0) {
                // Update editor's existed current month's salary
                DB::table('salary')
                    ->where('id', $editor->salary[0]->id)
                    ->update([
                        'salary' => $tmpSalary,
                        'updated_at' => $timeNow
                    ]);
            } else {
                // Insert editor's current month salary
                $arrSalary[] = [
                    'user_id' => $editor->id,
                    'status' => ModelsSalary::STATUS_UN_PAID,
                    'salary' => $tmpSalary,
                    'month' => $timeNow,
                    'created_at' => $timeNow,
                    'updated_at' => $timeNow
                ];
            }
        }
        DB::table('salary')->insert($arrSalary);
        echo ("Update monthly salary successed!");
        return true;
    }
}
