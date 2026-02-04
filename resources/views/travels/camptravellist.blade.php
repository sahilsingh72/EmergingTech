@include('components.navbar')
@include('components.sidebar')


<body class="hold-transition sidebar-mini layout-fixed">
    <div class="wrapper">
        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            <!-- Content Header (Page header) -->
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1 class="m-0 text-dark">Camp Travel & Allowance</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Finance & Bills</a></li>
                                <li class="breadcrumb-item active">Camp Travel & Allowance</li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>

            <section class="content">
                <div class="container-fluid">
                    <div class="py-12">
                        <div class="max-w-8xl mx-auto space-y-6">
                            <div class="p-3 sm:p-8 bg-white shadow sm:rounded-lg">
                                <div class="bg-white rounded-lg w-full">

                                    <h2 class="text-2xl font-semibold text-center mb-4">
                                        Training Travel Bills
                                    </h2>
                                    @if(auth()->user()->role->name == 'Accounts' || auth()->user()->role->name == 'OKCL')
                                        <div
                                            class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3 mb-2">
                                            <div class="w-full sm:w-auto">
                                                {{-- <button onclick="exportAttendance()"
                                                    class="btn btn-success w-full sm:w-40 text-center">

                                                </button> --}}
                                            </div>
                                            <div class="w-full sm:w-auto">
                                                <button onclick="exportAttendance()"
                                                    class="btn btn-success w-full sm:w-40 text-center">
                                                    Export Attendance
                                                </button>
                                            </div>
                                        </div>
                                    @endif
                                    @if(auth()->user()->role->name == 'Accounts' || auth()->user()->role->name == 'OKCL' || auth()->user()->role->name == 'DLC')
                                        <form method="GET" action="{{ route('camp.travel.list') }}"
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
                                        <div class="alert alert-danger">
                                            <ul class="mb-0">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                    @if(session('success'))
                                        <p class="bg-green-500 text-white p-2 rounded mb-3">
                                            {{ session('success') }}
                                        </p>
                                    @endif
                                    <div class="bg-white shadow rounded-lg p-4 overflow-x-auto">
                                        @php
                                            $grouped = $records->groupBy('school_id');
                                        @endphp
                                        <table id="filterTable" class="table table-bordered">
                                            <thead class="table-secondary">
                                                <tr>
                                                    <th></th>
                                                    <th>District</th>
                                                    <th>School</th>
                                                    <th>Training Date</th>
                                                    <th>Travel Route</th>
                                                    <th>Distance (km)</th>
                                                    <th>Total Amount (₹2.5/km)</th>
                                                    <th>Bills</th>
                                                    <th>Status</th>
                                                    @if(in_array(Auth::user()->role_id, [3, 8]))
                                                        <th>Action</th>
                                                    @endif
                                                </tr>
                                            </thead>

                                            <tbody>
                                                @foreach($grouped as $schoolId => $rows)

                                                    @php
                                                        $school = $rows->first()->school;
                                                        $collapse = 'school_' . $schoolId;
                                                        $total = $rows->sum(
                                                            fn($r) =>
                                                            $r->total_main_amount + ($r->total_return_amount ?? 0)
                                                        );
                                                        $statuses = $rows->pluck('status')->unique();
                                                    @endphp

                                                    {{-- SCHOOL HEADER --}}
                                                    <tr class="bg-gray-100 font-semibold cursor-pointer"
                                                        onclick="toggleSchool('{{ $collapse }}')">
                                                        <td class="text-center">
                                                            <i class="fas fa-chevron-down" id="icon-{{ $collapse }}"></i>
                                                        </td>
                                                        <td>{{ optional($rows->first()->district)->DSM_DSNM }}</td>
                                                        <td>{{ $school->scm_name }}</td>
                                                        <td>
                                                            {{ \Carbon\Carbon::parse($rows->first()->training_date)->format('d-m-Y') }}
                                                        </td>
                                                        <td></td>

                                                        <td>
                                                            {{ $rows->sum('main_distance') + $rows->sum('return_distance')}}
                                                            km
                                                        </td>
                                                        <td class="text-success">
                                                            ₹{{ number_format($total, 2) }}
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-info">
                                                                {{ $rows->count() }} Bills
                                                            </span>
                                                        </td>
                                                        <td>
                                                            @php
                                                                $statuses = $rows->pluck('status')->unique();
                                                            @endphp
                                                            @if($statuses->count() === 1)
                                                                <span
                                                                    class="badge bg-{{ $statuses->first() == 'Approved' ? 'success' : ($statuses->first() == 'Pending' ? 'warning' : 'danger') }}">
                                                                    {{ $statuses->first() }}
                                                                </span>
                                                            @else
                                                                <span class="badge bg-secondary">Mixed</span>
                                                            @endif
                                                        </td>
                                                        @if(in_array(Auth::user()->role_id, [3, 8]))
                                                            <td><i class="fas fa-folder-open"></i></td>
                                                        @endif
                                                    </tr>
                                                    
                                                    {{-- PROGRESS ROW --}}
                                                    <tr class="bg-white {{ $collapse }} d-none">
                                                        <td></td>
                                                        <td colspan="100%">
                                                            @php
                                                                $progress = $schoolProgress[$schoolId] ?? [];
                                                                $steps = [
                                                                    'Attendance Sheet' => $progress['attendance'] ?? false,
                                                                    'Training Photos' => $progress['photos'] ?? false,
                                                                    'Training Video' => $progress['video'] ?? false,
                                                                    'Student Feedback' => $progress['written_feedback'] ?? false,
                                                                    'Student Feedback Rating (120)' => $progress['student_feedback_rating'] ?? false,
                                                                    'Institute Feedback' => $progress['institute_feedback'] ?? false,
                                                                    'Institute Feedback Rating' => $progress['institute_feedback_rating'] ?? false,
                                                                    'Video Feedback' => $progress['video_feedback'] ?? false,
                                                                    'Completion Certificate' => $progress['certificate'] ?? false,
                                                                ];

                                                                $allCompleted = collect($steps)->every(fn($v) => $v === true);
                                                                $completed = collect($steps)->filter()->count();
                                                                $total = count($steps);
                                                                $percent = ($completed / $total) * 100;
                                                            @endphp

                                                            <!-- Progress Bar -->
                                                            <div class="mt-2">
                                                                <div class="h-3 bg-gray-200 rounded-full overflow-hidden">
                                                                    <div class="h-3 bg-green-600"
                                                                        style="width: {{ $percent }}%">
                                                                    </div>
                                                                </div>
                                                                <small class="text-muted">
                                                                    {{ $completed }} / {{ $total }} steps completed
                                                                </small>
                                                            </div>
                                                            <!-- Step Icons -->
                                                            <div class="flex justify-between text-sm mt-2">
                                                                @foreach($steps as $label => $done)
                                                                    @php
                                                                        $map = [
                                                                            'Attendance Sheet' => 'attendance_sheet',
                                                                            'Training Photos' => 'training_photo',
                                                                            'Training Video' => 'training_video',
                                                                            'Student Feedback' => 'written_feedback',
                                                                            'Student Feedback Rating (120)' => 'student_feedback_rating',
                                                                            'Institute Feedback' => 'institute_feedback',
                                                                            'Institute Feedback Rating' => 'institute_feedback_rating',
                                                                            'Video Feedback' => 'video_feedback',
                                                                            'Completion Certificate' => 'training_completion_certificate',
                                                                        ];
                                                                        $type = $map[$label];
                                                                    @endphp
                                                                    <div class="flex items-center gap-1 cursor-pointer"
                                                                        @if($label === 'Student Feedback Rating (120)')
                                                                            onclick="openStudentFeedback({{ $schoolId }})"
                                                                        @elseif($label === 'Institute Feedback Rating')
                                                                            onclick="openInstituteFeedback({{ $schoolId }})"
                                                                        @else
                                                                            onclick="loadUploads({{ $schoolId }}, '{{ $type }}', '{{ $label }}')"
                                                                        @endif
                                                                    >

                                                                        @if($done)
                                                                            <i class="fas fa-check-circle text-green-600"></i>
                                                                        @else
                                                                            <i class="fas fa-clock text-gray-400"></i>
                                                                        @endif

                                                                        <span class="{{ $done ? 'text-green-700' : 'text-gray-500' }}">
                                                                            {{ $label }}
                                                                        </span>
                                                                    </div>
                                                                @endforeach
                                                            </div>
                                                        </td>
                                                    </tr>

                                                    {{-- BILL ROWS --}}
                                                    @foreach($rows as $index => $bill)
                                                        <tr class="{{ $collapse }} d-none">
                                                            <td></td>
                                                            <td colspan="3">
                                                                <small class="text-primary cursor-pointer text-bold"
                                                                    onclick="openStaffModal({{ $bill->id }})">
                                                                    Total Staff:
                                                                    {{ $bill->members->count() }}
                                                                </small>
                                                                <br>
                                                            
                                                                <small class="text-muted">
                                                                    Applied on:
                                                                    <span class="text-dark">
                                                                        {{ \Carbon\Carbon::parse($bill->created_at)->format('d-m-Y') }}
                                                                    </span>
                                                                </small>

                                                                <br>

                                                                @if($bill->updated_at && $bill->updated_at->ne($bill->created_at))
                                                                    <small class="text-muted">
                                                                        Updated on:
                                                                        <span class="text-dark">
                                                                            {{ $bill->updated_at->format('d-m-Y') }}
                                                                        </span>
                                                                    </small>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <div class="badge bg-primary">
                                                                    <small class="text-bold">Travel Route (From → To)</small>
                                                                </div><br>
                                                                <small class="text-bold">Main:</small>
                                                                <small
                                                                    class="text-success text-bold">{{ $bill->main_from }}</small>
                                                                <small class="text-success text-bold">→
                                                                    {{ $bill->main_to }}</small><br>
                                                                @if($bill->has_return)
                                                                    <small class="text-bold">Return:</small>
                                                                    <small
                                                                        class="text-danger text-bold">{{ $bill->return_from }}</small>
                                                                    <small class="text-danger text-bold">→
                                                                        {{ $bill->return_to }}</small>
                                                                @endif

                                                            </td>

                                                            <td>
                                                                <div class="badge bg-primary">
                                                                    <small class="text-bold">Total km:</small>
                                                                    <small
                                                                        class="text-bold">{{ number_format($bill->main_distance + ($bill->return_distance ?? 0), 2)  }}
                                                                        km</small>
                                                                </div><br>
                                                                <small class="text-bold">Main:</small>
                                                                <small class="text-success text-bold">{{ $bill->main_distance }}
                                                                    km</small><br>

                                                                @if($bill->has_return)
                                                                    <small class="text-bold">Return:</small>
                                                                    <small
                                                                        class="text-danger text-bold">{{ $bill->return_distance }}
                                                                        km</small>
                                                                @endif
                                                            </td>


                                                            <td>
                                                                <div class="badge bg-primary">
                                                                    <small class="text-bold">Total Amount:</small>
                                                                    <small
                                                                        class="text-bold">₹{{ number_format($bill->total_main_amount + ($bill->total_return_amount ?? 0), 2) }}
                                                                        (Staff:
                                                                        {{ $bill->members->count() }})</small>
                                                                </div><br>
                                                                <small class="text-bold">Main:</small>
                                                                <small
                                                                    class="text-success text-bold">₹{{ number_format(($bill->total_main_amount ?? 0), 2) }}</small><small
                                                                    class="text-info">
                                                                    (₹2.5×{{ $bill->main_distance }}km×{{ $bill->members->count() }}
                                                                    staff)</small><br>
                                                                @if($bill->has_return)
                                                                    <small class="text-bold">Return:</small>
                                                                    <small
                                                                        class="text-danger text-bold">₹{{ number_format(($bill->total_return_amount ?? 0), 2) }}</small><small
                                                                        class="text-info">
                                                                        (₹2.5×{{ $bill->return_distance }}km×{{ $bill->members->count() }}
                                                                        staff)</small><br>
                                                                @endif
                                                            </td>

                                                            <td>
                                                                @if($bill->main_bill_url)
                                                                    <a href="{{ route('preview.file', ['path' => $bill->main_bill_path]) }}"
                                                                        target="_blank" class="badge btn-success d-block mb-1">
                                                                        Main Bill
                                                                    </a>
                                                                @else
                                                                    <span class="badge bg-secondary d-block mb-1">Main: Not
                                                                        uploaded</span>
                                                                @endif

                                                                @if($bill->has_return)
                                                                    @if($bill->return_bill_url)
                                                                        <a href="{{ route('preview.file', ['path' => $bill->return_bill_path]) }}"
                                                                            target="_blank" class="badge btn-danger d-block mb-1">
                                                                            Return Bill
                                                                        </a>
                                                                    @else
                                                                        <span class="badge bg-secondary d-block mb-1">Return: Not
                                                                            uploaded</span>
                                                                    @endif
                                                                @endif
                                                            </td>

                                                            <td>
                                                                <span
                                                                    class="badge bg-{{ $bill->status == 'Approved' ? 'success' : ($bill->status == 'Pending' ? 'warning' : 'danger') }}">
                                                                    {{ $bill->status }}
                                                                </span>

                                                                @if($bill->remarks)
                                                                    <br>
                                                                    <small class="text-muted">
                                                                        {{ $bill->remarks }}
                                                                    </small>
                                                                @endif
                                                            </td>

                                                            {{-- Edit Button --}}
                                                            @php
                                                                $roleId = Auth::user()->role_id;
                                                            @endphp
                                                            @if($roleId == 3)
                                                                <td>
                                                                    @if($bill->status !== 'Approved')
                                                                        {{-- Edit allowed for Pending & Rejected --}}
                                                                        <button class="btn btn-sm btn-warning mt-1" data-toggle="modal"
                                                                            data-target="#editModal{{ $bill->id }}" onclick='loadEditStaff(
                                                                                            {{ $bill->id }},
                                                                                            {{ $bill->school_id }},
                                                                                            @json(
                                                                                                $bill->members->map(fn($m) => $m->role . "_" . $m->member_id)
                                                                                            )
                                                                                                )' title="Edit Bill">
                                                                            <i class="fas fa-edit"></i>
                                                                        </button>
                                                                        <form action="{{ route('campTravel.delete', $bill->id) }}"
                                                                            method="POST"
                                                                            onsubmit="return confirm('Are you sure you want to delete this record?')"
                                                                            class="d-inline">
                                                                            @csrf
                                                                            <button class="btn btn-sm btn-danger mt-1"
                                                                                title="Delete Bill">
                                                                                <i class="fas fa-trash"></i>
                                                                            </button>
                                                                        </form>

                                                                    @else
                                                                        {{-- Edit disabled when Approved --}}
                                                                        <button class="btn btn-sm btn-secondary mt-1"
                                                                            title="Approved bills cannot be edited" disabled>
                                                                            <i class="fas fa-edit"></i>
                                                                        </button>
                                                                        <button class="btn btn-sm btn-danger mt-1"
                                                                            onclick="if(confirm('Are you sure you want to delete this record?')) { window.location='{{ route('camp.expense.delete', $bill->id) }}' }"
                                                                            title="Approved bills cannot be deleted" disabled>
                                                                            <i class="fas fa-trash"></i>
                                                                        </button>
                                                                    @endif
                                                                </td>

                                                            @elseif(in_array(Auth::user()->role_id, [8]))
                                                                <td>
                                                                    @if($bill->status == 'Pending')

                                                                        <form action="{{ route('campTravel.approve', $bill->id) }}"
                                                                            method="POST" class="d-inline">
                                                                            @csrf
                                                                            @if($allCompleted)
                                                                                <button class="btn btn-success btn-sm mt-1" title="Approve">
                                                                                    <i class="fas fa-check"></i>
                                                                                </button>
                                                                            @else
                                                                                <button class="btn btn-success btn-sm mt-1" title="Completion of all uploads before approval" disabled>
                                                                                    <i class="fas fa-check"></i>
                                                                                </button>
                                                                            @endif
                                                                        </form>

                                                                        <button class="btn btn-danger btn-sm mt-1" data-toggle="modal"
                                                                            data-target="#rejectModal{{ $bill->id }}" title="Reject">
                                                                            <i class="fas fa-times"></i>
                                                                        </button>


                                                                    @else
                                                                        {{---- SHOW REVERT BUTTON WHEN APPROVED OR REJECTED----}}
                                                                        <form action="{{ route('campTravel.revert', $bill->id) }}"
                                                                            method="POST">
                                                                            @csrf
                                                                            <button class="btn btn-warning btn-sm mt-1" title="Revert">
                                                                                <i class="fas fa-undo"></i>
                                                                            </button>
                                                                        </form>

                                                                    @endif
                                                                </td>
                                                            @endif
                                                        </tr>

                                                        <!-- Reject Modal -->
                                                        <div class="modal fade" id="rejectModal{{ $bill->id }}">
                                                            <div class="modal-dialog">
                                                                <form action="{{ route('campTravel.reject', $bill->id) }}"
                                                                    method="POST">
                                                                    @csrf

                                                                    <div class="modal-content">
                                                                        <div class="modal-header bg-danger text-white">
                                                                            <h5 class="modal-title">Reject Travel Bill</h5>
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

                                                    @endforeach
                                                @endforeach
                                            </tbody>
                                        </table>
                                        <div class="modal fade" id="staffModal" tabindex="-1">
                                            <div class="modal-dialog modal-md">
                                                <div class="modal-content">

                                                    <div class="modal-header bg-info text-white">
                                                        <h5 class="modal-title">Staff Details</h5>
                                                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                                                    </div>

                                                    <div class="modal-body">
                                                        <div id="staffModalBody">
                                                            <p class="text-muted">Loading staff…</p>
                                                        </div>
                                                    </div>

                                                    <div class="modal-footer">
                                                        <button class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                        {{-- Edit --}}
                                        @foreach($records as $bill)
                                            <div class="modal fade" id="editModal{{ $bill->id }}" tabindex="-1">
                                                <div class="modal-dialog modal-lg">
                                                    <form method="POST" action="{{ route('campTravel.update', $bill->id) }}"
                                                        enctype="multipart/form-data">
                                                        @csrf

                                                        <div class="modal-content">
                                                            <div class="modal-header bg-warning">
                                                                <h5 class="modal-title">Edit Travel Bill</h5>
                                                                <button type="button" class="close"
                                                                    data-dismiss="modal">&times;</button>
                                                            </div>


                                                            <div class="modal-body">
                                                                {{-- STAFF SELECTION --}}
                                                                <hr>
                                                                <h6 class="font-weight-bold mb-2">Staffs</h6>

                                                                <div class="border rounded p-2 bg-light">
                                                                    <div id="editStaffBox{{ $bill->id }}">
                                                                        <p class="text-muted">Loading staff…</p>
                                                                    </div>
                                                                </div>
                                                                {{-- MAIN TRAVEL --}}
                                                                <h6 class="font-weight-bold">Main Travel</h6>
                                                                <div class="row">
                                                                    <div class="col-md-6">
                                                                        <label>From</label>
                                                                        <input type="text" name="main_from"
                                                                            value="{{ $bill->main_from }}"
                                                                            class="form-control" required>
                                                                    </div>

                                                                    <div class="col-md-6">
                                                                        <label>To</label>
                                                                        <input name="main_to"
                                                                        id="main_to"
                                                                            value="{{ $bill->main_to }}"
                                                                            class="w-100 border p-2 rounded bg-gray-100"
                                                                            value="{{ $bill->school->scm_name }}" readonly>
                                                                    </div>

                                                                    <div class="col-md-4 mt-2">
                                                                        <label>Distance (KM)</label>
                                                                        <input type="number" step="0.01"
                                                                            name="main_distance"
                                                                            value="{{ $bill->main_distance }}"
                                                                            class="form-control" required>
                                                                    </div>

                                                                    <div class="col-md-4 mt-2">
                                                                        <label>Amount</label>
                                                                        <input type="number" step="0.01" name="main_amount"
                                                                            value="{{ $bill->main_amount }}"
                                                                            class="form-control" readonly>
                                                                    </div>

                                                                    <div class="col-md-4 mt-2">
                                                                        <label>Total Amount</label>
                                                                        <input type="number" step="0.01"
                                                                            name="total_main_amount"
                                                                            value="{{ $bill->total_main_amount }}"
                                                                            class="form-control" readonly>
                                                                    </div>
                                                                    <div class="col-md-12 mt-2">
                                                                        <label>Main Bill (PDF)</label>

                                                                        @if($bill->main_bill_url)
                                                                            <div class="mb-1">
                                                                                <a href="{{ route('preview.file', ['path' => $bill->main_bill_path]) }}"
                                                                                    target="_blank" class="badge btn-success">
                                                                                    View Current Main Bill
                                                                                </a>
                                                                            </div>
                                                                        @endif

                                                                        <input type="file" name="main_bill"
                                                                            accept="application/pdf" class="form-control" data-required-main="true">
                                                                        <small class="text-muted">Leave empty to keep
                                                                            existing bill</small>
                                                                    </div>

                                                                </div>
                                                                <input type="hidden" name="has_return" class="has-return"
                                                                    value="{{ $bill->has_return }}">

                                                                <a type="button"
                                                                    class="btn btn-sm mb-2 toggle-return mt-2
                                                                        {{ $bill->has_return ? 'btn-danger' : 'btn-primary' }}">
                                                                    {{ $bill->has_return ? '- Remove Return Travel' : '+ Add Return Travel Bill' }}
                                                                </a>

                                                                {{-- RETURN TRAVEL --}}
                                                                <div
                                                                    class="return-section {{ $bill->has_return ? '' : 'd-none' }}">
                                                                    <hr>
                                                                    <h6 class="font-weight-bold">Return Travel</h6>

                                                                    <div class="row">
                                                                        <div class="col-md-6">
                                                                            <label>From</label>
                                                                            <input name="return_from"
                                                                                id="return_from"
                                                                                class="w-100 border p-2 rounded bg-gray-100"
                                                                                value="{{ $bill->school->scm_name }}"
                                                                                readonly>
                                                                        </div>

                                                                        <div class="col-md-6">
                                                                            <label>To</label>
                                                                            <input type="text" name="return_to"
                                                                                data-required="true"
                                                                                value="{{ $bill->return_to }}"
                                                                                class="form-control">
                                                                        </div>

                                                                        <div class="col-md-4 mt-2">
                                                                            <label>Distance (KM)</label>
                                                                            <input type="number" step="0.01"
                                                                                name="return_distance" data-required="true"
                                                                                value="{{ $bill->return_distance }}"
                                                                                class="form-control">
                                                                        </div>

                                                                        <div class="col-md-4 mt-2">
                                                                            <label>Amount</label>
                                                                            <input type="number" step="0.01"
                                                                                name="return_amount"
                                                                                value="{{ $bill->return_amount }}"
                                                                                class="form-control" readonly>
                                                                        </div>

                                                                        <div class="col-md-4 mt-2">
                                                                            <label>Total Amount</label>
                                                                            <input type="number" step="0.01"
                                                                                name="total_return_amount"
                                                                                value="{{ $bill->total_return_amount }}"
                                                                                class="form-control" readonly>
                                                                        </div>
                                                                        <div class="col-md-12 mt-2">
                                                                            <label>Return Bill (PDF)</label>

                                                                            @if($bill->return_bill_url)
                                                                                <div class="mb-1">
                                                                                    <a href="{{ route('preview.file', ['path' => $bill->return_bill_path]) }}"
                                                                                        target="_blank"
                                                                                        class="badge btn-danger">
                                                                                        View Current Return Bill
                                                                                    </a>
                                                                                </div>
                                                                            @endif

                                                                            <input type="file" name="return_bill_file"
                                                                                accept="application/pdf"
                                                                                class="form-control">
                                                                            <small class="text-muted">Leave empty to keep
                                                                                existing bill</small>
                                                                        </div>


                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="modal-footer">
                                                                <button class="btn btn-secondary" data-dismiss="modal">
                                                                    Cancel
                                                                </button>
                                                                <button class="btn btn-success">
                                                                    Update
                                                                </button>
                                                            </div>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div id="pagination" class="flex justify-center space-x-2 mt-4"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- Upload Viewer Modal -->
                    <div class="modal fade" id="uploadViewerModal" tabindex="-1">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content">

                                <div class="modal-header bg-info text-white">
                                    <h5 class="modal-title" id="uploadViewerTitle">Uploads</h5>
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                </div>

                                <div class="modal-body">
                                    <div id="uploadList" class="list-group">
                                        <p class="text-muted">Loading…</p>
                                    </div>
                                </div>

                                <div class="modal-footer">
                                    <button class="btn btn-secondary" data-dismiss="modal">Close</button>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
    <script>
        function exportAttendance() {
            // Get the HTML table
            let table = document.getElementById("filterTable");

            // Convert table → worksheet
            let workbook = XLSX.utils.table_to_book(table, { sheet: "Travel" });

            // Download Excel file
            XLSX.writeFile(workbook, "TA_list.xlsx");
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
        function toggleSchool(id) {
            document.querySelectorAll('.' + id).forEach(row => {
                row.classList.toggle('d-none');
            });
            document.getElementById('icon-' + id)
                .classList.toggle('fa-chevron-up');
        }
    </script>
    <script>
        const RATE = 2.5;

        // TOGGLE RETURN (EDIT MODAL)
        $(document).on('click', '.toggle-return', function () {
            let modal = $(this).closest('.modal');
            let section = modal.find('.return-section');
            let hasReturn = modal.find('.has-return');

            if (section.hasClass('d-none')) {
                section.removeClass('d-none');
                hasReturn.val(1);

                section.find('[data-required="true"]').attr('required', true);

                $(this)
                    .text('- Remove Return Travel')
                    .removeClass('btn-primary')
                    .addClass('btn-danger');
            } else {
                section.addClass('d-none');
                hasReturn.val(0);

                section.find('input').val('');
                section.find('[data-required="true"]').removeAttr('required');

                modal.find('input[name="return_amount"]').val('');
                modal.find('input[name="total_return_amount"]').val('');

                $(this)
                    .text('+ Add Return Travel Bill')
                    .removeClass('btn-danger')
                    .addClass('btn-primary');
            }
        });

        // CALCULATIONS (EDIT MODAL)
        $(document).on('input', 'input[name="main_distance"]', function () {
            const modal = $(this).closest('.modal');
            const km = parseFloat(this.value) || 0;
            const staff = getEditStaffCount(modal);

            const amount = km * RATE;

            modal.find('input[name="main_amount"]').val(amount.toFixed(2));
            modal.find('input[name="total_main_amount"]').val((amount * staff).toFixed(2));
        });

        $(document).on('input', 'input[name="return_distance"]', function () {
            const modal = $(this).closest('.modal');
            const km = parseFloat(this.value) || 0;
            const staff = getEditStaffCount(modal);

            const amount = km * RATE;

            modal.find('input[name="return_amount"]').val(amount.toFixed(2));
            modal.find('input[name="total_return_amount"]').val((amount * staff).toFixed(2));
        });
        $(document).on('change', 'input[name="trainer_ids[]"]', function () {
            const modal = $(this).closest('.modal');
            const staff = getEditStaffCount(modal);

            const mainKm = parseFloat(modal.find('input[name="main_distance"]').val()) || 0;
            const returnKm = parseFloat(modal.find('input[name="return_distance"]').val()) || 0;

            const mainAmount = mainKm * RATE;
            const returnAmount = returnKm * RATE;

            modal.find('input[name="total_main_amount"]').val((mainAmount * staff).toFixed(2));

            if (modal.find('.has-return').val() == 1) {
                modal.find('input[name="total_return_amount"]').val((returnAmount * staff).toFixed(2));
            }
        });

    </script>

    <script>
        function loadEditStaff(billId, schoolId, selectedMembers) {
            const box = document.getElementById(`editStaffBox${billId}`);
            box.innerHTML = '<p>Loading…</p>';

            fetch(`/get-people-by-school/${schoolId}`)
                .then(res => res.json())
                .then(data => {
                    box.innerHTML = '';

                    renderEditGroup('Trainer', data.trainers, 'trainer', selectedMembers, billId);
                    renderEditGroup('Coordinator', data.coordinators, 'coordinator', selectedMembers, billId);
                    renderEditGroup('Supporting Staff', data.staff, 'staff', selectedMembers, billId);
                });
        }

        function renderEditGroup(title, items, role, selected, billId) {
            if (!items || items.length === 0) return;

            const box = document.getElementById(`editStaffBox${billId}`);

            box.innerHTML += `
        <div class="mb-2">
            <strong>${title}</strong>
            <div class="d-flex flex-wrap gap-3" id="${role}-edit-${billId}"></div>
        </div>
    `;

            const group = document.getElementById(`${role}-edit-${billId}`);

            items.forEach(item => {
                const key = `${role}_${item.id}`;
                const checked = selected.includes(key) ? 'checked' : '';

                group.innerHTML += `
            <label class="d-flex align-items-center gap-1">
                <input type="checkbox"
                       name="trainer_ids[]"
                       value="${key}"
                       ${checked}>
                ${item.name}
            </label>
        `;
            });
        }
    </script>
    <script>
        const ROLE_LIMITS = {
            trainer: 3,
            coordinator: 1,
            staff: 1
        };

        document.addEventListener('change', function (e) {
            if (e.target.name !== 'trainer_ids[]') return;

            const modal = e.target.closest('.modal'); //  scope to current modal
            const role = e.target.value.split('_')[0];

            const count = modal.querySelectorAll(
                `input[name="trainer_ids[]"]:checked[value^="${role}_"]`
            ).length;

            if (count > ROLE_LIMITS[role]) {
                e.target.checked = false;
                alert(`Maximum ${ROLE_LIMITS[role]} ${role}(s) allowed`);
            }
        });

        function getEditStaffCount(modal) {
            return modal.find('input[name="trainer_ids[]"]:checked').length;
        }
    </script>
    <script>
function openStaffModal(billId) {
    $('#staffModal').modal('show');

    const body = document.getElementById('staffModalBody');
    body.innerHTML = '<p class="text-muted">Loading staff…</p>';

    fetch(`/camp-travel/${billId}/staff`)
        .then(res => res.json())
        .then(data => {
            if (!data.length) {
                body.innerHTML = '<p class="text-danger">No staff found</p>';
                return;
            }

            let html = `
                <table class="table table-bordered">
                    <thead class="table-light">
                        <tr>
                            <th>Sno</th>
                            <th>Name</th>
                            <th>Role</th>
                        </tr>
                    </thead>
                    <tbody>
            `;

            data.forEach((item, index) => {
                html += `
                    <tr>
                        <td>${index + 1}</td>
                        <td>${item.name}</td>
                        <td class="text-capitalize">${item.role.replace('_',' ')}</td>
                    </tr>
                `;
            });

            html += '</tbody></table>';

            body.innerHTML = html;
        })
        .catch(() => {
            body.innerHTML = '<p class="text-danger">Failed to load staff</p>';
        });
}
</script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const schoolName = @json($bill->school->scm_name ?? '');

    const mainTo = document.getElementById('main_to');
    const returnFrom = document.getElementById('return_from');

    if (mainTo) mainTo.value = schoolName;
    if (returnFrom) returnFrom.value = schoolName;
});
</script>
<script>
        function loadUploads(schoolId, type, title) {

            let url = "{{ route('school.uploads.byType', ['schoolId' => '__ID__', 'type' => '__TYPE__']) }}"
                .replace('__ID__', schoolId)
                .replace('__TYPE__', type);

            fetch(url)
                .then(res => {
                    if (!res.ok) {
                        console.error('HTTP Error:', res.status);
                        throw new Error('Request failed');
                    }
                    return res.json();
                })
                .then(files => {
                    console.log('FILES:', files); // DEBUG

                    document.getElementById('uploadViewerTitle').innerText = title;
                    const list = document.getElementById('uploadList');
                    list.innerHTML = '';

                    if (!files.length) {
                        list.innerHTML = `<div class="text-muted">No files uploaded</div>`;
                        $('#uploadViewerModal').modal('show');
                        return;
                    }

                    files.forEach(file => {
                        const filename = file.school.replace(/[^A-Za-z0-9_\-]/g, '_') + '_' + file.file_type;

                        list.innerHTML += `
                            <a href="/preview-files?path=${encodeURIComponent(file.file_path)}
                            &filename=${filename}" target="_blank"
                            class="list-group-item list-group-item-action">
                                <i class="fas fa-eye mr-2 text-primary"></i>
                                Uploaded on ${new Date(file.created_at).toLocaleDateString()}
                            </a>
                        `;
                    });

                    $('#uploadViewerModal').modal('show');
                })
                .catch(err => {
                    console.error(err);
                    alert('Unable to load uploads');
                });

        }
        function openStudentFeedback(schoolId) {
            const url = "{{ route('student.feedback') }}?school_id=" + schoolId;
            window.location.href = url;
        }

        function openInstituteFeedback(schoolId) {
            const url = "{{ route('institute.feedback.entry') }}?school_id=" + schoolId;
            window.location.href = url;
        }
    </script>

</body>

@include('components.footer')