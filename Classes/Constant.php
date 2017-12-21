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
    const SYSTEM_TIMEZONE = "Asia/Jerusalem";
    const SYSTEM_DOMAIN = "https://845.co.il/";
    const SYSTEM_SUBFOLDER = "BugOrderSystemTest/";

    const API_URL = self::SYSTEM_DOMAIN.self::SYSTEM_SUBFOLDER."API_CALLS.php";
    const WEBMASTER_EMAIL = "frizen700@gmail.com";

    //Orders
    const ORDER_MAX_QUANTITY = 100;
    const ORDER_STATUS_STYLE = array(
        2 => array("rgb(0,140,0)",""),
        3 => array("rgb(0,173,204)",""),
        4 => array("rgb(150,0,0)","w3-animate-fading"),
        5 => array("rgb(150,130,50)",""),
        6 => array("rgb(120,90,200)",""),
        "default" => array("",""));

    const ORDER_ALERT_DAYS_REGION = 14;

    //Log
    const LOG_SYSTEM_NAME = self::SYSTEM_NAME."_LOG";
    const LOG_SUBFOLDER = "logs/";
    const LOG_DEFAULT_MAX_FILE = 12;
    const LOG_LOGGLY_TOKEN = "5ab1b876-ffde-450d-9ba8-c13f545c23b3";

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

    const EMAIL_CLIENT_SUMMERY_ORDER = <<<EMAIL
<div style="direction: rtl; font-family: arial, sans-serif; background: linear-gradient(to right, #f2fcfe, #f2fcfe); /*Standard*/">
    <div style="padding: 20px">
        <h1 style="font-style: italic">שלום {ClientName}, </h1>
        <h2 style="color: #555555"> אנו שמחים כי בחרת להזמין מאיתנו, אנו נעשה את מירב המאמצים בכדי לספק לך את המוצר בזמן הקצר ביותר.</h2>
        <h2 style="padding: 20px 20px 0 0 ;">צרפנו עבורך סיכום ודף מעקב אחר ההזמנה:</h2>
         <br><br>
    
        <a style="text-decoration: none; font-size: 24px; padding-right: 20px" href="https://bugtest.845.co.il/statuscheck.php/?id={StatusCheckURL}">לחץ כאן לצפייה בהזמנה.</a>
    
        <div style="font-size: 18px; padding: 0 20px 20px 0;">
        <br>
        תודה רבה, <br>
        סניף {ShopName}</div>
    </div>
</div>
EMAIL;

}