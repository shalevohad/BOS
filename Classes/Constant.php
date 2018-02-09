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
    const SYSTEM_DEBUG = True;
    const SYSTEM_TEST_OR_EMPTY = "Test";
    const SYSTEM_VERSION = "2.1";
    const SYSTEM_NAME = "BugOrderSystem".self::SYSTEM_TEST_OR_EMPTY ;
    const SYSTEM_TIMEZONE = "Asia/Jerusalem";
    const SYSTEM_DOMAIN = "https://845.co.il/";
    const SYSTEM_LOCAL_ABSOLUTE_PATH = "/home/coil1212";
    const SYSTEM_WEBHOST_ROOT_DIRECTORY = "/public_html";
    const SYSTEM_SUBFOLDER = "BugOrderSystem".self::SYSTEM_TEST_OR_EMPTY ."/";

    const API_URL = self::SYSTEM_DOMAIN.self::SYSTEM_SUBFOLDER."API_CALLS.php";
    const WEBMASTER_EMAIL = "frizen700@gmail.com";

    //Products
    const PRODUCT_MAX_QUANTITY = 100;
    const PRODUCTS_STATUS_STYLE = array(
        1 => array("",""),
        2 => array("",""),
        3 => array("color: rgb(0,140,0)",""),
        4 => array("color: rgb(0,173,204)",""),
        5 => array("color: rgb(150,0,0)","table-success"),
        6 => array("color: rgb(120,90,200)",""),
        7 => array("text-decoration: line-through",""),
        8 => array("",""),
        "default" => array("", "")
    );

    //Orders && Products
    const ORDER_STATUS_STYLE = array(
        1 => array("", ""),
        2 => array("color: rgb(0,140,0)",""),
        3 => array("color: rgb(0,173,204)",""),
        4 => array("color: rgb(150,0,0)","w3-animate-fading"),
        5 => array("color: rgb(150,130,50)",""),
        6 => array("color: rgb(120,90,200)",""),
        7 => array("",""),
        8 => array("",""),
        "default" => array("", "")
    );

    const ORDER_ALERT_DAYS_REGION = 14;
    const ORDER_PRODUCT_STATUS_TO_ORDER_STATUS_MAP = array(
        "Created" => "Open",
        "Pre_order" => "Pre_order",
        "Ordered" => "Ordered",
        "Enroute" => "Enroute",
        "Arrived" => "Arrived",
        "Client_Informed" => "Client_Informed",
        "Delivered" => "Delivered",
        "Aborted" => "Aborted"
    );

    //Log
    const LOG_SYSTEM_NAME = self::SYSTEM_NAME."_LOG";
    const LOG_SUBFOLDER = "logs/";
    const LOG_DEFAULT_MAX_FILE = 12;

    //Mysql
    const MYSQL_SERVER = "localhost";
    const MYSQL_SERVER_PORT = 3306;
    const MYSQL_DATABASE = "coil1212_bug";

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
    
        <a style="text-decoration: none; font-size: 24px; padding-right: 20px" href="https://bug.845.co.il/statuscheck.php/?id={StatusCheckURL}">לחץ כאן לצפייה בהזמנה.</a>
    
        <div style="font-size: 18px; padding: 0 20px 20px 0;">
        <br>
        תודה רבה, <br>
        סניף {ShopName}</div>
    </div>
</div>
EMAIL;

}