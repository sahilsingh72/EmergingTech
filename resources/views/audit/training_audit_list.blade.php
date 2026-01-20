@include('components.navbar')
@include('components.sidebar')

<body class="hold-transition sidebar-mini layout-fixed">

    <script src="https://cdn.tailwindcss.com"></script>
    <div class="wrapper">
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">Training Audit List</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active">Training Audit List</li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="py-12">
                        <div class="max-w-8xl mx-auto space-y-6">
                            <div class="p-3 sm:p-8 bg-white shadow sm:rounded-lg">
                                <div class="bg-white rounded-lg w-full">
                                    <!-- Title -->
                                    <h2 class="text-2xl font-semibold text-center mb-6">Training Audit List</h2>

                                    <div class="flex justify-end mb-1">
                                        <button id="exportExcel"
                                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-sm">
                                            <i class="fas fa-file-excel mr-2"></i>Export Excel
                                        </button>
                                    </div>
                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-2">
                                        <!-- District Filter -->
                                        <div class="w-full">
                                            <label class="text-sm font-medium block mb-1">District</label>
                                            <select id="districtFilter"
                                                class="w-full border rounded px-3 py-2 text-sm">
                                                <option value="">All Districts</option>
                                                @foreach($schools->pluck('scm_dist')->unique() as $dist)
                                                    <option value="{{ $dist }}">{{ $dist }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- School Filter -->
                                        <div class="w-full">
                                            <label class="text-sm font-medium block mb-1">School</label>
                                            <select id="schoolFilter"
                                                class="w-full border rounded px-3 py-2 text-sm">
                                                <option value="">All Schools</option>
                                                @foreach($schools as $school)
                                                    <option value="{{ $school->scm_id }}"
                                                            data-district="{{ $school->scm_dist }}">
                                                        {{ $school->scm_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>


                                    <div class="flex justify-between items-center mb-2">
                                        <!-- Rows per page -->
                                        <div>
                                            <label for="rowsPerPage" class="mr-2">Shows:</label>
                                            <select id="rowsPerPage" class="border rounded  pl-2 pr-5">
                                                <option value="5">5</option>
                                                <option value="10" selected>10</option>
                                                <option value="25">25</option>
                                                <option value="50">50</option>
                                            </select>
                                        </div>

                                        <!-- Search -->
                                        <div>
                                            <input type="text" id="searchInput" placeholder="Search..."
                                                class="border rounded p-2 w-full w-40 focus:outline-none focus:ring-2 focus:ring-blue-400">
                                        </div>
                                    </div>

                                    @if ($errors->any())
                                        <div class="bg-red-500 text-white p-3 rounded mb-4">
                                            <ul class="list-disc list-inside">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                    @if(session('success'))
                                        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                                            {{ session('success') }}
                                        </div>
                                    @endif

                                    <div class="bg-white shadow rounded-lg p-4 overflow-x-auto">
                                        <table id="filterTable" class="w-full border border-collapse">
                                            <thead class="bg-gray-100">
                                                <tr>
                                                    <th class="border px-3 py-2 sort" data-column="0">District</th>
                                                    <th class="border px-3 py-2 sort" data-column="1">School Name</th>
                                                    <th class="border px-3 py-2 sort" data-column="2">UDISE</th>
                                                    <th class="border px-3 py-2">Attendance</th>
                                                    <th class="border px-3 py-2">Training Photo</th>
                                                    <th class="border px-3 py-2">Training Video</th>
                                                    <th class="border px-3 py-2">Video Feedback</th>
                                                    <th class="border px-3 py-2">Student Feedback</th>
                                                    <th class="border px-3 py-2">Certificate</th>
                                                    <th class="border px-3 py-2">Training</th>
                                                    <th class="border px-3 py-2">Audit Status</th>
                                                    <th class="border px-3 py-2">Action</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @foreach($schools as $school)
                                                    @php
                                                        $totalStudents = \App\Models\StudentMst::where('stu_scm_id', $school->scm_id)
                                                            ->where('attendance', 1)->count();

                                                        $studentsWithFeedback = \App\Models\StudentMst::where('stu_scm_id', $school->scm_id)
                                                            ->where('attendance', 1)
                                                            ->whereNotNull('feedback_file_url')->count();

                                                        $hasAttendance = \App\Models\TrainingUpload::where('school_id', $school->scm_id)
                                                            ->where('file_type', 'attendance_sheet')->exists();

                                                        $hasPhoto = \App\Models\TrainingUpload::where('school_id', $school->scm_id)
                                                            ->where('file_type', 'training_photo')->exists();

                                                        $hasVideo = \App\Models\TrainingUpload::where('school_id', $school->scm_id)
                                                            ->where('file_type', 'training_video')->exists();

                                                        $hasVideoFeedback = \App\Models\TrainingUpload::where('school_id', $school->scm_id)
                                                            ->where('file_type', 'video_feedback')->exists();

                                                        $hasCertificate = \App\Models\TrainingUpload::where('school_id', $school->scm_id)
                                                            ->where('file_type', 'training_completion_certificate')->exists();
                                                    @endphp

                                                        <tr class="hover:bg-gray-50"
                                                            data-district="{{ $school->scm_dist }}"
                                                            data-school="{{ $school->scm_id }}">

                                                        <td class="border px-3 py-2">{{ $school->scm_dist }}</td>
                                                        <td class="border px-3 py-2">{{ $school->scm_name }}</td>
                                                        <td class="border px-3 py-2">{{ $school->scm_udise_code }}</td>

                                                        {{-- Attendance --}}
                                                        <td class="border px-3 py-2 text-center">
                                                            {!! $hasAttendance ? '✅' : '❌' !!}
                                                        </td>

                                                        {{-- Training Photo --}}
                                                        <td class="border px-3 py-2 text-center">
                                                            {!! $hasPhoto ? '✅' : '❌' !!}
                                                        </td>

                                                        {{-- Training Video --}}
                                                        <td class="border px-3 py-2 text-center">
                                                            {!! $hasVideo ? '✅' : '❌' !!}
                                                        </td>

                                                        {{-- Video Feedback --}}
                                                        <td class="border px-3 py-2 text-center">
                                                            {!! $hasVideoFeedback ? '✅' : '❌' !!}
                                                        </td>

                                                        {{-- Student Feedback --}}
                                                        <td class="border px-3 py-2 text-center">
                                                            {{ $studentsWithFeedback }} / {{ $totalStudents }}
                                                        </td>

                                                        {{-- Certificate --}}
                                                        <td class="border px-3 py-2 text-center">
                                                            {!! $hasCertificate ? '✅' : '❌' !!}
                                                        </td>

                                                        {{-- Training Completed --}}
                                                        <td class="border px-3 py-2 text-center">
                                                            {!! $school->training_completed ? '✅' : '❌' !!}
                                                        </td>

                                                        {{-- Audit Status --}}
                                                        <td class="border px-3 py-2 text-center">
                                                            @php
                                                                $badge = match ($school->audit_status) {
                                                                    'approved' => 'bg-green-100 text-green-700',
                                                                    'rejected' => 'bg-red-100 text-red-700',
                                                                    'reverted' => 'bg-yellow-100 text-yellow-700',
                                                                    default => 'bg-gray-200 text-gray-700',
                                                                };
                                                            @endphp
                                                            <span class="px-3 py-1 rounded-full text-xs {{ $badge }}">
                                                                {{ ucfirst($school->audit_status ?? 'pending') }}
                                                            </span>
                                                        </td>

                                                        {{-- Action --}}
                                                        <td class="border px-3 py-2 text-center">
                                                            <a href="{{ route('audit.show', $school->scm_id) }}"
                                                                class="text-blue-600 hover:underline">
                                                                View
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>

                                    <div id="pagination" class="flex justify-center space-x-2 mt-4"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script>
document.getElementById('exportExcel').addEventListener('click', function () {

    const table = document.getElementById('filterTable');
    if (!table) {
        alert('Table not found');
        return;
    }

    // Clone table to avoid modifying UI
    const clonedTable = table.cloneNode(true);

    // Remove hidden rows (filters + pagination)
    const rows = clonedTable.querySelectorAll('tbody tr');
    rows.forEach(row => {
        if (row.style.display === 'none') {
            row.remove();
        }
    });

    // Convert to workbook
    const workbook = XLSX.utils.table_to_book(clonedTable, {
        sheet: "Training Audit List"
    });

    // Export file
    XLSX.writeFile(workbook, "Training_Audit_List.xlsx");
});
</script>


    <script>
        $(document).ready(function () {
            let rowsPerPage = parseInt($("#rowsPerPage").val());
            let currentPage = 1;
            let sortDirection = {}; // keep track of each column's sorting state

            function renderTable() {
                let searchText = $("#searchInput").val().toLowerCase();
                let rows = $("#filterTable tbody tr");

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

            // Sorting click
            $(document).on("click", ".sort", function () {
                let columnIndex = $(this).data("column");
                sortDirection[columnIndex] = !sortDirection[columnIndex]; // toggle asc/desc
                let asc = sortDirection[columnIndex];

                let rows = $("#filterTable tbody tr").get();

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
                    $("#filterTable tbody").append(row);
                });

                currentPage = 1; // reset pagination after sort
                renderTable();
            });

            // Initial render
            renderTable();
        });
    </script>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const districtFilter = document.getElementById('districtFilter');
    const schoolFilter   = document.getElementById('schoolFilter');
    const rows           = document.querySelectorAll('#filterTable tbody tr');

    function applyFilters() {
        const district = districtFilter.value;
        const school   = schoolFilter.value;

        rows.forEach(row => {
            const rowDistrict = row.dataset.district;
            const rowSchool   = row.dataset.school;

            let show = true;

            if (district && rowDistrict !== district) {
                show = false;
            }

            if (school && rowSchool !== school) {
                show = false;
            }

            row.style.display = show ? '' : 'none';
        });
    }

    // District change
    districtFilter.addEventListener('change', function () {
        const selectedDistrict = this.value;

        // Reset school dropdown
        schoolFilter.value = '';

        // Show only schools of selected district
        [...schoolFilter.options].forEach(option => {
            if (!option.value) return;

            option.style.display =
                !selectedDistrict || option.dataset.district === selectedDistrict
                    ? ''
                    : 'none';
        });

        applyFilters();
    });

    // School change
    schoolFilter.addEventListener('change', applyFilters);
});
</script>



</body>
@include('components.footer')