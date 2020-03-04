<?php

use ICal\ICal;

/**
* Class to manage all the calendar related actions
*
* @author Ryan Cobelli <ryan.cobelli@gmail.com>
*/
class CalendarHelper extends Helper
{

    public function renderCalendar() {
        try {
            // Pull events from ical feed
            $ical = new ICal($this->config['cal_url'], array(
                'filterDaysAfter' => 8,
                'filterDaysBefore' => 2,
            ));

            echo '<div class="table-responsive">';
            echo "<table class='table'>";
            echo "<tr>";
            // Days of the week are headers
            for ($i = 0; $i < 7; $i++) {
                echo "<th style='width: 14%'>" . date('l', strtotime('+' . $i . ' days')) . "</th>";
            }
            echo "</tr>";

            for ($i = 0; $i < 7; $i++) {
                echo "<td>";
                // Pull events on that day only
                $events = $ical->eventsFromRange($i . ' day', ($i) . ' day');
                // Check if there is an event
                if (!empty($events)) {
                    // Render each event
                    echo "<ul>";
                    foreach ($events as $event) {
                        echo "<li>" . $event->summary . "</li>";
                    }
                    echo "</ul>";
                } else {
                    echo "❌";
                }
                echo "</td>";
            }
            echo "</table></div>";
        } catch (\Exception $e) {
            die($e);
        }
    }
}
?>
