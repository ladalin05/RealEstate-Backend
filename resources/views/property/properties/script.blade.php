<script>
$(document).ready(function () {

    const csrf = $('meta[name="csrf-token"]').attr('content');

    // ─────────────────────────────────────────────
    // 1. LOAD COUNTRIES ON PAGE LOAD (BEST PRACTICE)
    // ─────────────────────────────────────────────
    function loadCountries(selected = null) {
        $.get('/get-country', function (data) {

            let options = '<option value="">Select Country</option>';

            data.forEach(item => {
                options += `<option value="${item.id}">${item.name}</option>`;
            });

            $('#country').html(options);

            if (selected) {
                $('#country').val(selected);
            }

            $('#country').trigger('change.select2');
        });
    }

    // EDIT MODE SUPPORT (Laravel Blade value)
    const selectedCountry = "{{ $property->location->country_id ?? '' }}";
    loadCountries(selectedCountry);


    // ─────────────────────────────────────────────
    // 2. COUNTRY → PROVINCE
    // ─────────────────────────────────────────────
    $('#country').on('change', function () {

        let country_id = $(this).val();

        $('#province')
            .html('<option value="">Select Province</option>')
            .prop('disabled', !country_id)
            .val(null).trigger('change.select2');

        $('#district')
            .html('<option value="">Select District</option>')
            .prop('disabled', true)
            .val(null).trigger('change.select2');

        $('#commune')
            .html('<option value="">Select Commune</option>')
            .prop('disabled', true)
            .val(null).trigger('change.select2');

        if (!country_id) return;

        $.post('/get-province', {
            country_id,
            _token: csrf
        }, function (data) {

            let options = '<option value="">Select Province</option>';

            data.forEach(item => {
                options += `<option value="${item.id}">${item.name}</option>`;
            });

            $('#province')
                .html(options)
                .prop('disabled', false)
                .trigger('change.select2');
        });
    });


    // ─────────────────────────────────────────────
    // 3. PROVINCE → DISTRICT
    // ─────────────────────────────────────────────
    $('#province').on('change', function () {

        let province_id = $(this).val();

        $('#district')
            .html('<option value="">Select District</option>')
            .prop('disabled', !province_id)
            .val(null).trigger('change.select2');

        $('#commune')
            .html('<option value="">Select Commune</option>')
            .prop('disabled', true)
            .val(null).trigger('change.select2');

        if (!province_id) return;

        $.post('/get-district', {
            province_id,
            _token: csrf
        }, function (data) {

            let options = '<option value="">Select District</option>';

            data.forEach(item => {
                options += `<option value="${item.id}">${item.name}</option>`;
            });

            $('#district')
                .html(options)
                .prop('disabled', false)
                .trigger('change.select2');
        });
    });


    // ─────────────────────────────────────────────
    // 4. DISTRICT → COMMUNE
    // ─────────────────────────────────────────────
    $('#district').on('change', function () {

        let district_id = $(this).val();

        $('#commune')
            .html('<option value="">Select Commune</option>')
            .prop('disabled', !district_id)
            .val(null).trigger('change.select2');

        if (!district_id) return;

        $.post('/get-commune', {
            district_id,
            _token: csrf
        }, function (data) {

            let options = '<option value="">Select Commune</option>';

            data.forEach(item => {
                options += `<option value="${item.id}">${item.name}</option>`;
            });

            $('#commune')
                .html(options)
                .prop('disabled', false)
                .trigger('change.select2');
        });
    });

});
</script>