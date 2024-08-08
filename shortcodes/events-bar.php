<?php

// attributes: posts (int)

function events_bar_service_get_with_future_dates($posts, $numOfPosts = 4)
{
    $prefix = '_dci_evento_';
    $events = [];
    foreach ($posts as $post) {
        $multipleDates = dci_get_meta('date_multiple', $prefix, $post->ID);
        if (!empty($multipleDates)) {
            foreach ($multipleDates as $singleDate) {
                $date =  $singleDate['_dci_evento_date_multiple_time_date']; // 01-12-2024
                $date = implode('-', array_reverse(explode('-', $date))); // 2024-12-01
                $time = $singleDate['_dci_evento_date_multiple_time_inizio']; // 16:00
                $timestamp = strtotime($date.' '.$time.':00'); // 2024-12-01 16:00:00 => timestamp
                if ($timestamp > time()) {
                    $events[] = [
                        'id' => $post->ID,
                        'title' => html_entity_decode($post->post_title, ENT_QUOTES | ENT_HTML5, 'UTF-8'),
                        'timestamp' => (int)$timestamp,
                        'url' => get_permalink($post->ID),
                    ];
                }
            }
        }
    }
    usort($events, function ($postA, $postB) {
        return ($postA['timestamp'] < $postB['timestamp']) ? -1 : 1;
    });
    $events = array_slice($events, 0, $numOfPosts);
    return $events;
}

function limbiate_events_bar_shortcode($atts)
{
    $prefix = '_dci_evento_';
    $intlFormatter = new IntlDateFormatter(
        'it_IT',
        IntlDateFormatter::LONG,
        IntlDateFormatter::NONE,
        'Europe/Rome',
        IntlDateFormatter::GREGORIAN,
        'E d MMMM'
    );

    $atts = shortcode_atts(['posts' => 4, 'title' => 'In Arrivo in Teatro'], $atts, 'latest_posts');
    $args = array(
        'post_type'      => 'evento',
        'posts_per_page' => -1,
    );
    $eventi_query = new WP_Query($args);
    $allPosts = events_bar_service_get_with_future_dates($eventi_query->get_posts(), $atts['posts']);
    $output = '';

    if (count($allPosts) > 0) {
        $output .= '<div class="events-bar-main"><div class="container events-bar-container">';
        $output .= '<ul class="events-bar-list" style="--event-count: ' . max(count($allPosts) + 1, 1) . ';">';
        $output .= '<li class="event-item event-item-title">
            <p class="event-title-bigger">' . $atts['title'] . '</p>
            <div class="link-to-calendar"><a href="' . home_url('/eventi') . '">Calendario mensile &rarr;</a></div>
        </li>';
        foreach ($allPosts as $singlePost) {
            if ($singlePost['timestamp']) {
                $dateTime = new DateTime();
                $dateTime->setTimestamp($singlePost['timestamp']);
                $formattedDate = $intlFormatter->format($dateTime);
                $formattedHour = $dateTime->format('H:i');
                $output .= '<li class="event-item hoverable" onclick="window.location.href=\'' . $singlePost['url'] . '\'">';
                $output .=    '<p class="event-item-date">' . ucwords($formattedDate) . '</p>';
                $output .=    '<p class="event-item-title">' . $singlePost['title'] . '</p>';
                $output .=    '<p class="event-item-hour">ore ' . $formattedHour . '</p>';
                $output .= '</li>';
            }
        }
        $output .= '</ul></div></div>';
    }

    // $atts = shortcode_atts(['posts' => 4, 'title' => 'In Arrivo in Teatro'], $atts, 'latest_posts');

    // $output = '';

    // // Get current timestamp in the site's timezone
    // $current_time = current_time('timestamp');

    // $the_query = new WP_Query([
    //     'post_type' => 'evento',
    //     'post_status' => 'publish',
    //     'posts_per_page' => intval($atts['posts']),
    //     'ignore_sticky_posts' => true,
    //     'meta_key' => '_dci_evento_data_orario_inizio', // Specify the meta key to use for ordering
    //     'orderby' => 'meta_value_num', // Order by the numeric value of the meta key
    //     'order' => 'ASC', // Ascending order
    //     'meta_query' => [ // Filter posts to only those with a future date
    //         [
    //             'key' => '_dci_evento_data_orario_inizio',
    //             'value' => $current_time,
    //             'compare' => '>',
    //             'type' => 'NUMERIC',
    //         ],
    //     ],
    // ]);

    // if ($the_query->have_posts()) {
    //     $output .= '<div class="events-bar-main"><div class="container events-bar-container">';
    //     $output .= '<ul class="events-bar-list" style="--event-count: ' . max($the_query->post_count +1, 1) . ';">';
    //     $output .= '<li class="event-item event-item-title">
    //         <p class="event-title-bigger">' . $atts['title'] . '</p>
    //         <div class="link-to-calendar"><a href="' . home_url('/eventi') . '">Calendario mensile &rarr;</a></div>
    //     </li>';
    //     while ($the_query->have_posts()) {
    //         $the_query->the_post();
    //         $data_orario_inizio = get_post_meta(get_the_ID(), '_dci_evento_data_orario_inizio', true);
    //         if ($data_orario_inizio) {
    //             $dateTime = new DateTime();
    //             $dateTime->setTimestamp((int)$data_orario_inizio);
    //             $formattedDate = $intlFormatter->format($dateTime);
    //             $formattedHour = $dateTime->format('H:i');
    //             $output .= '<li class="event-item hoverable" onclick="window.location.href=\'' . get_permalink() .'\'">';
    //             $output .=    '<p class="event-item-date">' . ucwords($formattedDate) . '</p>';
    //             $output .=    '<p class="event-item-title">' . get_the_title() . '</p>';
    //             $output .=    '<p class="event-item-hour">ore ' . $formattedHour . '</p>';
    //             $output .= '</li>';
    //         }
    //     }
    //     $output .= '</ul></div></div>';
    // }

    // wp_reset_postdata();

    return $output;
}

add_shortcode('events_bar', 'limbiate_events_bar_shortcode');
