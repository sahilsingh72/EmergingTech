@include('components.navbar')
@include('components.sidebar')

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
                                        <div class="w-full sm:w-auto">
                                            <button onclick="exportAttendance()" class="btn btn-success w-full sm:w-40 text-center">
                                                Export Attendance
                                            </button>
                                        </div>
                                        {{-- <div class="flex gap-2 w-full sm:w-auto">
    <button id="toggleAllAttendance" class="btn btn-primary w-full">
    Mark All
</button>

</div> --}}
                                    </div>


                                    <div class="bg-white shadow rounded-lg p-4 overflow-x-auto">
                                        <table id="studentTable" class="w-full border-collapse">
                                            <thead class="bg-gray-100">
                                                <tr>
                                                    <th class="border px-4 py-2 text-left cursor-pointer sort"
                                                        data-column="0">S.No</th>
                                                    <th class="border px-4 py-2 text-left cursor-pointer sort"
                                                        data-column="1">Student Name</th>
                                                    <th class="border px-4 py-2 text-left cursor-pointer sort"
                                                        data-column="3">Class</th>
                                                    <th class="border px-4 py-2 text-left cursor-pointer sort"
                                                        data-column="5">Gender</th>
                                                    <th class="border px-4 py-2 text-left cursor-pointer sort"
                                                        data-column="7">Father's Name</th>
                                                    <th class="border py-2 text-center">Attendance
                                                        @php
                                                            $roleId = Auth::user()->role_id;
                                                        @endphp
                                                        @if($roleId == 3 || $roleId == 6)
                                                            <button id="toggleAllAttendance" class="btn btn-primary ml-1">
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
                                                                    data-id="{{ $student->stu_id }}"
                                                                    data-status="{{ $student->attendance }}"
                                                                >
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
                                    </div>
                                    
                                    <div id="pagination" class="flex justify-center space-x-2 mt-4"></div>

                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
<script>
    $(document).ready(function() {
        $("#filterSchool").on("change", function() {
            const schoolId = $(this).val();

            $.ajax({
                url: "{{ route('student.attendance.sheet') }}", // your route name
                method: "GET",
                data: { school_id: schoolId },
                success: function(response) {
                    const html = $(response).find("#studentTable tbody").html();
                    $("#studentTable tbody").html(html);
                },
                error: function() {
                    alert("Failed to fetch students.");
                }
            });
        });
    });
</script>
    <script>
        $(document).ready(function() {
            let rowsPerPage = parseInt($("#rowsPerPage").val());
            let currentPage = 1;
            let sortDirection = {}; // keep track of each column's sorting state

            function renderTable() {
                let searchText = $("#searchInput").val().toLowerCase();
                let rows = $("#studentTable tbody tr");

                // Filter rows
                rows.each(function() {
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
            $("#rowsPerPage").on("change", function() {
                rowsPerPage = parseInt($(this).val());
                currentPage = 1;
                renderTable();
            });

            // Search filter
            $("#searchInput").on("keyup", function() {
                currentPage = 1;
                renderTable();
            });

            // Pagination click
            $(document).on("click", ".page-btn", function() {
                currentPage = parseInt($(this).text());
                renderTable();
            });

            // 🔽 Sorting click
            $(document).on("click", ".sort", function() {
                let columnIndex = $(this).data("column");
                sortDirection[columnIndex] = !sortDirection[columnIndex]; // toggle asc/desc
                let asc = sortDirection[columnIndex];

                let rows = $("#studentTable tbody tr").get();

                rows.sort(function(a, b) {
                    let A = $(a).children("td").eq(columnIndex).text().toLowerCase();
                    let B = $(b).children("td").eq(columnIndex).text().toLowerCase();

                    // numeric check
                    if ($.isNumeric(A) && $.isNumeric(B)) {
                        return asc ? A - B : B - A;
                    } else {
                        return asc ? A.localeCompare(B) : B.localeCompare(A);
                    }
                });

                $.each(rows, function(index, row) {
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
$(document).on("click", ".attendance-btn", function () {

    let btn = $(this);
    let studentId = btn.data("id");
    let currentStatus = parseInt(btn.data("status"));  // 0 or 1
    let newStatus = currentStatus === 1 ? 0 : 1;        // toggle

    // Update UI instantly
    if (newStatus === 1) {
        btn.text("Present");
        btn.removeClass("bg-red-500 hover:bg-red-600")
           .addClass("bg-green-500 hover:bg-green-600");
    } else {
        btn.text("Absent");
        btn.removeClass("bg-green-500 hover:bg-green-600")
           .addClass("bg-red-500 hover:bg-red-600");
    }

    btn.data("status", newStatus);

    // Save to DB
    $.ajax({
        url: "{{ route('attendance.mark') }}",
        method: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            student_id: studentId,
            attendance: newStatus  // store numeric value
        },
        success: function() {
            console.log("Attendance updated");
        },
        error: function() {
            alert("Error updating attendance");
        }
    });

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
$("#toggleAllAttendance").on("click", function () {

    @php $roleId = Auth::user()->role_id; @endphp
    @if ($roleId != 3 && $roleId != 6)
        alert("You don't have permission to edit attendance.");
        return;
    @endif

    let allPresent = true;

    // Check if any student is absent
    $(".attendance-btn").each(function () {
        if ($(this).data("status") == 0) {
            allPresent = false;
        }
    });

    let newStatus = allPresent ? 0 : 1;

    // Update toggle button text
    $("#toggleAllAttendance").text(
        newStatus === 1 ? "Mark All" : "Mark All"
    );

    // Update UI of all student buttons
    $(".attendance-btn").each(function () {

        if (newStatus === 1) {
            $(this)
                .data("status", 1)
                .text("Present")
                .removeClass("bg-red-500 hover:bg-red-600")
                .addClass("bg-green-500 hover:bg-green-600");
        } else {
            $(this)
                .data("status", 0)
                .text("Absent")
                .removeClass("bg-green-500 hover:bg-green-600")
                .addClass("bg-red-500 hover:bg-red-600");
        }

    });

    // Send AJAX to backend
    $.ajax({
        url: "{{ route('attendance.markAll') }}",
        method: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            status: newStatus,
            school_id: $("#filterSchool").val()
        },
        success: function () {
            console.log("Bulk attendance updated");
        }
    });

});
</script>




</body>
@include('components.footer')