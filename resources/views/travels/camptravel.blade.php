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
                            <h1 class="m-0 text-dark">Training Travel & Allowance</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Finance & Bills</a></li>
                                <li class="breadcrumb-item active">Training Travel & Allowance</li>
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
                                <div class="bg-white p-8 rounded-lg w-full">
                                    <!-- Title -->
                                    <h2 class="text-2xl font-semibold text-center mb-6">Training Travel & Allowance</h2>

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
                                    <div class="mb-2 flex justify-end">
                                        <a href="{{route('camp.travel.list')}}"><button
                                                class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg shadow-md flex items-center gap-2">
                                                <i class="fas fa-list"></i> View Travel Bills
                                            </button></a>
                                    </div>
                                    <form action="{{ route('camp.travel.store') }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <!-- Hidden required values -->
                                        <input type="hidden" name="district_id" value="{{ $districtId }}">

                                        <!-- TRAVEL EXPENSE -->
                                        <div class="grid grid-cols-2 mb-2 gap-4">
                                            <div>
                                                <label class="font-semibold block mb-1">School</label>
                                                <select id="school_id" name="school"
                                                    class="w-100 border p-2 rounded" required>
                                                    <option value="">-- Select School --</option>
                                                    @foreach($schools as $school)
                                                        <option value="{{ $school->scm_id }}" >
                                                            {{ $school->scm_name }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div>
                                                <label class="font-semibold block mb-1">Date of Training</label>
                                                {{-- Display only --}}
                                                <input type="text"
                                                    id="training_date_display"
                                                     class="w-100 border p-2 rounded bg-gray-100"
                                                    placeholder="dd-mm-yyy"
                                                    readonly>

                                                {{-- Hidden field sent to backend --}}
                                                <input type="hidden"
                                                    name="training_date[]"
                                                    id="training_date">
                                            </div>
                                            
                                        </div>
                                        <div class="grid grid-cols-1 mb-4 gap-4">
                                            <div>
                                            <label class="font-semibold block mb-0">Staffs</label>
                                            <div id="trainerCheckboxes"
                                                class="border rounded p-2 bg-gray-50 flex flex-wrap gap-4">
                                                <p class="text-gray-400">Select school first</p>
                                            </div>
                                            </div>
                                        </div>
                                        <div class="border rounded p-4 mb-4 bg-gray-50">

                                            <h2 class="font-bold text-center text-gray-900 mb-3">Main Travel</h2>
                                            <hr><br>

                                            <div class="grid grid-cols-2 gap-4">

                                                <div>
                                                    <label class="font-semibold block mb-1">From</label>
                                                    <input name="main_from" class="w-100 border p-2 rounded"
                                                        placeholder="full address" required>

                                                </div>
                                                <div>
                                                    <label class="font-semibold block mb-1">To</label>
                                                    <input name="main_to" id="main_to"
                                                        class="w-100 border p-2 rounded bg-gray-100"
                                                        readonly
                                                        placeholder="full address" required>
                                                </div>

                                                <div>
                                                    <label class="font-semibold block mb-1">Distance (km)</label>
                                                    <input type="number" id="main_distance" name="main_distance" class="w-100 border p-2 rounded" placeholder="from - to" required>
                                                </div>
                                                <div>
                                                    <label class="font-semibold block mb-1">Upload Bill (PDF | all in one pdf | max: 5mb)</label>
                                                    <input name="main_bill" class="w-100 border p-2 rounded col-span-2"
                                                        type="file" accept="application/pdf" required>
                                                    @error('main_bill')
                                                        <p class="text-red-500 text-sm">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                                <div>
                                                    <label class="font-semibold block mb-1">Amount(₹2.5/km)</label>
                                                    <input name="main_amount"
                                                        id="main_amount"
                                                        class="w-100 border p-2 rounded bg-gray-100"
                                                        type="number"
                                                        readonly
                                                        placeholder="amount (₹2.5/km)">
                                                </div>
                                                <div>
                                                    <label class="font-semibold block mb-1">Total Amount (Amount × no of staffs)</label>
                                                    <input name="total_main_amount" id="total_main_amount"
                                                        class="w-100 border p-2 rounded bg-gray-100"
                                                        type="number"
                                                        readonly
                                                        placeholder="amount × no of staffs">
                                                </div>
                                            </div>
                                        </div>

                                        <input type="hidden" name="has_return" id="hasReturn" value="0">
                                        <div class="border rounded p-4 mb-4 bg-gray-50 hidden" id="returnTravel">
                                            <h2 class="font-bold text-center text-gray-900 mb-3">Return Travel</h2>
                                            <hr><br>

                                            <div class="grid grid-cols-2 gap-4">
                                                <div>
                                                    <label class="font-semibold block mb-1">From</label>
                                                    <input name="return_from"  id="return_from"
                                                        class="w-100 border p-2 rounded bg-gray-100" 
                                                        placeholder="full address" readonly>
                                                </div>
                                                <div>
                                                    <label class="font-semibold block mb-1">To</label>
                                                    <input name="return_to" class="w-100 border p-2 rounded"
                                                        placeholder="full address">

                                                </div>
                                                <div>
                                                    <label class="font-semibold block mb-1">Distance (km)</label>
                                                    <input type="number" id="return_distance" name="return_distance" class="w-100 border p-2 rounded" placeholder="from - to">
                                                </div>
                                                
                                                <div>
                                                    <label class="font-semibold block mb-1">Upload Bill (PDF | all in one pdf | max: 5mb)</label>
                                                    <input name="return_bill_file"
                                                        class="w-100 border p-2 rounded col-span-2" type="file"
                                                        accept="application/pdf">
                                                    @error('return_bill_file')
                                                        <p class="text-red-500 text-sm">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                                <div>
                                                    <label class="font-semibold block mb-1">Amount(₹2.5/km)</label>
                                                    <input name="return_amount"  id="return_amount"
                                                        class="w-100 border p-2 rounded bg-gray-100"
                                                        type="number"
                                                        readonly
                                                        placeholder="amount (₹2.5/km)">
                                                </div>
                                                <div>
                                                    <label class="font-semibold block mb-1">Total Amount (Amount × no of staffs)</label>
                                                    <input name="total_return_amount" id="total_return_amount"
                                                        class="w-100 border p-2 rounded bg-gray-100"
                                                        type="number"
                                                        readonly
                                                        placeholder="amount × no of staffs">
                                                </div>
                                            </div>
                                        </div>

                                        <a type="button" id="addReturnBtn"
                                            class="mb-4 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                                            + Add Return Travel Bill
                                        </a>
                                        <br>

                                        <!-- Submit -->
                                        <button type="submit" id="submitBtn"
                                            class="w-full bg-green-600 hover:bg-green-700 text-white p-3 mt-3 rounded-lg">
                                            Submit Travel Bill
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
        document.getElementById("specializationSelect").addEventListener("change", function () {
            const specialization = this.value;
            document.getElementById("specializationSelect").value = specialization;

            const districtId = "{{ $districtId }}";
            const trainerSelect = document.getElementById("trainerSelect");

            trainerSelect.innerHTML = `<option>Loading...</option>`;

            if (specialization !== "") {
                fetch(`/get-trainers/${districtId}/${specialization}`)
                    .then(response => response.json())
                    .then(data => {
                        trainerSelect.innerHTML = `<option value="">-- Select Trainer --</option>`;

                        data.forEach(trainer => {
                            trainerSelect.innerHTML += `
                            <option value="${trainer.trainer_id}">
                                ${trainer.trainer_name}
                            </option>`;
                        });
                    });
            } else {
                trainerSelect.innerHTML = `<option value="">-- Select Trainer --</option>`;
            }
        });
        document.getElementById("trainerSelect").addEventListener("change", function () {
            document.getElementById("trainerSelect").value = this.value;
        });
    </script>
<script>
    function syncReturnFrom() {
        const returnSection = document.getElementById("returnTravel");
        const mainToInput = document.getElementById("main_to");
        const returnFromInput = document.getElementById("return_from");

        if (!mainToInput || !returnFromInput) return;

        // Always keep return_from equal to main_to IF return is open
        if (!returnSection.classList.contains("hidden")) {
            returnFromInput.value = mainToInput.value;
        }
    }
</script>
    <script>
        const returnSection = document.getElementById("returnTravel");
        const toggleReturnBtn = document.getElementById("addReturnBtn");
        const hasReturnInput = document.getElementById("hasReturn");
        const returnBillInput = document.querySelector('input[name="return_bill_file"]');
        const mainToInput = document.getElementById("main_to");
        const returnFromInput = document.getElementById("return_from");

        if (!returnSection.classList.contains("hidden")) {
            returnFromInput.value = mainToInput.value;
        }

        const returnFields = [
            'return_from',
            'return_to',
            'return_distance',
            'return_amount',
            'total_return_amount',
            'return_bill_file'
        ];

        toggleReturnBtn.addEventListener("click", function () {
            if (returnSection.classList.contains("hidden")) {
                // SHOW RETURN
                returnSection.classList.remove("hidden");
                hasReturnInput.value = 1;

                syncReturnFrom();

                returnFields.forEach(name => {
                    const field = document.querySelector(`[name="${name}"]`);
                    if (field) field.setAttribute("required", true);
                });
                
                toggleReturnBtn.innerText = "- Remove Return Travel";
                toggleReturnBtn.classList.remove("bg-blue-600");
                toggleReturnBtn.classList.add("bg-red-600", "hover:bg-red-700");
            } else {
                // HIDE RETURN
                returnSection.classList.add("hidden");
                hasReturnInput.value = 0;

                returnFields.forEach(name => {
                    const field = document.querySelector(`[name="${name}"]`);
                    if (field) {
                        field.removeAttribute("required");
                        field.value = "";
                    }
                });

                toggleReturnBtn.innerText = "+ Add Return Travel Bill";
                toggleReturnBtn.classList.remove("bg-red-600", "hover:bg-red-700");
                toggleReturnBtn.classList.add("bg-blue-600");
            }
        });
    </script>

    <script>
        document.querySelector('input[name="main_bill"]').addEventListener('change', function () {
            if (this.files[0] && this.files[0].size > 5 * 1024 * 1024) {
                alert("Main bill file size must be less than 5MB!");
                this.value = "";
            }
        });

        document.querySelector('input[name="return_bill_file"]').addEventListener('change', function () {
            if (this.files[0] && this.files[0].size > 5 * 1024 * 1024) {
                alert("Return bill file size must be less than 5MB!");
                this.value = "";
            }
        });
    </script>
<script>
    const schools = @json($schools);

    document.getElementById('school_id').addEventListener('change', function () {
        const selectedId = this.value;
        const school = schools.find(s => s.scm_id == selectedId);

        if (school) {
            // Training date
            if (school.training_date) {
                document.getElementById('training_date').value = school.training_date;
                const dateObj = new Date(school.training_date);
                document.getElementById('training_date_display').value =
                    dateObj.toLocaleDateString('en-GB');
            }

            //  MAIN TO = SCHOOL NAME
            document.getElementById('main_to').value = school.scm_name;

            syncReturnFrom();

        } else {
            document.getElementById('training_date').value = '';
            document.getElementById('training_date_display').value = '';
            document.getElementById('main_to').value = '';
        }
    });
</script>

<script>
    document.getElementById('school_id').addEventListener('change', function () {
        const schoolId = this.value;
        const box = document.getElementById('trainerCheckboxes');

        box.innerHTML = '<p>Loading...</p>';

        if (!schoolId) {
            box.innerHTML = '<p>Select school first</p>';
            return;
        }

        fetch(`/get-people-by-school/${schoolId}`)
            .then(res => res.json())
            .then(data => {
                box.innerHTML = '';

                renderGroup('Trainer', data.trainers, 'trainer');
                renderGroup('Coordinator', data.coordinators, 'coordinator');
                renderGroup('Supporting Staff', data.staff, 'staff');

                if (
                    data.trainers.length === 0 &&
                    data.coordinators.length === 0 &&
                    data.staff.length === 0
                ) {
                    box.innerHTML = '<p class="text-red-500">No personnel found</p>';
                }
            });
    });

    function renderGroup(title, items, role) {
        if (!items || items.length === 0) return;

        const box = document.getElementById('trainerCheckboxes');

        box.innerHTML += `
            <div class="w-full">
                <p class="font-semibold text-gray-700 mb-1">${title}</p>
                <div class="flex flex-wrap gap-4 mb-1" id="${role}-group"></div>
            </div>
        `;

        const groupDiv = document.getElementById(`${role}-group`);

        items.forEach(item => {
            groupDiv.innerHTML += `
                <label class="flex items-center space-x-2">
                    <input type="checkbox"
                           name="trainer_ids[]"
                           value="${role}_${item.id}"
                           class="form-checkbox">
                    <span>${item.name}</span>
                </label>
            `;
        });
    }
    const ROLE_LIMITS = {
        trainer: 3,
        coordinator: 1,
        staff: 1
    };

    document.addEventListener('change', function (e) {
        if (e.target.name !== 'trainer_ids[]') return;

        const value = e.target.value; // e.g. trainer_12
        const role = value.split('_')[0];

        const checkedInRole = document.querySelectorAll(
            `input[name="trainer_ids[]"]:checked[value^="${role}_"]`
        ).length;

        if (checkedInRole > ROLE_LIMITS[role]) {
            e.target.checked = false;
            alert(`You can select maximum ${ROLE_LIMITS[role]} ${role}(s).`);
            return;
        }

        updateTotals();
        toggleSubmitButton();
    });

</script>

<script>
    const RATE_PER_KM = 2.5;

    function getSelectedTrainerCount() {
        return document.querySelectorAll('input[name="trainer_ids[]"]:checked').length;
    }

    function updateTotals() {
        const trainerCount = getSelectedTrainerCount();

        const mainAmount = parseFloat(document.getElementById('main_amount').value) || 0;
        const returnAmount = parseFloat(document.getElementById('return_amount').value) || 0;

        document.getElementById('total_main_amount').value =
            (mainAmount * trainerCount).toFixed(2);

        document.getElementById('total_return_amount').value =
            (returnAmount * trainerCount).toFixed(2);
    }

    // MAIN DISTANCE → AMOUNT
    document.getElementById('main_distance').addEventListener('input', function () {
        const km = parseFloat(this.value) || 0;
        document.getElementById('main_amount').value = (km * RATE_PER_KM).toFixed(2);
        updateTotals();
    });

    // RETURN DISTANCE → AMOUNT
    document.getElementById('return_distance').addEventListener('input', function () {
        const km = parseFloat(this.value) || 0;
        document.getElementById('return_amount').value = (km * RATE_PER_KM).toFixed(2);
        updateTotals();
    });

    // TRAINER SELECTION CHANGE
    document.addEventListener('change', function (e) {
        if (e.target.name === 'trainer_ids[]') {
            updateTotals();
        }
    });
</script>

<script>
    const submitBtn = document.getElementById('submitBtn');

    function toggleSubmitButton() {
        const count = document.querySelectorAll('input[name="trainer_ids[]"]:checked').length;

        submitBtn.disabled = count === 0;

        if (count === 0) {
            submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
        } else {
            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        }
    }

    // initial state
    toggleSubmitButton();

    // listen to trainer checkbox changes
    document.addEventListener('change', function (e) {
        if (e.target.name === 'trainer_ids[]') {
            toggleSubmitButton();
        }
    });
</script>


</body>
@include('components.footer')