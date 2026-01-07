@include('components.navbar')
@include('components.sidebar')


<body class="hold-transition sidebar-mini layout-fixed">

    <div class="wrapper">
        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">School Directory</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="{{ route('select.district') }}">School</a></li>
                                <li class="breadcrumb-item active">School Directory</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <section class="content">
                <div class="container-fluid">
                    <div class="py-1">
                        <div class="max-w-8xl mx-auto  space-y-6">
                            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">

                                <div class="mb-2 flex justify-end">
                                    <button id="exportBtn"
                                        class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow-md flex items-center gap-2">
                                        <i class="fas fa-file-excel"></i> Export Report
                                    </button>
                                </div>
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
                                            <option value="150">150</option>
                                        </select>
                                    </div>

                                    <!-- District Filter -->
                                    <div>
                                        <label for="districtFilter" class="mr-2">District:</label>
                                        <select id="districtFilter" class="border rounded pl-2 pr-5">
                                            <option value="">All Districts</option>
                                            @foreach($schools->pluck('scm_dist')->unique()->sort()->values() as $district)
                                                <option value="{{ $district }}">
                                                    {{ $district }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <!-- Column Selector -->
                                    <div class="relative">
                                        <label class="mr-2">Columns:</label>
                                        <button type="button" id="columnToggleBtn" class="border rounded pl-2 pr-5">
                                            Select Columns
                                        </button>

                                        <div id="columnDropdown"
                                            class="absolute z-50 bg-white border rounded shadow-md mt-1 p-2 hidden max-h-64 overflow-y-auto">

                                            @php
                                                $columns = [
                                                    'S.No',
                                                    'School Name',
                                                    'UDISE Code',
                                                    'District',
                                                    'Subdivision',
                                                    'Address',
                                                    'PIN',
                                                    'HM Name',
                                                    'HM Phone',
                                                    'SPOC Name',
                                                    'SPOC Phone',
                                                    'Student Count',
                                                    'Coordinator Count',
                                                    'Trainer Count',
                                                    'Support Staff Count',
                                                    'Smart Class',
                                                    'Power Backup',
                                                    'Training Date',
                                                    'Training Completed'
                                                ];
                                            @endphp

                                            @foreach($columns as $index => $col)
                                                <div class="flex items-center">
                                                    <input type="checkbox" class="column-checkbox mr-2"
                                                        data-column="{{ $index }}" checked>
                                                    <label>{{ $col }}</label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    <!-- Search -->
                                    <div class="w-full sm:w-auto">
                                        <input type="text" id="searchInput" placeholder="Search..."
                                            class="border rounded p-2 h-7 w-full sm:w-64  focus:outline-none focus:ring-2 focus:ring-blue-400">
                                    </div>


                                </div>

                                <div class="bg-white rounded-lg w-full">
                                    <div class="table-responsive">
                                        <table id="schoolTable"
                                            class="table table-bordered table-striped table-hover text-sm">
                                            <thead class="bg-gray-400">
                                                <tr>
                                                    <th>S.No</th>
                                                    <th>School Name</th>
                                                    <th>UDISE Code</th>
                                                    <th>District</th>
                                                    <th>Subdivision</th>
                                                    <th>Address</th>
                                                    <th>PIN</th>
                                                    <th>Student Count</th>
                                                    <th>Coordinator Count</th>
                                                    <th>Trainer Count</th>
                                                    <th>Support Staff Count</th>
                                                    <th>HM Name</th>
                                                    <th>HM Phone</th>
                                                    <th>SPOC Name</th>
                                                    <th>SPOC Phone</th>
                                                    <th>Smart Class</th>
                                                    <th>Power Backup</th>
                                                    <th>Training Date</th>
                                                    <th>Training Completed</th>
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @forelse($schools as $index => $school)
                                                    <tr>
                                                        <td>{{ $index + 1 }}</td>
                                                        <td>{{ $school->scm_name }}</td>
                                                        <td>{{ $school->scm_udise_code }}</td>
                                                        <td>{{ $school->scm_dist }}</td>
                                                        <td>{{ $school->scm_subdivision_name }}</td>
                                                        <td>{{ $school->scm_address }}</td>
                                                        <td>{{ $school->scm_pin_code }}</td>
                                                        <td>{{ $studentCounts[$school->scm_id] ?? '-' }}</td>
                                                        <td>{{ $coordinatorCounts[$school->scm_id] ?? '-' }}</td>
                                                        <td>{{ $trainerCounts[$school->scm_id] ?? '-' }}</td>
                                                        <td>{{ $ssupportStaffCounts[$school->scm_id] ?? '-' }}</td>
                                                        <td>{{ $school->scm_hm_name ?? '-' }}</td>
                                                        <td>{{ $school->scm_hm_phone ?? '-' }}</td>
                                                        <td>{{ $school->scm_spoc_name ?? '-' }}</td>
                                                        <td>{{ $school->scm_spoc_phone ?? '-' }}</td>
                                                        <td>
                                                            @if($school->scm_smartclass)
                                                                <span class="badge badge-success">Yes</span>
                                                            @elseif($school->scm_smartclass == 0)
                                                                -
                                                            @else
                                                                <span class="badge badge-danger">No</span>
                                                            @endif
                                                        </td>

                                                        <td>
                                                            @if($school->scm_powerbackup)
                                                                Yes ({{ $school->scm_powerbackup_type }})
                                                            @else
                                                                -
                                                            @endif
                                                        </td>

                                                        <td>{{ $school->training_date ?? '-' }}</td>

                                                        <td>
                                                            @if($school->training_completed)
                                                                <span class="badge badge-success">Completed</span>
                                                            @else
                                                                <span class="badge badge-warning">Pending</span>
                                                            @endif
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="17" class="text-center text-danger">
                                                            No school records found
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
                </div>
            </section>
        </div>
    </div>

    <script>
        $(document).ready(function () {

            let rowsPerPage = parseInt($("#rowsPerPage").val());
            let currentPage = 1;

            function renderTable() {
                let searchText = $("#searchInput").val().toLowerCase();
                let selectedDistrict = $("#districtFilter").val().toLowerCase();
                let rows = $("#schoolTable tbody tr");

                // FILTER rows
                rows.each(function () {
                    let rowText = $(this).text().toLowerCase();
                    let districtText = $(this).children("td").eq(3).text().toLowerCase(); // District column

                    let matchesSearch = rowText.indexOf(searchText) > -1;
                    let matchesDistrict = selectedDistrict === "" || districtText === selectedDistrict;

                    $(this).toggle(matchesSearch && matchesDistrict);
                });

                // PAGINATION
                let visibleRows = rows.filter(":visible");
                let totalPages = Math.ceil(visibleRows.length / rowsPerPage);

                visibleRows.hide();
                let start = (currentPage - 1) * rowsPerPage;
                let end = start + rowsPerPage;
                visibleRows.slice(start, end).show();

                // FIX S.No (RENUMBER ONLY VISIBLE ROWS)
                let serial = (currentPage - 1) * rowsPerPage + 1;
                visibleRows.slice(start, end).each(function () {
                    $(this).children("td").eq(0).text(serial++);
                });

                // PAGINATION BUTTONS
                let pagination = $("#pagination");
                pagination.empty();

                for (let i = 1; i <= totalPages; i++) {
                    pagination.append(
                        `<button class="px-3 py-1 border rounded ${i === currentPage ? 'bg-blue-500 text-white' : ''} page-btn">${i}</button>`
                    );
                }
            }

            // EVENTS
            $("#rowsPerPage").on("change", function () {
                rowsPerPage = parseInt($(this).val());
                currentPage = 1;
                renderTable();
            });

            $("#searchInput").on("keyup", function () {
                currentPage = 1;
                renderTable();
            });

            $("#districtFilter").on("change", function () {
                currentPage = 1;
                renderTable();
            });

            $(document).on("click", ".page-btn", function () {
                currentPage = parseInt($(this).text());
                renderTable();
            });

            // INITIAL LOAD
            renderTable();
        });
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script>
        document.getElementById("exportBtn").addEventListener("click", function () {
            let table = document.getElementById("schoolTable");
            let data = [];

            let headers = [];
            table.querySelectorAll("thead th").forEach((th, index) => {
                if (th.offsetParent !== null) {
                    headers.push(th.innerText.trim());
                }
            });
            data.push(headers);

            table.querySelectorAll("tbody tr").forEach(row => {
                if (row.style.display !== "none") {
                    let rowData = [];
                    row.querySelectorAll("td").forEach((td) => {
                        if (td.offsetParent !== null) {
                            rowData.push(td.innerText.replace(/\s+/g, ' ').trim());
                        }
                    });
                    data.push(rowData);
                }
            });

            if (data.length === 1) {
                alert("No data to export");
                return;
            }

            let wb = XLSX.utils.book_new();
            let ws = XLSX.utils.aoa_to_sheet(data);
            XLSX.utils.book_append_sheet(wb, ws, "School Directory");

            XLSX.writeFile(wb, "School_List.xlsx");
        });
    </script>

    <script>
        $(document).ready(function () {
            // Toggle dropdown
            $("#columnToggleBtn").on("click", function () {
                $("#columnDropdown").toggleClass("hidden");
            });

            // Hide dropdown when clicking outside
            $(document).on("click", function (e) {
                if (!$(e.target).closest("#columnToggleBtn, #columnDropdown").length) {
                    $("#columnDropdown").addClass("hidden");
                }
            });

            // Column visibility toggle
            $(".column-checkbox").on("change", function () {
                let columnIndex = $(this).data("column");
                let isChecked = $(this).is(":checked");

                $("#schoolTable tr").each(function () {
                    if (isChecked) {
                        $(this).find("th, td").eq(columnIndex).show();
                    } else {
                        $(this).find("th, td").eq(columnIndex).hide();
                    }
                });
            });

        });
    </script>


</body>
@include('components.footer')