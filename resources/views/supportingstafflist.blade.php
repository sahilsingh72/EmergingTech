@include('components.navbar')
@include('components.sidebar')
<style>
    .sort::after {
        content: " ⇅";
        font-size: 0.7rem;
        color: gray;
    }

    #sustaffTable {
        table-layout: auto;
        /* allow natural sizing */
        width: 100%;
        /* still stretch full table */
    }

    #sustaffTable th,
    #sustaffTable td {
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
                            <h1 class="m-0 text-dark">Supporting Staff</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Supporting Staff</a></li>
                                <li class="breadcrumb-item active">Program Team</li>
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
                                    <h2 class="text-2xl font-semibold text-center mb-6"></i>Supporting Staff List</h2>
                                    <div class="mb-4 flex justify-end">
                                        <button id="addStaffBtn"
                                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg shadow-md flex items-center gap-2">
                                            <i class="fas fa-user-plus"></i> Add Supporting Staff
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

                                    <!-- Staff Table -->
                                    <div class="bg-white shadow rounded-lg p-4 overflow-x-auto">
                                        <table id="sustaffTable" class="w-full border-collapse">
                                            <thead>
                                                <tr class="bg-gray-100 text-left">
                                                    <th class="p-2 border">SNO</th>
                                                    <th class="p-2 border">Name</th>
                                                    <th class="p-2 border">Email</th>
                                                    <th class="p-2 border">Phone</th>
                                                    <th class="p-2 border">Photo</th>
                                                    <th class="p-2 border">CV</th>
                                                    <th class="p-2 border">Education Certificates</th>
                                                    <th class="p-2 border">Aadhaar Card</th>
                                                    <th class="p-2 border text-center">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($suppstaffs as $index => $suppstaff)
                                                    <tr>
                                                        <td class="p-2 border">{{ $index + 1 }}</td>
                                                        <td class="p-2 border">{{ $suppstaff->ss_name }}</td>
                                                        <td class="p-2 border">{{ $suppstaff->email }}</td>
                                                        <td class="p-2 border">{{ $suppstaff->phone }}</td>

                                                        {{-- Photo --}}
                                                        <td class="p-2 border">
                                                            @if ($suppstaff->photo)
                                                                <a href="{{ asset('storage/' . $suppstaff->photo) }}"
                                                                    target="_blank">
                                                                    <img src="{{ asset('storage/' . $suppstaff->photo) }}"
                                                                        alt="suppstaff Photo"
                                                                        class="h-12 w-12 object-cover rounded-full mx-auto hover:scale-110 transition">
                                                                </a>
                                                            @else
                                                                -
                                                            @endif
                                                        </td>
                                                        <td class="p-2 border">
                                                            @if ($suppstaff->cv)
                                                                <a href="{{ asset('storage/' . $suppstaff->cv) }}"
                                                                    target="_blank" class="text-blue-600">View CV</a>
                                                            @else
                                                                -
                                                            @endif
                                                        </td>
                                                        {{-- Education Certificates --}}
                                                        <td class="p-2 border">
                                                            @php
                                                                $educationCertificates = [];
                                                                if (is_string($suppstaff->education_certificates)) {
                                                                    $educationCertificates =
                                                                        json_decode(
                                                                            $suppstaff->education_certificates,
                                                                            true,
                                                                        ) ?? [];
                                                                } elseif (is_array($suppstaff->education_certificates)) {
                                                                    $educationCertificates =
                                                                        $suppstaff->education_certificates;
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
                                                            @if ($suppstaff->aadhar_card)
                                                                <a href="{{ asset('storage/' . $suppstaff->aadhar_card) }}"
                                                                    target="_blank" class="text-blue-600">View</a>
                                                            @else
                                                                -
                                                            @endif
                                                        </td>
                                                        {{-- Actions --}}
                                                        <td class="p-2 border text-center">
                                                            <button type="button" class="text-green-500 mx-1 editBtn"
                                                                data-id="{{ $suppstaff->ss_id }}"
                                                                data-name="{{ $suppstaff->ss_name }}"
                                                                data-email="{{ $suppstaff->email }}"
                                                                data-phone="{{ $suppstaff->phone }}"
                                                                data-whatsapp_number="{{ $suppstaff->whatsapp_number}}"
                                                                data-dist_id="{{ $suppstaff->dist_id }}"
                                                                data-district="{{ $suppstaff->district }}"
                                                                data-pincode="{{ $suppstaff->pincode}}"
                                                                data-address="{{ $suppstaff->address}}"
                                                                data-school_id="{{ $suppstaff->scm_id }}"
                                                                data-photo="{{ $suppstaff->photo }}"
                                                                data-cv="{{ $suppstaff->cv }}" 
                                                                data-highest_qualification="{{ $suppstaff->highest_qual }}">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                            {{-- <button type="button" class="text-red-500 mx-1 deleteBtn"
                                                                data-id="{{ $suppstaff->ss_id }}">
                                                                <i class="fas fa-trash-alt"></i>
                                                            </button> --}}
                                                        </td>
                                                    </tr>
                                                @endforeach

                                            </tbody>
                                        </table>
                                    </div>

                                    <div id="pagination" class="flex justify-center space-x-2 mt-4"></div>


                                    <!-- Add Staff Modal -->
                                    <div id="addStaffModal"
                                        class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
                                        <div
                                            class="bg-white rounded-lg shadow-lg  max-w-5xl p-6 max-h-[90vh] overflow-y-auto">

                                            <!-- Header -->
                                            <div class="flex justify-between items-center mb-4">
                                                <h3 class="text-xl font-semibold">Add Supporting Staff</h3>
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
                                            <form id="suppstaffForm" method="POST"
                                                action="{{ route('supstaff.store') }}" enctype="multipart/form-data">
                                                @csrf
                                                <div class="grid grid-cols-2 gap-8">
                                                    <!-- Left Column: Staff Info -->
                                                    <div class="space-y-4">
                                                        <div>
                                                            <label
                                                                class="block text-sm font-medium text-gray-700">Supporting Staff Name</label>
                                                            <input type="text" name="ss_name"
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
                                                                class="block text-sm font-medium text-gray-700">School</label>
                                                            <select name="school" id="schoolSelect" class="w-full border rounded p-2 mt-1" required>
                                                                <option value="">-- Select School --</option>
                                                                @foreach ($schools as $school)
                                                                    <option value="{{ $school->scm_id }}" data-name="{{ $school->scm_name }}">
                                                                        {{ $school->scm_name }}
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
                                                                class="w-full border rounded p-2 mt-1" required>
                                                        </div>
                                                        <div>
                                                            <label
                                                                class="block text-sm font-medium text-gray-700">WhatsApp Number</label>
                                                            <input type="text" name="whatsapp_number"
                                                                class="w-full border rounded p-2 mt-1" required>
                                                        </div>
                                                        <div>
                                                            <label
                                                                class="block text-sm font-medium text-gray-700">Pincode</label>
                                                            <input type="number" name="pincode"
                                                                class="w-full border rounded p-2" placeholder="Enter 6-digit Pincode" required>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="grid grid-cols-1">
                                                    <div>
                                                        <label
                                                            class="block text-sm font-medium text-gray-700 mt-3">Address (Enter full address)</label>
                                                        <textarea name="address" rows="3" class="w-full border rounded p-2 mt-1" required></textarea>
                                                    </div>
                                                </div>

                                                 <div class="grid grid-cols-2 gap-8 mt-3">
                                                    <!-- Left Column: Staff Info -->
                                                    <div class="space-y-4">
                                                        
                                                        <div>
                                                           <div>
                                                               <label class="block text-sm font-medium text-gray-700">Highest Qualification</label>
                                                               <select id="qualification" name="highest_qualification" class="w-full border rounded p-2 mt-1 mb-4" required>
                                                                   <option value="">-- Select Qualification --</option>
                                                                   <option value="B-Tech">B-Tech</option>
                                                                   <option value="BCA">BCA</option>
                                                                   <option value="B.Sc (CS/IT)">B.Sc (CS/IT)</option>
                                                                   <option value="Other">Other (Equivalent)</option>
                                                                </select>

                                                                <!-- Hidden text input for "Other" -->
                                                                <div id="otherQualificationDiv" class="hidden">
                                                                    <input type="text" id="otherQualification" name="other_qualification" 
                                                                        class="w-full border rounded p-2 mt-1" 
                                                                        placeholder="Please specify your qualification">
                                                                </div>
                                                           </div>
                                                        </div>    
                                                    </div>

                                                    <!-- Right Column: File Uploads -->
                                                    <div class="space-y-4">

                                                        <div>
                                                            <label
                                                                class="block text-sm font-medium text-gray-700">Educational
                                                                Qualification Certificates</label>
                                                            <input type="file" name="education_certificates[]"
                                                                multiple accept=".pdf,.jpg,.jpeg,.png"
                                                                class="w-full border rounded p-2 mt-1" required>
                                                            <p class="text-[12px] text-gray-600">*Allowed formats: PDF,
                                                                JPG, PNG. Max size: 2 MB</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="grid grid-cols-2 gap-8 mt-3">
                                                    <!-- Left Column: staff Info -->
                                                    <div class="space-y-4">
                                                        
                                                        <div>
                                                            <label
                                                                class="block text-sm font-medium text-gray-700">Photo</label>
                                                            <input type="file" name="photo"
                                                                accept=".jpg,.jpeg,.png"
                                                                class="w-full border rounded p-2 mt-1" required>
                                                            <p class="text-[12px] text-gray-600">*Allowed formats: JPG,
                                                                PNG. Max size: 2 MB</p>
                                                        </div>
                                                        <div>
                                                            <label class="block text-sm font-medium text-gray-700">Aadhaar Card (with address in one pdf)</label>
                                                            <input type="file" name="aadhar_card"
                                                                accept=".pdf"
                                                                class="w-full border rounded p-2 mt-1" required>
                                                            <p class="text-[12px] text-gray-600">*Allowed formats: PDF. Max size: 2 MB</p>
                                                        </div>
                                                        
                                                    </div>

                                                    <!-- Right Column: File Uploads -->
                                                    <div class="space-y-4">

                                                        
                                                        <div>
                                                            <label class="block text-sm font-medium text-gray-700">CV /
                                                                Resume</label>
                                                            <input type="file" name="cv"
                                                                accept=".pdf"
                                                                class="w-full border rounded p-2 mt-1" required>
                                                            <p class="text-[12px] text-gray-600">*Allowed formats: PDF. Max size: 2 MB</p>
                                                        </div>
                                                    </div>
                                                </div>

                                                <!-- Buttons -->
                                                <div class="mt-6 flex justify-end gap-3">
                                                    <button type="button" id="cancelModal"
                                                        class="px-4 py-2 border rounded-lg hover:bg-gray-100">Cancel</button>
                                                    <button type="submit"
                                                        class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg shadow-md">
                                                        Save Staff
                                                    </button>
                                                </div>
                                            </form>

                                        </div>
                                    </div>

                                    <!-- Edit Staff Modal -->
                                    <div id="editStaffModal"
                                        class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
                                        <div
                                            class="bg-white rounded-lg shadow-lg max-w-5xl p-6 max-h-[90vh] overflow-y-auto">
                                            <!-- Header -->
                                            <div class="flex justify-between items-center mb-4">
                                                <h3 class="text-xl font-semibold">Edit Supporting Staff</h3>
                                                <button id="closeEditModal"
                                                    class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
                                            </div>

                                            <form id="editStaffForm" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="ss_id" id="editStaffId">

                                                <div class="grid grid-cols-2 gap-8">
                                                    <div class="space-y-4">
                                                        <div>
                                                            <label class="block text-sm font-medium">Supporting Staff Name</label>
                                                            <input type="text" name="ss_name"
                                                                id="editStaffName"
                                                                class="w-full border rounded p-2 mt-1">
                                                        </div>
                                                        <div>
                                                            <label class="block text-sm font-medium">Email</label>
                                                            <input type="email" name="email"
                                                                id="editStaffEmail"
                                                                class="w-full border rounded p-2 mt-1">
                                                        </div>
                                                        <div>
                                                            <label
                                                                class="block text-sm font-medium text-gray-700">School</label>
                                                            <select name="school" id="editStaffSchool" class="w-full border rounded p-2 mt-1" required>
                                                                <option value="">-- Select School --</option>
                                                                @foreach ($schools as $school)
                                                                    <option value="{{ $school->scm_id }}" data-name="{{ $school->scm_name }}">
                                                                        {{ $school->scm_name }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="space-y-4">
                                                        <div>
                                                            <label class="block text-sm font-medium">Phone Number</label>
                                                            <input type="text" name="phone"
                                                                id="editStaffPhone" required
                                                                class="w-full border rounded p-2 mt-1">
                                                        </div>
                                                        <div>
                                                            <label
                                                                class="block text-sm font-medium text-gray-700">WhatsApp Number</label>
                                                            <input type="text" name="whatsapp_number"
                                                                id="editStaffWhatsapp" required
                                                                class="w-full border rounded p-2 mt-1">
                                                        </div>
                                                        <div>
                                                            <label
                                                            class="block text-sm font-medium text-gray-700">Pincode</label>
                                                            <input type="text" name="pincode" required
                                                            id="editStaffPincode"
                                                            class="w-full border rounded p-2" placeholder="Enter 6-digit Pincode">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="mt-4">
                                                    <label class="block text-sm font-medium">Address</label>
                                                    <textarea name="address" id="editStaffAddress" rows="3" class="w-full border rounded p-2 mt-1" required></textarea>
                                                </div>

                                                <!-- File fields -->
                                                <div class="grid grid-cols-2 gap-8 mt-4">
                                                    <div class="space-y-4">

                                                        <div>
                                                           <div>
                                                               <label class="block text-sm font-medium text-gray-700">Highest Qualification</label>
                                                                <select id="editQualification" name="highest_qualification" class="w-full border rounded p-2 mt-1 mb-4" required>
                                                                    <option value="">-- Select Qualification --</option>
                                                                    <option value="B-Tech">B-Tech</option>
                                                                    <option value="BCA">BCA</option>
                                                                    <option value="B.Sc (CS/IT)">B.Sc (CS/IT)</option>
                                                                    <option value="Other">Other (Equivalent)</option>
                                                                </select>

                                                                <div id="editOtherQualificationDiv" class="hidden">
                                                                    <input type="text" id="editOtherQualification" name="other_qualification"
                                                                            class="w-full border rounded p-2 mt-1" 
                                                                            placeholder="Please specify your qualification">
                                                                </div>
                                                           </div>
                                                        </div>

                                                        
                                                        <div>
                                                            <label class="block text-sm font-medium">Photo</label>
                                                            <input type="file" name="photo"
                                                                id="editStaffPhoto"
                                                                accept=".jpg,.jpeg,.png" 
                                                                class="w-full border rounded p-2 mt-1">

                                                            <div id="editPhotoPreviewContainer" class="mt-2 hidden">
                                                                <p class="text-sm text-gray-600">Current Photo:</p>
                                                                <img id="editPhotoPreview" src="" alt="Current Photo"
                                                                    class="h-16 w-16 object-cover rounded-full border">
                                                            </div>
                                                            <p class="text-[12px] text-gray-600">*Allowed formats: JPG,
                                                                PNG. Max size: 2 MB</p>
                                                        </div>

                                                        <div>
                                                            <label class="block text-sm font-medium">Aadhaar Card (with address in one pdf)</label>
                                                            <input type="file" name="aadhar_card"
                                                                id="editStaffAadhar"
                                                                accept=".pdf"
                                                                class="w-full border rounded p-2 mt-1">
                                                            <p class="text-[12px] text-gray-600">*Allowed formats: PDF. Max size: 2 MB</p>
                                                        </div>
                                                    </div>
                                                    <div class="space-y-4">
                                                        <div>
                                                            <label class="block text-sm font-medium">Educational
                                                                Qualification Certificates</label>
                                                            <input type="file" name="education_certificates[]"
                                                                id="editStaffEdu"
                                                                multiple accept=".pdf,.jpg,.jpeg,.png"
                                                                class="w-full border rounded p-2 mt-1">
                                                            <p class="text-[12px] text-gray-600">*Allowed formats: PDF,
                                                                JPG, PNG. Max size: 2 MB</p>
                                                        </div>
                                                        <div>
                                                            <label class="block text-sm font-medium">CV /
                                                                Resume</label>
                                                            <input type="file" name="cv"
                                                                id="editStaffcv"
                                                                accept=".pdf"
                                                                class="w-full border rounded p-2 mt-1">
                                                                
                                                            <p class="text-[12px] text-gray-600">*Allowed formats: PDF. Max size: 2 MB</p>
                                                        </div>
                                                        
                                            
                                                    </div>
                                                </div>

                                                <div class="mt-6 flex justify-end gap-3">
                                                    <button type="button" id="cancelEditModal"
                                                        class="px-4 py-2 border rounded-lg hover:bg-gray-100">Cancel</button>
                                                    <button type="submit"
                                                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg shadow-md">
                                                        Update Supporting Staff
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>


                                    <!-- Delete Confirmation Modal -->
                                    <div id="deleteStaffModal"
                                        class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
                                        <div class="bg-white rounded-lg shadow-lg max-w-md p-6">
                                            <h3 class="text-xl font-semibold mb-4">Confirm Delete</h3>
                                            <p class="mb-6">Are you sure you want to delete this Supporting Staff?</p>
                                            <form id="deleteStaffForm" method="POST">
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
document.addEventListener("DOMContentLoaded", function() {
    // Add modal
    document.getElementById('qualification').addEventListener('change', function () {
        const otherDiv = document.getElementById('otherQualificationDiv');
        const otherInput = document.getElementById('otherQualification');

        if (this.value === 'Other') {
            otherDiv.classList.remove('hidden');
            otherInput.required = true;
        } else {
            otherDiv.classList.add('hidden');
            otherInput.required = false;
            otherInput.value = '';
        }
    });

    // Edit modal
    document.getElementById('editQualification').addEventListener('change', function () {
        const otherDiv = document.getElementById('editOtherQualificationDiv');
        const otherInput = document.getElementById('editOtherQualification');

        if (this.value === 'Other') {
            otherDiv.classList.remove('hidden');
            otherInput.required = true;
        } else {
            otherDiv.classList.add('hidden');
            otherInput.required = false;
            otherInput.value = '';
        }
    });
});
</script>
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
                let rows = $("#sustaffTable tbody tr");

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

                let rows = $("#sustaffTable tbody tr").get();

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
                    $("#sustaffTable tbody").append(row);
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
            $("#addStaffBtn").on("click", function() {
                $("#addStaffModal").removeClass("hidden");
            });
            $("#closeModal, #cancelModal").on("click", function() {
                $("#addStaffModal").addClass("hidden");
            });
        });
    </script>

    <script>
        // Auto fetch Date
        document.addEventListener("DOMContentLoaded", function() {
            let today = new Date().toISOString().split('T')[0];
            document.getElementById("training_date").value = today;
        });
    </script>
    @if ($errors->any())
        <script>
            document.addEventListener("DOMContentLoaded", function() {
                // Open modal if there are validation errors
                document.getElementById("addStaffModal").classList.remove("hidden");
            });
        </script>
    @endif
    <script>
        $(document).ready(function() {
            // Edit Modal
            $(".editBtn").on("click", function() {
                let id = $(this).data("id");
                $("#editStaffId").val(id);
                $("#editStaffName").val($(this).data("name"));
                $("#editStaffEmail").val($(this).data("email"));
                $("#editStaffSchool").val($(this).data("school_id"));
                $("#editStaffPhone").val($(this).data("phone"));
                $("#editStaffWhatsapp").val($(this).data("whatsapp_number"));
                $("#editStaffPincode").val($(this).data("pincode"));
                $("#editStaffAddress").val($(this).data("address"));
                // $("#editStaffPhoto").val($(this).data("photo"));
                
                let qual = $(this).data("highest_qualification");
                    const standardOptions = ["B-Tech", "BCA", "B.Sc (CS/IT)"];

                    if (standardOptions.includes(qual)) {
                        $("#editQualification").val(qual);
                        $("#editOtherQualificationDiv").addClass("hidden");
                        $("#editOtherQualification").val('');
                    } else {
                        $("#editQualification").val('Other');
                        $("#editOtherQualificationDiv").removeClass("hidden");
                        $("#editOtherQualification").val(qual);
                    }

                // Get district values
                let distId = $(this).data("dist_id");   // DSM_DSCD
                let distName = $(this).data("district"); // DSM_DSNM
                let schoolId = $(this).data("school");
                
                $("#schoolSelect").val(schoolId); 
                
                $("#editDistrictSelect").val(distId); // select correct option
                $("#editDistrictName").val(distName); // hidden input

                // Set form action dynamically
                $("#editStaffForm").attr("action", "/supp-staff/" + id);

                $("#editStaffModal").removeClass("hidden");

                let photoPath = $(this).data("photo");
                if (photoPath) {
                    $("#editPhotoPreview").attr("src", "/storage/" + photoPath);
                    $("#editPhotoPreviewContainer").removeClass("hidden");
                } else {
                    $("#editPhotoPreviewContainer").addClass("hidden");
                }



                
            });

            $("#closeEditModal, #cancelEditModal").on("click", function() {
                $("#editStaffModal").addClass("hidden");
            });

            // Delete Modal
            $(".deleteBtn").on("click", function() {
                let id = $(this).data("id");
                $("#deleteStaffForm").attr("action", "/supp-staff/" + id);
                $("#deleteStaffModal").removeClass("hidden");
            });

            $("#cancelDeleteModal").on("click", function() {
                $("#deleteStaffModal").addClass("hidden");
            });
        });
    </script>
</body>
@include('components.footer')
