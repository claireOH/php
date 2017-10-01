<?php
/**
 * Created by PhpStorm.
 * User: User
 * Date: 2017-09-13
 * Time: 오전 10:38
 */

    //DB연결
    define("HOST", "localhost");
    define("USER", "root");
    define("PASSWORD", "autoset");
    define("DB_NAME", "bulletinboard");

    $db_con = @mysql_connect(HOST, USER, PASSWORD);
    $db_select = @mysql_select_db(DB_NAME, $db_con);

?>