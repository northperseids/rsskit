<?php

require('util.php');

if (array_key_exists('id', $_GET)) {
    $pkid = $_GET['id'];

    $check = checkFeed($pkid);

    if (!$check) {
        die('No system with that ID has created an RSS feed yet.');
    } else {
        $fronterQuery = queryPK($pkid, 'front');
        $systemQuery = queryPK($pkid, 'sys');

        if ($fronterQuery[0] !== 200 || $systemQuery[0] !== 200) {
            die("Error fetching PluralKit information." . $fronterQuery[0]);
        } else {

            header("Content-type: text/xml");

            $fronters = json_decode($fronterQuery[1]);
            $system = json_decode($systemQuery[1]);

            $systemName = $system->name;
            $systemTag = $system->tag;

            $membersArray = $fronters->members;

            echo '<?xml version="1.0" encoding="UTF-8" ?>
        <rss version="2.0">
        <channel>
            <title>' . $systemName . '</title>
            <link>' . BASEURL . '</link>
            <description>Current Fronters</description>';

            if (count($membersArray) == 0) {
                echo "<item>
                    <title>No fronters listed.</title>
                </item>";
            } else {
                foreach ($membersArray as $member) {
                echo "<item>
                    <title>" . $member->name . "</title>
                </item>";
            }
            }

            echo '</channel>
                </rss>';
        }
    }
}