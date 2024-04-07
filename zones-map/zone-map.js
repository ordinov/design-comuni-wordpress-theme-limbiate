jQuery(document).ready(function($) {
    $('.limbiate-zone-svg path').hover(
        function() {
            var zoneIndex = $(this).data('zone');
            const zoneImage = $('img[data-zone="' + zoneIndex + '"][data-img-type="on"]');
            $('img.limbiate-zone-image[data-img-type="on"]').each((k, el) => {
                if ($(el).attr('data-zone') == zoneIndex) {
                    $(el).fadeIn(200);
                }
            })
            $('img.limbiate-zone-image[data-img-type="off"]').each((k, el) => {
                if ($(el).attr('data-zone') !== zoneIndex) {
                    $(el).fadeIn(200);
                }
            })
        },
        function() {
            $('img.limbiate-zone-image[data-img-type="on"]').each((k, el) => {
                $(el).fadeOut(200);
            })
            $('img.limbiate-zone-image[data-img-type="off"]').each((k, el) => {
                $(el).fadeIn(200);
            })
        }
    );
});