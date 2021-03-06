<?php
/**
 * Created by PhpStorm.
 * User: YogevAgranov
 * Date: 11/09/2017
 * Time: 15:15
 */

namespace BugOrderSystem;

use Log\ELogLevel;

class OrderProducts {
    private $orderId;
    /**
     * @var Products
     */
    private $product;
    private $remarks;
    /**
     * @var EProductStatus
     */
    private $status;
    private $quantity;

    /**
     * OrderProducts constructor.
     * @param int $orderId
     * @param Products $product
     * @param int $quantity
     * @param EProductStatus $status
     * @param string|null $remarks
     * @throws Exception
     * @throws \Exception
     */
    public function __construct(int $orderId, Products $product, int $quantity, EProductStatus $status, string $remarks = null) {
        if ($quantity < 1 || $quantity > Constant::PRODUCT_MAX_QUANTITY) {
            throw new Exception("כמות לא חוקית של פריטים! ניתן לשנות את הכמות בין 1 ל-{0}!", $quantity, Constant::PRODUCT_MAX_QUANTITY);
        }

        $this->quantity = $quantity;
        $this->orderId = $orderId;
        $this->product = $product;
        $this->status = $status;
        $this->remarks = $remarks;
    }

    /**
     * @return string
     */
    public function GetProductName() {
        return $this->product->GetName();
    }

    /**
     * @return string
     */
    public function GetProductRemark() {
        return $this->product->GetRemark();
    }

    /**
     * @return string
     */
    public function GetProductBarcode() {
        return $this->product->GetBarcode();
    }

    /**
     * @return string
     */
    public function GetRemarks() {
        return $this->remarks;
    }

    /**
     * @return int
     */
    public function GetQuantity() {
        return $this->quantity;
    }

    /**
     * @return EProductStatus
     */
    public function GetStatus() {
        return $this->status;
    }

    /**
     * @param EProductStatus $newStatus
     * @return EProductStatus
     * @throws Exception
     * @throws \Exception
     */
    public function ChangeStatus(EProductStatus $newStatus) {
        $logText = "הסטטוס של {product} השתנה מ-{oldStatus} ל-{newStatus}";
        BugOrderSystem::GetLog()->Write($logText, ELogLevel::INFO(), array("product" => $this, "oldStatus" => $this->status->getDesc(), "newStatus" => $newStatus->getDesc()));

        $this->status = $newStatus;
        $this->Update(false, true);

        return $this->status;
    }

    /**
     * @param $remarks
     * @param bool $update
     * @param bool $log
     * @throws Exception
     * @throws \Exception
     */
    public function SetRemarks($remarks, bool $update = true, bool $log = true) {
        if ($remarks !== $this->remarks) {

            if ($log) {
                $logText = "ההערות של {orderProduct} השתנה ל-'{newRemarks}'";
                BugOrderSystem::GetLog()->Write($logText, ELogLevel::INFO(), array("orderProduct" => $this, "newRemarks"=> $remarks));
            }

            $this->remarks = $remarks;
            if ($update)
                $this->Update(!$log);
        }
    }

    /**
     * @param int $quantity
     * @param bool $update
     * @param bool $log
     * @throws Exception
     * @throws \Exception
     */
    public function SetQuantity(int $quantity, bool $update = true, bool $log = true) {
        if ($quantity !== $this->quantity) {
            if($quantity < 1 || $quantity > Constant::PRODUCT_MAX_QUANTITY)
                throw new Exception("\"כמות לא חוקית של פריטים! ניתן לשנות את הכמות בין 1 ל-{0}!", $quantity, Constant::PRODUCT_MAX_QUANTITY);

            if ($log) {
                $logText = "הכמות של {orderProduct} השתנה מ{oldQuantity} ל{newQuantity}";
                BugOrderSystem::GetLog()->Write($logText, ELogLevel::INFO(), array("orderProduct" => $this, "oldQuantity"=> $this->quantity, "newQuantity" => $quantity, "orderId" => $this->orderId, "ProductBarcode" => $this->product->GetBarcode()));
            }

            $this->quantity = $quantity;
            if ($update)
                $this->Update(!$log);
        }
    }

    /**
     * @param bool $log
     * @param bool $statusChange
     * @throws Exception
     * @throws \Exception
     */
    public function Update(bool $log = true, bool $statusChange = false) {
        $orderObject = &Order::GetById($this->orderId);
        $orderObject->ProductsUpdate($log, $statusChange);
    }

    /**
     * @return string
     */
    public function __toString() {
        return "המוצר {$this->GetProductName()} ({$this->GetProductBarcode()}) בהזמנה {$this->orderId}";
    }
}