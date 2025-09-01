<!DOCTYPE html>
<html>
<head>
    <title>Dependent Dropdown Example</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-gray-100 p-6">

<div class="max-w-xl mx-auto bg-white shadow-lg rounded-lg p-6">
    <h2 class="text-2xl font-bold mb-4">Dependent Dropdowns</h2>

    <!-- DLC Dropdown -->
    <label class="block text-sm font-medium text-gray-700">Select DLC</label>
    <select id="dlc_id2" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2">
        <option value="">-- Choose DLC --</option>
        @foreach($dlcs as $dlc)
            <option value="{{ $dlc->dlc_id }}">{{ $dlc->dlc_cnm }}-{{$dlc->dlc_dst}}</option>
        @endforeach
    </select>

    <!-- Block Dropdown -->
    <label class="block text-sm font-medium text-gray-700 mt-4">Select Block</label>
    <select id="block_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2" disabled>
        <option value="">-- Choose Block --</option>
    </select>

    <!-- School Dropdown -->
    <label class="block text-sm font-medium text-gray-700 mt-4">Select School</label>
    <select id="institute_id" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2" disabled>
        <option value="">-- Choose School --</option>
    </select>
</div>

<script>
$(document).ready(function () {
    // On DLC change
    $('#dlc_id2').change(function () {
        var dlc_id = $(this).val();
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

</body>
</html>
