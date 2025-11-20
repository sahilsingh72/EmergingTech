@include('components.navbar')
@include('components.sidebar')
<style>
    .sort::after {
        content: " ⇅";
        font-size: 0.7rem;
        color: gray;
    }

    #trainerTable {
        table-layout: auto;
        /* allow natural sizing */
        width: 100%;
        /* still stretch full table */
    }

    #trainerTable th,
    #trainerTable td {
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
                            <h1 class="m-0 text-dark">Trainer List</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Trainer List</a></li>
                                <li class="breadcrumb-item active"> Coordinator & Trainer</li>
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
                            <div class="bg-white shadow sm:rounded-lg">
                                <div class="bg-white p-8 rounded-lg w-full">
                                    <!-- Title -->
                                    <h2 class="text-2xl font-semibold text-center mb-6"></i>Trainer list</h2>
                                     @php
                                        $roleId = Auth::user()->role_id;
                                        $SahiluserId = Auth::user()->id;
                                    @endphp
                                    @if($roleId == 3 || $SahiluserId == 1)
                                        <div class="mb-4 flex justify-end">
                                            <button id="addTrainerBtn"
                                                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg shadow-md flex items-center gap-2">
                                                <i class="fas fa-user-plus"></i> Add Trainer
                                            </button>
                                        </div>
                                    @endif

                                    @php
                                        $roleId = Auth::user()->role_id;
                                    @endphp
                                    @if($roleId == 2)
                                        <div class="mb-4 flex justify-end">
                                            <button id="exportBtn"
                                                class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg shadow-md flex items-center gap-2">
                                                <i class="fas fa-file-excel"></i> Export Report
                                            </button>
                                        </div>
                                    @endif

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
                                                <option value="100">100</option>
                                            </select>
                                        </div>

                                        <!-- District Filter -->
                                        <div>
                                            <label for="districtFilter" class="mr-2">District:</label>
                                            <select id="districtFilter" class="border rounded pl-2 pr-5">
                                                <option value="">All</option>
                                                @foreach($districts as $d)
                                                    <option value="{{ $d->DSM_DSCD }}">{{ $d->DSM_DSNM }}</option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Search -->
                                        <div class="w-full sm:w-auto ">
                                            <input type="text" id="searchInput" placeholder="Search..."
                                                class="border rounded p-2 h-7 w-full sm:w-64 focus:outline-none focus:ring-2 focus:ring-blue-400">
                                        </div>
                                        
                                    </div>

                                    <!-- Trainer Table -->
                                    <div class="bg-white shadow rounded-lg p-3 overflow-x-auto">
                                        <table id="trainerTable" class="w-full border-collapse">
                                            <thead>
                                                <tr class="bg-gray-100 text-left">
                                                    <th class="p-2 border">SNO</th>
                                                    <th class="p-2 border">District</th>
                                                    <th class="p-2 border">School</th>
                                                    <th class="p-2 border">Specialization</th>
                                                    <th class="p-2 border">Name</th>
                                                    <th class="p-2 border">Phone</th>
                                                    <th class="p-2 border">Email</th>
                                                    <th class="p-2 border">Address</th>
                                                    <th class="p-2 border">Photo</th>
                                                    <th class="p-2 border">CV</th>
                                                    <th class="p-2 border">Experience</th>
                                                    <th class="p-2 border">Education Certificates</th>
                                                    <th class="p-2 border">Aadhaar Card</th>
                                                    @php
                                                        $roleId = Auth::user()->role_id;
                                                        $SahiluserId = Auth::user()->id;
                                                    @endphp
                                                    @if($roleId == 3 || $SahiluserId == 1 )
                                                        <th class="p-2 border text-center">Actions</th>
                                                    @endif
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($trainers as $index => $trainer)
                                                    <tr>
                                                        <td class="p-2 border">{{ $index + 1 }}</td>
                                                        @php
                                                            $dist_id = $trainer->dist_id;
                                                            $dist_nm = 'N/A';
                                                            foreach ($districts as $d) {
                                                                if ($d->DSM_DSCD == $dist_id) {
                                                                    $dist_nm = $d->DSM_DSNM;
                                                                    break;
                                                                }
                                                            }
                                                        @endphp
                                                        <td class="p-2 border" data-district-code="{{ $dist_id }}">
                                                            {{ $dist_nm }}
                                                        </td>
                                                        <td class="p-2 border">
                                                            @if($trainer->schools->isNotEmpty())
                                                                @foreach($trainer->schools as $school)
                                                                    <span class="bg-green-100 text-green-700 px-2 py-1 rounded text-xs mr-1">
                                                                        {{ $school->scm_name }},
                                                                    </span></br>
                                                                @endforeach
                                                            @else
                                                                <span class="text-gray-500">No School Assigned</span>
                                                            @endif
                                                        </td>
                                                        <td class="p-2 border">
                                                            @if (is_array($trainer->specialization))
                                                                {{ implode(', ', $trainer->specialization) }}
                                                            @else
                                                                {{ $trainer->specialization }}
                                                            @endif
                                                        </td>
                                                        <td class="p-2 border">{{ $trainer->trainer_name }}</td>
                                                        <td class="p-2 border">{{ $trainer->phone }}</td>
                                                        <td class="p-2 border">{{ $trainer->email }}</td>
                                                        <td class="p-2 border">{{ $trainer->address }}</td>
                                                        {{-- Photo --}}
                                                        <td class="p-2 border">
                                                            @if ($trainer->photo)
                                                                <a href="{{ asset('storage/' . $trainer->photo) }}"
                                                                    target="_blank">
                                                                    <img src="{{ asset('storage/' . $trainer->photo) }}"
                                                                        alt="Trainer Photo"
                                                                        class="h-12 w-12 object-cover rounded-full mx-auto hover:scale-110 transition">
                                                                </a>
                                                            @else
                                                                -
                                                            @endif
                                                        </td>
                                                        <td class="p-2 border">
                                                            @if ($trainer->cv)
                                                                <a href="{{ asset('storage/' . $trainer->cv) }}"
                                                                    target="_blank" class="text-blue-600">View CV</a>
                                                            @else
                                                                -
                                                            @endif
                                                        </td>

                                                        {{-- Experience --}}
                                                        <td class="p-2 border">
                                                            @if ($trainer->experience_certificate)
                                                                <a href="{{ asset('storage/' . $trainer->experience_certificate) }}"
                                                                    target="_blank" class="text-blue-600">View</a>
                                                            @else
                                                                -
                                                            @endif
                                                        </td>


                                                        {{-- Education Certificates --}}
                                                        <td class="p-2 border">
                                                            @php
                                                                $educationCertificates = [];
                                                                if (is_string($trainer->education_certificates)) {
                                                                    $educationCertificates =
                                                                        json_decode(
                                                                            $trainer->education_certificates,
                                                                            true,
                                                                        ) ?? [];
                                                                } elseif (is_array($trainer->education_certificates)) {
                                                                    $educationCertificates =
                                                                        $trainer->education_certificates;
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
                                                            @if ($trainer->aadhar_card)
                                                                <a href="{{ asset('storage/' . $trainer->aadhar_card) }}"
                                                                    target="_blank" class="text-blue-600">View</a>
                                                            @else
                                                                -
                                                            @endif
                                                        </td>
                                                        {{-- Actions --}}
                                                        @php
                                                            $roleId = Auth::user()->role_id;
                                                            $SahiluserId = Auth::user()->id;
                                                        @endphp
                                                        @if($roleId == 3 || $SahiluserId == 1)
                                                        <td class="p-2 border text-center">
                                                            <button type="button" class="text-green-500 mx-1 editBtn"
                                                                data-id="{{ $trainer->trainer_id }}"
                                                                data-name="{{ $trainer->trainer_name }}"
                                                                data-email="{{ $trainer->email }}"
                                                                data-phone="{{ $trainer->phone }}"
                                                                data-whatsapp_number="{{ $trainer->whatsapp_number}}"
                                                                data-dist_id="{{ $trainer->dist_id }}"
                                                                data-district="{{ $trainer->district }}"
                                                                data-pincode="{{ $trainer->pincode}}"
                                                                data-specialization="{{ is_array($trainer->specialization) ? implode(',', $trainer->specialization) : $trainer->specialization }}"
                                                                data-address="{{ $trainer->address }}"
                                                                {{-- data-school_id="{{ $trainer->scm_id }}" --}}
                                                                data-school="{{ implode(',', $trainer->school_ids ?? []) }}"
                                                                data-highest_qualification="{{ $trainer->highest_qual }}">
                                                                <i class="fas fa-edit"></i>
                                                            </button>
                                                            @php
                                                                $SahiluserId = Auth::user()->id;
                                                            @endphp
                                                            @if($SahiluserId == 1)
                                                                <button type="button" class="text-red-500 mx-1 deleteBtn"
                                                                    data-id="{{ $trainer->trainer_id }}">
                                                                    <i class="fas fa-trash-alt"></i>
                                                                </button>
                                                            @endif
                                                        </td>
                                                        @endif
                                                    </tr>
                                                @endforeach

                                            </tbody>
                                        </table>
                                    </div>

                                    <div id="pagination" class="flex justify-center space-x-2 mt-4"></div>
                                    <!-- Add Trainer Modal -->
                                    <div id="addTrainerModal"
                                        class="content-wrapper fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50 px-2">
                                        <div
                                            class="bg-white rounded-lg shadow-lg  max-w-5xl p-6 max-h-[80vh] overflow-y-auto">

                                            <!-- Header -->
                                            <div class="flex justify-between items-center mb-4">
                                                <h3 class="text-xl font-semibold">Add Trainer</h3>
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
                                            <form id="trainerForm" method="POST"
                                                action="{{ route('trainers.store') }}" enctype="multipart/form-data">
                                                @csrf
                                                <div class="grid grid-cols-2 gap-8">
                                                    <!-- Left Column: Trainer Info -->
                                                    <div class="space-y-4">
                                                        <div>
                                                            <label
                                                                class="block text-sm font-medium text-gray-700">Trainer Name</label>
                                                            <input type="text" name="trainer_name"
                                                                class="w-full border rounded p-2 mt-1" required>
                                                        </div>
                                                        <div>
                                                            <label
                                                                class="block text-sm font-medium text-gray-700">Email</label>
                                                            <input type="email" name="email"
                                                                class="w-full border rounded p-2 mt-1" required>
                                                        </div>

                                                        <div>
                                                            <label class="block text-sm font-medium text-gray-700 mb-1">School</label>
                                                            <select name="school[]" id="schoolSelect" multiple class="w-full border-gray-300 rounded-md shadow-sm">
                                                                @foreach ($schools as $school)
                                                                <option value="{{ $school->scm_id }}">{{ $school->scm_name }}</option>
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
                                                                class="block text-sm font-medium text-gray-700">Phone</label>
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
                                                            <input type="number" name="pincode"
                                                                class="w-full border rounded p-2" placeholder="Enter 6-digit Pincode">
                                                        </div> 
                                                    </div>
                                                </div>
                                                <div class="grid grid-cols-1 mt-3">
                                                    <div>
                                                        <label
                                                            class="block text-sm font-medium text-gray-700">Specialization</label>
                                                        <select class="w-full border rounded p-2 mt-1"
                                                            name="specialization[]" id="specialization" multiple>
                                                            <option value="AI">AI</option>
                                                            <option value="IoT & Robotics">IoT & Robotics</option>
                                                            <option value="Cybersecurity">Cybersecurity</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="grid grid-cols-1">
                                                    <div>
                                                        <label
                                                            class="block text-sm font-medium text-gray-700 mt-3">Address (Enter full address)</label>
                                                        <textarea name="address" rows="2" class="w-full border rounded p-2 mt-1"></textarea>
                                                    </div>
                                                </div>
                                                <div class="grid grid-cols-2 gap-8 mt-3">
                                                    <!-- Left Column: Trainer Info -->
                                                    <div class="space-y-4">
                                                        <div>
                                                            <div>
                                                               <label class="block text-sm font-medium text-gray-700">Highest Qualification</label>
                                                               <select id="qualification" name="highest_qualification" class="w-full border rounded p-2 mt-1 mb-4" required>
                                                                   <option value="">-- Select Qualification --</option>
                                                                   <option value="B-Tech">B-Tech</option>
                                                                   <option value="MCA">MCA</option>
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
                                                                class="block text-sm font-medium text-gray-700">Experience
                                                                Certificate</label>
                                                            <input type="file" name="experience_certificate"
                                                                accept=".pdf,.jpg,.jpeg,.png"
                                                                class="w-full border rounded p-2 mt-1" required>
                                                            <p class="text-[12px] text-gray-600">*Allowed formats: PDF,
                                                                JPG, PNG. Max size: 2 MB</p>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="grid grid-cols-2 gap-8 mt-3">
                                                    <!-- Left Column: Trainer Info -->
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
                                                        
                                                    <!-- Right Column: File Uploads -->
                                                    <div class="space-y-4">
                                                        <div>
                                                            <label class="block text-sm font-medium text-gray-700">CV /
                                                                Resume</label>
                                                            <input type="file" name="cv"
                                                                accept=".pdf,.doc,.docx"
                                                                class="w-full border rounded p-2 mt-1" required>
                                                            <p class="text-[12px] text-gray-600">*Allowed formats: PDF. Max size: 2 MB</p>
                                                        </div>
                                                        <div>
                                                            <label class="block text-sm font-medium text-gray-700">Aadhaar Card (with address in one pdf)</label>
                                                            <input type="file" name="aadhar_card"
                                                                accept=".pdf,.doc,.docx"
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
                                                        Save Trainer
                                                    </button>
                                                </div>
                                            </form>

                                        </div>
                                    </div>

                                    <!-- Edit Trainer Modal -->
                                    <div id="editTrainerModal"
                                        class="content-wrapper fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50 px-2">
                                        <div
                                            class="bg-white rounded-lg shadow-lg max-w-5xl p-6 max-h-[80vh] overflow-y-auto">
                                            <!-- Header -->
                                            <div class="flex justify-between items-center mb-4">
                                                <h3 class="text-xl font-semibold">Edit Trainer</h3>
                                                <button id="closeEditModal"
                                                    class="text-gray-500 hover:text-gray-700 text-2xl">&times;</button>
                                            </div>

                                            <form id="editTrainerForm" method="POST" enctype="multipart/form-data">
                                                @csrf
                                                @method('PUT')
                                                <input type="hidden" name="trainer_id" id="editTrainerId">

                                                <div class="grid grid-cols-2 gap-8">
                                                    <div class="space-y-4">
                                                        <div>
                                                            <label class="block text-sm font-medium">Trainer Name</label>
                                                            <input type="text" name="trainer_name"
                                                                id="editTrainerName"
                                                                class="w-full border rounded p-2 mt-1">
                                                        </div>
                                                        <div>
                                                            <label class="block text-sm font-medium">Email</label>
                                                            <input type="email" name="email"
                                                                id="editTrainerEmail"
                                                                class="w-full border rounded p-2 mt-1">
                                                        </div>
                                                        <div>
                                                            <label class="block text-sm font-medium text-gray-700 mb-1">School</label>
                                                            <select name="school[]" id="editSchoolSelect" multiple class="w-full border-gray-300 rounded-md shadow-sm">
                                                                @foreach ($schools as $school)
                                                                <option value="{{ $school->scm_id }}"
                                                                    @if(in_array($school->scm_id, $trainer->school_ids ?? [])) selected @endif>
                                                                    {{ $school->scm_name }}_{{ $school->scm_udise_code }}
                                                                </option>
                                                                @endforeach
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="space-y-4">
                                                        <div>
                                                            <label class="block text-sm font-medium">Phone</label>
                                                            <input type="text" name="phone"
                                                                id="editTrainerPhone"
                                                                class="w-full border rounded p-2 mt-1">
                                                        </div>
                                                        <div>
                                                            <label
                                                                class="block text-sm font-medium text-gray-700">WhatsApp Number</label>
                                                            <input type="text" name="whatsapp_number"
                                                                id="editTrainerWhatsapp"
                                                                class="w-full border rounded p-2 mt-1">
                                                        </div>
                                                        <div>
                                                            <label
                                                            class="block text-sm font-medium text-gray-700">Pincode</label>
                                                            <input type="number" name="pincode"
                                                            id="editTrainerPincode"
                                                            class="w-full border rounded p-2" placeholder="Enter 6-digit Pincode">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="grid grid-cols-1 mt-3">
                                                    <div>
                                                        <label
                                                            class="block text-sm font-medium text-gray-700">Specialization</label>
                                                        <select class="w-full border rounded p-2 mt-1"
                                                            name="specialization[]" id="editSpecialization" multiple>
                                                            <option value="AI">AI</option>
                                                            <option value="IoT & Robotics">IoT & Robotics</option>
                                                            <option value="Cybersecurity">Cybersecurity</option>
                                                        </select>
                                                    </div>
                                                </div>

                                                <div class="mt-4">
                                                    <label class="block text-sm font-medium">Address</label>
                                                    <textarea name="address" id="editTrainerAddress" rows="3" class="w-full border rounded p-2 mt-1"></textarea>
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
                                                                    <option value="MCA">MCA</option>
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
                                                                accept=".jpg,.jpeg,.png"
                                                                class="w-full border rounded p-2 mt-1">
                                                            <p class="text-[12px] text-gray-600">*Allowed formats: JPG,
                                                                PNG. Max size: 2 MB</p>
                                                        </div>
                                                        
                                                        <div>
                                                            <label class="block text-sm font-medium">Educational
                                                                Qualification Certificates</label>
                                                            <input type="file" name="education_certificates[]"
                                                                multiple accept=".pdf,.jpg,.jpeg,.png"
                                                                class="w-full border rounded p-2 mt-1">
                                                            <p class="text-[12px] text-gray-600">*Allowed formats: PDF,
                                                                JPG, PNG. Max size: 2 MB</p>
                                                        </div>
                                                    </div>
                                                    <div class="space-y-4">
                                                        <div>
                                                            <label class="block text-sm font-medium">Experience
                                                                Certificate</label>
                                                            <input type="file" name="experience_certificate"
                                                                accept=".pdf,.jpg,.jpeg,.png"
                                                                class="w-full border rounded p-2 mt-1">
                                                            <p class="text-[12px] text-gray-600">*Allowed formats: PDF,
                                                                JPG, PNG. Max size: 2 MB</p>
                                                        </div>
                                                        <div>
                                                            <label class="block text-sm font-medium">CV /
                                                                Resume</label>
                                                            <input type="file" name="cv"
                                                                accept=".pdf"
                                                                class="w-full border rounded p-2 mt-1">
                                                            <p class="text-[12px] text-gray-600">*Allowed formats: PDF. Max size: 2 MB</p>
                                                        </div>
                                                        
                                                        <div>
                                                            <label class="block text-sm font-medium text-gray-700">Aadhaar Card (with address in one pdf)</label>
                                                            <input type="file" name="aadhar_card"
                                                                id="editTrainerAadhar"
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
                                                        Update Trainer
                                                    </button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>


                                    <!-- Delete Confirmation Modal -->
                                    <div id="deleteTrainerModal"
                                        class="fixed inset-0 bg-black bg-opacity-50 hidden flex items-center justify-center z-50">
                                        <div class="bg-white rounded-lg shadow-lg max-w-md p-6">
                                            <h3 class="text-xl font-semibold mb-4">Confirm Delete</h3>
                                            <p class="mb-6">Are you sure you want to delete this trainer?</p>
                                            <form id="deleteTrainerForm" method="POST">
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
        document.addEventListener('DOMContentLoaded', function () {
            if (document.querySelector('#schoolSelect')) {
        window.addSchoolSelect = new TomSelect('#schoolSelect', {
            plugins: ['remove_button'],
            create: false,
            maxItems: null,
            placeholder: '-- Select one or more schools --',
            sortField: { field: "text", direction: "asc" },
        });
    }

    // 🏫 TomSelect for Edit Modal (School)
    if (document.querySelector('#editSchoolSelect')) {
        window.editSchoolSelect = new TomSelect('#editSchoolSelect', {
            plugins: ['remove_button'],
            create: false,
            maxItems: null,
            placeholder: '-- Select one or more schools --',
            sortField: { field: "text", direction: "asc" },
        });
    }
    $(document).on("click", ".editBtn", function() {
        let trainerId = $(this).data("id");
        let schoolIds = $(this).data("school").toString().split(",");
        let specs = $(this).data("specialization").toString().split(",");

        // ✅ Update TomSelect (School)
        window.editSchoolSelect.clear();
        schoolIds.forEach(id => {
            window.editSchoolSelect.addItem(id.trim());
        });

        // ✅ Update Choices.js (Specialization)
        window.editSpecializationChoices.removeActiveItems();
        specs.forEach(s => {
            window.editSpecializationChoices.setChoiceByValue(s.trim());
        });

        $("#editTrainerModal").removeClass("hidden");
    });

});
         
</script>

<link href="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/css/tom-select.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/tom-select@2.3.1/dist/js/tom-select.complete.min.js"></script>

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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/gh/bbbootstrap/libraries@main/choices.min.css">
    <script src="https://cdn.jsdelivr.net/gh/bbbootstrap/libraries@main/choices.min.js"></script>
    <script>
        $(document).ready(function() {

            var multipleCancelButton = new Choices('#specialization', {
                removeItemButton: true,
                maxItemCount: 1,
                searchResultLimit: 3,
                renderChoiceLimit: 3
            });

            var multipleCancelButton1 = new Choices('#editSpecialization', {
                removeItemButton: true,
                maxItemCount: 1,
                searchResultLimit: 3,
                renderChoiceLimit: 3
            });
            // When opening edit modal, preselect values
    $(".editBtn").on("click", function() {
        let specs = $(this).data("specialization").split(",");
        multipleCancelButton1.removeActiveItems(); // remove previous selections
        specs.forEach(s => {
            multipleCancelButton1.setChoiceByValue(s.trim());
        });
        $("#editTrainerModal").removeClass("hidden");
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
                let rows = $("#trainerTable tbody tr");

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

                let rows = $("#trainerTable tbody tr").get();

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
                    $("#trainerTable tbody").append(row);
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
            $("#addTrainerBtn").on("click", function() {
                $("#addTrainerModal").removeClass("hidden");
            });
            $("#closeModal, #cancelModal").on("click", function() {
                $("#addTrainerModal").addClass("hidden");
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
                document.getElementById("addTrainerModal").classList.remove("hidden");
            });
        </script>
    @endif
    <script>
        $(document).ready(function() {
            // Edit Modal
            $(".editBtn").on("click", function() {
                let id = $(this).data("id");
                $("#editTrainerId").val(id);
                $("#editTrainerName").val($(this).data("name"));
                $("#editTrainerEmail").val($(this).data("email"));
                // $("#editTrainerSchool").val($(this).data("school_id"));
                $("#editTrainerPhone").val($(this).data("phone"));
                $("#editTrainerWhatsapp").val($(this).data("whatsapp_number"));
                $("#editTrainerPincode").val($(this).data("pincode"));
                $("#editTrainerAddress").val($(this).data("address"));
                
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
                
                // Specialization handling
                let specs = $(this).data("specialization").split(",");
                $("#editSpecialization option").prop("selected", false);
                specs.forEach(s => {
                    $("#editSpecialization option[value='" + s.trim() + "']").prop("selected",
                    true);
                });

                // ✅ Set schools (multi-select)
                let schoolIds = $(this).data("school").toString().split(",");
                $("#editSchoolSelect option").prop("selected", false);
                schoolIds.forEach(id => {
                    $("#editSchoolSelect option[value='" + id.trim() + "']").prop("selected", true);
                });

                // Set form action dynamically
                $("#editTrainerForm").attr("action", "/trainers/" + id);

                $("#editTrainerModal").removeClass("hidden");
            });

            $("#closeEditModal, #cancelEditModal").on("click", function() {
                $("#editTrainerModal").addClass("hidden");
            });

            // Delete Modal
            $(".deleteBtn").on("click", function() {
                let id = $(this).data("id");
                $("#deleteTrainerForm").attr("action", "/trainers/" + id);
                $("#deleteTrainerModal").removeClass("hidden");
            });

            $("#cancelDeleteModal").on("click", function() {
                $("#deleteTrainerModal").addClass("hidden");
            });
        });
    </script>
    <script>
document.getElementById("exportBtn").addEventListener("click", function () {

    let table = document.getElementById("trainerTable");

    // Columns to skip (0-based index)
    // sno=0, name=1, email=2, phone=3,Specialization=4, address=5, district=6, school=7
    // unwanted: Photo=8, CV=8, Edu Cert=9, Aadhar=10, Actions=11
    let skipCols = [8,9,10,11,12];

    let exportedData = [];
    let rows = table.querySelectorAll("tr");

    rows.forEach((row, rowIndex) => {
        let rowData = [];
        let cols = row.querySelectorAll("th, td");

        cols.forEach((cell, colIndex) => {
            if (!skipCols.includes(colIndex)) {
                rowData.push(cell.innerText.trim());
            }
        });

        exportedData.push(rowData);
    });

    // Create Excel sheet
    let wb = XLSX.utils.book_new();
    let ws = XLSX.utils.aoa_to_sheet(exportedData);

    XLSX.utils.book_append_sheet(wb, ws, "Trainers");

    // Download it
    XLSX.writeFile(wb, "Trainers.xlsx");
});
</script>

<script src="https://cdn.jsdelivr.net/npm/xlsx/dist/xlsx.full.min.js"></script>
<script>
    //dist filter
document.addEventListener("DOMContentLoaded", function () {
    const districtFilter = document.getElementById("districtFilter");
    const table = document.getElementById("trainerTable");
    const rows = table.getElementsByTagName("tr");

    districtFilter.addEventListener("change", function () {
        const selectedDistrict = this.value;

        for (let i = 1; i < rows.length; i++) {
            const distCell = rows[i].getElementsByTagName("td")[1]; // District column
            if (!distCell) continue;

            const districtCode = distCell.getAttribute("data-district-code");

            if (selectedDistrict === "" || districtCode === selectedDistrict) {
                rows[i].style.display = "";
            } else {
                rows[i].style.display = "none";
            }
        }
    });
});
</script>
    
</body>
@include('components.footer')
