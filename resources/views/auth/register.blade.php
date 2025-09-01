<x-guest-layout>
    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name -->
        <div>
            <x-input-label for="name" :value="__('Name')" />
            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Role -->
        <div class="mt-4">
            <x-input-label for="role_id" :value="__('Select Role')" />
            <select name="role_id" id="role_id" required
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                <option value="">-- Choose Role --</option>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                @endforeach
            </select>
        </div>

        <!-- DLC Section -->
        <div class="mt-4" id="district_section" style="display:none;">
            <label for="districtdlc" class="block text-sm font-medium text-gray-700">Select District</label>
            <select name="district_id" id="district"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                <option value="">-- Choose District --</option>
                @foreach($dlcdsts->unique('dlc_dstID') as $district)
                    <option value="{{ $district->dlc_dstID }}">{{ $district->dlc_dst }}</option>
                @endforeach
            </select>
            {{-- DLC Dropdown --}}
            <label for="dlc" class="block text-sm font-medium text-gray-700 mt-2">DLC</label>
            <input type="text" id="dlc_name"
                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm bg-gray-100"
                readonly>

            {{-- Hidden field to actually store dlc_id in DB --}}
            <input type="hidden" name="dlc_id" id="dlc_id">

        </div>

        <!-- Institute / Trainer Section -->
        <div class="mt-4" id="institute_section" style="display:none;">
            <label for="dlc_id" class="block text-sm font-medium text-gray-700">Assign Under (DLC)</label>
            <select name="dlc_id2" id="dlc_id2" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                <option value="">-- Choose DLC --</option>
                @foreach($dlcdsts as $dlc)
                    <option value="{{ $dlc->dlc_id }}" data-district="{{ $dlc->dlc_dstID }}">
                {{ $dlc->dlc_cnm }} - {{ $dlc->dlc_dst }}
            </option>
                @endforeach
            </select>

            <!-- Hidden District ID for Institute/Trainer -->
            <input type="hidden" name="district_id2" id="district_id_hidden">
      

            <!-- Block Dropdown -->
            <label class="block text-sm font-medium text-gray-700 mt-4">Select Block</label>
            <select id="block_id" name="block_id" class="mt-1 block w-full border-gray-300  shadow-sm p-2" disabled>
                <option value="">-- Choose Block --</option>
            </select>

            <!-- School Dropdown -->
            <label class="block text-sm font-medium text-gray-700 mt-4">Select School</label>
            <select id="institute_id" name="institute_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2" disabled>
                <option value="">-- Choose School --</option>
            </select>
        </div>

        <!-- Email Address -->
        <div class="mt-4">
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <x-text-input id="password" class="block mt-1 w-full"
                            type="password"
                            name="password"
                            required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Confirm Password -->
        <div class="mt-4">
            <x-input-label for="password_confirmation" :value="__('Confirm Password')" />

            <x-text-input id="password_confirmation" class="block mt-1 w-full"
                            type="password"
                            name="password_confirmation" required autocomplete="new-password" />

            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('login') }}">
                {{ __('Already registered?') }}
            </a>

            <x-primary-button class="ms-4">
                {{ __('Register') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(document).ready(function () {
    $('#role_id').change(function () {
        var selectedRole = $(this).find("option:selected").text();

        // Hide all first
        $('#district_section').hide();
        $('#institute_section').hide();

        if (selectedRole === "DLC") {
            $('#district_section').show();
        } 
        else if (selectedRole === "Institute" || selectedRole === "Trainer") {
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
document.getElementById('district').addEventListener('change', function () {
    let districtId = $(this).val();

        $('#dlc_name').val('');
        $('#dlc_id').val('');

        if (districtId) {
            fetch('/get-dlcs/' + districtId)
                .then(response => response.json())
                .then(data => {
                    if (data.length > 0) {
                        // Take first DLC (or loop for multiple)
                        $('#dlc_name').val(data[0].dlc_cnm);
                        $('#dlc_id').val(data[0].dlc_id);
                    }
                });
    }
});
</script>
<script>
$(document).ready(function () {
    // On DLC change
    $('#dlc_id2').change(function () {
        var dlc_id = $(this).val();
        let district_id = $(this).find(':selected').data('district');
        
        // set hidden district_id
        $('#district_id_hidden').val(district_id);

        $('#block_id').prop('disabled', true).html('<option value="">-- Choose Block --</option>');
        $('#institute_id').prop('disabled', true).html('<option value="">-- Choose School --</option>');

        if (dlc_id) {
            $.ajax({
                url: "/filter/blocks/" + dlc_id,
                type: "GET",
                success: function (data) {
                    if (data.length > 0) {
                        $('#block_id').prop('disabled', false);
                        $.each(data, function (i, block) {
                            $('#block_id').append('<option value="'+block.scm_blockid+'">'+block.scm_block+'</option>');
                        });
                    }
                }
            });
        }
    });

    // On Block change
    $('#block_id').change(function () {
        var block_id = $(this).val();
        $('#institute_id').prop('disabled', true).html('<option value="">-- Choose School --</option>');

        if (block_id) {
            $.ajax({
                url: "/filter/schools/" + block_id,
                type: "GET",
                success: function (data) {
                    if (data.length > 0) {
                        $('#institute_id').prop('disabled', false);
                        $.each(data, function (i, school) {
                            $('#institute_id').append('<option value="'+school.scm_id+'">'+school.scm_name+'</option>');
                        });
                    }
                }
            });
        }
    });
});
</script>
