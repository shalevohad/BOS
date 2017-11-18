<?php
/**
 * Created by PhpStorm.
 * User: Yogev
 * Date: 14-Oct-17
 * Time: 17:53
 */

namespace BugOrderSystem;

class Constant
{
    const SYSTEM_NAME = "BugOrderSystemTest";
    const WEBMASTER_EMAIL = "frizen700@gmail.com";

    const SYSTEM_DOMAIN = "https://845.co.il/";
    const SYSTEM_SUBFOLDER = "BugOrderSystemTest/";
    const API_URL = self::SYSTEM_DOMAIN.self::SYSTEM_SUBFOLDER."API_CALLS.php";

    //Log
    const LOG_SUBFOLDER = "logs/";
    const DEFAULT_MAX_FILE = 12;
    const SYSTEM_LOG_NAME = self::SYSTEM_NAME."_LOG";

    //Mysql
    const MYSQL_SERVER = "localhost";
    const MYSQL_SERVER_PORT = 3306;
    const MYSQL_DATABASE = "coil1212_bug";
    const MYSQL_DATABASE_USERNAME = "coil1212_bug";
    const MYSQL_DATABASE_PASSWORD = "Bug946394";

    //Email Template's
    const EMAIL_SHOP_NEED_TO_ORDER = <<<EMAIL
    שלום <span style="">{Name}</span>,<br/>
    <br/>
   הזמנה מספר <span style="font-weight: bold;">{OrderID}</span> על שם <span style="">{ClientName}</span>,<Br/>
   של המוכר  <span style="">{SellerName}</span> אשר נפתחה אתמול, עדיין לא הוזמנה.<br/>
   <br/>
   נא לדאוג להזמינה בהקדם!
EMAIL;

    const EMAIL_SELLER_NEED_TO_ORDER = <<<EMAIL
    שלום <span style="">{Name}</span>,<br/>
    <br/>
   הזמנה מספר <span style="font-weight: bold;">{OrderID}</span> על שם <span style="">{ClientName}</span>,<Br/>
  אשר נפתחה אתמול, עדיין לא הוזמנה.<br/>
   <br/>
   נא לדאוג להזמינה בהקדם!
EMAIL;

    const EMAIL_CLIENT_ORDER_ARRIVED = <<<EMAIL
    שלום {Name},<br/>
    <br/>
    ההזמנה שביצעת בסניף {ShopName} הגיעה לסניף ומוכנה לאיסוף.<br/>
    <br/>
    בברכה,<Br/>
    <br/>
    סניף {ShopName}
EMAIL;

    const ORDER_STATUS_STYLE = array(
        2 => array("rgb(0,140,0)",""),
        3 => array("rgb(0,173,204)",""),
        4 => array("rgb(150,0,0)","w3-animate-fading"),
        5 => array("rgb(150,130,50)",""),
        6 => array("rgb(120,90,200)",""),
        "default" => array("","")
);



}