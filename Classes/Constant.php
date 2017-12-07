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

    const ORDER_STATUS_STYLE = array(
        2 => array("rgb(0,140,0)",""),
        3 => array("rgb(0,173,204)",""),
        4 => array("rgb(150,0,0)","w3-animate-fading"),
        5 => array("rgb(150,130,50)",""),
        6 => array("rgb(120,90,200)",""),
        "default" => array("",""));

    //Log
    const LOG_SYSTEM_NAME = self::SYSTEM_NAME."_LOG";
    const LOG_SUBFOLDER = "logs/";
    const LOG_DEFAULT_MAX_FILE = 12;

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

//todo: write summery order.
    const EMAIL_CLIENT_SUMMERY_ORDER = <<<EMAIL
<div style="direction: rtl; font-family: arial, sans-serif;">
<div style="background: rgba(211,255,241,0.2); padding: 20px">
    <h1 style="font-style: italic">שלום {ClientName}, </h1>
    <h2 style="color: #555555"> אנו שמחים כי בחרת להזמין מאיתנו, אנו נעשה את מירב המאמצים בכדי לספק לך את המוצר בזמן הקצר ביותר.</h2>
    <h2>צרפנו עבורך סיכום ודף מעקב אחר ההזמנה:</h2>
</div>
<div style="background: rgba(254,254,209,0.22)">
     <br><br>
    <span style="font-size: 18px; padding: 10px;"> סיכום הזמנתך: </span><br>
    <ul style="list-style-type: none; font-size: 18px">
      <li>  <b>מספר הזמנה:</b> {OrderId} </li><br>
      <li>  <b>תאריך:</b> {OrderDate} </li><br>
      <li>  <b>שם המזמין:</b>  {ClientName} </li><br>
      <li>  <b>סניף:</b>  {ShopName} </li><br>
      <li>  <b>כתובת:</b>  {Address} </li><br>
      <li>  <b>מוכר:</b>  {Seller} </li><br>
      <li>  <b>טלפון לבירורים:</b> {PhoneNumber} </li><br>
    </ul>

    <a style="text-decoration: none; font-size: 24px; padding-right: 20px" href="https://bugtest.845.co.il/statuscheck.php/?id={StatusCheckURL}">לחץ כאן לצפייה בסטאטוס ההזמנה</a>

    <div style="font-size: 18px; padding: 10px;">
    <br>
    תודה רבה, <br>
    סניף {ShopName}</div>
    </div>
</div>
EMAIL;






}