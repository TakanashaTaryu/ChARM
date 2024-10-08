<?php
// File: src/functions.php

function getSnapToken($order_id, $amount) {
    global $midtrans_server_key;
    
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => "https://app.sandbox.midtrans.com/snap/v1/transactions",
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => "",
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => "POST",
        CURLOPT_POSTFIELDS => json_encode(array(
            'transaction_details' => array(
                'order_id' => $order_id,
                'gross_amount' => $amount,
            )
        )),
        CURLOPT_HTTPHEADER => array(
            "Authorization: Basic " . base64_encode($midtrans_server_key . ":"),
            "Content-Type: application/json",
            "Accept: application/json"
        ),
    ));

    $response = curl_exec($curl);
    $err = curl_error($curl);

    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
        return null;
    } else {
        $result = json_decode($response, true);
        return $result['token'] ?? null;
    }
}
