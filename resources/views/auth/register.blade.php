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
                                    <!-- Role -->
                                    <div class="form-group mt-4">
                                        <x-input-label for="role_id" :value="__('Select Role')" />
                                        <select name="role_id" id="role_id" required
                                            class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                            <option value="">-- Choose Role --</option>
                                            @foreach ($roles as $role)
                                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <!-- Role-specific Fields -->
                                    <div id="role_fields" class="d-none">

                                        <!-- OCAC / OKCL -->
                                        <div class="ocac_okcl d-none">
                                            <div class="form-group mt-4">
                                                <x-input-label for="name" :value="__('Name')" />
                                                <x-text-input id="name" class="form-control" type="text" name="name"
                                                    :value="old('name')" required autofocus autocomplete="name" />
                                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                                            </div>
                                        </div>

                                        <!-- Coordinator -->
                                        <div class="coordinator d-none">
                                            <div class="form-group mt-4">
                                                <x-input-label for="coordinator_id" :value="__('Select Coordinator')" />
                                                <select name="coordinator_id" id="coordinator_id" class="form-control">
                                                    <option value="">-- Select Coordinator --</option>
                                                    @foreach($coordinators as $c)
                                                    <option value="{{ $c->coordinator_id }}">{{ $c->coordinator_name }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group mt-4">
                                                <x-input-label for="zone_id" :value="__('Select Zone')" />
                                                <select name="zone_id" id="zone_id" class="form-control">
                                                    <option value="">-- Select Zone --</option>
                                                    @foreach($zones as $z)
                                                    <option value="{{ $z->DSM_ZONEID }}">{{ $z->DSM_ZONEID }}</option>
                                                    @endforeach
                                                </select>
                                            </div>

                                            <div class="form-group mt-4">
                                                <x-input-label for="district_id" :value="__('Select District')" />
                                                <select name="district_id" id="district_id" class="form-control">
                                                    <option value="">-- Select District --</option>
                                                </select>
                                            </div>

                                            <div class="form-group mt-4">
                                                <x-input-label for="school_id" :value="__('Select School')" />
                                                <select name="school_id" id="school_id" class="form-control">
                                                    <option value="">-- Select School --</option>
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Trainer -->
                                        <div class="trainer d-none">
                                            <div class="form-group mt-4">
                                                <x-input-label for="trainer_id" :value="__('Select Trainer')" />
                                                <select name="trainer_id" id="trainer_id" class="form-control">
                                                    <option value="">-- Select Trainer --</option>
                                                    {{-- @foreach($trainers as $t)
                                                    <option value="{{ $t->id }}">{{ $t->name }}</option>
                                                    @endforeach --}}
                                                </select>
                                            </div>

                                            <div class="form-group mt-4">
                                                <x-input-label for="zone_id" :value="__('Select Zone')" />
                                                <select name="zone_id" id="zone_id" class="form-control">
                                                    <option value="">-- Select Zone --</option>
                                                    {{-- @foreach($zones as $z)
                                                    <option value="{{ $z->id }}">{{ $z->zone_name }}</option>
                                                    @endforeach --}}
                                                </select>
                                            </div>

                                            <div class="form-group mt-4">
                                                <x-input-label for="district_id" :value="__('Select District')" />
                                                <select name="district_id" id="district_id" class="form-control">
                                                    <option value="">-- Select District --</option>
                                                    {{-- @foreach($districts as $d)
                                                    <option value="{{ $d->DSM_DSCD }}">{{ $d->DSM_DSNM }}</option>
                                                    @endforeach --}}
                                                </select>
                                            </div>

                                            <div class="form-group mt-4">
                                                <x-input-label for="school_id" :value="__('Select School')" />
                                                <select name="school_id" id="school_id" class="form-control">
                                                    <option value="">-- Select School --</option>
                                                    {{-- @foreach($schools as $s)
                                                    <option value="{{ $s->scm_id }}">{{ $s->scm_name }}</option>
                                                    @endforeach --}}
                                                </select>
                                            </div>

                                            <div class="form-group mt-4">
                                                <x-input-label for="assign_coordinator" :value="__('Assign Under (Coordinator)')" />
                                                <select name="assign_coordinator" id="assign_coordinator"
                                                    class="form-control">
                                                    <option value="">-- Select Coordinator --</option>
                                                    @foreach($coordinators as $c)
                                                    <option value="{{ $c->coordinator_id }}">{{ $c->coordinator_name }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <!-- Institute -->
                                        <div class="institute d-none">
                                            <div class="form-group mt-4">
                                                <x-input-label for="zone_id" :value="__('Select Zone')" />
                                                <select name="zone_id" id="zone_id" class="form-control">
                                                    <option value="">-- Select Zone --</option>
                                                    {{-- @foreach($zones as $z)
                                                    <option value="{{ $z->id }}">{{ $z->zone_name }}</option>
                                                    @endforeach --}}
                                                </select>
                                            </div>

                                            <div class="form-group mt-4">
                                                <x-input-label for="district_id" :value="__('Select District')" />
                                                <select name="district_id" id="district_id" class="form-control">
                                                    <option value="">-- Select District --</option>
                                                    {{-- @foreach($districts as $d)
                                                    <option value="{{ $d->DSM_DSCD }}">{{ $d->DSM_DSNM }}</option>
                                                    @endforeach --}}
                                                </select>
                                            </div>

                                            <div class="form-group mt-4">
                                                <x-input-label for="school_id" :value="__('Select School')" />
                                                <select name="school_id" id="school_id" class="form-control">
                                                    <option value="">-- Select School --</option>
                                                    {{-- @foreach($schools as $s)
                                                    <option value="{{ $s->scm_id }}">{{ $s->scm_name }}</option>
                                                    @endforeach --}}
                                                </select>
                                            </div>

                                            <div class="form-group mt-4">
                                                <x-input-label for="assign_coordinator" :value="__('Assign Under (Coordinator)')" />
                                                <select name="assign_coordinator" id="assign_coordinator"
                                                    class="form-control">
                                                    <option value="">-- Select Coordinator --</option>
                                                    @foreach($coordinators as $c)
                                                    <option value="{{ $c->coordinator_id }}">{{ $c->coordinator_name }}
                                                    </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <!-- Email Address -->
                                        <div class="form-group mt-4">
                                            <x-input-label for="email" :value="__('Email')" />
                                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                                                :value="old('email')" required autocomplete="username" />
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
                                        <div class="mt-4">
                                            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                                            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                                                type="password" name="password_confirmation" required
                                                autocomplete="new-password" />
                                            <x-input-error :messages="$errors->get('password_confirmation')"
                                                class="mt-2" />
                                        </div>
                                    </div>

                                    <div class="flex items-center justify-end mt-4">


                                        <x-primary-button class="ms-4">
                                            {{ __('Register') }}
                                        </x-primary-button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
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

                    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                    <script>
                        $(document).ready(function () {
                            $('#role_id').on('change', function () {
                                let roleId = $(this).val();
                                let roleName = $("#role_id option:selected").text().toLowerCase();

                                // Hide everything
                                $('#role_fields').addClass('d-none');
                                $('.ocac_okcl, .coordinator, .trainer, .institute').addClass('d-none');
                                $('#submit_btn_wrapper').addClass('d-none');

                                if (roleId) {
                                    $('#role_fields').removeClass('d-none');
                                    $('#submit_btn_wrapper').removeClass('d-none');

                                    if (roleName === 'ocac' || roleName === 'okcl') {
                                        $('.ocac_okcl').removeClass('d-none');
                                    } else if (roleName === 'coordinator') {
                                        $('.coordinator').removeClass('d-none');
                                    } else if (roleName === 'trainer') {
                                        $('.trainer').removeClass('d-none');
                                    } else if (roleName === 'institute') {
                                        $('.institute').removeClass('d-none');
                                    }
                                }
                            });
                        });
                    </script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $('#zone_id').on('change', function () {
        var zone_id = this.value;
        $('#district_id').html('<option value="">Loading...</option>');
        $.get('/get-districts/' + zone_id, function (data) {
            $('#district_id').empty().append('<option value="">-- Select District --</option>');
            $.each(data, function (key, district) {
                $('#district_id').append('<option value="' + district.DSM_DSCD + '">' + district.DSM_DSNM + '</option>');
            });
        });
    });

    $('#district_id').on('change', function () {
        var district_id = this.value;
        $('#school_id').html('<option value="">Loading...</option>');
        $.get('/get-schools/' + district_id, function (data) {
            $('#school_id').empty().append('<option value="">-- Select School --</option>');
            $.each(data, function (key, school) {
                $('#school_id').append('<option value="' + school.scm_id + '">' + school.scm_name + '</option>');
            });
        });
    });
</script>

                </div>
        </div>
    </div>
</body>
@include('components.footer')





{{-- @include('components.navbar')
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
                    <x-guest-layout style="width:100%; max-width:none; margin:auto;">
                        <form method="POST" action="{{ route('register') }}">
                            @csrf
                            <!-- Name -->
                            <div>
                                <x-input-label for="name" :value="__('Name')" />
                                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name"
                                    :value="old('name')" required autofocus autocomplete="name" />
                                <x-input-error :messages="$errors->get('name')" class="mt-2" />
                            </div>

                            <!-- Role -->
                            <div class="mt-4">
                                <x-input-label for="role_id" :value="__('Select Role')" />
                                <select name="role_id" id="role_id" required
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="">-- Choose Role --</option>
                                    @foreach ($roles as $role)
                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Zone Section -->
                            <div class="mt-4" id="district_section" style="display:none;">


                                <label for="zone_id" class="block text-sm font-medium text-gray-700">Select Zone</label>
                                <select name="zone_id" id="zone_id"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="">-- Choose Zone --</option>
                                    @foreach ($zones as $zone)
                                    <option value="{{ $zone->DSM_ZONEID }}">Zone {{ $zone->DSM_ZONEID }}</option>
                                    @endforeach
                                </select>
                                <!-- District Dropdown -->
                                <label for="district_id" class="block text-sm font-medium text-gray-700 mt-4">Select
                                    District</label>
                                <select name="district_id" id="district_id"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" disabled>
                                    <option value="">-- Choose District --</option>
                                </select>

                            </div>

                            <!-- Institute / Trainer Section -->
                            <div class="mt-4" id="institute_section" style="display:none;">
                                <label for="dlc_id" class="block text-sm font-medium text-gray-700">Assign Under
                                    (DLC)</label>
                                <select name="dlc_id2" id="dlc_id2"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="">-- Choose DLC --</option>
                                    @foreach ($dlcdsts as $dlc)
                                    <option value="{{ $dlc->dlc_id }}" data-district="{{ $dlc->dlc_dstID }}">
                                        {{ $dlc->dlc_cnm }} - {{ $dlc->dlc_dst }}
                                    </option>
                                    @endforeach
                                </select>

                                <!-- Hidden District ID for Institute/Trainer -->
                                <input type="hidden" name="district_id2" id="district_id_hidden">


                                <!-- Block Dropdown -->
                                <label class="block text-sm font-medium text-gray-700 mt-4">Select Block</label>
                                <select id="block_id" name="block_id"
                                    class="mt-1 block w-full border-gray-300  shadow-sm p-2" disabled>
                                    <option value="">-- Choose Block --</option>
                                </select>

                                <!-- School Dropdown -->
                                <label class="block text-sm font-medium text-gray-700 mt-4">Select School</label>
                                <select id="institute_id" name="institute_id"
                                    class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2" disabled>
                                    <option value="">-- Choose School --</option>
                                </select>
                            </div>

                            <!-- Email Address -->
                            <div class="mt-4">
                                <x-input-label for="email" :value="__('Email')" />
                                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email"
                                    :value="old('email')" required autocomplete="username" />
                                <x-input-error :messages="$errors->get('email')" class="mt-2" />
                            </div>

                            <!-- Password -->
                            <div class="mt-4">
                                <x-input-label for="password" :value="__('Password')" />

                                <x-text-input id="password" class="block mt-1 w-full" type="password" name="password"
                                    required autocomplete="new-password" />

                                <x-input-error :messages="$errors->get('password')" class="mt-2" />
                            </div>

                            <!-- Confirm Password -->
                            <div class="mt-4">
                                <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

                                <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                                    name="password_confirmation" required autocomplete="new-password" />

                                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                            </div>

                            <div class="flex items-center justify-end mt-4">
                                <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                                    href="{{ route('login') }}">
                                    {{ __('Already registered?') }}
                                </a>

                                <x-primary-button class="ms-4">
                                    {{ __('Register') }}
                                </x-primary-button>
                            </div>
                        </form>
                    </x-guest-layout>
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

                    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
                    <script>
                        $(document).ready(function () {
                            $('#role_id').change(function () {
                                var selectedRole = $(this).find("option:selected").text();

                                // Hide all first
                                $('#district_section').hide();
                                $('#institute_section').hide();

                                if (selectedRole === "DLC" || selectedRole === "Coordinator") {
                                    $('#district_section').show();
                                } else if (selectedRole === "Institute" || selectedRole === "Trainer") {
                                    $('#institute_section').show();
                                }
                            });
                        });
                        $(document).ready(function () {
                            $('form').submit(function () {
                                let role = $('#role_id option:selected').text();

                                if (role === "Institute" || role === "Trainer") {
                                    let dlcValue = $('#dlc_id2').val();
                                    $('#dlc_id').val(dlcValue); // copy to main dlc_id
                                }
                            });
                        });
                    </script>
                    <script>
                        $('#zone_id').change(function () {
                            var zone_id = $(this).val();
                            $('#district_id').prop('disabled', true).html('<option value="">-- Choose District --</option>');

                            if (zone_id) {
                                $.ajax({
                                    url: "/filter/districts/" + zone_id,
                                    type: "GET",
                                    success: function (data) {
                                        if (data.length > 0) {
                                            $('#district_id').prop('disabled', false);
                                            $.each(data, function (i, district) {
                                                $('#district_id').append(
                                                    '<option value="' + district.DSM_DSCD + '">' + district.DSM_DSNM + '</option>'
                                                );
                                            });
                                        }
                                    }
                                });
                            }
                        });

                    </script>
                    <script>

                    </script>

                </div>
        </div>
    </div>
</body>
@include('components.footer') --}}