<?php
/**
 * Created by PhpStorm.
 * User: Yogev
 * Date: 09-Sep-17
 * Time: 17:57
 */

namespace BugOrderSystem;

require_once "OrderProducts.php";

use Log\ELogLevel;

class Order
{

    private static $orders = array();
    private static $loadedAll = false;

    const TABLE_NAME = "orders";
    const TABLE_KEY_COLUMN = "OrderId";

    private $id;
    private $clientId;
    private $shopId;
    private $sellerId;
    private $remarks;
    private $timeStamp;
    private $OrderInnerStatus;
    /**
     * @var EOrderStatus[]
     */
    private $status;
    /**
     * @var OrderProducts[]
     */
    private $orderProducts;

    /**
     * Order constructor.
     * @param array $orderData
     * @throws \Exception
     */
    private function __construct(array $orderData)
    {
        //\Services::dump($orderData);
        $this->id = $orderData["OrderId"];
        $this->clientId = $orderData["ClientId"];
        $this->shopId = $orderData["ShopId"];
        $this->sellerId = $orderData["SellerId"];
        $this->remarks = $orderData["Remarks"];
        $this->timeStamp = new \DateTime($orderData["Timestamp"]);

        $orderArray = BugOrderSystem::GetDB()->where("OrderId", $orderData["OrderId"])->get("orderproducts", null);
        foreach ($orderArray as $orderProduct) {
            $this->orderProducts[$orderProduct["ProductId"]] = new OrderProducts($this->id, $orderProduct["ProductName"], $orderProduct["ProductBarcode"]);
        }

        if (in_array($orderData["Status"], EOrderStatus::toArray()))
            $this->OrderInnerStatus = EOrderStatus::search($orderData["Status"]);
        $this->status = $this->GetStatus();
    }

    /**
     * @param $orderId
     * @param $orderData
     * @return mixed
     * @throws Exception
     */
    private static function AddOrderByOrderData($orderId, $orderData)
    {
        $res = @self::$orders[$orderId];

        if (!empty($res))
            throw new Exception("Order {0} already exists on this array!", $res, $orderId);

        if (count($orderData) == 0)
            throw new Exception("No data found on this order {0}, order {0} not exists!", null, $orderId);

        self::$orders[$orderId] = new Order($orderData);
        return self::$orders[$orderId];

    }

    /**
     * @param int $orderId
     * @return Order
     * @throws Exception
     * @throws \Exception
     */
    public static function &GetById(int $orderId)
    {
        if (empty($orderId))
            throw new Exception("Illegal order Id {0}!", null, $orderId);

        $res = @self::$orders[$orderId];

        if (empty($res)) {
            $orderData = BugOrderSystem::GetDB()->where(self::TABLE_KEY_COLUMN, $orderId)->getOne(self::TABLE_NAME);

            if (empty($orderData))
                throw new Exception("No data found on order {0}!", null, $orderId);

            $res = self::AddOrderByOrderData($orderId, $orderData);
        }

        return $res;
    }

    /**
     * @param Client $client
     * @param Shop $shop
     * @param Seller $seller
     * @param string $remarks
     * @return Order
     * @throws Exception
     * @throws \Exception
     */
    public static function Add(Client $client, Shop $shop, Seller $seller, string $remarks = "")
    {
        $OrderData= array(
            "ClientId" => $client->GetId(),
            "ShopId" => $shop->GetId(),
            "SellerId" => $seller->GetId(),
            "Remarks" => $remarks,
            "Status" => 0
        );

        $success = BugOrderSystem::GetDB()->insert(self::TABLE_NAME, $OrderData);
        if (!$success)
            throw new Exception("Unable to Add new order", $OrderData);

        $logText = "הזמנה חדשה מספר {orderId} נוספה ל{shop} על-ידי {seller}";
        BugOrderSystem::GetLog()->Write($logText, ELogLevel::INFO(), array("orderId" => $success, "shop" => $shop, "seller" => $seller));

        $res = &self::GetById($success);
        return $res;
    }

    /**
     * @param callable $function_doEachIteration
     * @param array $OrderByArray
     * @throws Exception
     * @throws \Exception
     */
    public static function LoopAll(callable $function_doEachIteration, array $OrderByArray = array())
    {
        if (!self::$loadedAll) {
            $dbObject = BugOrderSystem::GetDB();
            foreach ($OrderByArray as $orderBy) {
                $dbObject->orderBy($orderBy[0], $orderBy[1]);
            }
            $orderData = $dbObject->orderBy("Timestamp","DESC")->get(self::TABLE_NAME);

            foreach ($orderData as $order) {
                if (!array_key_exists($order[self::TABLE_KEY_COLUMN], self::$orders)) {
                    self::AddOrderByOrderData($order[self::TABLE_KEY_COLUMN], $order);
                }
            }

            self::$loadedAll = true;
        }

        foreach (self::$orders as $order) {
            call_user_func($function_doEachIteration, $order);
        }
    }

    /**
     * @param Shop $shop
     * @return Order[]
     * @throws Exception
     * @throws \Exception
     */
    public static function GetShopOrders(Shop $shop)
    {
        $shopOrders = array();
        Order::LoopAll(function (Order $order) use ($shop, &$shopOrders) {
            if ($order->GetShop() === $shop) {
                array_push($shopOrders, $order);
            }
        });
        return $shopOrders;
    }

    /**
     * @param Shop $shop
     * @return Order[]
     * @throws Exception
     * @throws \Exception
     */
    public static function GetOldOrders(Shop $shop) {
            $shopOrdersOld = array();
            Order::LoopAll(function (Order $order) use ($shop, &$shopOrdersOld) {
                if ($order->GetShop() === $shop) {
                    if($order->GetStatus() == EOrderStatus::Delivered() || $order->GetStatus() == EOrderStatus::Aborted()) {
                        array_push($shopOrdersOld, $order);
                    }
                }
            });
            return $shopOrdersOld;
    }

    /**
     * @param Shop $shop
     * @return Order[]
     * @throws Exception
     * @throws \Exception
     */
    public static function GetArrivedOrders(Shop $shop, \DateTime $since = null) {
        $shopOrdersOld = array();
        Order::LoopAll(function (Order $order) use ($shop, &$shopOrdersOld, $since) {
            if ($order->GetShop() === $shop) {
                if($order->GetStatus() == EOrderStatus::Delivered()) {
                    if (empty($since) || ($order->GetTimeStamp() >= $since)) {
                        array_push($shopOrdersOld, $order);
                    }
                }
            }
        });
        return $shopOrdersOld;
    }

    /**
     * @param Shop $shop
     * @param \DateTime|null $since
     * @return Order[]
     * @throws Exception
     * @throws \Exception
     */
    public static function GetCanceledOrders(Shop $shop, \DateTime $since = null) {
        $shopOrdersOld = array();
        Order::LoopAll(function (Order $order) use ($shop, &$shopOrdersOld, $since) {
            if ($order->GetShop() === $shop) {
                if($order->GetStatus() == EOrderStatus::Aborted()) {
                    if (empty($since) || ($order->GetTimeStamp() >= $since)) {
                        array_push($shopOrdersOld, $order);
                    }
                }
            }
        });
        return $shopOrdersOld;
    }

    /**
     * @param Shop $shop
     * @return Order[]
     * @throws Exception
     * @throws \Exception
     */
    public static function GetActiveOrders(Shop $shop) {
        $shopOrdersActive = array();
        Order::LoopAll(function (Order $order) use ($shop, &$shopOrdersActive) {
            if ($order->GetShop() === $shop && $order->GetStatus() != EOrderStatus::Delivered() &&
                $order->GetStatus() != EOrderStatus::Aborted() &&
                $order->GetStatus() != EOrderStatus::Pre_order()) {
                array_push($shopOrdersActive, $order);
            }
        });
        return $shopOrdersActive;
    }

    /**
     * @param Shop $shop
     * @param $searchKey
     * @return Order[]
     * @throws Exception
     * @throws \Exception
     */
    public static function GetSearchOrders(Shop $shop, $searchKey) {
        $shopOrdersActive = array();
        Order::LoopAll(function (Order $order) use ($shop, &$shopOrdersActive, $searchKey) {
            if ($order->GetShop() === $shop) {
                if(strpos($order->GetClient()->GetFullName(),$searchKey) !== false ||
                    strpos($order->GetSeller()->GetFullName(),$searchKey) !== false ||
                    strpos($order->GetId(),$searchKey) !== false ||
                    strpos($order->GetClient()->GetPhoneNumber(),$searchKey) !== false) {
                    array_push($shopOrdersActive, $order);
                }
            }
        });
        return $shopOrdersActive;
    }

    /**
     * @param Shop $shop
     * @return Order[]
     * @throws Exception
     * @throws \Exception
     */
    public static function GetPreOrders(Shop $shop) {
        $shopPreOrders = array();
        Order::LoopAll(function (Order $order) use ($shop, &$shopPreOrders) {
            if ($order->GetShop() === $shop) {
                if($order->GetStatus() == EOrderStatus::Pre_order()) {
                    array_push($shopPreOrders, $order);
                }
            }
        });
        return $shopPreOrders;
    }

    /**
     * @param string $ProductName
     * @param string $ProductBarcode
     * @param string $Remarks
     * @throws Exception
     * @throws \Exception
     */
    public function AddOrderProduct(string $ProductName, string $ProductBarcode, string $Remarks) {
        if (array_key_exists($this->id, $this->orderProducts))
            throw new Exception("המוצר {0} כבר קיים בהזמנה של הלקוח ולכן לא ניתן להוסיפו!", $this->orderProducts, $ProductName);

        $orderProductObject = new OrderProducts($this->id, $ProductName, $ProductBarcode, $Remarks);
        $this->orderProducts[$this->id] = $orderProductObject;

        $logText = "נוסף המוצר {orderProductObject} ל{Order}";
        BugOrderSystem::GetLog()->Write($logText, ELogLevel::INFO(), array("orderProductObject" => $orderProductObject, "Order" => $this));
    }

    /**
     * @return int
     */
    public function GetId() {
        return $this->id;
    }

    /**
     * @return Shop
     * @throws \Exception
     */
    public function GetShop() {
        $shop = &Shop::GetById($this->shopId);
        return $shop;
    }

    /**
     * @return Seller
     * @throws DBException
     * @throws Exception
     * @throws \Exception
     */
    public function GetSeller() {
        $seller = &Seller::GetById($this->sellerId);
        return $seller;
    }

    /**
     * @return string
     */
    public function GetRemarks() {
        return (string)$this->remarks;
    }

    /**
     * @return EOrderStatus
     * @throws \Exception
     */
    public function GetStatus() {
        //status calculation//
        if (is_array($this->orderProducts) && count($this->orderProducts) > 0) {
            $leastProductStatus = "";
            $mapKeys = array_keys(Constant::ORDER_PRODUCT_STATUS_TO_ORDER_STATUS_MAP);
            foreach ($this->orderProducts as $product) {
                $ProductStatus = $product->GetStatus();
                if (!empty($leastProductStatus)) {
                    $currentProductLoc = @array_search($ProductStatus->getName(), $mapKeys);
                    if ($currentProductLoc !== false && $currentProductLoc > @array_search($leastProductStatus->getName(), $mapKeys))
                        continue;
                }

                $leastProductStatus = $ProductStatus;
            }

            if (!empty($leastProductStatus)) {
                $orderCalculatedStatusKey = Constant::ORDER_PRODUCT_STATUS_TO_ORDER_STATUS_MAP[$leastProductStatus->getName()];
                $this->status = EOrderStatus::$orderCalculatedStatusKey();
                return $this->status;
            }
        }

        $this->status = EOrderStatus::Unknown();
        return $this->status;
    }

    /**
     * @return \DateTime
     */
    public function GetTimeStamp() {
        return $this->timeStamp;
    }

    /**
     * @return OrderProducts[]
     */
    public function GetOrderProducts() {
        return $this->orderProducts;
    }

    /**
     * @return Client
     * @throws \Exception
     */
    public function GetClient() {
        $clientObject = &Client::GetById($this->clientId);
        return $clientObject;
    }

    /**
     * @param EOrderStatus $status
     * @return EOrderStatus|EOrderStatus[]|static
     * @throws Exception
     * @throws \Exception
     */
    public function ChangeStatus(EOrderStatus $status) {
        $info = array("Status" => $status->getValue());
        BugOrderSystem::GetDB()->where(self::TABLE_KEY_COLUMN, $this->id)->update(self::TABLE_NAME, $info);

        $logText = "סטטוס {$this} השתנה";
        BugOrderSystem::GetLog()->Write($logText, ELogLevel::INFO(), array(EOrderStatus::search($status)->getDesc()));

        $this->status = $status;
        return $this->status;
    }

    /**
     * @param $sellerId
     * @param bool $update
     * @throws Exception
     * @throws \Exception
     */
    public function SetSellerId($sellerId, bool $update = true) {
        $this->sellerId = $sellerId;
        if ($update)
            $this->Update();
    }

    /**
     * @param $remarks
     * @param bool $update
     * @throws Exception
     * @throws \Exception
     */
    public function SetRemarks($remarks, bool $update = true) {
        $this->remarks = $remarks;
        if ($update)
            $this->Update();
    }

    /**
     * @throws Exception
     * @throws \Exception
     */
    public function Update() {
        $now = new \DateTime("now", new \DateTimeZone(Constant::SYSTEM_TIMEZONE));
        $updateArray = array(
            "SellerId" => $this->sellerId,
            "Remarks" => $this->remarks
        );
        $success = BugOrderSystem::GetDB()->where(self::TABLE_KEY_COLUMN, $this->id)->update(self::TABLE_NAME, $updateArray, 1);
        if (!$success)
            throw new Exception("לא ניתן לעדכן את {0}", $updateArray, $this);

        $logText = "הלקוח ".$this." עודכן";
        BugOrderSystem::GetLog()->Write($logText, ELogLevel::INFO(), $updateArray);
    }

    /**
     * @return string
     */
    public function __toString() {
        return "הזמנה " . $this->id;
    }
}