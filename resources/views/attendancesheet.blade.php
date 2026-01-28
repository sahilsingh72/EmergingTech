@include('components.navbar')
@include('components.sidebar')
<style>
    #presentCounter {
        transition: all 0.25s ease-in-out;
    }

    #presentCounter.updated {
        background-color: #d1e9ff !important;
        transform: scale(1.05);
    }
</style>

<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">Student Attendance</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Student Attendance</a></li>
                                <li class="breadcrumb-item active">Training Evidences</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content relative">
                <div class="container-fluid py-12">
                    <div class="max-w-8xl mx-auto space-y-6">
                        <div class="p-3 sm:p-8 bg-white shadow sm:rounded-lg">
                            <div class="bg-white rounded-lg w-full">
                                <h2 class="text-2xl font-semibold text-center mb-6">Student Attendance Sheet</h2>


                                <!-- Student Table -->
                                <div class="flex justify-between items-center mb-4">
                                    <!-- Rows per page -->
                                    <div>
                                        <label for="rowsPerPage" class="mr-2">Shows:</label>
                                        <select id="rowsPerPage" class="border rounded  pl-2 pr-5">
                                            <option value="5">5</option>
                                            <option value="10" selected>10</option>
                                            <option value="25">25</option>
                                            <option value="50">50</option>
                                            <option value="100">100</option>
                                        </select>
                                    </div>

                                    <!-- Search -->
                                    <div>
                                        <input type="text" id="searchInput" placeholder="Search..."
                                            class="border rounded p-2 w-40">
                                    </div>
                                </div>
                                    
                                    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 mb-2">
                                        @php
                                            $roleId = Auth::user()->role_id;
                                        @endphp
                                        @if($roleId == 1 || $roleId == 2 || $roleId == 8)

                                            <div class="flex flex-col sm:flex-row gap-3 w-full">
                                                {{-- District --}}
                                                <div class="flex items-center gap-2 w-full">
                                                    <label class="font-semibold text-gray-700">District</label>
                                                    <select id="filterDistrict" class="border rounded p-2 w-full">
                                                        <option value="">-- Select District --</option>
                                                        @foreach($districts as $district)
                                                            <option value="{{ $district->DSM_DSCD }}">
                                                                {{ $district->DSM_DSNM }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                {{-- School --}}
                                                <div class="flex items-center gap-2 w-full">
                                                    <label class="font-semibold text-gray-700">School</label>
                                                    <select id="filterSchool" class="border rounded p-2 w-full">
                                                        <option value="">-- Select School --</option>
                                                    </select>
                                                </div>
                                            </div>

                                        @else

                                            <div class="flex flex-col sm:flex-row sm:items-center gap-2 w-full sm:w-auto">
                                                <label for="filterSchool" class="font-semibold text-gray-700 whitespace-nowrap">Select School:</label>
                                                <select id="filterSchool" class="border rounded p-2 w-full">
                                                    <option value="">-- Select School --</option>
                                                    @foreach ($schools as $school)
                                                        <option value="{{ $school->scm_id }}" 
                                                            {{ isset($schoolId) && $schoolId == $school->scm_id ? 'selected' : '' }}>
                                                            {{ $school->scm_name }} ({{ $school->scm_udise_code }})
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        @endif

                                        <div class="w-full sm:w-auto">
                                            <button onclick="exportAttendance()" class="btn btn-success w-full sm:w-40 text-center">
                                                Export Attendance
                                            </button>
                                        </div>
                                    </div>

                                    <div class="bg-white shadow rounded-lg p-4 overflow-x-auto">
                                        @php
                                            $roleId = Auth::user()->role_id;
                                        @endphp
                                        @if($roleId == 3 || $roleId == 6)
                                            <div class="w-full flex justify-end mb-2">
                                                <div id="presentCounter" class="inline-flex items-center bg-blue-100 text-blue-700 px-3 py-1 rounded-full 
                                                    font-semibold text-sm shadow-sm">
                                                    <span id="presentIcon" class="mr-1">✔</span>
                                                    Present: 0 / 120
                                                </div>
                                            </div>
                                        @endif
                                        <table id="studentTable" class="w-full border-collapse">
                                            <thead class="bg-gray-100">
                                                <tr>
                                                    <th class="border px-4 py-2 text-left cursor-pointer sort" data-column="0">S.No</th>
                                                    <th class="border px-4 py-2 text-left cursor-pointer sort" data-column="1">Student Name</th>
                                                    <th class="border px-4 py-2 text-left cursor-pointer sort" data-column="3">Class</th>
                                                    <th class="border px-4 py-2 text-left cursor-pointer sort" data-column="5">Gender</th>
                                                    <th class="border px-4 py-2 text-left cursor-pointer sort" data-column="7">Father's Name</th>
                                                    <th class="border py-2 text-center">Attendance
                                                        @php
                                                            $roleId = Auth::user()->role_id;
                                                        @endphp
                                                        @if($roleId == 3 || $roleId == 6)
                                                            <button id="toggleAllAttendance" class="btn btn-primary ml-1" data-mode="absent">
                                                                Mark All
                                                            </button>
                                                        @endif
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @forelse($students as $index => $student)
                                                    <tr class="hover:bg-gray-50">
                                                        <td class="border px-4 py-2 text-center">{{ $index + 1 }}</td>
                                                        <td class="border px-4 py-2">{{ $student->stu_name }}</td>
                                                        <td class="border px-4 py-2">{{ $student->stu_class }}</td>
                                                        <td class="border px-4 py-2">{{ $student->stu_gender }}</td>
                                                        <td class="border px-4 py-2">{{ $student->stu_fathername }}</td>
                                                        <td class="border px-4 py-2 text-center">
                                                            @php
                                                                $roleId = Auth::user()->role_id;
                                                            @endphp
                                                            @if($roleId == 3 || $roleId == 6)
                                                                <button
                                                                    class="attendance-btn {{ $student->attendance == 1 ? 'bg-green-500 hover:bg-green-600' : 'bg-red-500 hover:bg-red-600' }} text-white text-sm px-3 py-1 rounded"
                                                                    data-id="{{ $student->stu_id }}" data-status="{{ $student->attendance }}">
                                                                    {{ $student->attendance == 1 ? 'Present' : 'Absent' }}
                                                                </button>

                                                            @else
                                                                <!-- VIEW ONLY (OKCL or others) -->
                                                                <span class="
                                                                    px-3 py-1 rounded text-white text-sm
                                                                    {{ $student->attendance == 1 ? 'bg-green-500' : 'bg-red-500' }}
                                                                    ">
                                                                    {{ $student->attendance == 1 ? 'Present' : 'Absent' }}
                                                                </span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="13" class="text-center py-4">Please select school for students
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                        @php
                                            $roleId = Auth::user()->role_id;
                                        @endphp
                                        @if($roleId == 3 || $roleId == 6)
                                            <hr class="mt-4 border-none h-1 bg-gray-200">
                                            <div class="text-center mt-4">
                                                <button id="saveAttendance" class="btn btn-success px-5 py-2 text-lg font-semibold">
                                                    Save Attendance
                                                </button>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div id="pagination" class="flex justify-center space-x-2 mt-4"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
    <div id="limitPopup"
        style="display:none; position:fixed; top:20px; right:20px; background:#ff4d4d; 
                color:white; padding:12px 18px; border-radius:8px; font-weight:600;
                box-shadow:0 4px 10px rgba(0,0,0,0.2); z-index:9999;">
        Maximum 120 students can be marked Present.
    </div>

<script>
    $(document).ready(function () {
        $("#filterSchool").on("change", function () {
            const schoolId = $(this).val();

            $.ajax({
                url: "{{ route('student.attendance.sheet') }}", // your route name
                method: "GET",
                data: { school_id: schoolId },
                success: function (response) {
                    const html = $(response).find("#studentTable tbody").html();
                    $("#studentTable tbody").html(html);

                    updatePresentCounter();
                },
                error: function () {
                    alert("Failed to fetch students.");
                }
            });
        });
    });
</script>
<script>
    $(document).ready(function () {
        let rowsPerPage = parseInt($("#rowsPerPage").val());
        let currentPage = 1;
        let sortDirection = {}; // keep track of each column's sorting state

        function renderTable() {
            let searchText = $("#searchInput").val().toLowerCase();
            let rows = $("#studentTable tbody tr");

            // Filter rows
            rows.each(function () {
                let rowText = $(this).text().toLowerCase();
                $(this).toggle(rowText.indexOf(searchText) > -1);
            });

            // Pagination
            let visibleRows = rows.filter(":visible");
            let totalRows = visibleRows.length;
            let totalPages = Math.ceil(totalRows / rowsPerPage);

            visibleRows.hide();
            let start = (currentPage - 1) * rowsPerPage;
            let end = start + rowsPerPage;
            visibleRows.slice(start, end).show();

            // Render pagination buttons
            let pagination = $("#pagination");
            pagination.empty();

            for (let i = 1; i <= totalPages; i++) {
                pagination.append(
                    `<button class="px-3 py-1 border rounded ${i === currentPage ? 'bg-blue-500 text-white' : 'bg-white'} page-btn">${i}</button>`
                );
            }
        }

        // Change rows per page
        $("#rowsPerPage").on("change", function () {
            rowsPerPage = parseInt($(this).val());
            currentPage = 1;
            renderTable();
        });

        // Search filter
        $("#searchInput").on("keyup", function () {
            currentPage = 1;
            renderTable();
        });

        // Pagination click
        $(document).on("click", ".page-btn", function () {
            currentPage = parseInt($(this).text());
            renderTable();
        });

        // 🔽 Sorting click
        $(document).on("click", ".sort", function () {
            let columnIndex = $(this).data("column");
            sortDirection[columnIndex] = !sortDirection[columnIndex]; // toggle asc/desc
            let asc = sortDirection[columnIndex];

            let rows = $("#studentTable tbody tr").get();

            rows.sort(function (a, b) {
                let A = $(a).children("td").eq(columnIndex).text().toLowerCase();
                let B = $(b).children("td").eq(columnIndex).text().toLowerCase();

                // numeric check
                if ($.isNumeric(A) && $.isNumeric(B)) {
                    return asc ? A - B : B - A;
                } else {
                    return asc ? A.localeCompare(B) : B.localeCompare(A);
                }
            });

            $.each(rows, function (index, row) {
                $("#studentTable tbody").append(row);
            });

            currentPage = 1; // reset pagination after sort
            renderTable();
        });

        // Initial render
        renderTable();
    });
</script>
<script>
    function showLimitPopup() {
        let popup = $("#limitPopup");
        popup.fadeIn(300);

        setTimeout(() => {
            popup.fadeOut(300);
        }, 2000);
    }
    $(document).on("click", ".attendance-btn", function () {

        let btn = $(this);
        let studentId = btn.data("id");
        let currentStatus = parseInt(btn.data("status"));  // 0 or 1
        let newStatus = currentStatus === 1 ? 0 : 1;        // toggle

        // Count how many are currently Present
        let currentPresentCount = 0;
        $(".attendance-btn").each(function () {
            if ($(this).data("status") == 1) {
                currentPresentCount++;
            }
        });

        // Prevent marking more than 120 present
        // if (newStatus === 1 && currentPresentCount >= 120) {
        //     showLimitPopup(); 
        //     return;
        // }

        // UI update
        if (newStatus === 1) {
            btn.text("Present")
                .removeClass("bg-red-500 hover:bg-red-600")
                .addClass("bg-green-500 hover:bg-green-600");
        } else {
            btn.text("Absent")
                .removeClass("bg-green-500 hover:bg-green-600")
                .addClass("bg-red-500 hover:bg-red-600");
        }

        btn.data("status", newStatus);

        // updateButtonUI(btn, newStatus);
        updatePresentCounter();


    });

</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>

<script>
    function exportAttendance() {
        // Get the HTML table
        let table = document.getElementById("studentTable");

        // Convert table → worksheet
        let workbook = XLSX.utils.table_to_book(table, { sheet: "Attendance" });

        // Download Excel file
        XLSX.writeFile(workbook, "attendance_list.xlsx");
    }
</script>

<script>

    $(document).on("click", "#toggleAllAttendance", function () {

        let btn = $(this);
        let mode = btn.data("mode"); // "absent" or "present120"
        let schoolId = $("#filterSchool").val();

        if (!schoolId) {
            Swal.fire({
                icon: "warning",
                title: "Please select school first.",
                confirmButtonText: "OK",
                confirmButtonColor: "#d33"
            });
            return;
        }

        if (mode === "absent") {
            //  1st Click → Mark ALL Absent
            markAllInUI(0);   // Update UI instantly
            updatePresentCounter();

            // change next mode/text
            btn.data("mode", "present120");
            btn.text("Mark All");

        } else {
            //  2nd Click → Mark FIRST 120 Present
            markFirst120InUI(1);
            updatePresentCounter();

            // change next mode/text
            btn.data("mode", "absent");
            btn.text("Mark All");
        }

    });
    function markAllInUI(status) {
        $(".attendance-btn").each(function () {
            updateButtonUI($(this), status);
        });
    }

    function markFirst120InUI(status) {
        $(".attendance-btn").each(function (index) {
            if (index < 120) {
                updateButtonUI($(this), status);
            } else {
                updateButtonUI($(this), 0);  // others absent
            }
        });
    }

    function updateButtonUI(btn, status) {

        btn.data("status", status);

        if (status === 1) {
            btn.text("Present")
                .removeClass("bg-red-500 hover:bg-red-600")
                .addClass("bg-green-500 hover:bg-green-600");
        } else {
            btn.text("Absent")
                .removeClass("bg-green-500 hover:bg-green-600")
                .addClass("bg-red-500 hover:bg-red-600");
        }
    }

</script>
<script>
    function updatePresentCounter() {
        let count = 0;

        $(".attendance-btn").each(function () {
            if ($(this).data("status") == 1) {
                count++;
            }
        });

        // Update text
        $("#presentCounter").html(`<span id="presentIcon" class="mr-1">✔</span> Present: ${count}`);

        // Add animation
        $("#presentCounter").addClass("updated");
        setTimeout(() => {
            $("#presentCounter").removeClass("updated");
        }, 300);
    }
</script>
<script>
$(document).on("click", "#saveAttendance", function () {

    let schoolId = $("#filterSchool").val();

    if (!schoolId) {
        Swal.fire({
            icon: "warning",
            title: "Please select school first.",
            confirmButtonText: "OK",
            confirmButtonColor: "#d33"
        });
        return;
    }

    let attendanceData = [];
    let presentCount = 0;

    $(".attendance-btn").each(function () {
        let status = $(this).data("status");

        attendanceData.push({
            student_id: $(this).data("id"),
            attendance: status
        });
        if (status == 1) presentCount++;
    });

    if (presentCount < 120) {
        Swal.fire({
            icon: "warning",
            title: "Not Enough Present Students",
            html: `
                <b>You marked only ${presentCount} students as Present.</b><br><br>
                You must mark <span style="color:red; font-weight:bold;">more than 120</span> students <span style="color:green; font-weight:bold;">Present</span> before saving.
            `,
            confirmButtonText: "OK",
            confirmButtonColor: "#d33"
        });
        return;
    }


    // if (presentCount > 120) {
    //     Swal.fire({
    //         icon: "warning",
    //         title: "Warning: More than 120 students cannot be marked Present.",
    //         confirmButtonText: "OK",
    //         confirmButtonColor: "#d33"
    //     });
    //     return;
    // }

    $.ajax({
        url: "{{ route('attendance.saveAll') }}",   
        method: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            school_id: schoolId,
            attendance: attendanceData
        },
        beforeSend: function () {
            $("#saveAttendance").prop("disabled", true).text("Saving...");
        },
        success: function (response) {
            Swal.fire({
                icon: "success",
                title: "Attendance Saved!",
                text: "All attendance records were updated successfully.",
                confirmButtonText: "OK",
                confirmButtonColor: "#3085d6"
            });

            $("#saveAttendance").prop("disabled", false).text("Save Attendance");
        },
        error: function () {
            Swal.fire({
                icon: "error",
                title: "Save Failed",
                text: "Something went wrong while saving attendance.",
                confirmButtonText: "OK",
                confirmButtonColor: "#d33"
            });
            $("#saveAttendance").prop("disabled", false).text("Save Attendance");
        }
    });

});
</script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).on("change", "#filterDistrict", function () {
    let districtId = $(this).val();

    $("#filterSchool").html('<option value="">Loading...</option>');

    if (!districtId) {
        $("#filterSchool").html('<option value="">-- Select School --</option>');
        return;
    }

    $.ajax({
        url: "{{ route('schools.byDistrict') }}",
        method: "GET",
        data: { district_id: districtId },
        success: function (schools) {
            let options = '<option value="">-- Select School --</option>';
            schools.forEach(school => {
                options += `
                    <option value="${school.scm_id}">
                        ${school.scm_name} (${school.scm_udise_code})
                    </option>`;
            });
            $("#filterSchool").html(options);
        }
    });
});
</script>


</body>
@include('components.footer')