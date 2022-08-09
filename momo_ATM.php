<?php 
    function execPostRequest($url, $data)
    {

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data))
        );
        curl_setopt($ch, CURLOPT_TIMEOUT, 3);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
        //execute post
        $result = curl_exec($ch);
        //close connection
        curl_close($ch);
        return $result;
    }
    
    
    $endpoint = "https://test-payment.momo.vn/v2/gateway/api/create";
    
    
    $partnerCode = 'MOMOBKUN20180529';
    $accessKey = 'klm05TvNBzhg7h7j';
    $secretKey = 'at67qH6mk8w5Y1nAyMoYKMWACiEi2bsa';
    $orderInfo = "Thanh toán qua MoMo";
    $amount = $_POST['total'];
    $orderId = time() ."";
    $redirectUrl = "http://localhost:8080/food/payment_online.php?user_id=".$_POST['user_id']."&name=".$_POST['name']."&phone=".$_POST['phone']."&address=".$_POST['address']."&sale_price=".$_POST['sale']."&sale_id=".$_POST['sale_id']."";
    $ipnUrl = "https://webhook.site/b3088a6a-2d17-4f8d-a383-71389a6c600b";
    $extraData = "";
    
    
    // if (!empty($_POST)) {
        $partnerCode = $partnerCode;
        $accessKey = $accessKey;
        $secretKey = $secretKey;
        $orderId = time(); // Mã đơn hàng
        $orderInfo = $orderInfo;
        $amount = $amount;
        $sale_price = $_POST['sale'];
        // $ipnUrl = $_POST["ipnUrl"];
        $redirectUrl = $redirectUrl;
        $extraData = $extraData;
    
        $requestId = time() . "";
        $requestType = "payWithATM";

        // $extraData = ($_POST["extraData"] ? $_POST["extraData"] : "");
        //before sign HMAC SHA256 signature
        $rawHash = "accessKey=" . $accessKey . "&amount=" . $amount . "&extraData=" . $extraData . "&ipnUrl=" . $ipnUrl . "&orderId=" . $orderId . "&orderInfo=" . $orderInfo . "&partnerCode=" . $partnerCode . "&redirectUrl=" . $redirectUrl . "&requestId=" . $requestId . "&requestType=" . $requestType;
        $signature = hash_hmac("sha256", $rawHash, $secretKey);
        $data = array('partnerCode' => $partnerCode,
            'partnerName' => "Test",
            "storeId" => "MomoTestStore",
            'requestId' => $requestId,
            'amount' => $amount,
            'sale_price' => $sale_price,
            'orderId' => $orderId,
            'orderInfo' => $orderInfo,
            'redirectUrl' => $redirectUrl,
            'ipnUrl' => $ipnUrl,
            'lang' => 'vi',
            'extraData' => $extraData,
            'requestType' => $requestType,
            'signature' => $signature);
        $result = execPostRequest($endpoint, json_encode($data));
        $jsonResult = json_decode($result, true);  // decode json
    
        //Just a example, please check more in there
    
        header('Location: ' . $jsonResult['payUrl']);
    // }


    
?>