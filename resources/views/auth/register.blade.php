@include('components.navbar')
@include('components.sidebar')

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- ✅ Add favicon -->
    <link rel="icon" type="image/png" href="{{ asset('Et.webp') }}">

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

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
                            <h1 class="m-0 text-dark">User Creation</h1>
                        </div><!-- /.col -->
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Settings</a></li>
                                <li class="breadcrumb-item active">User Creation</li>
                            </ol>
                        </div><!-- /.col -->
                    </div><!-- /.row -->
                </div><!-- /.container-fluid -->
            </div>
            <!-- /.content-header -->

            <!-- Main content -->
            <section class="content">
                <div class="container-fluid">
                    <div class="min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-gray-100">
                        <div class="w-full max-w-5xl my-6 px-6 py-4 bg-white shadow-md overflow-hidden sm:rounded-lg">
                            <div style="margin-bottom:5%">
                                <div style="margin-bottom:2%">
                                    <a href="/">
                                        <x-application-logo class="w-40 h-30 fill-current text-gray-500 mx-auto" />
                                    </a>
                                </div>
                                <div>
                                    <h3
                                        style="font-weight:bold;font-size:150%; color:#01628E; width:100%; text-align:center; align-items:center; margin:auto;">
                                        Statewide Training & Awareness Camps on Emerging Technologies</h3>
                                </div>
                            </div>
                            <div>
                                <form method="POST" action="{{ route('register') }}">
                                    @csrf

                                    <!-- Step 1: Role Selection -->
                                    <div class="mb-4">
                                        <x-input-label for="role_id" :value="__('Select Role')" />
                                        <select name="role_id" id="role_id" class="border rounded w-full p-2">
                                            <option value="">-- Select Role --</option>
                                            @foreach($roles as $role)
                                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!--  All other fields (hidden initially) -->
                                    <div id="role_fields" class="hidden">

                                        <!-- Name Field (shown only for OCAC, OKCL, Institute, dlc) -->
                                        <div class="mb-4 hidden" id="name_field">
                                            <x-input-label id="name_label" for="name" :value="__('name')" />
                                            <input type="text" name="name" id="name" class="border rounded w-full p-2"
                                                :value="{{ old('name') }}" required autofocus autocomplete="name">
                                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                        </div>

                                        <!-- Hidden name for Trainer/Coordinator -->
                                        <input type="hidden" name="hidden_name" id="hidden_name">
                                        <x-input-error :messages="$errors->get('hidden_name')" class="mt-2" />

                                        <!-- Coordinator Fields -->
                                        <div class="coordinator-fields hidden mb-4 mt-4">

                                            <x-input-label for="coordinator" :value="__(key: 'Select Coordinator')" />
                                            <select name="coordinator_id" class="border rounded w-full p-2 mb-2">
                                                <option value="">-- Select Coordinator --</option>
                                                @foreach($coordinators as $c)
                                                    <option value="{{ $c->coordinator_id }}"
                                                        data-name="{{ $c->coordinator_name }}">{{ $c->coordinator_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Trainer Fields -->
                                        <div class="trainer-fields hidden my-4">

                                            <x-input-label for="trainer" :value="__('Select Trainer')" />
                                            <select name="trainer_id" class="border rounded w-full p-2 mb-2">
                                                <option value="">-- Select Trainer --</option>
                                                @foreach($trainers as $t)
                                                    <option value="{{ $t->trainer_id }}">{{ $t->trainer_name }}</option>
                                                @endforeach
                                            </select>
                                            <x-input-label for="assign" :value="__('Assign Under (Coordinator)')" />
                                            <select name="trainer_assignUnder_id" class="border rounded w-full p-2">
                                                <option value="">-- Assign Coordinator --</option>
                                                @foreach($coordinators as $c)
                                                    <option value="{{ $c->coordinator_id }}">{{ $c->coordinator_name }}
                                                    </option>
                                                @endforeach
                                            </select>

                                        </div>

                                        <!-- Institute Fields -->
                                        <div class="institute-fields hidden mb-4">
                                            <x-input-label for="assign" :value="__('Assign Under (Coordinator)')" />
                                            <select name="institute_assignUnder_id" class="border rounded w-full p-2">
                                                <option value="">-- Assign Coordinator --</option>
                                                @foreach($coordinators as $c)
                                                    <option value="{{ $c->coordinator_id }}">{{ $c->coordinator_name }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>

                                        <!-- Zone / District / Institute (only for Coordinator, Trainer, Institute) -->
                                        <div class="role-location hidden mb-4">
                                            <div class="mb-4">

                                                <x-input-label for="Zone" :value="__('Zone')" />
                                                <select name="zone_id" class="border rounded w-full p-2">
                                                    <option value="">-- Select Zone --</option>
                                                    @foreach($zones as $z)
                                                        <option value="{{ $z->DSM_ZONEID }}">{{ $z->DSM_ZONEID }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="mb-4">
                                                <x-input-label for="District" :value="__('District')" />
                                                <select name="district_id" class="border rounded w-full p-2">
                                                    <option value="">-- Select District --</option>
                                                </select>
                                            </div>

                                            <div class="mb-4">

                                                <x-input-label :value="__('School / Institute')" />
                                                <select name="institute_id" class="border rounded w-full p-2">
                                                    <option value="">-- Select School/Institute --</option>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Email -->
                                        <div class="mb-4">
                                            <x-input-label for="email" :value="__('Email')" />
                                            <input type="email" name="email" id="email"
                                                class="border rounded w-full p-2" value="{{ old('email') }}" required>
                                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                        </div>

                                        <!-- Password -->
                                        <div class="mt-4">
                                            <x-input-label for="password" :value="__('Password')" />

                                            <x-text-input id="password" class="block mt-1 w-full" type="password"
                                                name="password" required autocomplete="new-password" />

                                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                        </div>

                                        <!-- Confirm Password -->
                                        <div class="my-4">
                                            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

                                            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                                                type="password" name="password_confirmation" required
                                                autocomplete="new-password" />

                                            <x-input-error :messages="$errors->get('password_confirmation')"
                                                class="mt-2" />
                                        </div>
                                    </div>
                                    <x-primary-button type="submit" class="ms-4">
                                        {{ __('Register') }}
                                    </x-primary-button>
                                </form>
                            </div>
                        </div>
                    </div>
                    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                    <script>
                        $(document).ready(function () {

                            $('#role_id').on('change', function () {
                                let role = $('#role_id option:selected').text().toLowerCase();
                                $('#role_fields').removeClass('hidden');

                                // Hide all role-specific fields first
                                $('.coordinator-fields, .trainer-fields, .institute-fields, .role-location').addClass('hidden');
                                $('#name_field').addClass('hidden');
                                $('#hidden_name').val('');

                                // Reset
                                $('#name_field').addClass('hidden');
                                $('#name').prop('disabled', true); // disable text field
                                $('#hidden_name').val('');

                                if (role === 'coordinator') {
                                    $('.coordinator-fields, .role-location').removeClass('hidden');
                                }
                                else if (role === 'trainer') {
                                    $('.trainer-fields, .role-location').removeClass('hidden');
                                }
                                else if (role === 'institute') {
                                    $('.institute-fields, .role-location').removeClass('hidden');
                                    $('#name_field').removeClass('hidden');
                                    $('#name_label').text('HM Name');
                                    $('#name').prop('disabled', false); // enable text input
                                }
                                else if (role === 'ocac' || role === 'okcl' || role === 'dlc' || role === 'accounts') {
                                    $('#name_field').removeClass('hidden');
                                    $('#name_label').text('Name');
                                    $('#name').prop('disabled', false); // enable text input
                                }
                            });

                            // Fill hidden name input for Coordinator
                            $('#coordinator_id').on('change', function () {
                                let name = $('#coordinator_id option:selected').text();
                                $('#hidden_name').val(name);
                            });

                            // Fill hidden name input for Trainer
                            $('#trainer_id').on('change', function () {
                                let name = $('#trainer_id option:selected').text();
                                $('#hidden_name').val(name);
                            });


                            // AJAX: Load districts when zone changes
                            $('select[name="zone_id"]').on('change', function () {
                                let zone = $(this).val();
                                let $district = $('select[name="district_id"]');
                                $district.html('<option>Loading...</option>');
                                if (zone) {
                                    $.get('/get-districts/' + zone, function (data) {
                                        $district.empty().append('<option value="">-- Select District --</option>');
                                        $.each(data, function (_, d) {
                                            $district.append('<option value="' + d.DSM_DSCD + '">' + d.DSM_DSNM + '</option>');
                                        });
                                    });
                                }
                            });

                            // AJAX: Load schools when district changes
                            $('select[name="district_id"]').on('change', function () {
                                let district = $(this).val();
                                let $school = $('select[name="institute_id"]');
                                $school.html('<option>Loading...</option>');
                                if (district) {
                                    $.get('/get-schools/' + district, function (data) {
                                        $school.empty().append('<option value="">-- Select School/Institute --</option>');
                                        $.each(data, function (_, s) {
                                            $school.append('<option value="' + s.scm_id + '">' + s.scm_udise_code + ' | ' + s.scm_name + '</option>');
                                        });
                                    });
                                }
                            });

                        });
                    </script>
                    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
                    @if (session('success'))
                        <script>
                            Swal.fire({
                                title: '✅ Success!',
                                text: "{{ session('success') }}",
                                icon: 'success',
                                confirmButtonText: 'OK'
                            })
                        </script>
                    @endif


                    <!-- Validation Errors -->
                    @if ($errors->any())
                        <script>
                            let errorMessage = "";
                            @foreach ($errors->all() as $error)
                                errorMessage += "{{ $error }}\n";
                            @endforeach

                            Swal.fire({
                                title: '⚠️ Warning!',
                                text: errorMessage,
                                icon: 'warning',
                                confirmButtonText: 'OK'
                            });
                        </script>
                    @endif

                </div>
        </div>
    </div>
</body>
@include('components.footer')