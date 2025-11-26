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
                                <div class="bg-white p-8 rounded-lg w-full">
                                    <!-- Title -->
                                    <h2 class="text-2xl font-semibold text-center mb-6">Trainer Travel & Allowance</h2>

                                    @if(session('success'))
                                        <p class="bg-green-500 text-white p-2 rounded mb-3">
                                            {{ session('success') }}
                                        </p>
                                    @endif
                                    <form action="{{ route('trainer.travel.store') }}" method="POST"
                                        enctype="multipart/form-data">
                                        @csrf
                                        <!-- Hidden required values -->
                                        {{-- <input type="hidden" name="trainer_id" id="trainerID"> --}}
                                        {{-- <input type="hidden" name="specialization" id="specValue"> --}}
                                        <input type="hidden" name="district_id" value="{{ $districtId }}">

                                        <!-- TRAVEL EXPENSE -->
                                        <div class="grid grid-cols-2 mb-4 gap-4">
                                            <div>
                                                <label class="font-semibold block mb-1">Specialization</label>
                                                <select id="specializationSelect" name="specialization"
                                                    class="w-100 border p-2 rounded" required>
                                                    <option value="">-- Select Specialization --</option>
                                                    @foreach($specializations as $spec)
                                                        <option value="{{ $spec }}" {{ old('specialization') == $spec ? 'selected' : '' }}>
                                                            {{ $spec }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            <div>
                                                <label class="font-semibold block mb-1">Trainer</label>
                                                <select id="trainerSelect" name="trainer_id"
                                                    class="w-100 border p-2 rounded" required>
                                                    <option value="">-- Select Trainer --</option>
                                                </select>
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
                                                    <input name="main_to" class="w-100 border p-2 rounded"
                                                        value="OKCL, Bhubaneshwar" readonly>
                                                </div>
                                                <div>
                                                    <label class="font-semibold block mb-1">Date of Travel</label>
                                                    <input name="main_date" class="w-100 border p-2 rounded" type="date"
                                                        required>

                                                </div>

                                                <div>
                                                    <label class="font-semibold block mb-1">Mode of Travel</label>
                                                    <select name="main_mode" class="w-100 border p-2 rounded" required>
                                                        <option value="">-- Select Mode --</option>
                                                        <option>Bus</option>
                                                        <option>Train</option>
                                                        <option>Bike (₹7/km)</option>
                                                    </select>
                                                </div>
                                                <div>
                                                    <label class="font-semibold block mb-1">Amount(₹)</label>
                                                    <input name="main_amount" class="w-100 border p-2 rounded"
                                                        type="number" required placeholder="Amount">
                                                </div>
                                                <div>
                                                    <label class="font-semibold block mb-1">Upload Bill (PDF)</label>
                                                    <input name="main_bill" class="w-100 border p-2 rounded col-span-2"
                                                        type="file" accept="application/pdf">
                                                    @error('main_bill')
                                                        <p class="text-red-500 text-sm">{{ $message }}</p>
                                                    @enderror
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
                                                    <input name="return_from" class="w-100 border p-2 rounded"
                                                        value="OKCL, Bhubaneshwar" readonly>
                                                </div>
                                                <div>
                                                    <label class="font-semibold block mb-1">To</label>
                                                    <input name="return_to" class="w-100 border p-2 rounded"
                                                        placeholder="full address">

                                                </div>
                                                <div>
                                                    <label class="font-semibold block mb-1">Mode of Travel</label>
                                                    <select name="return_mode" class="w-100 border p-2 rounded">
                                                        <option value="">-- Select Mode --</option>
                                                        <option>Bus</option>
                                                        <option>Train</option>
                                                        <option>Bike (₹7/km)</option>
                                                    </select>
                                                </div>
                                                <div>
                                                    <label class="font-semibold block mb-1">Amount(₹)</label>
                                                    <input name="return_amount" class="w-100 border p-2 rounded"
                                                        type="number" placeholder="Amount">
                                                </div>
                                                <div>
                                                    <label class="font-semibold block mb-1">Upload Bill (PDF)</label>
                                                    <input name="return_bill_file"
                                                        class="w-100 border p-2 rounded col-span-2" type="file"
                                                        accept="application/pdf">
                                                    @error('return_bill_file')
                                                        <p class="text-red-500 text-sm">{{ $message }}</p>
                                                    @enderror
                                                </div>
                                            </div>
                                        </div>

                                        <a type="button" id="addReturnBtn"
                                            class="mb-4 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                                            + Add Return Travel Bill
                                        </a>
                                        <br>

                                        <!-- Submit -->
                                        <button type="submit"
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
        const returnSection = document.getElementById("returnTravel");
        const toggleReturnBtn = document.getElementById("addReturnBtn");
        const hasReturnInput = document.getElementById("hasReturn");

        const returnFields = [
            "return_to",
            "return_mode",
            "return_amount",
        ];
        toggleReturnBtn.addEventListener("click", function () {
            if (returnSection.classList.contains("hidden")) {
                // SHOW RETURN
                returnSection.classList.remove("hidden");
                hasReturnInput.value = 1;

                returnFields.forEach(name => {
                    const field = document.querySelector(`[name="${name}"]`);
                    if (field) { field.setAttribute("required", true); }
                });

                toggleReturnBtn.innerText = "- Remove Return Travel";
                toggleReturnBtn.classList.remove("bg-blue-600");
                toggleReturnBtn.classList.add("bg-red-600", "hover:bg-red-700");
            } else {
                // HIDE RETURN
                returnSection.classList.add("hidden");
                hasReturnInput.value = 0;

                // Clear all fields inside return form
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
            if (this.files[0] && this.files[0].size > 3 * 1024 * 1024) {
                alert("Main bill file size must be less than 3MB!");
                this.value = "";
            }
        });

        document.querySelector('input[name="return_bill_file"]').addEventListener('change', function () {
            if (this.files[0] && this.files[0].size > 3 * 1024 * 1024) {
                alert("Return bill file size must be less than 3MB!");
                this.value = "";
            }
        });
    </script>

</body>
@include('components.footer')