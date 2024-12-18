<?php
// URL da API
$url = "https://api.utmify.com.br/api-credentials/orders";

// Cabeçalho com o token de autenticação
$headers = [
    "Content-Type: application/json",
    "x-api-token: MI7blUSOewG4nq9zJkBWJgYJpq2wLX69uQ3N"
];

// Capturando os parâmetros via GET
$orderId = $_GET['orderId'] ?? '';
$platform = $_GET['platform'] ?? '';
$paymentMethod = $_GET['paymentMethod'] ?? '';
$status = $_GET['status'] ?? '';
$createdAt = $_GET['createdAt'] ?? '';
$approvedDate = $_GET['approvedDate'] ?? null;
$refundedAt = $_GET['refundedAt'] ?? null;

$customer = [
    "name" => $_GET['customerName'] ?? '',
    "email" => $_GET['customerEmail'] ?? '',
    "phone" => $_GET['customerPhone'] ?? '',
    "document" => $_GET['customerDocument'] ?? '',
    "country" => $_GET['customerCountry'] ?? '',
    "ip" => $_GET['customerIp'] ?? ''
];

$product = [
    "id" => $_GET['productId'] ?? '',
    "name" => $_GET['productName'] ?? '',
    "planId" => $_GET['productPlanId'] ?? null,
    "planName" => $_GET['productPlanName'] ?? null,
    "quantity" => $_GET['productQuantity'] ?? 1,
    "priceInCents" => $_GET['productPriceInCents'] ?? 0
];

$trackingParameters = [
    "src" => $_GET['trackingSrc'] ?? null,
    "sck" => $_GET['trackingSck'] ?? null,
    "utm_source" => $_GET['utm_source'] ?? '',
    "utm_campaign" => $_GET['utm_campaign'] ?? '',
    "utm_medium" => $_GET['utm_medium'] ?? '',
    "utm_content" => $_GET['utm_content'] ?? '',
    "utm_term" => $_GET['utm_term'] ?? ''
];

$commission = [
    "totalPriceInCents" => $_GET['commissionTotalPriceInCents'] ?? 0,
    "gatewayFeeInCents" => $_GET['commissionGatewayFeeInCents'] ?? 0,
    "userCommissionInCents" => $_GET['commissionUserCommissionInCents'] ?? 0
];

$isTest = isset($_GET['isTest']) ? filter_var($_GET['isTest'], FILTER_VALIDATE_BOOLEAN) : false;

// Montando o corpo da requisição
$data = [
    "orderId" => $orderId,
    "platform" => $platform,
    "paymentMethod" => $paymentMethod,
    "status" => $status,
    "createdAt" => $createdAt,
    "approvedDate" => $approvedDate,
    "refundedAt" => $refundedAt,
    "customer" => $customer,
    "products" => [$product],
    "trackingParameters" => $trackingParameters,
    "commission" => $commission,
    "isTest" => $isTest
];

// Inicializa o cURL
$ch = curl_init($url);

// Configurações do cURL
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

// Executa a requisição e obtém a resposta
$response = curl_exec($ch);

// Abre o arquivo de log
$logFile = 'utmify.log';
$logEntry = date('Y-m-d H:i:s') . ' | Payload: ' . json_encode($data, JSON_UNESCAPED_SLASHES) . ' | Response: ' . $response . PHP_EOL;

// Verifica se ocorreu algum erro
if (curl_errno($ch)) {
    $logEntry = date('Y-m-d H:i:s') . ' | Payload: ' . json_encode($data, JSON_UNESCAPED_SLASHES) . ' | Error: ' . curl_error($ch) . PHP_EOL;
}

// Escreve no arquivo de log
file_put_contents($logFile, $logEntry, FILE_APPEND);

// Fecha a conexão cURL
curl_close($ch);

// Mostra o resultado no navegador
echo 'Payload enviado: ' . json_encode($data, JSON_PRETTY_PRINT) . PHP_EOL;
echo 'Resposta da API: ' . $response;
?>