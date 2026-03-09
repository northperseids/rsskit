<?php

// constants for easy access
define('USERAGENT', 'Neartsua (neartsua.me) PluralKit RSS Feed Generator (rsskit.neartsua.me)');
define('PKAPIURL', 'https://api.pluralkit.me/v2/systems/');
define('BASEURL', 'http://localhost/rsskit/');
define('FEEDURL', 'http://localhost/rsskit/feeds/feed.php?id=');

$localconfig = parse_ini_file('./localconfig.ini');
define('CONFIGFILE', $localconfig['deploy']);

function queryPK($pkid, $option)
{
    $curl = curl_init();

    if ($option === 'front') {
        curl_setopt($curl, CURLOPT_URL, PKAPIURL . $pkid . '/fronters');
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    } else if ($option === 'sys') {
        curl_setopt($curl, CURLOPT_URL, PKAPIURL . $pkid);
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    } else {
        curl_setopt($curl, CURLOPT_URL, PKAPIURL . $pkid);
        curl_setopt($curl, CURLOPT_HTTPHEADER, ['Content-Type: application/json', 'Authorization: ' . $option]);
    }
    curl_setopt($curl, CURLOPT_USERAGENT, USERAGENT);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($curl);
    $statuscode = curl_getinfo($curl, CURLINFO_HTTP_CODE);

    return [$statuscode, $response];
}

function pkDBConnect()
{
    $DBA = null;

    $DBA = parse_ini_file(CONFIGFILE);

    $conn = new mysqli($DBA['rsskit_dbhost'], $DBA['rsskit_username'], $DBA['rsskit_password'], $DBA['rsskit_dbname']);
    $conn->set_charset('utf8mb4');
    // Check connection
    if ($conn->connect_error) {
        return false;
    } else {
        return $conn;
    }
}

function addFeed($pkid)
{
    $conn = pkDBConnect();
    if (!$conn) {
        return false;
    }
    $sql = "SELECT * FROM feeds WHERE pkid=?";
    $statement = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($statement, "s", $pkid);
    mysqli_stmt_execute($statement);
    $result = mysqli_stmt_get_result($statement);
    if (mysqli_num_rows($result) > 0) {
        return true;
    } else {
        $sql = "INSERT INTO feeds (pkid, date_created) Values (?, CURDATE())";
        $statement = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($statement, "s", $pkid);
        $success = mysqli_stmt_execute($statement);
        if ($success) {
            return true;
        } else {
            return false;
        }
    }
}

function checkFeed($pkid)
{
    $conn = pkDBConnect();
    if (!$conn) {
        return false;
    }
    $sql = "SELECT * FROM feeds WHERE pkid=?";
    $statement = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($statement, "s", $pkid);
    mysqli_stmt_execute($statement);
    $result = mysqli_stmt_get_result($statement);
    if (mysqli_num_rows($result) > 0) {
        return true;
    } else {
        return false;
    }
}

function removeFeed($pkid)
{
    $conn = pkDBConnect();
    if (!$conn) {
        return false;
    }
    $sql = "SELECT * FROM feeds WHERE pkid=?";
    $statement = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($statement, "s", $pkid);
    mysqli_stmt_execute($statement);
    $result = mysqli_stmt_get_result($statement);
    if (mysqli_num_rows($result) > 0) {
        $sql = "DELETE FROM feeds WHERE pkid=?";
        $statement = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($statement, "s", $pkid);
        $success = mysqli_stmt_execute($statement);
        if ($success) {
            return true;
        } else {
            return false;
        }
    } else {
        return 0;
    }
}