<script>
    $(document).ready(function () {


        // ✅ Province → District
        $('#province').change(function () {
            let province_id = $(this).val();

            $('#district').html('<option value="">Select District</option>').prop('disabled', !province_id);
            $('#commune').html('<option value="">Select Commune</option>').prop('disabled', true);

            if (!province_id) return;

            $.post('/get-district', {
                province_id: province_id,
                _token: $('meta[name="csrf-token"]').attr('content')
            }, function (data) {

                data.forEach(item => {
                    $('#district').append(
                        `<option value="${item.id}">${item.name_en}</option>`
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
                        `<option value="${item.id}">${item.name_en}</option>`
                    );
                });

            });
        });

    });
</script>