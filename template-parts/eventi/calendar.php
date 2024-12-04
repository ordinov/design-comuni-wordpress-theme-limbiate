<?php

$args = array(
    'post_type' => 'evento',
    'posts_per_page' => -1,
    'post_status' => 'publish',
    'meta_key' => '_dci_evento_date_multiple',
    'meta_value_num' => array('compare' => 'EXISTS'),
    'orderby' => 'meta_value_num',
    'order' => 'ASC',
);

$prefix = '_dci_evento_';
$events_query = new WP_Query($args);

$events = array();
if ($events_query->have_posts()) {
    while ($events_query->have_posts()) {
        $events_query->the_post();

        $multipleDates = dci_get_meta('date_multiple', $prefix, get_the_ID());
        if (!empty($multipleDates)) {
            foreach ($multipleDates as $singleDate) {
                $date =  $singleDate['_dci_evento_date_multiple_time_date'];
                $date = implode('-', array_reverse(explode('-', $date)));
                $startDate = $date . 'T' . $singleDate['_dci_evento_date_multiple_time_inizio'] . ':00';
                if (isset($singleDate['_dci_evento_date_multiple_time_fine']))
                    $endDate = $date . 'T' . $singleDate['_dci_evento_date_multiple_time_fine'] . ':00';
                else
                    $endDate = null;
                $eventColor = '#707070';
                $arguments = wp_get_post_terms(get_the_ID(), 'argomenti', array('fields' => 'names'));
                $arguments = array_map('strtolower', $arguments);
                if (in_array('serale', $arguments)) $eventColor = '#33573F';
                if (in_array('family', $arguments)) $eventColor = '#ED6D11';
                if (in_array('off', $arguments)) $eventColor = '#5B232B';

                // $eventColor = ($event_start_timestamp < $current_timestamp) ? 'grey' : $originalEventColor;
                $events[] = [
                    'title' => html_entity_decode(get_the_title(), ENT_QUOTES | ENT_HTML5, 'UTF-8') . $startDate,
                    'start' => $startDate,
                    // 'end' => $endDate ?? null,
                    'url' => get_permalink(),
                    'color' => $eventColor
                ];
            }
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
    console.log(<?php echo json_encode($events); ?>);
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('limbiate-calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            locale: 'it',
            // timeZone: 'Europe/Rome',
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