<?php
global $post;

// Check if the post has a featured image
if (has_post_thumbnail($post->ID)) {
    // Get the ID of the featured image
    $img_id = get_post_thumbnail_id($post->ID);
    
    // Get the full image URL
    $img_url = wp_get_attachment_image_url($img_id, 'full');
    
    // Get the alt text for the featured image
    $image_alt = get_post_meta($img_id, '_wp_attachment_image_alt', true);
    
    // Optional: To display the image you can use wp_get_attachment_image()
    $img = wp_get_attachment_image($img_id, 'full', false, array('alt' => $image_alt));
} else {
    // Handle cases where there is no featured image
    $img_url = ''; // Default image URL or leave blank
    $image_alt = ''; // Default alt text or leave blank
    $img = ''; // Default image or placeholder
}

if ($img_url) {
?>

<div class="container-fluid my-3">
    <div class="row">
        <figure class="figure px-0 img-full">
            <div class="image-container">
                <img
                    src="<?php echo $img_url; ?>"
                    class="figure-img img-fluid"
                    alt="<?php echo $image_alt; ?>"
                />
            </div>
            <?php if ($img->post_excerpt)  {?>
            <figcaption class="figure-caption text-center pt-3">
                <?php echo $img->post_excerpt; ?>
            </figcaption>
            <?php } ?>
        </figure>
    </div>
</div>

<style>
.image-container {
    position: relative;
    width: 100%;
    padding-top: 56.25%; /* 16:9 aspect ratio */
    overflow: hidden;
}

.image-container img {
    position: absolute;
    top: 50%;
    left: 50%;
    width: 100%;
    height: auto;
    transform: translate(-50%, -50%);
}

.image-container img.landscape {
    width: auto;
    height: 100%;
}
</style>

<?php } ?>
