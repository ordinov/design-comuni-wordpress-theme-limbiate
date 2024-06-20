<?php

$args = array(
    'post_type' => 'evento',
    'posts_per_page' => -1,
    'post_status' => 'publish',
    'meta_key' => '_dci_evento_data_orario_inizio',
    'meta_value_num' => array('compare' => 'EXISTS'),
    'orderby' => 'meta_value_num',
    'order' => 'ASC',
);

$events_query = new WP_Query($args);

$events = array();
if ($events_query->have_posts()) {
    while ($events_query->have_posts()) {
        $events_query->the_post();

        $event_start_timestamp = get_post_meta(get_the_ID(), '_dci_evento_data_orario_inizio', true);
        if ($event_start_timestamp) {
            $start_date_formatted = date('Y-m-d\TH:i:s', $event_start_timestamp);
            $eventColor = ($event_start_timestamp < $current_timestamp) ? 'grey' : '#9B1733';
            $events[] = array(
                'title' => html_entity_decode(get_the_title(), ENT_QUOTES | ENT_HTML5, 'UTF-8'),
                'start' => $start_date_formatted,
                'url' => get_permalink(),
                'color' => $eventColor
            );
        }
    }
    wp_reset_postdata();
}
?>

<div id="limbiate-calendar-container" class="container">
    <div class="row">
        <div class="col-md-12">
            <div id="limbiate-calendar"></div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('limbiate-calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            locale: 'it',
            initialView: 'dayGridMonth',
            editable: false,
            events: <?php echo json_encode($events); ?>,
            eventDisplay: 'block',
            displayEventTime: true,
            displayEventEnd: false,
            eventTimeFormat: {
                hour: '2-digit',
                minute: '2-digit',
                hour12: false,
                meridiem: false,
                omitZeroMinute: false,
            },
            eventContent: function(arg) {
                console.log(arg);
                let startTime = arg.event.start ? new Date(arg.event.start) : '';
                let formattedTime = startTime ? `Ore ${startTime.getHours()}:${startTime.getMinutes().toString().padStart(2, '0')}` : '';

                let timeDiv = document.createElement('div');
                timeDiv.textContent = formattedTime;
                timeDiv.className = 'limbiate-cal-date';

                let titleDiv = document.createElement('div');
                titleDiv.textContent = arg.event.title;
                titleDiv.className = 'limbiate-cal-title';

                let arrayOfDomNodes = [timeDiv, titleDiv];
                return {
                    domNodes: arrayOfDomNodes
                };
            }
        });
        calendar.render();
    });
</script>