@include('components.navbar')
@include('components.sidebar')
<style>
    @page {
        size: A4;
        margin: 15mm;
    }

    @media print {

        /* Hide everything */
        body * {
            visibility: hidden;
        }

        /* Show only slip */
        .print-slip,
        .print-slip * {
            visibility: visible;
        }

        /* Position slip correctly */
        .print-slip {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            box-shadow: none !important;
        }

        /* Remove unwanted UI */
        .no-print,
        .main-header,
        .main-sidebar,
        .content-header,
        .breadcrumb,
        footer,
        .main-footer {
            display: none !important;
        }
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
                            <h1 class="m-0 text-dark">Food Bill Slip</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#"></a>Finance & Bills</li>
                                <li class="breadcrumb-item active">Food Bill Slip</li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="max-w-8xl mx-auto bg-white shadow rounded-lg p-8">
                        <div class="print-slip max-w-4xl mx-auto bg-white shadow rounded-lg p-8">
                        <!-- HEADER -->
                        <div class="text-center mb-6">
                            <h2 class="text-2xl font-bold uppercase">
                                Odisha Knowledge Corporation Ltd (OKCL)
                            </h2>
                            <p class="text-gray-600 mt-1">
                                Camp Food Bill Slip
                            </p>
                        </div>

                        <!-- BILL DETAILS -->
                        <table class="table table-bordered w-full mb-6">
                            <tr>
                                <th class="w-1/3 bg-gray-100">School Name</th>
                                <td>{{ $bill->school->scm_name }}</td>
                            </tr>
                            <tr>
                                <th class="bg-gray-100">UDISE Code</th>
                                <td>{{ $bill->school->scm_udise_code }}</td>
                            </tr>
                            <tr>
                                <th class="bg-gray-100">District</th>
                                <td>{{ $bill->school->scm_dist }}</td>
                            </tr>
                            <tr>
                                <th class="bg-gray-100">Training Date</th>
                                <td>
                                    {{ \Carbon\Carbon::parse($bill->training_date)->format('d-m-Y') }}
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-gray-100">Bill Type</th>
                                <td>{{ $bill->bill_type }}</td>
                            </tr>
                            <tr>
                                <th class="bg-gray-100">Amount</th>
                                <td class="font-bold text-green-700">
                                    ₹ {{ number_format($bill->amount, 2) }}
                                </td>
                            </tr>
                            <tr>
                                <th class="bg-gray-100">Submitted By</th>
                                <td>{{ optional($bill->uploadedBy)->name }}</td>
                            </tr>
                            <tr>
                                <th class="bg-gray-100">Submitted On</th>
                                <td>{{ $bill->created_at->format('d-m-Y') }}</td>
                            </tr>
                            <tr>
                                <th class="bg-gray-100">Status</th>
                                <td>
                                    @if($bill->status === 'Pending')
                                        <span class="badge bg-warning">Pending</span>
                                    @elseif($bill->status === 'Approved')
                                        <span class="badge bg-success">Approved</span>
                                    @else
                                        <span class="badge bg-danger">Rejected</span>
                                    @endif
                                </td>
                            </tr>
                        </table>

                        <!-- SIGNATURE SECTION -->
                        <div class="grid grid-cols-2 gap-10 mt-10 text-center">
                            <div>
                                <div class="border-t pt-2">School Authority</div>
                            </div>
                            <div>
                                <div class="border-t pt-2">OKCL / Accounts Officer</div>
                            </div>
                        </div>

                        <!-- ACTION BUTTONS -->
                        <div class="flex justify-center gap-4 mt-8 no-print"">
                            <button onclick="window.print()"
                                class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded">
                                <i class="fas fa-print"></i> Print Slip
                            </button>

                            <a href="{{ route('foodbills.list') }}"
                                class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-2 rounded">
                                Back to List
                            </a>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </div>
</body>
@include('components.footer')