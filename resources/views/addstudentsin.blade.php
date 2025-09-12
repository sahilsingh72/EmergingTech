@include('components.navbar')
@include('components.sidebar')
<style>
    .sort::after {
        content: " ⇅";
        font-size: 0.7rem;
        color: gray;
    }

    #studentTable {
        table-layout: auto;
        /* allow natural sizing */
        width: 100%;
        /* still stretch full table */
    }

    #studentTable th,
    #studentTable td {
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
                            <h1 class="m-0 text-dark">Add Student</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Add Student</a></li>
                                <li class="breadcrumb-item active">Students</li>
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
                                    <h2 class="text-2xl font-semibold text-center mb-6">Add Student</h2>
                                    <!-- Flash Messages -->
                                    @if (session('success'))
                                        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
                                            {{ session('success') }}
                                        </div>
                                    @endif
                                    @if ($errors->any())
                                        <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                                            <ul class="list-disc list-inside">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                    {{-- <form action="{{ route('student.store') }}" method="POST" class="space-y-4">
                                        @csrf

                                        <div>
                                            <label class="block text-sm">Name</label>
                                            <input type="text" name="stu_name"
                                                class="w-full border rounded px-3 py-2" value="{{ old('stu_name') }}"
                                                required>
                                        </div>

                                        <div>
                                            <label class="block text-sm">Roll Number</label>
                                            <input type="text" name="stu_roll_number"
                                                class="w-full border rounded px-3 py-2"
                                                value="{{ old('stu_roll_number') }}" required>
                                        </div>

                                        <div>
                                            <label class="block text-sm">Class</label>
                                            <input type="text" name="stu_class"
                                                class="w-full border rounded px-3 py-2" value="{{ old('stu_class') }}"
                                                required>
                                        </div>

                                        <div>
                                            <label class="block text-sm">Section</label>
                                            <input type="text" name="stu_section"
                                                class="w-full border rounded px-3 py-2" value="{{ old('stu_section') }}"
                                                required>
                                        </div>

                                        <div>
                                            <label class="block text-sm">Gender</label>
                                            <select name="stu_gender" class="w-full border rounded px-3 py-2" required>
                                                <option value="">Select Gender</option>
                                                <option value="Male"
                                                    {{ old('stu_gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                                <option value="Female"
                                                    {{ old('stu_gender') == 'Female' ? 'selected' : '' }}>Female
                                                </option>
                                                <option value="Other"
                                                    {{ old('stu_gender') == 'Other' ? 'selected' : '' }}>Other</option>
                                            </select>
                                        </div>

                                        <div>
                                            <label class="block text-sm">Date of Birth</label>
                                            <input type="date" name="stu_dob" class="w-full border rounded px-3 py-2"
                                                value="{{ old('stu_dob') }}" required>
                                        </div>

                                        <div>
                                            <label class="block text-sm">Father's Name</label>
                                            <input type="text" name="stu_fathername"
                                                class="w-full border rounded px-3 py-2"
                                                value="{{ old('stu_fathername') }}" required>
                                        </div>

                                        <div>
                                            <label class="block text-sm">UDISE Code</label>
                                            <input type="text" name="stu_scm_udise"
                                                class="w-full border rounded px-3 py-2"
                                                value="{{ old('stu_scm_udise') }}" required>
                                        </div>

                                        <div>
                                            <label class="block text-sm">School Name</label>
                                            <input type="text" name="stu_schoolname"
                                                class="w-full border rounded px-3 py-2"
                                                value="{{ old('stu_schoolname') }}" required>
                                        </div>

                                        <div>
                                            <label class="block text-sm">Block</label>
                                            <input type="text" name="stu_block"
                                                class="w-full border rounded px-3 py-2" value="{{ old('stu_block') }}"
                                                required>
                                        </div>

                                        <div>
                                            <label class="block text-sm">District</label>
                                            <input type="text" name="stu_dist"
                                                class="w-full border rounded px-3 py-2" value="{{ old('stu_dist') }}"
                                                required>
                                        </div>

                                        <div class="text-right">
                                            <a href="{{ route('studentlist') }}"
                                                class="bg-gray-500 text-white px-4 py-2 rounded">Cancel</a>
                                            <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Add
                                                Student</button>
                                        </div>
                                    </form> --}}
                                    <!-- Form -->
                            <form action="{{ route('student.store') }}" method="POST" class="space-y-6">
                                @csrf

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Name -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Name</label>
                                        <input type="text" name="stu_name" value="{{ old('stu_name') }}"
                                            class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring-green-500 focus:border-green-500" required>
                                    </div>

                                    <!-- Roll Number -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Roll Number</label>
                                        <input type="text" name="stu_roll_number" value="{{ old('stu_roll_number') }}"
                                            class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring-green-500 focus:border-green-500" required>
                                    </div>

                                    <!-- Class -->
                                    <div>
    <label class="block text-sm font-medium text-gray-700">Class</label>
    <select name="stu_classid" id="stu_classid" class="w-full border rounded px-3 py-2" required>
        <option value="">-- Select Class --</option>
        <option value="1" data-name="8">Class 8</option>
        <option value="2" data-name="9">Class 9</option>
        <option value="3" data-name="10">Class 10</option>
        <option value="4" data-name="11">Class 11</option>
        <option value="5" data-name="12">Class 12</option>
    </select>
</div>
<input type="hidden" name="stu_class" id="stu_class">

                                    <!-- Section -->
                                    <div>
    <label class="block text-sm font-medium text-gray-700">Section</label>
    <select name="stu_sectionid" id="stu_sectionid" class="w-full border rounded px-3 py-2" required>
        <option value="">-- Select Section --</option>
        <option value="1" data-name="A">A</option>
        <option value="2" data-name="B">B</option>
        <option value="3" data-name="C">C</option>
        <option value="4" data-name="D">D</option>
        <option value="5" data-name="E">E</option>
        <option value="6" data-name="F">F</option>
    </select>
</div>
<input type="hidden" name="stu_section" id="stu_section">

                                    <!-- Gender -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Gender</label>
                                        <select name="stu_gender" class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring-green-500 focus:border-green-500" required>
                                            <option value="">Select Gender</option>
                                            <option value="Male" {{ old('stu_gender') == 'Male' ? 'selected' : '' }}>Male</option>
                                            <option value="Female" {{ old('stu_gender') == 'Female' ? 'selected' : '' }}>Female</option>
                                            <option value="Other" {{ old('stu_gender') == 'Other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                    </div>

                                    <!-- Date of Birth -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Date of Birth</label>
                                        <input type="date" name="stu_dob" value="{{ old('stu_dob') }}"
                                            class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring-green-500 focus:border-green-500" required>
                                    </div>

                                    <!-- Father's Name -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Father's Name</label>
                                        <input type="text" name="stu_fathername" value="{{ old('stu_fathername') }}"
                                            class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring-green-500 focus:border-green-500" required>
                                    </div>

                                    <!-- UDISE -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">UDISE Code</label>
                                        <input type="text" name="stu_scm_udise" value="{{ old('stu_scm_udise') }}"
                                            class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring-green-500 focus:border-green-500" required>
                                    </div>

                                    <!-- School Name -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">School Name</label>
                                        <input type="text" name="stu_schoolname" value="{{ old('stu_schoolname') }}"
                                            class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring-green-500 focus:border-green-500" required>
                                    </div>

                                    <!-- Block -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Block</label>
                                        <input type="text" name="stu_block" value="{{ old('stu_block') }}"
                                            class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring-green-500 focus:border-green-500" required>
                                    </div>

                                    <!-- District -->
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">District</label>
                                        <input type="text" name="stu_dist" value="{{ old('stu_dist') }}"
                                            class="mt-1 block w-full border rounded-lg px-3 py-2 focus:ring-green-500 focus:border-green-500" required>
                                    </div>
                                </div>

                                <!-- Buttons -->
                                <div class="flex justify-end space-x-4 pt-6">
                                    <a href="{{ route('studentlist') }}" class="bg-gray-500 hover:bg-gray-600 text-white px-5 py-2 rounded-lg">Cancel</a>
                                    <button type="submit" class="bg-green-600 hover:bg-green-700 text-white px-5 py-2 rounded-lg">Add Student</button>
                                </div>
                            </form>


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
document.getElementById("stu_classid").addEventListener("change", function() {
    let selected = this.options[this.selectedIndex];
    document.getElementById("stu_class").value = selected.getAttribute("data-name");
});

document.getElementById("stu_sectionid").addEventListener("change", function() {
    let selected = this.options[this.selectedIndex];
    document.getElementById("stu_section").value = selected.getAttribute("data-name");
});
</script>




</body>
@include('components.footer')
