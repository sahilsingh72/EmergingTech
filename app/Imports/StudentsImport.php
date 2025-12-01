<?php

namespace App\Imports;

use App\Models\StudentMst;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Illuminate\Validation\ValidationException;

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
        $rows->shift(); // Skip header row

        // Limit rows to 130 students
        $filteredRows = $rows->filter(function ($row) {
            $values = collect($row)->map(fn($cell) => trim((string)$cell));
            return $values->filter()->isNotEmpty(); // keep only non-empty rows
        });

        // Count existing students in DB for this school
        $existingCount = StudentMst::where('stu_scm_id', $this->school->scm_id)->count();

        // Total after upload
        $totalAfterUpload = $existingCount + $filteredRows->count();

        if ($totalAfterUpload > 130) {
            throw ValidationException::withMessages([
                'excel' => "A school can have a maximum of 130 students. 
                            Already you added {$existingCount} students.  
                            In your Excel {$filteredRows->count()} students.  
                            Total would be {$totalAfterUpload}, which exceeds the limit."
            ]);
        }

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
        
        foreach ($filteredRows as $index => $row) {
            $rowNumber = $index + 2; // Excel row number

            try {
                $name   = trim($row[0] ?? '');
                $gender = strtoupper(trim($row[1] ?? ''));
                $father = trim($row[2] ?? '');
                $class  = trim($row[3] ?? '');
                
                 if ($name === '') {
                    throw new \Exception("Row $rowNumber: Student Name is missing.");
                }

                if ($gender === '') {
                    throw new \Exception("Row $rowNumber: Gender is missing.");
                }

                // VALID GENDER VALUES
                $allowedGender = ['M', 'MALE', 'F', 'FEMALE', 'O', 'OTHER'];

                if (!in_array($gender, $allowedGender)) {
                    throw new \Exception("Row $rowNumber: Invalid Gender '$gender'. Allowed: Male, Female, Other.");
                }

                if ($father === '') {
                    throw new \Exception("Row $rowNumber: Father's Name is missing.");
                }

                if (!isset($classMap[$class])) {
                    throw new \Exception("Row $rowNumber: Invalid Class '$class'. Allowed: 8, 9, 10, 11, 12.");
                }

                if (in_array($gender, ['M', 'MALE']))  $gender = 'M';
                if (in_array($gender, ['F', 'FEMALE'])) $gender = 'F';
                if (in_array($gender, ['O', 'OTHER']))  $gender = 'O';

                StudentMst::create([
                    'stu_name'         => $name,
                    // 'stu_roll_number'  => $row[1],
                    'stu_gender'       => $gender,
                    // 'stu_dob'          => \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[3])->format('Y-m-d'),
                    'stu_fathername'   => $father,
                    'stu_classid'      => $classMap[$class],
                    'stu_class'        => $class,
                    // 'stu_sectionid'    => $sectionId,
                    // 'stu_section'      => $sectionLetter,
                    'stu_scm_id'       => $this->school->scm_id,
                    'stu_scm_udise'    => $this->school->scm_udise_code,
                    'stu_schoolname'   => $this->school->scm_name,
                    'stu_distid'       => $this->districtId,
                    // 'stu_address'      => $row[7] ?? '',
                ]);
            } catch (\Exception $e) {
                // THROW CLEAN MESSAGE – NOT SQL ERROR
                throw ValidationException::withMessages([
                    'excel' => $e->getMessage()
                ]);
            }
        }
    }
}