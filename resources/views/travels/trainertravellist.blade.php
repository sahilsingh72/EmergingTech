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
                            <h1 class="m-0 text-dark">Trainer Travel & Allowance</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Finance & Bills</a></li>
                                <li class="breadcrumb-item active">Trainer Travel & Allowance</li>
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
                            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                                <div class="bg-white rounded-lg w-full">
                                    <!-- Title -->
                                    <h2 class="text-2xl font-semibold text-center mb-6">Trainer Travel & Allowance
                                        Requests</h2>
                                    <div
                                        class="bg-blue-100 text-blue-700 text-center px-4 py-2 rounded mb-4 font-semibold">
                                        Note: All trainers who attended the OKCL training session were given
                                        <strong>₹500</strong> as setting charges.
                                    </div>

                                    @if(auth()->user()->role->name == 'Accounts' || auth()->user()->role->name == 'OKCL' || auth()->user()->role->name == 'DLC')
                                        <form method="GET" action="{{ route('trainer.travel.list') }}"
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
                                        @if(auth()->user()->role->name == 'Accounts' || auth()->user()->role->name == 'OKCL')
                                            @if(!$selectedDistrict)
                                                <div class="alert alert-info">
                                                    Please select a district to view district's travel allowance requests.
                                                </div>
                                            @endif
                                        @endif
                                    @endif


                                    <table class="table table-bordered">
                                        <thead class="table-secondary">
                                            <tr>
                                                <th>Sno</th>
                                                <th>District</th>
                                                <th>Trainer</th>
                                                <th>Specialization</th>
                                                <th>Main Travel</th>
                                                <th>Return Travel</th>
                                                <th>Total Amount</th>
                                                <th>Bill File</th>
                                                <th>Applied On</th>
                                                <th>Training Date</th>
                                                <th>Status</th>
                                                @if(in_array(Auth::user()->role_id, [3, 8]))
                                                    <th>Action</th>
                                                @endif
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @forelse($records as $index => $row)
                                                <tr>
                                                    <td>{{ $index + 1 }}</td>
                                                    <td>{{ $row->district->DSM_DSNM ?? '-' }}</td>
                                                    <td>{{ $row->trainer->trainer_name ?? '-' }}</td>
                                                    <td>{{ $row->specialization }}</td>

                                                    <td>
                                                        <strong>{{ $row->main_from }} → {{ $row->main_to }}</strong><br>
                                                        Mode: {{ $row->main_mode }}<br>
                                                        Amount: ₹{{ $row->main_amount }}
                                                    </td>

                                                    <td>
                                                        @if($row->has_return)
                                                            <strong>{{ $row->return_from }} → {{ $row->return_to }}</strong><br>
                                                            Mode: {{ $row->return_mode }}<br>
                                                            Amount: ₹{{ $row->return_amount }}
                                                        @else
                                                            <span class="text-muted">No return</span>
                                                        @endif
                                                    </td>

                                                    <td>
                                                        ₹{{ $row->main_amount + ($row->return_amount ?? 0) }}
                                                    </td>

                                                    <td>
                                                        {{-- Main Bill --}}
                                                        @if($row->main_bill_url)
                                                            <a href="{{ route('trainer.travel.preview', ['path' => $row->main_bill]) }}"
                                                                target="_blank" class="btn btn-sm btn-primary mb-1 w-full">
                                                                View Main Bill
                                                            </a>
                                                        @else
                                                            <span class="badge bg-secondary d-block mb-1">Main: Not
                                                                uploaded</span>
                                                        @endif


                                                        {{-- Show Return Section **only when return is applied** --}}
                                                        @if($row->has_return)

                                                            @if($row->return_bill_url)
                                                                <a href="{{ route('preview.file', ['path' => $row->return_bill_file]) }}"
                                                                    target="_blank" class="btn btn-sm btn-success w-full">
                                                                    View Return Bill
                                                                </a>
                                                            @else
                                                                <span class="badge bg-secondary d-block">Return: Not uploaded</span>
                                                            @endif

                                                        @endif

                                                    </td>
                                                    <td>{{ $row->created_at->format('d M, Y') }}</td>
                                                    <td>
                                                        {{ $row->training_date ? date('d-m-Y', strtotime($row->training_date)) : 'Not Set' }}

                                                        @if(auth()->user()->role_id == 8)
                                                            <!-- Accounts Can Edit Training Date -->
                                                            <form
                                                                action="{{ route('trainerTravel.updateTrainingDate', $row->id) }}"
                                                                method="POST" class="mt-2">
                                                                @csrf
                                                                <input type="date" name="training_date"
                                                                    class="form-control form-control-sm"
                                                                    value="{{ $row->training_date }}">

                                                                <button class="btn btn-sm btn-warning mt-1">
                                                                    Update Date
                                                                </button>
                                                            </form>
                                                        @endif
                                                    </td>

                                                    <td>
                                                        {{-- <span class="badge bg-warning">Pending</span> --}}
                                                        @if($row->status == 'Pending')
                                                            <span class="badge bg-warning">Pending</span>
                                                        @elseif($row->status == 'Approved')
                                                            <span class="badge bg-success">Approved</span>
                                                        @else
                                                            <span class="badge bg-danger">Rejected</span>
                                                        @endif

                                                        @if($row->status_updated_by)
                                                            <br>
                                                            <small class="text-muted">
                                                                Updated by: {{ optional($row->statusUpdatedByUser)->name }} <br>
                                                                Remarks: {{ $row->remarks }} <br>
                                                            </small>
                                                        @endif
                                                    </td>

                                                    <td>
                                                        {{-- Edit Button --}}
                                                        @php
                                                            $roleId = Auth::user()->role_id;
                                                        @endphp
                                                        @if($roleId == 3)
                                                            <button class="btn btn-sm btn-warning mt-1 w-full"
                                                                onclick="openEditModal({{ $row->id }})">
                                                                Edit
                                                            </button>
                                                        @endif
                                                        @if(in_array(Auth::user()->role_id, [8]))
                                                            @if($row->status == 'Pending')

                                                                <form action="{{ route('trainerTravel.approve', $row->id) }}"
                                                                    method="POST" class="d-inline">
                                                                    @csrf
                                                                    <button
                                                                        class="btn btn-success btn-sm mt-1 w-full">Approve</button>
                                                                </form>

                                                                <button class="btn btn-danger btn-sm mt-1 w-full"
                                                                    data-toggle="modal" data-target="#rejectModal{{ $row->id }}">
                                                                    Reject
                                                                </button>

                                                            @else

                                                                {{-- -------- SHOW REVERT BUTTON WHEN APPROVED OR REJECTED --------
                                                                --}}
                                                                <form action="{{ route('trainerTravel.revert', $row->id) }}"
                                                                    method="POST">
                                                                    @csrf
                                                                    <button class="btn btn-warning btn-sm mt-1 w-full">
                                                                        Revert
                                                                    </button>
                                                                </form>
                                                            @endif
                                                            <!-- Reject Modal -->
                                                            <div class="modal fade" id="rejectModal{{ $row->id }}">
                                                                <div class="modal-dialog">
                                                                    <form action="{{ route('trainerTravel.reject', $row->id) }}"
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
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr>
                                                    <td colspan="10" class="text-center text-muted">No Travel Claims
                                                        Submitted</td>
                                                </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
        </section>
    </div>
    </div>
    <!-- Edit Travel Modal -->
    <div id="editModal"
        class="content-wrapper fixed inset-0 hidden z-50 bg-black bg-opacity-50 flex items-center justify-center px-2">
        <div class="bg-white rounded-lg shadow-lg max-w-5xl w-full max-h-[80vh] overflow-y-auto p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold mb-4">Edit Travel Bill</h3>
            </div>
            <form id="editForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <input type="hidden" name="record_id" id="editRecordId">

                <!-- Main Travel -->
                <div class="border rounded p-4 mb-4 bg-gray-50">
                    <h4 class="font-bold mb-2 text-center">Main Travel</h4>
                    <hr><br>
                    <div class="grid grid-cols-2 gap-4 mb-4">
                        <div>
                            <label class="block font-semibold mb-1">From</label>
                            <input type="text" name="main_from" id="edit_main_from" class="w-full border p-2 rounded"
                                required>
                        </div>
                        <div>
                            <label class="block font-semibold mb-1">To</label>
                            <input type="text" name="main_to" id="edit_main_to" class="w-full border p-2 rounded"
                                readonly>
                        </div>
                        <div>
                            <label class="block font-semibold mb-1">Date of Travel</label>
                            <input type="date" name="main_date" id="edit_main_date" class="w-full border p-2 rounded"
                                required>
                        </div>
                        <div>
                            <label class="block font-semibold mb-1">Mode of Travel</label>
                            <select name="main_mode" id="edit_main_mode" class="w-full border p-2 rounded" required>
                                <option>Bus</option>
                                <option>Train</option>
                                <option>Bike (₹7/km)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-semibold mb-1">Amount (₹)</label>
                            <input type="number" name="main_amount" id="edit_main_amount"
                                class="w-full border p-2 rounded" required>
                        </div>
                        <div>
                            <label class="block font-semibold mb-1">Main Bill (PDF)</label>
                            <input type="file" name="main_bill" id="edit_main_bill" accept="application/pdf">
                        </div>
                    </div>
                </div>
                <!-- Return Travel -->
                <div id="editReturnSection" class="border rounded p-4 mb-4 bg-gray-50 hidden">
                    <h4 class="font-bold mb-2 text-center">Return Travel</h4>
                    <hr><br>
                    <input type="hidden" name="has_return" id="editHasReturn" value="0">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block font-semibold mb-1">Return From</label>
                            <input type="text" name="return_from" id="edit_return_from"
                                class="w-full border p-2 rounded" readonly>
                        </div>
                        <div>
                            <label class="block font-semibold mb-1">Return To</label>
                            <input type="text" name="return_to" id="edit_return_to" class="w-full border p-2 rounded"
                                placeholder="full address">
                        </div>
                        <div>
                            <label class="block font-semibold mb-1">Mode of Travel</label>
                            <select name="return_mode" id="edit_return_mode" class="w-full border p-2 rounded">
                                <option value="">-- Select Mode --</option>
                                <option>Bus</option>
                                <option>Train</option>
                                <option>Bike (₹7/km)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block font-semibold mb-1">Amount (₹)</label>
                            <input type="number" name="return_amount" id="edit_return_amount"
                                class="w-full border p-2 rounded" placeholder="Amount">
                        </div>
                        <div>
                            <label class="block font-semibold mb-1">Return Bill (PDF)</label>
                            <input type="file" name="return_bill_file" id="edit_return_bill" accept="application/pdf">
                        </div>
                    </div>
                </div>

                <button type="button" id="editAddReturnBtn"
                    class="mb-4 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded hidden">
                    + Add Return Travel
                </button>


                <div class="mt-4 flex justify-end gap-2">
                    <button type="button" class="bg-gray-400 px-4 py-2 rounded"
                        onclick="closeEditModal()">Cancel</button>
                    <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Update</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openEditModal(id) {
            const records = @json($records);
            const data = records.find(r => r.id === id);
            if (!data) return;

            // Fill Main Travel
            document.getElementById('editRecordId').value = data.id;
            document.getElementById('edit_main_from').value = data.main_from;
            document.getElementById('edit_main_to').value = data.main_to;
            document.getElementById('edit_main_date').value = data.main_date;
            document.getElementById('edit_main_mode').value = data.main_mode;
            document.getElementById('edit_main_amount').value = data.main_amount;

            // Fill Return Travel if exists
            const returnSection = document.getElementById('editReturnSection');
            const addReturnBtn = document.getElementById('editAddReturnBtn');
            const hasReturn = data.has_return;

            if (hasReturn) {
                returnSection.classList.remove('hidden');
                addReturnBtn.classList.add('hidden');

                document.getElementById('editHasReturn').value = 1;
                document.getElementById('edit_return_from').value = data.return_from;
                document.getElementById('edit_return_to').value = data.return_to;
                document.getElementById('edit_return_mode').value = data.return_mode;
                document.getElementById('edit_return_amount').value = data.return_amount;
            } else {
                returnSection.classList.add('hidden');
                addReturnBtn.classList.remove('hidden');
                document.getElementById('editHasReturn').value = 0;
            }

            // Set form action dynamically
            document.getElementById('editForm').action = '/trainer-travel/' + data.id;

            document.getElementById('editModal').classList.remove('hidden');
        }

        // Close modal
        function closeEditModal() {
            document.getElementById('editModal').classList.add('hidden');
        }

        // Add Return Travel if user clicks
        document.getElementById('editAddReturnBtn').addEventListener('click', function () {
            document.getElementById('editReturnSection').classList.remove('hidden');
            document.getElementById('editHasReturn').value = 1;
            this.classList.add('hidden');

            // Make required fields
            ['edit_return_to', 'edit_return_mode', 'edit_return_amount'].forEach(id => {
                const el = document.getElementById(id);
                if (el) el.setAttribute('required', true);
            });
        });
    </script>


</body>
@include('components.footer')