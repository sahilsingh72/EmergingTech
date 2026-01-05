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
                            <h1 class="m-0 text-dark">Expenses Bill</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#"></a>Finance & Bills</li>
                                <li class="breadcrumb-item active">Expenses Bill</li>
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

                                    <h2 class="text-2xl font-semibold text-center mb-6">Camp Expense Entry</h2>

                                    @if(session('success'))
                                        <p class="bg-green-500 text-white p-2 rounded mb-3">
                                            {{ session('success') }}
                                        </p>
                                    @endif
                                    <div class="mb-2 flex justify-end">
                                        <a href="{{route('camp.expense.list')}}"><button
                                            class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg shadow-md flex items-center gap-2">
                                            <i class="fas fa-list"></i> View Expense Bills
                                        </button></a>
                                    </div>
                                    <form action="{{ route('camp.expense.store') }}" method="POST" enctype="multipart/form-data">
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

                                        <div id="expenseContainer"></div>

                                        <button type="button" id="addRowBtn"
                                            class="mt-4 bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
                                            + Add Bill
                                        </button>

                                        <button type="submit" id="submitBtn"
                                            class="mt-6 w-full bg-green-600 hover:bg-green-700 text-white py-3 rounded font-bold">
                                            Submit All Bills
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
        let billOptions = ["Inauguration", "Generator", "Camp Fooding", "Misc"];
        let usedOptions = [];

        const container = document.getElementById("expenseContainer");
        const addBtn = document.getElementById("addRowBtn");

        function updateAddButtonState() {
            let lastRow = container.lastElementChild;
            if (!lastRow) return addBtn.disabled = true;

            let selectedValue = lastRow.querySelector(".billTypeSelect").value;
            addBtn.disabled = (selectedValue.trim() === "");

            addBtn.classList.toggle("bg-gray-400", addBtn.disabled);
            addBtn.classList.toggle("bg-blue-600", !addBtn.disabled);
        }

        function lockPreviousDropdowns() {
            let rows = [...container.children];
            rows.forEach((row, index) => {
                if (index !== rows.length - 1) {
                    let select = row.querySelector(".billTypeSelect");
                    select.classList.add("pointer-events-none", "bg-gray-200");
                    // select.disabled = true;
                }
            });
        }

        function restoreOption(removedValue) {
            usedOptions = usedOptions.filter(v => v !== removedValue);
        }

        function renderRow() {
            let available = billOptions.filter(opt => !usedOptions.includes(opt));

            let row = document.createElement("div");
            row.className = "border rounded bg-gray-50 p-4 mt-4 relative";

            row.innerHTML = `
        <!-- Remove Button -->
        <button class="removeBtn absolute top-2 right-2 bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded hidden">-</button>

        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="font-semibold block mb-1">Bill Type</label>
                <select name="bill_type[]" 
                        class="billTypeSelect w-full border p-2 rounded">
                    <option value="">-- Select --</option>
                    ${available.map(option => `<option value="${option}">${option}</option>`).join("")}
                </select>
                <input type="text" 
                    name="custom_bill_type[]" 
                    placeholder="Enter custom type" class="miscInput border p-2 rounded w-full mt-2 hidden" />
            </div>

            <div>
                <label class="font-semibold block mb-1">Date of Training</label>
                <input type="date" 
                    name="training_date[]"
                    class="border p-2 rounded w-full" required/>
            </div>

            <div>
                <label class="font-semibold block mb-1">Amount(₹)</label>
                <input type="number" 
                    name="amount[]"
                    class="border p-2 rounded w-full" placeholder="Amount" required/>
            </div>

            <div>
                <label class="font-semibold block mb-1">Upload Bill (PDF)</label>
                <input type="file" accept="application/pdf" 
                    name="bill_file[]"
                    class="border p-2 rounded w-full" required/>
            </div>
        </div>
    `;

            container.appendChild(row);

            // Show remove button only if more than one row
            if (container.children.length > 1) {
                row.querySelector(".removeBtn").classList.remove("hidden");
            }

            row.querySelector(".removeBtn").addEventListener("click", () => {
                let selectedValue = row.querySelector(".billTypeSelect").value;
                restoreOption(selectedValue);
                row.remove();
                updateAddButtonState();
                lockPreviousDropdowns();
            });

            let select = row.querySelector(".billTypeSelect");

            select.addEventListener("change", (e) => {
                let selected = e.target.value;
                let miscInput = row.querySelector(".miscInput");

                if (selected === "Misc") {
                    miscInput.classList.remove("hidden");

                    miscInput.addEventListener("blur", function () {
                        let customValue = this.value.trim();

                        if (customValue !== "") {
                            usedOptions.push(customValue);

                            e.target.innerHTML += `<option value="${customValue}" selected>${customValue}</option>`;
                            e.target.value = customValue;

                            this.classList.add("hidden");

                            updateAddButtonState();
                            lockPreviousDropdowns();
                        }
                    });

                } else {
                    if (selected !== "" && !usedOptions.includes(selected)) {
                        usedOptions.push(selected);
                    }
                }

                updateAddButtonState();
                lockPreviousDropdowns();
            });

            updateAddButtonState();
        }

        // first row on load
        renderRow();

        // add new row action
        addBtn.addEventListener("click", () => {
            renderRow();
        });

        addBtn.disabled = true;
        addBtn.classList.add("bg-gray-400");
    </script>


</body>
@include('components.footer')