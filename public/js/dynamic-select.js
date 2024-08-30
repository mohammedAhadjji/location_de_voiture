// public/js/dynamic-select.js

$(document).ready(function() {
    $('#brand-select').on('change', function() {
        var brandId = $(this).val();
        
        if (brandId) {
            $.ajax({
                url: '/ajax/brand/' + brandId + '/models',
                type: 'GET',
                success: function(response) {
                    var modelSelect = $('#model-select');
                    modelSelect.empty();
                    modelSelect.append('<option value="">Select Model</option>');

                    $.each(response.models, function(index, model) {
                        modelSelect.append('<option value="' + model.id + '">' + model.name + '</option>');
                    });
                },
                error: function() {
                    alert('Failed to fetch models');
                }
            });
        } else {
            $('#model-select').empty().append('<option value="">Select Model</option>');
        }
    });
});
