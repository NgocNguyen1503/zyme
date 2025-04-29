<?php

namespace App\Exports;

use App\Models\File;
use App\Models\Salary;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SalaryExport implements FromCollection, WithHeadings, WithStyles
{
    private $month;

    public function __construct($month)
    {
        $this->month = $month;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        $exportData = [];
        // Du lieu dau ra o day se phai khop vs headings
        $salaries = Salary::with([
            'user',
            'user.files' => function ($fileQuery) {
                return $fileQuery->where('status', File::STATUS_DONE);
            }
        ])->whereMonth('month', $this->month)
            ->orderBy('id', 'DESC')->get();
        foreach ($salaries as $salary) {
            $salary->txt_status = Salary::CONVERT_PAID_STATUS[$salary->status];
            $salaryTmp = "0";
            if ($salary->salary != 0) {
                $salaryTmp = $salary->salary;
            }
            $exportData[] = [
                $salary->id,
                $salary->user->name,
                $salary->user->email,
                count($salary->user->files),
                Salary::CONVERT_PAID_STATUS[$salary->status],
                $salaryTmp
            ];
        }
        return collect($exportData);
    }

    public function headings(): array
    {
        return [
            '#',
            '名前',
            'メール',
            '完了',
            '支払い状況',
            '給料'
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => [
                    'color' => ['rgb' => 'FFFFFF'],
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '0000FF']
                ]
            ]
        ];
    }
}