<?php
    const SERVER='mysql220.phy.lolipop.lan';
    const DBNAME='LAA1517808-final';
    const USER='LAA1517808';
    const PASS='Pass0406';
    $connect = 'mysql:host='.SERVER.';dbname='.DBNAME.';charset=utf8';
    $pdo=new PDO($connect,USER,PASS);
?>