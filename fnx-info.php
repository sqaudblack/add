<?php

if (isset($_GET['orderId'])) {
    // Higieniza o valor do 'id' para evitar problemas de segurança
    $id = htmlspecialchars($_GET['orderId']);
    
    // Construa a URL com o parâmetro 'id'
    $url = 'https://go.payspectra.com/api/v1/transaction.getPayment?id=' . $id;

    // Defina os cabeçalhos para a requisição
    $headers = [
        'Authorization: ccb208c6-eaf0-40a4-8747-ddeaa7528934',
        'Accept: application/json',
    ];

    // Inicializa a sessão cURL
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Para capturar a resposta da API
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);  // Define os cabeçalhos HTTP

    // Executa a requisição
    $response = curl_exec($ch);

    // Verifica se houve erro na execução da requisição
    if (curl_errno($ch)) {
        echo curl_error($ch);
    } else {
        echo $response; // Exibe a resposta da API
    }

    // Fecha a sessão cURL
    curl_close($ch);

} else {
    echo 'Erro: O parâmetro "id" não foi fornecido na URL.';
}

?>
