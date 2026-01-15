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
                                <li class="breadcrumb-item"><a href="#"></a>Finance & Bills</li>
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
                            <div class=" sm:p-8 bg-white shadow sm:rounded-lg">
                                <div class="bg-white p-8 rounded-lg w-full">

                                    <h2 class="text-2xl font-semibold text-center mb-6">Camp Fooding Entry</h2>

                                    @if(session('success'))
                                        <p class="bg-green-500 text-white p-2 rounded mb-3">
                                            {{ session('success') }}
                                        </p>
                                    @endif
                                    @if(session('error'))
                                        <div class="bg-red-500 text-white p-3 rounded mb-4">
                                            {{ session('error') }}
                                        </div>
                                    @endif
                                    <div class="mb-2 flex justify-end">
                                        <a href="{{route('foodbills.list')}}"><button
                                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg shadow-md flex items-center gap-2">
                                            <i class="fas fa-list"></i> View Food Bills
                                        </button></a>
                                    </div>
                                    <form action="{{ route('foodbills.store') }}" method="POST" enctype="multipart/form-data">
                                    @csrf

                                        <!-- SCHOOL SELECT -->
                                        <div class="mb-5">
                                            <label class="font-semibold block mb-1">Select School</label>
                                            <select name="school_id" id="school_id" class="form-control" required>
                                                <option value="">-- Select School --</option>
                                                @foreach($schools as $school)
                                                    <option value="{{ $school->scm_id }}">
                                                        {{ $school->scm_name }} - {{ $school->scm_udise_code }},
                                                        {{ $school->scm_dist }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <div id="expenseContainer">
                                            <div class="border rounded bg-gray-50 p-4 mt-4 relative">

                                                <div class="grid grid-cols-2 gap-4">
                                                    {{-- FIXED BILL TYPE --}}
                                                    <div>
                                                        <label class="font-semibold block mb-1">
                                                            Bill Type
                                                        </label>
                                                        <input type="text"
                                                            value="Camp Fooding"
                                                            class="border p-2 rounded w-full bg-gray-200"
                                                            disabled>

                                                        <input type="hidden"
                                                            name="bill_type[]"
                                                            value="Camp Fooding">
                                                    </div>

                                                    <div>
                                                        <label class="font-semibold block mb-1">Date of Training</label>
                                                        {{-- Display only --}}
                                                        <input type="text"
                                                            id="training_date_display"
                                                            class="border p-2 rounded w-full bg-gray-200"
                                                            readonly>

                                                        {{-- Hidden field sent to backend --}}
                                                        <input type="hidden"
                                                            name="training_date[]"
                                                            id="training_date">
                                                    </div>

                                                    <div>
                                                        <label class="font-semibold block mb-1">Amount (₹)</label>
                                                        <input type="number"
                                                            name="amount[]"
                                                            class="border p-2 rounded w-full amountInput"
                                                            placeholder="Amount (Max ₹17,500)"
                                                            max="17500"
                                                            min="1"
                                                            required>
                                                    </div>

                                                    <div>
                                                        <label class="font-semibold block mb-1">Upload Bill (PDF)</label>
                                                        <input type="file"
                                                            accept="application/pdf"
                                                            name="bill_file[]"
                                                            class="border p-2 rounded w-full"
                                                            required />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>



                                        <button type="submit" id="submitBtn"
                                            class="mt-6 w-full bg-green-600 hover:bg-green-700 text-white py-3 rounded font-bold">
                                            Submit Bills
                                        </button>
                                    </form>



                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

    </div>
<script>
    const schools = @json($schools);

    document.getElementById('school_id').addEventListener('change', function () {
        const selectedId = this.value;

        const school = schools.find(s => s.scm_id == selectedId);

        if (school && school.training_date) {
            // hidden field for form submit
            document.getElementById('training_date').value = school.training_date;

            // readable format for UI (DD-MM-YYYY)
            const dateObj = new Date(school.training_date);
            document.getElementById('training_date_display').value =
                dateObj.toLocaleDateString('en-GB');
        } else {
            document.getElementById('training_date').value = '';
            document.getElementById('training_date_display').value = '';
        }
    });
</script>

</body>
@include('components.footer')