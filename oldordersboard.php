<?php
/**
 * Created by PhpStorm.
 * User: Yogev
 * Date: 19-Sep-17
 * Time: 11:27
 */

namespace BugOrderSystem;

session_start();

require_once "Classes/BugOrderSystem.php";


$shopId = $_SESSION["ShopId"];
if(!isset($shopId)) {
    header("Location: login.php");
}
$shopObject = &Shop::GetById($shopId);

//setting header
require_once "Header.php";
//setting page title
\Services::setPlaceHolder($GLOBALS["PageTemplate"], "PageTitle", "הזמנות ישנות");
//setting menu bar
\Services::setPlaceHolder($GLOBALS["PageTemplate"], "shopName", $shopObject->GetShopName());
\Services::setPlaceHolder($GLOBALS["PageTemplate"], "oldOrdersClass", "active");
///


$PageBody = <<<PAGE
<main>
    <oldorderboard>
    <div class="wrapper" style="margin-top:45px">
        <div class="form-group" style="direction: rtl">
            <div style="width: 200px; float: left">
                <input type="text" name="search" placeholder="חיפוש" id="search" class="form-control">
            </div>
            <select name="state" id="maxRows" class="form-control" style="width:150px;">
                <option value="5000">הצג הכל</option>
                <option value="5">5</option>
                <option value="10">10</option>
                <option value="15">15</option>
                <option value="20">20</option>
                <option value="50">50</option>
                <option value="75">75</option>
                <option value="100">100</option>
            </select>
        </div>
                <table id="mytable" class="table table-bordered" style="direction: rtl">
                  <thead>
                    <tr>
                        <th>הזמנה</th>
                        <th>תאריך</th>
                        <th>שם הלקוח</th>
                        <th>פלאפון</th>
                        <th>מוצר</th>
                        <th>ברקוד</th> 
                        <th>הערות</th>
                        <th>מוכרן</th>
                        <th>סטאטוס</th>
                    </tr>
                  </thead>
                  <tbody>
                    {OrderBoard_Table_Template}
                  </tbody>
                </table>
                
         <div class="pagination-container">
            <nav>
                <ul class="pagination"></ul>
            </nav>
        
         </div>

    <script>
    $(document).ready(function() {
        $('#search').keyup(function() {
          search_table($(this).val());
        });
    });
    
    function search_table(value) {
      $('#mytable tbody tr').each(function() {
        var found = 'false';
        $(this).each(function() {
          if($(this).text().toLowerCase().indexOf(value.toLowerCase()) >= 0) {
              found = 'true';
          }
        });
        if(found == 'true') {
            $(this).show();
        } else {
            $(this).hide();
        }
      });
    }
    
    
    </script>

    <script>
    var table = '#mytable';
    $('#maxRows').on('change', function(){
        $('.pagination').html('');
        var trnum = 0;
        var maxRows = parseInt($(this).val());
        var totalRows = $(table+' tbody tr').length;
        $(table+' tr:gt(0)').each(function(){
            trnum++;
            if(trnum > maxRows){
                $(this).hide()
            }
            if(trnum <= maxRows){
                $(this).show()
            }
        });
        if(totalRows > maxRows){
            var pagenum = Math.ceil(totalRows/maxRows);
            for(var i=1;i<=pagenum;){
                $('.pagination').append('<li data-page="'+i+'">\<span>'+ i++ +'<span class="sr-only">(current)</span></span>\</li>').show()
            }
        }
        $('.pagination li:first-child').addClass('active');
        $('.pagination li').on('click',function(){
            var pageNum = $(this).attr('data-page');
            var trIndex = 0;
            $('.pagination li').removeClass('active');
            $(this).addClass('active');
            $(table+' tr:gt(0)').each(function(){
                trIndex++;
                if(trIndex > (maxRows*pageNum) || trIndex <= ((maxRows*pageNum)-maxRows)){
                    $(this).hide()
                } else{
                    $(this).show()
                }
            })
        })
    })
 
    </script>
                
            </div>
        </div>
    </oldorderboard>
</main>
PAGE;


$OrderBoard_Table_Temlplate = <<<EOF
<tr data-action="OpenBOSDialog" data-page="vieworder.php" data-dialogTitle="הזמנה {orderId}" data-variables="id={orderId}&ShowHeaderFooter=0">
   <td>{orderId}</td>
   <td>{orderDate}</td>
   <td>{clientName}</td>
   <td>{clientCellPhone}</td>
   <td>
        <ul>{productTemplate}</ul>
   </td>
    <td>
        <ul>{barcodeTemplate}</ul>
   </td>
   <td>{orderRemarks}</td>
   <td>{orderSellerName}</td>
   <td>{orderStatus}</td>  
</tr>
EOF;
$productOrderTemplate_Quantity_More_Then_One = "<li><span style='color: indianred'> {ProductQuantity} X </span>{ProductName}</li>";
$productOrderTemplate_Quantity_One = "<li>{ProductName}</li>";
$productOrderTemplate_Quantity_One_Code = "<li>{ProductCode}</li>";

$shopOrders = &Order::GetOldOrders($shopObject);

$orderBoard = (count($shopOrders) > 0) ? "" : "<tr colspan='7'><div id='no-orders-available'>אין הזמנות </div></tr>";
foreach ($shopOrders as $order) {
    $orderBoard .= $OrderBoard_Table_Temlplate;
    \Services::setPlaceHolder($orderBoard, "orderId", $order->GetId());
    \Services::setPlaceHolder($orderBoard, "orderStatus", $order->GetStatus()->getDesc());
    try {
        \Services::setPlaceHolder($orderBoard, "orderSellerName", $order->GetSeller()->GetFullName());
    } catch (\Exception $e) {
        $errorMsg = $e->getMessage();
        \Services::setPlaceHolder($orderBoard, "orderSellerName", "מוכר לא ידוע");
        //\Services::ExceptionToDB($e);
    }
    \Services::setPlaceHolder($orderBoard, "orderRemarks", $order->GetRemarks());
    \Services::setPlaceHolder($orderBoard, "clientCellPhone", $order->GetClient()->GetPhoneNumber());
    \Services::setPlaceHolder($orderBoard, "clientName", $order->GetClient()->GetFullName());
    \Services::setPlaceHolder($orderBoard, "orderDate", $order->GetTimeStamp()->format("d/m/Y"));

    $orderProductString = "";
    foreach ($order->GetOrderProducts() as $orderProduct) {
        if ($orderProduct->GetQuantity() > 1) {
            $orderProductString .= $productOrderTemplate_Quantity_More_Then_One;
            \Services::setPlaceHolder($orderProductString, "ProductQuantity", $orderProduct->GetQuantity());
        } else {
            $orderProductString .= $productOrderTemplate_Quantity_One;
        }
        \Services::setPlaceHolder($orderProductString, "ProductName", $orderProduct->getProductName());
    }
    \Services::setPlaceHolder($orderBoard, "productTemplate", $orderProductString);

    $orderProductCode = "";
    foreach ($order->GetOrderProducts() as $orderProduct) {
            $orderProductCode .= $productOrderTemplate_Quantity_One_Code;

        \Services::setPlaceHolder($orderProductCode, "ProductCode", $orderProduct->GetProductBarcode());
    }
    \Services::setPlaceHolder($orderBoard, "barcodeTemplate", $orderProductCode);

}
\Services::setPlaceHolder($PageBody, "OrderBoard_Table_Template", $orderBoard);
\Services::setPlaceHolder($GLOBALS["PageTemplate"],"PageBody",$PageBody);
echo $GLOBALS["PageTemplate"];

