<?php
/**
 * Created by PhpStorm.
 * User: Yogev
 * Date: 21-Sep-17
 * Time: 19:13
 */

namespace BugOrderSystem;

session_start();
require_once "Classes/BugOrderSystem.php";

$shopId = $_SESSION["ShopId"];
if(!isset($shopId)) {
    header("Location: login.php");
}
$orderId = $_GET["id"];
$productId = $_GET["productId"];
$shopObject = Shop::GetById($shopId);
$productsObject = Order::GetById($orderId)->GetOrderProducts();


//setting header
require_once "Header.php";
$PageTemplate = headerTemplate;
//setting page title
\Services::setPlaceHolder($PageTemplate, "PageTitle", "עריכת מוצר");
//setting menu bar
$PageTemplate .= headerMenu;
\Services::setPlaceHolder($PageTemplate, "shopName", $shopObject->GetShopName());
\Services::setPlaceHolder($PageTemplate, "ordersBoardClass", "'current'");
///


$PageTemplate .= <<<PAGE
<main>
    <div class="wrapper">
        <div id="new-order">

        <form class="new-order" method="POST">
   <center>       {productId}  -  עריכת פריט</center>
            <br>
            שם המוצר<br>
            <input type="text" name="ProductName" value="{productName}" required><br>
            ברקוד<br>
            <input type="text" name="ProductBarcode" value="{productBarcode}" onkeyup="this.value=this.value.replace(/[^\d]/,'')" required><br>
            כמות<br>
            <input type="text" name="Quantity" value="{productQuantity}" onkeyup="this.value=this.value.replace(/[^\d]/,'')" required><br>

            <br>
            :הערות למוצר
            <input type="text" name="Remarks" value="{productRemarks}"><br>

            <br>
            <button type="submit" name="editorder">עדכן פריט</button>

            <br>
            </form>

        </div>
    </div>
</main>
PAGE;
//setting footer
$PageTemplate .= footer;


foreach ($productsObject as $productObject){
    if($productObject->GetId() == $productId) {
        \Services::setPlaceHolder($PageTemplate, "productId", $productObject->GetId());
        \Services::setPlaceHolder($PageTemplate, "productName", $productObject->getProductName());
        \Services::setPlaceHolder($PageTemplate, "productBarcode", $productObject->GetProductBarcode());
        \Services::setPlaceHolder($PageTemplate, "productQuantity", $productObject->GetQuantity());
        \Services::setPlaceHolder($PageTemplate, "productRemarks", $productObject->GetRemarks());
        $newProductObject = $productObject;
    }
}

//Take form filed and make them variable.
if(isset($_POST['editorder'])) {
    include_once("inc/db_connect.req.php");

    $product_name = $_POST['ProductName'];
    $product_barcode = $_POST['ProductBarcode'];
    $product_quantity = $_POST['Quantity'];
    $product_remarks = $_POST['Remarks'];


        //Update product
    if(!empty($product_name) && !empty($product_barcode) && !empty($product_quantity)) {
        try {
            $newProductObject->ProductUpdate(array("ProductName" => $product_name, "ProductBarcode" => $product_barcode, "Quantity" => $product_quantity, "Remarks" => $product_remarks));
            header("Location: vieworder.php?id=$orderId");
        }catch (\Exception $e) {
            $errorMsg = $e->getMessage();
            echo $errorMsg;
        }
    } else {
        echo "נא למלא את כל השדות";
    }
}

echo $PageTemplate;


?>