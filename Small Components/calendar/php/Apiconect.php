<?php

/**
 * Created by PhpStorm.
 * User: edd
 * Date: 07/04/2016
 * Time: 12:33
 */
class Apiconect
{

    public function Data($day, $month, $year)
    {
        $month = $this->Mons($month);
        if (file_exists('../data/' . $year . '/' . $month . '/' . $day . '-data.json')) {
            $weburlserv = '../data/' . $year . '/' . $month . '/' . $day . '-data.json';
            $data = file_get_contents($weburlserv);
            $result = (array)json_decode($data);
            $result = $this->Order($result);
            return $result;
        } else {
            $weburlserv = 'http://eurytus.livegoals.dk/api/v1.1/schedule/' . $year . '-' . $month . '-' . $day . '?next=30&options=streams&locale=en&sport=a0c77e28-69fb-11e4-85c8-5254005a5aa0&rank=999';
            $client = curl_init($weburlserv);
            curl_setopt($client, CURLOPT_RETURNTRANSFER, true);
            $respon = curl_exec($client);
            $result = (array)json_decode($respon);

            if (!file_exists('../data/' . $year)) {
                mkdir('../data/' . $year, 0755);
            }
            if (!file_exists('../data/' . $year . '/' . $month)) {
                mkdir('../data/' . $year . '/' . $month, 0755);
            }
            $fp = fopen('../data/' . $year . '/' . $month . '/' . $day . '-data.json', 'w');
            fwrite($fp, $respon);
            fclose($fp);
            $result = $this->Order($result);
            return $result;
        }
    }
    public function Order($event)
    {
        $result = array();
        $eventList = $event['events'];
        for ($i = 0; $i < @$event['count']; $i++) {

            $result[$i]['start_date'] = date("H:m", strtotime($eventList[$i]->start_date));
            $result[$i]['home'] = $this->Clearfeet($eventList[$i]->home->name);
            $result[$i]['home_score'] = $this->Clearfeet(@$eventList[$i]->home->score->current);
            $result[$i]['away'] = $this->Clearfeet($eventList[$i]->away->name);
            $result[$i]['away_score'] = $this->Clearfeet(@$eventList[$i]->away->score->current);
            $result[$i]['count'] = @$event['count'];
            @$regiontype = gettype($eventList[$i]->recurring_competition->region->name);
            @$logo = gettype($eventList[$i]->away->logo);
            @$logo_home = gettype($eventList[$i]->home->logo);
            if ($regiontype == 'string') {
                $result[$i]['region_name'] = $eventList[$i]->recurring_competition->region->name;
                $result[$i]['region_id'] = $eventList[$i]->recurring_competition->region->id;
                @$alfa2 = $eventList[$i]->recurring_competition->region->alpha2;
            } else {
                $result[$i]['region_name'] = $eventList[$i]->competition->name;
                $result[$i]['region_id'] = $eventList[$i]->competition->id;
                @$alfa2 = $eventList[$i]->competition->region->alpha2;
            }
            if ($logo != 'string') {
                $result[$i]['logo'] = "storage/app/media/General/people.png";
            } else {
                $result[$i]['logo'] = $eventList[$i]->away->logo;
            }

            if ($logo_home != 'string') {
                $result[$i]['logo_home'] = "storage/app/media/General/people.png";
            } else {
                $result[$i]['logo_home'] = $eventList[$i]->home->logo;
            }
            if ($alfa2 == "") {
                @$alfa2 = "intl";
            }

        }
        return $result;
    }
    public function Mons($month)
    {
        $meses = array(
            "Jan" => 1,
            "Feb" => 2,
            "Mar" => 3,
            "Apr" => 4,
            "May" => 5,
            "Jun" => 6,
            "Jul" => 7,
            "Aug" => 8,
            "Sep" => 9,
            "Oct" => 10,
            "Nov" => 11,
            "Dec" => 12
        );
        return $meses[$month];
    }
    public function Clearfeet($s)
    {
        $s = trim($s, "\t\n\r\0\x0B");
        return $s;
    }


}