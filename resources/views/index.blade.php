<!DOCTYPE html>
<html>
<head>
    <title>School Filter by DLC</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body class="bg-gray-100 p-6">

    <div class="max-w-xl mx-auto bg-white shadow-lg rounded-lg p-6">
        <h2 class="text-2xl font-bold mb-4">Filter Schools by DLC</h2>

        <!-- DLC Dropdown -->
        <label class="block text-sm font-medium text-gray-700">Select DLC</label>
        <select id="dlcDropdown" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm p-2">
            <option value="">-- Choose DLC --</option>
            @foreach($dlcs as $dlc)
                <option value="{{ $dlc->dlc_id }}">{{ $dlc->dlc_cnm }}</option>
            @endforeach
        </select>

        <!-- Schools Table -->
        <div class="mt-6">
            <table class="w-full border border-gray-300 text-sm text-left hidden" id="schoolsTable">
                <thead class="bg-gray-200">
                    <tr>
                        <th class="px-3 py-2 border">District ID</th>
                        <th class="px-3 py-2 border">Dist name</th>
                        <th class="px-3 py-2 border">Block ID</th>
                        <th class="px-3 py-2 border">Block Name</th>
                        <th class="px-3 py-2 border">school name</th>
                    </tr>
                </thead>
                <tbody id="schoolsBody"></tbody>
            </table>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            $('#dlcDropdown').change(function () {
                var dlc_id = $(this).val();

                if (dlc_id) {
                    $.ajax({
                        url: "/schools/get-by-dlc/" + dlc_id,
                        type: "GET",
                        success: function (data) {
                            let table = $('#schoolsTable');
                            let tbody = $('#schoolsBody');
                            tbody.empty();

                            if (data.length > 0) {
                                table.removeClass('hidden');
                                $.each(data, function (i, school) {
                                    tbody.append(`
                                        <tr>
                                            <td class="px-3 py-2 border">${school.scm_distid}</td>
                                            <td class="px-3 py-2 border">${school.scm_dist}</td>
                                            <td class="px-3 py-2 border">${school.scm_blockid}</td>
                                            <td class="px-3 py-2 border">${school.scm_block}</td>
                                            <td class="px-3 py-2 border">${school.scm_name}</td>
                                        </tr>
                                    `);
                                });
                            } else {
                                table.addClass('hidden');
                                alert("No schools found for this DLC.");
                            }
                        }
                    });
                } else {
                    $('#schoolsTable').addClass('hidden');
                }
            });
        });
    </script>
</body>
</html>
