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
            $ical = new ICal();
            $ical->initUrl($this->config['cal_url'], $username = null, $password = null, $userAgent = null);

            $events = $ical->eventsFromInterval('1 week');

            foreach ($events as $event) {
                $dtstart = $ical->iCalDateToDateTime($event->dtstart_array[3]);

                $output = '<b>' . $dtstart->format('m/d/Y') . "</b> " . $event->summary;
                if (!empty($event->description)) {
                    $output .= ": " . $event->description;
                }
                $output .=  "</br>";
                echo $output;
            }
        } catch (\Exception $e) {
            die($e);
        }
    }
}
?>
