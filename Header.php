<?php
/**
 * Created by PhpStorm.
 * User: Yogev
 * Date: 24-Sep-17
 * Time: 20:30
 */
namespace BugOrderSystem;

const headerTemplate = <<<Header
<!DOCTYPE html>
<html>
<head>
    <title>{PageTitle}</title>
    
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <link rel="stylesheet" type="text/css" href="css/table.css">
    
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.2/css/jquery.dataTables.min.css">

    <!-- jquery Core -->
        <script
        src="https://code.jquery.com/jquery-3.2.1.min.js"
        integrity="sha256-hwg4gsxgFZhOsEEamdOYGBf13FyQuiTwlAQgxVSNgt4="
        crossorigin="anonymous"></script>
        <!-- <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
  
    <!-- BootStrap -->
        <script src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.10.16/js/dataTables.bootstrap4.min.js"></script>
        <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
    <!-- End BootStrap -->
    
    <!-- jquery ui -->
        <script
        src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js" 
        integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU=" 
        crossorigin="anonymous"></script>
  
    <!--site main js-->
    <script src="js/main.js"></script>
    <script src="js/jqueryMain.js"></script>
    
    <meta name="viewport" content="width=device-width">
</head>

Header;

const headerMenu = <<<HeaderMenu
    <body>
              <header>

              <div class="wrapper">
                  <logo>
                      <img src="images/logo.png" alt="logo">
                  </logo>
                  <div class="shop-details">שלום, {shopName}
                    <br><a href="logout.php"><img src="images/icons/exit.png" alt="exit"></a>
                  </div>
                  <nav>
                    <div id="burger-nav"><img src="images/icons/burger.png" alt="exit"></div>
                      <h2>Main Navigation</h2>
                      <ul>
                        <ul>
                        <li><form id="searchBox" name="searchbox" action="search.php" method="get">
                             <input type="text" placeholder="חיפוש" name="search">
                            <input type="submit" style="position: relative; left: -9999px; width: 1px; height: 1px;" tabindex="2" />
                            </form>
                        </li>
                         <li><a href="oldordersboard.php" class={oldOrdersClass}">הזמנות ישנות</a></li>
                         <li><a href="neworder.php" class={newOrdersClass}>הזמנה חדשה</a></li>
                         <li><a href="Ordersboard.php" class={ordersBoardClass}>לוח הזמנות</a></li>
                         <li><a href="index.php" class={mainPageClass}>ראשי</a></li>
                      </ul>
                      </ul>
                  </nav>
                  </div>
              </header>
HeaderMenu;

/*
const shopMenu = <<<ShopMenu
                      <ul>
                        <li><form id="searchBox" name="searchbox" action="search.php" method="get">
                             <input type="text" placeholder="חיפוש" name="search">
                            <input type="submit" style="position: relative; left: -9999px; width: 1px; height: 1px;" tabindex="2" />
                            </form>
                        </li>
                         <li><a href="oldordersboard.php" class={oldOrdersClass}">הזמנות ישנות</a></li>
                         <li><a href="neworder.php" class={newOrdersClass}>הזמנה חדשה</a></li>
                         <li><a href="Ordersboard.php" class={ordersBoardClass}>לוח הזמנות</a></li>
                         <li><a href="index.php" class={mainPageClass}>ראשי</a></li>
                      </ul>
ShopMenu;

const regionMenu = <<<RegionMenu
                      <ul>
                         <li><a href="index.php" class={mainPageClass}>ראשי</a></li>
                      </ul>
RegionMenu;
*/

const footer = <<<Footer

</body>
<footer>
        <div id="footer"> ~Beta 1.0~ </div>
</footer>
</html>
Footer;


