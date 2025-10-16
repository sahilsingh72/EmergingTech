<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\StudentMst;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentsImport implements ToCollection
{
    protected $districtId;
    protected $school;

    public function __construct($districtId, $school)
    {
        $this->districtId = $districtId;
        $this->school = $school;
    }

    public function collection(Collection $rows)
    {
        // Skip header row
        $rows->shift();

        $classMap = [
            8  => 1,
            9  => 2,
            10 => 3,
            11 => 4,
            12 => 5
        ];

        $sectionMap = [
            'A' => 1,
            'B' => 2,
            'C' => 3,
            'D' => 4,
            'E' => 5,
            'F' => 6,
            'G' => 7,
            'H' => 8,
            'I' => 9,
            'J' => 10,
            'K' => 11,
        ];
        
        foreach ($rows as $row) {
            if (!$row[0]) continue; // skip empty rows

            $classNumber = $row[5]; // stu_class column from Excel (like 8, 9, 10)
            $sectionLetter = strtoupper($row[6]); // stu_section column from Excel (like A, B, C)
            $classId = $classMap[$classNumber] ?? null;
            $sectionId = $sectionMap[$sectionLetter] ?? null;

            StudentMst::create([
                'stu_name'         => $row[0],
                'stu_roll_number'  => $row[1],
                'stu_gender'       => $row[2],
                'stu_dob'          => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[3])->format('Y-m-d'),
                'stu_fathername'   => $row[4],
                'stu_classid'      => $classId,
                'stu_class'        => $classNumber,
                'stu_sectionid'    => $sectionId,
                'stu_section'      => $sectionLetter,
                'stu_scm_id'       => $this->school->scm_id,
                'stu_scm_udise'    => $this->school->scm_udise_code,
                'stu_schoolname'   => $this->school->scm_name,
                'stu_distid'       => $this->districtId,
                'stu_address'      => $row[7] ?? '',
            ]);
        }
    }
}