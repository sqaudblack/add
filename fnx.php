<?php
// Captura os dados via GET
$nome = isset($_GET['name']) ? $_GET['name'] : '';
$email = isset($_GET['email']) ? $_GET['email'] : '';
$cpf = isset($_GET['cpf']) ? $_GET['cpf'] : '';
$phone = isset($_GET['whatsapp']) ? $_GET['whatsapp'] : '';
$unitPrice = isset($_GET['unitPrice']) ? $_GET['unitPrice'] : 0; 
$prod = isset($_GET['prod']) ? $_GET['prod'] : '';
$unitPrice = (int) ($unitPrice * 1); 
$amount = $unitPrice; 

function gerarEmailAleatorio($nome) {
    $primeiroNome = strtok($nome, ' ');
    $numeroAleatorio = rand(1000, 9999);
    $dominio = 'gmail.com';
    return strtolower($primeiroNome . $numeroAleatorio . '@' . $dominio);
}

if (empty($email)) {
    $email = gerarEmailAleatorio($nome);
}

function limparTelefone($telefone) {
    return preg_replace('/\D/', '', $telefone);
}

function gerarTelefoneRandomico() {
    // Gerar o código de área (DDD) entre 11 e 99
    $ddd = rand(11, 99);

    // Gerar a primeira parte do número (9 obrigatoriamente na frente, e mais 4 dígitos)
    $primeiraParte = '9' . rand(1000, 9999);

    // Gerar a segunda parte do número (mais 4 dígitos)
    $segundaParte = rand(1000, 9999);

    // Concatenar tudo sem traços ou espaços
    return $ddd . $primeiraParte . $segundaParte;
}

// Se o telefone estiver vazio, gera um telefone aleatório
if (empty($phone)) {
    $phone = gerarTelefoneRandomico();
} else {
    $phone = limparTelefone($phone);
}

$url = 'https://go.payspectra.com/api/v1/transaction.purchase';

$data = [
    "name" => $nome,
    "email" => $email,
    "cpf" => $cpf,
    "phone" => $phone, 
    "paymentMethod" => "PIX",  // Método de pagamento fixo
    "amount" => $amount,  // Valor do produto passado via GET
    "traceable" => true,  // Atributo que indica se a transação é rastreável
    "items" => [
        [
            "unitPrice" => $unitPrice,  // Preço do produto igual ao amount
            "title" => "$prod",  // Nome do produto
            "quantity" => 1,  // Quantidade fixa
            "tangible" => true  // Indica que o produto é tangível
        ]
    ]
];

$payload = json_encode($data);
$headers = [
    'Authorization: ccb208c6-eaf0-40a4-8747-ddeaa7528934',
    'Content-Type: application/json',
    'Accept: application/json'  // Adicione esse header
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

header('Content-Type: application/json');

$response = curl_exec($ch);
$statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

// Tratamento da resposta
if ($statusCode >= 200 && $statusCode < 300) {
    // Resposta com sucesso
    echo $response;
} else {
    // Resposta com erro
    echo json_encode([
        "error" => true,
        "statusCode" => $statusCode,
        "message" => "Erro ao realizar a transação.",
        "details" => json_decode($response, true)
    ]);
}

curl_close($ch);
?>
