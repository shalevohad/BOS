<?php
/**
 * Created by PhpStorm.
 * User: Yogev
 * Date: 12-Dec-17
 * Time: 23:25
 */

session_start();
$shopId = $_SESSION["ShopId"];
if(!isset($shopId)) {
header("Location: ../login.php");
}
?>

<html xmlns="http://www.w3.org/1999/xhtml" lang="heb" dir="rtl">
<style>
    html {
        background-color: #c4d2d6;
        font-size: 18px;
        letter-spacing: 1px;
        line-height: 22px;
        padding: 10px;
    }

    html a {
        text-decoration: none;
    }
</style>

<h1>לוג צפייה:</h1>
