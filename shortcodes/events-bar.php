<?php

// attributes: posts (int)
function limbiate_events_bar_shortcode($atts)
{
    $intlFormatter = new IntlDateFormatter(
        'it_IT',
        IntlDateFormatter::LONG,
        IntlDateFormatter::NONE,
        'Europe/Rome',
        IntlDateFormatter::GREGORIAN,
        'E d MMMM'
    );

    $atts = shortcode_atts(['posts' => 4], $atts, 'latest_posts');

    $output = '';

    // Get current timestamp in the site's timezone
    $current_time = current_time('timestamp');

    $the_query = new WP_Query([
        'post_type' => 'evento',
        'post_status' => 'publish',
        'posts_per_page' => intval($atts['posts']),
        'ignore_sticky_posts' => true,
        'meta_key' => '_dci_evento_data_orario_inizio', // Specify the meta key to use for ordering
        'orderby' => 'meta_value_num', // Order by the numeric value of the meta key
        'order' => 'ASC', // Ascending order
        'meta_query' => [ // Filter posts to only those with a future date
            [
                'key' => '_dci_evento_data_orario_inizio',
                'value' => $current_time,
                'compare' => '>',
                'type' => 'NUMERIC',
            ],
        ],
    ]);

    if ($the_query->have_posts()) {
        $output .= '<div class="events-bar-main"><div class="container events-bar-container">';
        $output .= '<ul class="events-bar-list" style="--event-count: ' . max($the_query->post_count +1, 1) . ';">';
        $output .= '<li class="event-item event-item-title">
            <p class="event-title-bigger">In Arrivo in Teatro</p>
            <div class="link-to-calendar"><a href="' . home_url('/eventi') . '">Calendario mensile &rarr;</a></div>
        </li>';
        while ($the_query->have_posts()) {
            $the_query->the_post();
            $data_orario_inizio = get_post_meta(get_the_ID(), '_dci_evento_data_orario_inizio', true);
            if ($data_orario_inizio) {
                $dateTime = new DateTime();
                $dateTime->setTimestamp((int)$data_orario_inizio);
                $formattedDate = $intlFormatter->format($dateTime);
                $formattedHour = $dateTime->format('H:i');
                $output .= '<li class="event-item hoverable" onclick="window.location.href=\'' . get_permalink() .'\'">';
                $output .=    '<p class="event-item-date">' . ucwords($formattedDate) . '</p>';
                $output .=    '<p class="event-item-title">' . get_the_title() . '</p>';
                $output .=    '<p class="event-item-hour">ore ' . $formattedHour . '</p>';
                $output .= '</li>';
            }
        }
        $output .= '</ul></div></div>';
    }

    wp_reset_postdata();

    return $output;
}

add_shortcode('events_bar', 'limbiate_events_bar_shortcode');
