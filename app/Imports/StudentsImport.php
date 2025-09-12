<?php

namespace App\Imports;

use Carbon\Carbon;
use App\Models\StudentMst;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentsImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        $classMap = [
            8 => 1,
            9 => 2,
            10 => 3,
            11 => 4,
            12 => 5,
        ];
        $classId = $classMap[$row['class']] ?? null;

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
        $sectionInput = strtoupper($row['section'] ?? '');
        $sectionId = $sectionMap[$sectionInput] ?? null;

        return new StudentMst([
            'stu_name'       => $row['name'],
            'stu_roll_number'=> $row['roll_number'] ?? null,
            'stu_gender'     => $row['gender'] ?? null,
            'stu_dob'        => $this->excelDateToMySQLDate($row['dob']) ?? null,
            'stu_fathername' => $row['father_name'] ?? null,
            'stu_classid'    => $classId ?? null,
            'stu_class'      => $row['class'] ?? null,
            'stu_sectionid'  => $sectionId ?? null,
            'stu_section'    => $row['section'] ?? null,
            'stu_scm_id'     => $row['scm_id'] ?? null,
            'stu_scm_udise'  => $row['scm_udise'] ?? null,  // ✅ fixed snake_case
            'stu_schoolname' => $row['school_name'] ?? null,
            'stu_distid'     => $row['dist_id'] ?? null,
            'stu_dist'       => $row['dist'] ?? null,
            'stu_blockid'    => $row['block_id'] ?? null,
            'stu_block'      => $row['block'] ?? null,
        ]);
    }
   private function excelDateToMySQLDate($value)
    {
        if (is_numeric($value)) {
            // Excel serial → UNIX timestamp → YYYY-MM-DD
            $timestamp = ($value - 25569) * 86400;
            return \Carbon\Carbon::createFromTimestamp($timestamp)->format('Y-m-d');
        } elseif ($value) {
            // Convert any date string to YYYY-MM-DD
            return \Carbon\Carbon::parse($value)->format('Y-m-d');
        }
        return null;
    }
}
// Excel column headings must match (name, roll_number, gender, etc.).