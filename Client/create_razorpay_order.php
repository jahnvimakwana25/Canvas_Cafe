<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

// Razorpay API credentials (must match frontend environment: test or live)
$keyId = 'rzp_test_RGNKMSuRsmumYS';
$keySecret = 'APeQ4rNIyFjZR2VozLUyA4AL';

if (empty($keyId) || empty($keySecret)) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Missing Razorpay credentials'
    ]);
    exit;
}

try {
    $json = file_get_contents('php://input');
    $data = json_decode($json, true);

    if (!isset($data['amount'])) {
        throw new Exception('Missing required amount');
    }

    $orderData = [
        'receipt'         => 'rcpt_' . uniqid(),
        'amount'          => $data['amount'], // paise
        'currency'        => $data['currency'] ?? 'INR',
        'payment_capture' => 1
    ];

    $auth = base64_encode($keyId . ':' . $keySecret);

    $ch = curl_init('https://api.razorpay.com/v1/orders');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($orderData));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'Authorization: Basic ' . $auth
    ]);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $responseData = json_decode($response, true);

    if ($httpCode !== 200 || !isset($responseData['id'])) {
        $errorMsg = $responseData['error']['description'] ?? 'Failed to create Razorpay order';
        throw new Exception($errorMsg);
    }

    echo json_encode([
        'success' => true,
        'id' => $responseData['id'],       // Razorpay order_id (important!)
        'amount' => $responseData['amount'],
        'currency' => $responseData['currency']
    ]);
} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'error' => $e->getMessage()
    ]);
}
