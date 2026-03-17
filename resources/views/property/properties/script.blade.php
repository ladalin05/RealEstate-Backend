
<script>
    $(document).ready(function () {

        // ✅ Load Country first
        $.get('/get-country', function (data) {
            $('#country').html('<option value="">Select Country</option>');

            data.forEach(item => {
                $('#country').append(
                    `<option value="${item.id}">${item.name}</option>`
                );
            });
        });

        // ✅ Country → City
        $('#country').change(function () {
            let country_id = $(this).val();

            $('#city').html('<option value="">Select City</option>').prop('disabled', !country_id);
            $('#district').html('<option value="">Select District</option>').prop('disabled', true);
            $('#commune').html('<option value="">Select Commune</option>').prop('disabled', true);

            if (!country_id) return;

            $.post('/get-city', {
                country_id: country_id,
                _token: $('meta[name="csrf-token"]').attr('content')
            }, function (data) {

                data.forEach(item => {
                    $('#city').append(
                        `<option value="${item.id}">${item.name}</option>`
                    );
                });

            });
        });

        // ✅ City → District
        $('#city').change(function () {
            let city_id = $(this).val();

            $('#district').html('<option value="">Select District</option>').prop('disabled', !city_id);
            $('#commune').html('<option value="">Select Commune</option>').prop('disabled', true);

            if (!city_id) return;

            $.post('/get-district', {
                city_id: city_id,
                _token: $('meta[name="csrf-token"]').attr('content')
            }, function (data) {

                data.forEach(item => {
                    $('#district').append(
                        `<option value="${item.id}">${item.name}</option>`
                    );
                });

            });
        });

        // ✅ District → Commune
        $('#district').change(function () {
            let district_id = $(this).val();

            $('#commune').html('<option value="">Select Commune</option>').prop('disabled', !district_id);

            if (!district_id) return;

            $.post('/get-commune', {
                district_id: district_id,
                _token: $('meta[name="csrf-token"]').attr('content')
            }, function (data) {

                data.forEach(item => {
                    $('#commune').append(
                        `<option value="${item.id}">${item.name}</option>`
                    );
                });

            });
        });

    });
</script>