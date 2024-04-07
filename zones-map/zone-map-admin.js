jQuery(document).ready(function($) {
    // Add image button
    $('.map-upload-image-off-button').click(function(e) {
        e.preventDefault();
        var button = $(this);
        var customUploader = wp.media({
            title: 'Seleziona immagine',
            library: { type: 'image' },
            button: { text: 'Usa questa immagine' },
            multiple: false
        }).on('select', function() {
            var attachment = customUploader.state().get('selection').first().toJSON();
            button.closest('td').find('.map-image-off-id').val(attachment.id);
            button.siblings('img').attr('src', attachment.url);
        }).open();
    });

    $('.map-upload-image-on-button').click(function(e) {
        e.preventDefault();
        var button = $(this);
        var customUploader = wp.media({
            title: 'Seleziona immagine',
            library: { type: 'image' },
            button: { text: 'Usa questa immagine' },
            multiple: false
        }).on('select', function() {
            var attachment = customUploader.state().get('selection').first().toJSON();
            button.closest('td').find('.map-image-on-id').val(attachment.id);
            button.siblings('img').attr('src', attachment.url);
        }).open();
    });

    // Clear row button
    $('.map-clear-row-button').click(function(e) {
        e.preventDefault();
        var row = $(this).closest('tr');
        row.find('input[type="text"]').val('');
        row.find('select').val('');
        row.find('.map-image-off-id').val('');
        row.find('.map-image-on-id').val('');
        row.find('.map-preview-image-off').attr('src', '');
        row.find('.map-preview-image-on').attr('src', '');
    });
});
