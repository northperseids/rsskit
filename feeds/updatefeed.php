<?php

require('util.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pkid = $_POST["systemid"];
    $auth = $_POST["auth"];
    $action = $_REQUEST["action"];

    $pkresponse = queryPK($pkid, $auth);

    if ($pkresponse[0] !== 200) {
        die("Error fetching PluralKit information." . $pkresponse[0]);
    } else {
        $sysdata = json_decode($pkresponse[1]);
        if ($sysdata->privacy === null) {
            die("Authorization token incorrect.");
        } else {
            if ($action === 'Create') {
                $add = addFeed($pkid);
                if (!$add) {
                    die('Error connecting to database.');
                } else {
                    header('location:' . FEEDURL . $pkid);
                    die('beep');
                }
            } else {
                $remove = removeFeed($pkid);
                if ($remove === true) {
                    die("Removed " . $pkid . " from database.");
                } else if ($remove === 0) {
                    die("Couldn't find system ID in database.");
                } else {
                    die("Error connecting to database.");
                }
            }
        }
    }
}

?>