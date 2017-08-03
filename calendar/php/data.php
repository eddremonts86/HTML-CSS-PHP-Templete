<?php
/**
 * Created by PhpStorm.
 * User: edd
 * Date: 07/04/2016
 * Time: 9:21
 */
    include 'Apiconect.php';
        $obj = new Apiconect();
        $fulldate = $_GET['fulldate'];
        $fulldate = explode(' ', $fulldate);
        $day = $fulldate[2];
        $month = $fulldate[1];
        $year = $fulldate[3];
        $total = $obj->Data($day, $month, $year);
    echo json_encode($total, JSON_FORCE_OBJECT);


