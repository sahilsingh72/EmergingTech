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
                            <h1 class="m-0 text-dark">Food Bills</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Finance & Bills</a></li>
                                <li class="breadcrumb-item active">Food Bills</li>
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
                                    <h2 class="text-2xl font-semibold text-center mb-6">Food Bills
                                        Requests</h2>

                                    {{-- @if(auth()->user()->role->name == 'Accounts' || auth()->user()->role->name ==
                                    'OKCL')
                                    <div
                                        class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 mb-2">
                                        <div class="w-full sm:w-auto">
                                            <button onclick="exportAttendance()"
                                                class="btn btn-success w-full sm:w-40 text-center">
                                            </button>
                                        </div>
                                        <div class="w-full sm:w-auto">
                                            <button onclick="exportAttendance()"
                                                class="btn btn-success w-full sm:w-40 text-center">
                                                Export Attendance
                                            </button>
                                        </div>
                                    </div>
                                    @endif --}}
                                    @if(auth()->user()->role->name == 'Accounts' || auth()->user()->role->name == 'OKCL' || auth()->user()->role->name == 'DLC')
                                        <form method="GET" action="{{ route('camp.expense.list') }}"
                                            class="mb-3 d-flex gap-2">
                                            @if(auth()->user()->role->name == 'Accounts' || auth()->user()->role->name == 'OKCL')
                                                {{-- District Filter --}}
                                                <select name="district_id" class="form-control">
                                                    <option value="">-- All Districts --</option>
                                                    @foreach($districts as $d)
                                                        <option value="{{ $d->DSM_DSCD }}" {{ request('district_id') == $d->DSM_DSCD ? 'selected' : '' }}>
                                                            {{ $d->DSM_DSNM }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            @endif
                                            @if(auth()->user()->role->name == 'Accounts' || auth()->user()->role->name == 'OKCL' || auth()->user()->role->name == 'DLC')
                                                {{-- Status Filter --}}
                                                <select name="status" class="form-control">
                                                    <option value="">-- All Status --</option>
                                                    <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                                                    <option value="Approved" {{ request('status') == 'Approved' ? 'selected' : '' }}>Approved</option>
                                                    <option value="Rejected" {{ request('status') == 'Rejected' ? 'selected' : '' }}>Rejected</option>
                                                </select>
                                            @endif
                                            <button class="btn btn-primary">Filter</button>
                                        </form>
                                        @if (auth()->user()->role->name == 'Accounts' || auth()->user()->role->name == 'OKCL')
                                            @if(!$districtId)
                                                <div class="alert alert-info">
                                                    Please select a district to view the expense bills.
                                                </div>
                                            @endif
                                        @endif
                                    @endif

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
                                    <div class="bg-white shadow rounded-lg p-4 overflow-x-auto">
                                        <table id="filterTable" class="table table-bordered">
                                            <thead class="table-secondary">
                                                <tr>
                                                    <th></th>
                                                    <th>District</th>
                                                    <th>School</th>
                                                    <th>Training Date</th>
                                                    <th>Total Amount</th>
                                                    <th>Bill</th>
                                                    <th>Status</th>
                                                    @if(in_array(Auth::user()->role_id, [3, 8, 2]))
                                                        <th>Slip</th>
                                                    @endif
                                                    @if(in_array(Auth::user()->role_id, [3, 8]))
                                                        <th>Action</th>
                                                    @endif
                                                </tr>
                                            </thead>
                                            @php
                                                $groupedRecords = $records->groupBy('school_id');
                                            @endphp
                                            <tbody>
                                                @foreach($groupedRecords as $schoolId => $schoolRows)
                                                    @php
                                                        $school = $schoolRows->first()->school;
                                                        $schoolTotal = $schoolRows->sum('amount');
                                                        $collapseId = 'school_' . $schoolId;
                                                    @endphp

                                                    {{--SCHOOL HEADER ROW --}}
                                                    <tr class="school-row bg-gray-100 cursor-pointer font-semibold"
                                                        onclick="toggleSchool('{{ $collapseId }}')">
                                                        <td class="text-center">
                                                            <i class="fas fa-chevron-down" id="icon-{{ $collapseId }}"></i>
                                                        </td>
                                                        <td>{{ $school->scm_dist }}</td>
                                                        <td>{{ $school->scm_name }}</td>
                                                        <td>
                                                            {{ \Carbon\Carbon::parse($schoolRows->first()->training_date)->format('d-m-Y') }}
                                                        </td>
                                                        <td class="text-green-700">
                                                            ₹{{ number_format($schoolTotal, 2) }}
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-info">
                                                                {{ $schoolRows->count() }} Bills
                                                            </span>
                                                        </td>
                                                        <td>
                                                            @php
                                                                $statuses = $schoolRows->pluck('status')->unique();
                                                            @endphp
                                                            @if($statuses->count() === 1)
                                                                @if($statuses->first() == 'Pending')
                                                                    <span class="badge bg-warning">All Pending</span>
                                                                @elseif($statuses->first() == 'Approved')
                                                                    <span class="badge bg-success">All Approved</span>
                                                                @else
                                                                    <span class="badge bg-danger">All Rejected</span>
                                                                @endif
                                                            @else
                                                                <span class="badge bg-secondary">Mixed Statuses</span>
                                                            @endif
                                                        </td>
                                                        @if(in_array(Auth::user()->role_id, [3, 8, 2]))
                                                            <td>
                                                                <i class="fas fa-print"></i>
                                                            </td>
                                                        @endif
                                                        @if(in_array(Auth::user()->role_id, [3, 8]))
                                                            <td><i class="fas fa-folder-open"></i></td>
                                                        @endif
                                                    </tr>

                                                    {{--BILL ROWS (COLLAPSIBLE) --}}
                                                    @foreach($schoolRows as $index => $row)
                                                        <tr class="bill-row {{ $collapseId }} d-none bg-white">
                                                            <td></td>
                                                            <td colspan="3">
                                                                <strong>{{ $row->bill_type }}</strong><br>
                                                                <small class="text-muted">
                                                                    Applied on:
                                                                    {{ \Carbon\Carbon::parse($row->created_at)->format('d-m-Y') }}
                                                                </small>
                                                            </td>
                                                            <td>
                                                                ₹{{ number_format($row->amount, 2) }}
                                                            </td>

                                                            <td>
                                                                @if($row->bill_path)
                                                                    <a href="{{ route('camp.expense.preview', ['path' => $row->bill_path]) }}"
                                                                        target="_blank" class="btn btn-sm btn-primary"> View
                                                                    </a>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if($row->status == 'Pending')
                                                                    <span class="badge bg-warning">Pending</span>
                                                                @elseif($row->status == 'Approved')
                                                                    <span class="badge bg-success">Approved</span>
                                                                @elseif($row->status == 'Rejected')
                                                                    <span class="badge bg-danger">Rejected</span>
                                                                    <br>
                                                                    <small class="text-warning">
                                                                        Correction required to resubmit bill.
                                                                    </small>
                                                                @endif
                                                                @if($row->status_updated_by)
                                                                    <br>
                                                                    <small class="text-muted">
                                                                        Updated by: {{optional($row->statusUpdatedByUser)->name}}
                                                                        <br>
                                                                        Remarks: {{ $row->remarks }} <br>
                                                                    </small>
                                                                @endif
                                                            </td>

                                                            <td>
    <a href="{{ route('foodbill.slip', $row->id) }}"
       target="_blank"
       class="btn btn-sm btn-info">
        <i class="fas fa-file-alt"></i> Slip
    </a>
</td>
                                                            @if(in_array(Auth::user()->role_id, [3]))
                                                                <td>
                                                                    @if($row->status !== 'Approved')
                                                                        {{-- Edit allowed for Pending & Rejected --}}
                                                                        <button class="btn btn-sm btn-warning mt-1"
                                                                            onclick="openEditModal({{ $row->id }})" title="Edit Bill">
                                                                            <i class="fas fa-edit"></i>
                                                                        </button>
                                                                        <button class="btn btn-sm btn-danger mt-1"
                                                                            onclick="if(confirm('Are you sure you want to delete this record?')) { window.location='{{ route('camp.expense.delete', $row->id) }}' }"
                                                                            title="Delete Bill">
                                                                            <i class="fas fa-trash"></i>
                                                                        </button>
                                                                    @else
                                                                        {{-- Edit disabled when Approved --}}
                                                                        <button class="btn btn-sm btn-secondary mt-1"
                                                                            title="Approved bills cannot be edited" disabled>
                                                                            <i class="fas fa-edit"></i>
                                                                        </button>
                                                                        <button class="btn btn-sm btn-danger mt-1"
                                                                            onclick="if(confirm('Are you sure you want to delete this record?')) { window.location='{{ route('camp.expense.delete', $row->id) }}' }"
                                                                            title="Approved bills cannot be deleted" disabled>
                                                                            <i class="fas fa-trash"></i>
                                                                        </button>
                                                                    @endif

                                                                </td>
                                                            @elseif (in_array(Auth::user()->role_id, [8]))
                                                                <td>
                                                                    @if($row->status == 'Pending')
                                                                        <form method="POST"
                                                                            action="{{ route('camp.expense.approve', $row->id) }}"
                                                                            class="inline">
                                                                            @csrf
                                                                            <button type="submit" class="btn btn-sm btn-success mt-1"
                                                                                title="Approve">
                                                                                <i class="fas fa-check"></i>
                                                                            </button>
                                                                        </form>
                                                                        <button class="btn btn-danger btn-sm mt-1" data-toggle="modal"
                                                                            data-target="#rejectModal{{ $row->id }}" title="Reject">
                                                                            <i class="fas fa-times"></i>
                                                                        </button>

                                                                    @else
                                                                        {{-- SHOW REVERT BUTTON WHEN APPROVED OR REJECTED- --}}
                                                                        <form action="{{ route('camp.expense.revert', $row->id) }}"
                                                                            method="POST">
                                                                            @csrf
                                                                            <button class="btn btn-warning btn-sm mt-1" title="Revert">
                                                                                <i class="fas fa-undo"></i>
                                                                            </button>
                                                                        </form>
                                                                    @endif
                                                                </td>

                                                                {{-- revert modal --}}
                                                                <div class="modal fade" id="rejectModal{{ $row->id }}">
                                                                    <div class="modal-dialog">
                                                                        <form action="{{ route('camp.expense.reject', $row->id) }}"
                                                                            method="POST">
                                                                            @csrf

                                                                            <div class="modal-content">
                                                                                <div class="modal-header bg-danger text-white">
                                                                                    <h5 class="modal-title">Reject Expense Bill</h5>
                                                                                </div>

                                                                                <div class="modal-body">
                                                                                    <label>Remarks (Reason for Rejection)</label>
                                                                                    <textarea name="remarks" class="form-control"
                                                                                        required></textarea>
                                                                                </div>

                                                                                <div class="modal-footer">
                                                                                    <button type="button" class="btn btn-secondary"
                                                                                        data-dismiss="modal">Cancel</button>
                                                                                    <button class="btn btn-danger">Reject</button>
                                                                                </div>
                                                                            </div>
                                                                        </form>
                                                                    </div>
                                                                </div>
                                                            @endif
                                                        </tr>
                                                    @endforeach
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
    <!-- Edit Expense Modal -->
    <div id="editModal"
        class="content-wrapper fixed inset-0 hidden z-50 bg-black bg-opacity-50 flex items-center justify-center px-2">
        <div class="bg-white rounded-lg shadow-lg max-w-5xl w-full max-h-[80vh] overflow-y-auto p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold mb-4">Edit Expense Bill</h3>
            </div>
            <form id="editForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <input type="hidden" name="record_id" id="editRecordId">

                <div class="mb-4">
                    <label class="font-semibold block mb-1">Bill Type</label>
                    <select name="bill_type" class="border p-2 rounded w-full billTypeSelect" id="edit_bill_type"
                        required>
                        <option value="">-- Select --</option>
                        @foreach($billTypes as $type)
                            <option value="{{ $type }}">{{ $type }}</option>
                        @endforeach
                    </select>

                    <input type="text" name="custom_bill_type" id="edit_custom_bill_type" placeholder="Enter bill type"
                        class="border p-2 rounded w-full mt-2 hidden" />
                </div>
                <div class="mb-4">
                    <label class="font-semibold block mb-1">Training Date</label>
                    <input type="date" name="training_date" id="edit_training_date" class="border p-2 rounded w-full"
                        required />
                </div>
                <div class="mb-4">
                    <label class="font-semibold block mb-1">Amount(₹)</label>
                    <input type="number" name="amount" id="edit_amount" class="border p-2 rounded w-full"
                        placeholder="Amount" required />
                </div>
                <div class="mb-4">
                    <label class="font-semibold block mb-1">Upload Bill (PDF)</label>
                    <input type="file" name="bill_file" accept="application/pdf" id="edit_bill_file"
                        class="border p-2 rounded w-full" />
                    <small class="text-muted">Leave blank to keep existing bill.</small>
                </div>

                <div class="mt-4 flex justify-end gap-2">
                    <button type="button" class="bg-gray-400 px-4 py-2 rounded"
                        onclick="closeEditModal()">Cancel</button>
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Update</button>
                </div>
            </form>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script>
        function exportAttendance() {
            // Get the HTML table
            let table = document.getElementById("filterTable");

            // Convert table → worksheet
            let workbook = XLSX.utils.table_to_book(table, { sheet: "Expense" });

            // Download Excel file
            XLSX.writeFile(workbook, "C-E_list.xlsx");
        }
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const billTypeSelect = document.getElementById('edit_bill_type');
            const customInput = document.getElementById('edit_custom_bill_type');

            billTypeSelect.addEventListener('change', function () {
                if (this.value === 'Misc') {
                    customInput.classList.remove('hidden');
                    customInput.required = true;
                    customInput.focus();
                } else {
                    customInput.classList.add('hidden');
                    customInput.required = false;
                    customInput.value = '';
                }
            });
        });
    </script>
    <script>
        function openEditModal(id) {
            const record = @json($records->keyBy('id'));
            const data = record[id];

            const billTypeSelect = document.getElementById('edit_bill_type');
            const customInput = document.getElementById('edit_custom_bill_type');

            // Reset
            billTypeSelect.value = '';
            customInput.value = '';
            customInput.classList.add('hidden');

            // Check if bill_type exists in predefined list
            let exists = false;
            for (let option of billTypeSelect.options) {
                if (option.value === data.bill_type) {
                    exists = true;
                    break;
                }
            }

            if (exists) {
                billTypeSelect.value = data.bill_type;
            } else {
                // Custom bill type (Misc)
                billTypeSelect.value = 'Misc';
                customInput.classList.remove('hidden');
                customInput.value = data.bill_type;
            }

            document.getElementById('edit_training_date').value = data.training_date;
            document.getElementById('edit_amount').value = data.amount;

            document.getElementById('editRecordId').value = id;
            document.getElementById('editForm').action = `/camp-expense/${id}`;
            document.getElementById('editModal').classList.remove('hidden');
        }

        // Close modal
        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }
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

            // 🔽 Sorting click
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
        function toggleSchool(className) {
            const rows = document.querySelectorAll('.' + className);
            const icon = document.getElementById('icon-' + className);

            rows.forEach(row => {
                row.classList.toggle('d-none');
            });

            icon.classList.toggle('fa-chevron-down');
            icon.classList.toggle('fa-chevron-up');
        }
    </script>



</body>
@include('components.footer')