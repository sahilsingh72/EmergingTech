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
                            <h1 class="m-0 text-dark">Training Completion Audit</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                                <li class="breadcrumb-item active">Training Completion Audit</li>
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
                            <div class="p-3 sm:p-8 bg-white shadow sm:rounded-lg">
                                <div class="bg-white rounded-lg w-full">
                                    <!-- Title -->
                                    <h2 class="text-2xl font-semibold text-center mb-6">Training Completion Audit</h2>


                                    @if ($errors->any())
                                        <div class="bg-red-500 text-white p-3 rounded mb-4">
                                            <ul class="list-disc list-inside">
                                                @foreach ($errors->all() as $error)
                                                    <li>{{ $error }}</li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    @endif
                                    <div class="bg-white shadow rounded-lg p-4 overflow-x-auto ">
                                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center gap-3">
                                            <form method="GET" class="grid grid-cols-2 md:grid-cols-2 gap-4 mb-6">
                                                <div>
                                                    <label class="block text-sm font-medium">District</label>
                                                    <select name="district" onchange="this.form.submit()"
                                                        class="w-full border rounded p-2">
                                                        <option value="">-- Select District --</option>
                                                        @foreach($districts as $d)
                                                            <option value="{{ $d->scm_dist }}"
                                                                {{ $selectedDistrict == $d->scm_dist ? 'selected' : '' }}>
                                                                {{ $d->scm_dist }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>

                                                <div>
                                                    <label class="block text-sm font-medium">School</label>
                                                    <select name="school_id" onchange="this.form.submit()"
                                                        class="w-full border rounded p-2">
                                                        <option value="">-- Select School --</option>
                                                        @foreach($schools as $s)
                                                            <option value="{{ $s->scm_id }}"
                                                                {{ $selectedSchoolId == $s->scm_id ? 'selected' : '' }}>
                                                                {{ $s->scm_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </form>
                                            <div class="w-full sm:w-auto grid grid-cols-1 md:grid-cols-1">
                                                <button onclick="window.location='{{ route('audit.list') }}'"
                                                    class="px-4 py-2 bg-blue-600 text-white rounded mb-4">
                                                    View Audit List
                                                </button>
                                            </div>
                                        </div>

                                        {{-- Status Section --}}
                                        @if($school)
                                            <div class="border rounded-lg p-6 mb-6">
                                                <h3 class="text-lg font-semibold mb-4">
                                                    {{ $school->scm_name }} ({{ $school->scm_dist }})
                                                </h3>

                                                <div class="grid grid-cols-2 md:grid-cols-3 gap-4 text-center">
                                                    @php
                                                        function box($ok) {
                                                            return $ok
                                                                ? 'border-green-500 bg-green-100 text-green-700'
                                                                : 'border-red-400 bg-red-100 text-red-600';
                                                        }
                                                    @endphp

                                                    <div class="p-3 border rounded {{ box($stats['attendance_sheet']) }}">
                                                        Attendance Sheet
                                                    </div>

                                                    <div class="p-3 border rounded {{ box($stats['training_video']) }}">
                                                        Training Video
                                                    </div>

                                                    <div class="p-3 border rounded {{ box($stats['video_feedback']) }}">
                                                        Video Feedback
                                                    </div>

                                                    <div class="p-3 border rounded bg-yellow-100 border-yellow-400">
                                                        Student Feedbacks<br>
                                                        <b>{{ $stats['feedback_count'] }}</b>
                                                    </div>

                                                    <div class="p-3 border rounded {{ box($stats['certificate']) }}">
                                                        Completion Certificate
                                                    </div>

                                                    <div class="p-3 border rounded {{ box($stats['training_completed']) }}">
                                                        Training Completed
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Audit Action --}}
                                            @if(auth()->user()->role->name === 'Accounts')
                                                <div class="border-t pt-4">
                                                    <h4 class="text-md font-semibold mb-3">Audit Action</h4>

                                                    <form method="POST" action="{{ route('audit.action') }}">
                                                        @csrf

                                                        <input type="hidden" name="school_id" value="{{ $school->scm_id }}">

                                                        <textarea name="comment" required
                                                            class="w-full border rounded p-2 mb-4"
                                                            placeholder="Enter audit comment (mandatory)"></textarea>

                                                        <div class="flex gap-3">
                                                            <button type="submit" name="action" value="approve"
                                                                class="px-4 py-2 bg-green-600 text-white rounded">
                                                                Approve
                                                            </button>

                                                            <button type="submit" name="action" value="reject"
                                                                class="px-4 py-2 bg-red-600 text-white rounded">
                                                                Reject
                                                            </button>

                                                            <button type="submit" name="action" value="revert"
                                                                class="px-4 py-2 bg-yellow-500 text-white rounded">
                                                                Revert
                                                            </button>
                                                        </div>
                                                    </form>

                                                </div>
                                            @endif
                                        @endif

                                        {{-- Success --}}
                                        @if(session('success'))
                                            <div class="mt-4 p-3 bg-green-100 text-green-700 rounded">
                                                {{ session('success') }}
                                            </div>
                                        @endif

        

                                    </div>
                                    <div id="pagination" class="flex justify-center space-x-2 mt-4"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

</body>
@include('components.footer')