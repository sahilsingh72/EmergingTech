@include('components.navbar')
@include('components.sidebar')
<style>
    .sort::after {
        content: " ⇅";
        font-size: 0.7rem;
        color: gray;
    }

    #coordinatorTable {
        table-layout: auto;
        /* allow natural sizing */
        width: 100%;
        /* still stretch full table */
    }

    #coordinatorTable th,
    #coordinatorTable td {
        white-space: nowrap;
        /* prevent text wrapping */
    }
</style>

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
                            <h1 class="m-0 text-dark">Coordinator List</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Coordinator List</a></li>
                                <li class="breadcrumb-item active">Coordinator & Trainer</li>
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
                        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
                            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                                <div class="bg-white p-8 rounded-lg w-full">
                                    <!-- Title -->
                                    <h2 class="text-2xl font-semibold text-center mb-6"></i>Coordinator List</h2>
                                    <div class="mb-4 flex justify-end">
                                        <button id="addCoordinatorBtn"
                                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg shadow-md flex items-center gap-2">
                                            <i class="fas fa-user-plus"></i> Add Coordinator
                                        </button>
                                    </div>
                                    @if (session('success'))
                                        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                                            {{ session('success') }}
                                        </div>
                                    @endif

                                    @if (session('error'))
                                        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                                            {{ session('error') }}
                                        </div>
                                    @endif
                                    <div class="flex justify-between items-center mb-4">
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
                                                class="border rounded p-2 w-64">
                                        </div>
                                    </div>

                                    <!-- Coordinator Table -->
                                    <div class="bg-white shadow rounded-lg p-4 overflow-x-auto">
                                        <table id="coordinatorTable" class="w-full border-collapse">
                                            <thead>
                                                <tr class="bg-gray-100 text-left">
                                                    <th class="p-2 border">SNO</th>
                                                    <th class="p-2 border">Name</th>
                                                    <th class="p-2 border">Email</th>
                                                    <th class="p-2 border">Phone</th>
                                                    <th class="p-2 border">Photo</th>
                                                    <th class="p-2 border">CV</th>
                                                    <th class="p-2 border">Experience</th>
                                                    <th class="p-2 border">Education Certificates</th>
                                                    <th class="p-2 border">Aadhaar Card</th>
                                                    <th class="p-2 border text-center">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($coordinators as $index => $coordinator)
                                                    <tr>
                                                        <td class="p-2 border">{{ $index + 1 }}</td>
                                                        <td class="p-2 border">{{ $coordinator->coordinator_name }}</td>
                                                        <td class="p-2 border">{{ $coordinator->email }}</td>
                                                        <td class="p-2 border">{{ $coordinator->phone }}</td>

                                                        {{-- Photo --}}
                                                        <td class="p-2 border">
                                                            @if ($coordinator->photo)
                                                                <a href="{{ asset('storage/' . $coordinator->photo) }}"
                                                                    target="_blank">
                                                                    <img src="{{ asset('storage/' . $coordinator->photo) }}"
                                                                        alt="Coordinator Photo"
                                                                        class="h-12 w-12 object-cover rounded-full mx-auto hover:scale-110 transition">
                                                                </a>
                                                            @else
                                                                -
                                                            @endif
                                                        </td>
                                                        <td class="p-2 border">
                                                            @if ($coordinator->cv)
                                                                <a href="{{ asset('storage/' . $coordinator->cv) }}"
                                                                    target="_blank" class="text-blue-600">View CV</a>
                                                            @else
                                                                -
                                                            @endif
                                                        </td>

                                                        {{-- Experience --}}
                                                        <td class="p-2 border">
                                                            @if ($coordinator->experience_certificate)
                                                                <a href="{{ asset('storage/' . $coordinator->experience_certificate) }}"
                                                                    target="_blank" class="text-blue-600">View</a>
                                                            @else
                                                                -
                                                            @endif
                                                        </td>


                                                        {{-- Education Certificates --}}
                                                        <td>
                                                            @php
                                                                $educationCertificates = [];
                                                                if (is_string($coordinator->education_certificates)) {
                                                                    $educationCertificates =
                                                                        json_decode(
                                                                            $coordinator->education_certificates,
                                                                            true,
                                                                        ) ?? [];
                                                                } elseif (is_array($coordinator->education_certificates)) {
                                                                    $educationCertificates =
                                                                        $coordinator->education_certificates;
                                                                }
                                                            @endphp

                                                            @if (count($educationCertificates) > 0)
                                                                @foreach ($educationCertificates as $certificate)
                                                                    <a href="{{ asset('storage/' . $certificate) }}"
                                                                        target="_blank"
                                                                        class="text-blue-600 block">View</a>
                                                                @endforeach
                                                            @else
                                                                -
                                                            @endif
                                                        </td>
                                                        <td class="p-2 border">
                                                            @if ($coordinator->aadhar_card)
                                                                <a href="{{ asset('storage/' . $coordinator->aadhar_card) }}"
                                                                    target="_blank" class="text-blue-600">View</a>
                                                            @else
                                                                -
                                                            @endif
                                                        </td>
                                                        {{-- Actions --}}
                                                        <td class="p-2 border text-center">
                                                            <button type="button" class="text-green-500 mx-1 editBtn"
                                                                data-id="{{ $coordinator->coordinator_id }}"
                                                                data-name="{{ $coordinator->coordinator_name }}"
                                                                data-email="{{ $coordinator->email }}"
                                                                data-phone="{{ $coordinator->phone }}"
                                                                data-whatsapp_number="{{ $coordinator->whatsapp_number}}"
                                                                data-dist_id="{{ $coordinator->dist_id }}"
                                                                data-district="{{ $coordinator->district }}"
                                                                data-pincode="{{ $coordinator->pincode}}"
                                                                data-address="{{ $coordinator->address}}">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                            <button type="button" class="text-red-500 mx-1 deleteBtn"
                                                                data-id="{{ $coordinator->coordinator_id }}">
                                                                <i class="fas fa-trash-alt"></i>
                                                            </button>
                                                        </td>
                                                    </tr>
                                                @endforeach

                                            </tbody>
                                        </table>
                                    </div>

                                    <div id="pagination" class="flex justify-center space-x-2 mt-4"></div>
                                    <!-- Add Coordinator Modal -->
                                    <div id="addCoordinatorModal"
                                        class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
                                        <div
                                            class="bg-white rounded-lg shadow-lg  max-w-5xl p-6 max-h-[90vh] overflow-y-auto">

                                            <!-- Header -->
                                            <div class="flex justify-between items-center mb-4">
                                                <h3 class="text-xl font-semibold">Add Coordinator</h3>
                                                <button id="closeModal"
                                                    class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
                                            </div>


                                            @if ($errors->any())
                                                <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                                                    <strong>Whoops! Something went wrong:</strong>
                                                    <ul class="mt-2 list-disc list-inside">
                                                        @foreach ($errors->all() as $error)
                                                            <li>{{ $error }}</li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            @endif
                                            <!-- Form -->
                                            <form id="coordinatorForm" method="POST"
                                                action="{{ route('coordinators.store') }}" enctype="multipart/form-data">
                                                @csrf
                                                <div class="grid grid-cols-2 gap-8">
                                                    <!-- Left Column: Coordinator Info -->
                                                    <div class="space-y-4">
                                                        <div>
                                                            <label
                                                                class="block text-sm font-medium text-gray-700">Coordinator Name</label>
                                                            <input type="text" name="coordinator_name"
                                                                class="w-full border rounded p-2 mt-1" required>
                                                        </div>
                                                        <div>
                                                            <label
                                                                class="block text-sm font-medium text-gray-700">Email</label>
                                                            <input type="email" name="email"
                                                                class="w-full border rounded p-2 mt-1" required>
                                                        </div>
                                                        <div>
                                                            <label
                                                                class="block text-sm font-medium text-gray-700">District</label>
                                                            <select name="dist_id" id="districtSelect" class="w-full border rounded p-2 mt-1" required>
                                                                <option value="">-- Select District --</option>
                                                                @foreach ($districts as $district)
                                                                    <option value="{{ $district->DSM_DSCD }}" data-name="{{ $district->DSM_DSNM }}">
                                                                        {{ $district->DSM_DSNM }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                        
                                                        <!-- hidden input to store district name -->
                                                        <input type="hidden" name="district" id="districtName">

                                                    </div>

                                                    <!-- Right Column: File Uploads -->
                                                    <div class="space-y-4">
                                                        <div>
                                                            <label
                                                                class="block text-sm font-medium text-gray-700">Phone Number</label>
                                                            <input type="text" name="phone"
                                                                class="w-full border rounded p-2 mt-1">
                                                        </div>
                                                        <div>
                                                            <label
                                                                class="block text-sm font-medium text-gray-700">WhatsApp Number</label>
                                                            <input type="text" name="whatsapp_number"
                                                                class="w-full border rounded p-2 mt-1">
                                                        </div>
                                                        <div>
                                                            <label
                                                                class="block text-sm font-medium text-gray-700">Pincode</label>
                                                            <input type="text" name="pincode"
                                                                class="w-full border rounded p-2" placeholder="Enter 6-digit Pincode">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="grid grid-cols-1">
                                                    <div>
                                                        <label
                                                            class="block text-sm font-medium text-gray-700 mt-3">Address (Enter full address)</label>
                                                        <textarea name="address" rows="3" class="w-full border rounded p-2 mt-1"></textarea>
                                                    </div>
                                                </div>
                                                <div class="grid grid-cols-2 gap-8 mt-3">
                                                    <!-- Left Column: Coordinator Info -->
                                                    <div class="space-y-4">
                                                        
                                                       
                                                        <div>
                                                            <label class="block text-sm font-medium text-gray-700">CV /
                                                                Resume</label>
                                                            <input type="file" name="cv"
                                                                accept=".pdf,.doc,.docx"
                                                                class="w-full border rounded p-2 mt-1">
                                                            <p class="text-[12px] text-gray-600">*Allowed formats: PDF,
                                                                DOC, DOCX. Max size: 2 MB</p>
                                                        </div>
                                                        <div>
                                                            <label
                                                                class="block text-sm font-medium text-gray-700">Educational
                                                                Qualification Certificates</label>
                                                            <input type="file" name="education_certificates[]"
                                                                multiple accept=".pdf,.jpg,.jpeg,.png"
                                                                class="w-full border rounded p-2 mt-1">
                                                            <p class="text-[12px] text-gray-600">*Allowed formats: PDF,
                                                                JPG, PNG. Max size: 2 MB</p>
                                                        </div>
                                                        
                                                        <div>
                                                            <label class="block text-sm font-medium text-gray-700">Aadhaar Card (with address in one pdf)</label>
                                                            <input type="file" name="aadhar_card"
                                                                accept=".pdf,.doc,.docx"
                                                                class="w-full border rounded p-2 mt-1">
                                                            <p class="text-[12px] text-gray-600">*Allowed formats: PDF,
                                                                DOC, DOCX. Max size: 2 MB</p>
                                                        </div>
                                                    </div>

                                                    <!-- Right Column: File Uploads -->
                                                    <div class="space-y-4">

                                                        <div>
                                                            <label
                                                                class="block text-sm font-medium text-gray-700">Experience
                                                                Certificate</label>
                                                            <input type="file" name="experience_certificate"
                                                                accept=".pdf,.jpg,.jpeg,.png"
                                                                class="w-full border rounded p-2 mt-1">
                                                            <p class="text-[12px] text-gray-600">*Allowed formats: PDF,
                                                                JPG, PNG. Max size: 2 MB</p>
                                                        </div>
                                                        <div>
                                                            <label
                                                                class="block text-sm font-medium text-gray-700">Photo</label>
                                                            <input type="file" name="photo"
                                                                accept=".jpg,.jpeg,.png"
                                                                class="w-full border rounded p-2 mt-1">
                                                            <p class="text-[12px] text-gray-600">*Allowed formats: JPG,
                                                                PNG. Max size: 2 MB</p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Buttons -->
                                                <div class="mt-6 flex justify-end gap-3">
                                                    <button type="button" id="cancelModal"
                                                        class="px-4 py-2 border rounded-lg hover:bg-gray-100">Cancel</button>
                                                    <button type="submit"
                                                        class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg shadow-md">
                                                        Save Coordinator
                                                    </button>
                                                </div>
                                            </form>

                                        </div>
                                    </div>

                                    <!-- Edit Coordinator Modal -->
                                    <div id="editCoordinatorModal"
                                        class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
                                        <div
                                            class="bg-white rounded-lg shadow-lg max-w-5xl p-6 max-h-[90vh] overflow-y-auto">
                                            <!-- Header -->
                                            <div class="flex justify-between items-center mb-4">
                                                <h3 class="text-xl font-semibold">Edit Coordinator</h3>
                                                <button id="closeEditModal"
                                                    class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
                                            </div>

                                            <form id="editCoordinatorForm" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="coordinator_id" id="editCoordinatorId">

                                                <div class="grid grid-cols-2 gap-8">
                                                    <div class="space-y-4">
                                                        <div>
                                                            <label class="block text-sm font-medium">Coordinator Name</label>
                                                            <input type="text" name="coordinator_name"
                                                                id="editCoordinatorName"
                                                                class="w-full border rounded p-2 mt-1">
                                                        </div>
                                                        <div>
                                                            <label class="block text-sm font-medium">Email</label>
                                                            <input type="email" name="email"
                                                                id="editCoordinatorEmail"
                                                                class="w-full border rounded p-2 mt-1">
                                                        </div>
                                                        <div>
                                                            <label
                                                            class="block text-sm font-medium text-gray-700">District</label>
                                                            <select name="dist_id" id="editDistrictSelect" class="w-full border rounded p-2 mt-1" required>
                                                                <option value="">-- Select District --</option>
                                                                @foreach ($districts as $district)
                                                                    <option value="{{ $district->DSM_DSCD }}" data-name="{{ $district->DSM_DSNM }}">
                                                                        {{ $district->DSM_DSNM }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            <!-- hidden input for district name -->
                                                            <input type="hidden" name="district" id="editDistrictName">
                                                        </div>
                                                    </div>

                                                    <div class="space-y-4">
                                                        <div>
                                                            <label class="block text-sm font-medium">Phone</label>
                                                            <input type="text" name="phone"
                                                                id="editCoordinatorPhone"
                                                                class="w-full border rounded p-2 mt-1">
                                                        </div>
                                                        <div>
                                                            <label
                                                                class="block text-sm font-medium text-gray-700">WhatsApp Number</label>
                                                            <input type="text" name="whatsapp_number"
                                                                id="editCoordinatorWhatsapp"
                                                                class="w-full border rounded p-2 mt-1">
                                                        </div>
                                                        <div>
                                                            <label
                                                            class="block text-sm font-medium text-gray-700">Pincode</label>
                                                            <input type="text" name="pincode"
                                                            id="editCoordinatorPincode"
                                                            class="w-full border rounded p-2" placeholder="Enter 6-digit Pincode">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="mt-4">
                                                    <label class="block text-sm font-medium">Address</label>
                                                    <textarea name="address" id="editCoordinatorAddress" rows="3" class="w-full border rounded p-2 mt-1"></textarea>
                                                </div>

                                                <!-- File fields -->
                                                <div class="grid grid-cols-2 gap-8 mt-4">
                                                    <div class="space-y-4">
                                                        <div>
                                                            <label class="block text-sm font-medium">CV /
                                                                Resume</label>
                                                            <input type="file" name="cv"
                                                                id="editCoordinatorcv"
                                                                accept=".pdf,.doc,.docx"
                                                                class="w-full border rounded p-2 mt-1">
                                                            <p class="text-[12px] text-gray-600">*Allowed formats: PDF,
                                                                DOC, DOCX. Max size: 2 MB</p>
                                                        </div>
                                                        <div>
                                                            <label class="block text-sm font-medium">Educational
                                                                Qualification Certificates</label>
                                                            <input type="file" name="education_certificates[]"
                                                                id="editCoordinatorEdu"
                                                                multiple accept=".pdf,.jpg,.jpeg,.png"
                                                                class="w-full border rounded p-2 mt-1">
                                                            <p class="text-[12px] text-gray-600">*Allowed formats: PDF,
                                                                JPG, PNG. Max size: 2 MB</p>
                                                        </div>
                                                        <div>
                                                            <label class="block text-sm font-medium text-gray-700">Aadhaar Card (with address in one pdf)</label>
                                                            <input type="file" name="aadhar_card"
                                                                id="editCoordinatorAadhar"
                                                                accept=".pdf,.doc,.docx"
                                                                class="w-full border rounded p-2 mt-1">
                                                            <p class="text-[12px] text-gray-600">*Allowed formats: PDF,
                                                                DOC, DOCX. Max size: 2 MB</p>
                                                        </div>
                                                    </div>
                                                    <div class="space-y-4">
                                                        <div>
                                                            <label class="block text-sm font-medium">Experience
                                                                Certificate</label>
                                                            <input type="file" name="experience_certificate"
                                                                id="editCoordinatorExp"
                                                                accept=".pdf,.jpg,.jpeg,.png"
                                                                class="w-full border rounded p-2 mt-1">
                                                            <p class="text-[12px] text-gray-600">*Allowed formats: PDF,
                                                                JPG, PNG. Max size: 2 MB</p>
                                                        </div>
                                                        <div>
                                                            <label class="block text-sm font-medium">Photo</label>
                                                            <input type="file" name="photo"
                                                                id="editCoordinatorPhoto"
                                                                accept=".jpg,.jpeg,.png"
                                                                class="w-full border rounded p-2 mt-1">
                                                            <p class="text-[12px] text-gray-600">*Allowed formats: JPG,
                                                                PNG. Max size: 2 MB</p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="mt-6 flex justify-end gap-3">
                                                    <button type="button" id="cancelEditModal"
                                                        class="px-4 py-2 border rounded-lg hover:bg-gray-100">Cancel</button>
                                                    <button type="submit"
                                                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg shadow-md">
                                                        Update Coordinator
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>


                                    <!-- Delete Confirmation Modal -->
                                    <div id="deleteCoordinatorModal"
                                        class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
                                        <div class="bg-white rounded-lg shadow-lg max-w-md p-6">
                                            <h3 class="text-xl font-semibold mb-4">Confirm Delete</h3>
                                            <p class="mb-6">Are you sure you want to delete this coordinator?</p>
                                            <form id="deleteCoordinatorForm" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <div class="flex justify-end gap-3">
                                                    <button type="button" id="cancelDeleteModal"
                                                        class="px-4 py-2 border rounded-lg hover:bg-gray-100">Cancel</button>
                                                    <button type="submit"
                                                        class="bg-red-600 hover:bg-red-700 text-white px-6 py-2 rounded-lg shadow-md">
                                                        Delete
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>


                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
    </div>
    <script>
        // add
        document.getElementById("districtSelect").addEventListener("change", function() {
            let selected = this.options[this.selectedIndex];
            document.getElementById("districtName").value = selected.getAttribute("data-name");
        });
        // edit #// For Edit Modal
        document.getElementById("editDistrictSelect").addEventListener("change", function() {
            let selected = this.options[this.selectedIndex];
            document.getElementById("editDistrictName").value = selected.getAttribute("data-name");
        });
    </script>
    <script>
        $(document).ready(function() {
            let rowsPerPage = parseInt($("#rowsPerPage").val());
            let currentPage = 1;
            let sortDirection = {}; // keep track of each column's sorting state

            function renderTable() {
                let searchText = $("#searchInput").val().toLowerCase();
                let rows = $("#coordinatorTable tbody tr");

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

                let rows = $("#coordinatorTable tbody tr").get();

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
                    $("#coordinatorTable tbody").append(row);
                });

                currentPage = 1; // reset pagination after sort
                renderTable();
            });

            // Initial render
            renderTable();
        });
    </script>
    <script>
        $(document).ready(function() {
            $("#addCoordinatorBtn").on("click", function() {
                $("#addCoordinatorModal").removeClass("hidden");
            });
            $("#closeModal, #cancelModal").on("click", function() {
                $("#addCoordinatorModal").addClass("hidden");
            });
        });
    </script>

    <script>
        // Auto fetch Date
        document.addEventListener("DOMContentLoaded", function() {
            let today = new Date().toISOString().split('T')[0];
            document.getElementById("training_date").value = today;
        });
        // Auto fetch School Name (example: from session/auth)
        const loggedInSchool =
            "BINIKEYEE NODAL HIGH SCHOOL (21150216101), Athamallik, Angul-759125"; // Replace with Blade variable in Laravel
        document.getElementById("schoolName").value = loggedInSchool;
    </script>
    @if ($errors->any())
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                // Open modal if there are validation errors
                document.getElementById("addCoordinatorModal").classList.remove("hidden");
            });
        </script>
    @endif
    <script>
        $(document).ready(function() {
            // Edit Modal
            $(".editBtn").on("click", function() {
                let id = $(this).data("id");
                $("#editCoordinatorId").val(id);
                $("#editCoordinatorName").val($(this).data("name"));
                $("#editCoordinatorEmail").val($(this).data("email"));
                // $("#editCoordinatorDistrict").val($(this).data("district"));
                $("#editCoordinatorPhone").val($(this).data("phone"));
                $("#editCoordinatorWhatsapp").val($(this).data("whatsapp_number"));
                $("#editCoordinatorPincode").val($(this).data("pincode"));
                $("#editCoordinatorAddress").val($(this).data("address"));

                // Get district values
                let distId = $(this).data("dist_id");   // DSM_DSCD
                let distName = $(this).data("district"); // DSM_DSNM

                $("#editDistrictSelect").val(distId); // select correct option
                $("#editDistrictName").val(distName); // hidden input

                // Set form action dynamically
                $("#editCoordinatorForm").attr("action", "/coordinators/" + id);

                $("#editCoordinatorModal").removeClass("hidden");

                
            });

            $("#closeEditModal, #cancelEditModal").on("click", function() {
                $("#editCoordinatorModal").addClass("hidden");
            });

            // Delete Modal
            $(".deleteBtn").on("click", function() {
                let id = $(this).data("id");
                $("#deleteCoordinatorForm").attr("action", "/coordinators/" + id);
                $("#deleteCoordinatorModal").removeClass("hidden");
            });

            $("#cancelDeleteModal").on("click", function() {
                $("#deleteCoordinatorModal").addClass("hidden");
            });
        });
    </script>
</body>
@include('components.footer')
