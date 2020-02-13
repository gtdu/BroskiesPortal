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
            $ical = new ICal();
            $ical->initUrl($this->config['cal_url'], $username = null, $password = null, $userAgent = null);

            echo "<table class='table'>";
            echo "<tr>";
            // Days of the week are headers
            for ($i = 0; $i < 7; $i++) {
                echo "<th>" . date('l', strtotime('+' . $i . ' days')) . "</th>";
            }
            echo "</tr>";

            for ($i = 0; $i < 7; $i++) {
                echo "<td>";
                // Pull events on that day only
                $events = $ical->eventsFromRange($i . ' day', ($i) . ' day');
                // Check if there is an event
                if (!empty($events)) {
                    // Render each event
                    foreach ($events as $event) {
                        echo $event->summary . "</br>";
                    }
                } else {
                    echo "<i>Nothing</i>";
                }
                echo "</td>";
            }
            echo "</table>";
        } catch (\Exception $e) {
            die($e);
        }
    }
}
?>
