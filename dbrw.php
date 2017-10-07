<?php
    // NOTICE: This file contains debug code. Please comment it before formal release for security reasons.
    // IMPORTANT: This project is trashed and the new project will not be uploaded due to security concerns. (It is already in use.)
    // Code are suitable for PHP 7 which uses PDO Library.

    ini_set("display_errors", "On"); //debug

    define('EFZ_DB_NAME', 'data'); //DB Name
    define('EFZ_DB_USER', 'test'); //Your database username
    define('EFZ_DB_PASSWORD', 'test'); //Your database users password
    define('EFZ_DB_HOST', 'localhost'); //The hostname for your database
    define('EFZ_DB_PORT', '3306');

    try {
        $dbh = new PDO('mysql:host='.EFZ_DB_HOST.';port='.EFZ_DB_PORT.'; dbname='.EFZ_DB_NAME,
            EFZ_DB_USER,EFZ_DB_PASSWORD,array(PDO::ATTR_PERSISTENT=>true)
        );
        $dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        $dbh->query("SET NAMES utf8");
        $dbh->query("SET sql_mode = ''");
    } catch(PDOException $e) {
        die("Error connecting to the database: ".$e->getMessage());
    }
    echo 'DB Connection Success.<br>';

    function efz_do_query($query,$params,$dbh){
        $sth = $dbh->prepare($query);
        try{
            $sth->execute($params);
        }catch(PDOException $e) {
            $sth->debugDumpParams(); //debug
            die("Query Failed: ".$e->getMessage()); //debug
        }
        return $sth;
    }

    function efz_do_fetch_result($sth){
        return $sth->fetch(PDO::FETCH_ASSOC);
    }

    function efz_do_fetch_result_all($sth){
        return $sth->fetchAll();
    }

    $query = "SELECT * FROM test";
    $params = null;
    $sth = efz_do_query($query,$params,$dbh);
    echo '<table border = "0">';
    while(true){
        $row = efz_do_fetch_result($sth);
        if ($row == null) {break;}
        echo '<tr>';
        foreach ($row as $tmp){
            echo '<td>'.$tmp.'</td>';
        }
        echo '</tr>';
    }
    echo '</table>';

    $dbh = null;
