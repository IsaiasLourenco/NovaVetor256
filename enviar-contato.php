<?php

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.html");
    exit;
}

$nome     = trim($_POST["nome"]     ?? "");
$email    = trim($_POST["email"]    ?? "");
$telefone = trim($_POST["telefone"] ?? "");
$mensagem = trim($_POST["mensagem"] ?? "");

if ($nome === "" || $email === "" || $mensagem === "") {
    die("Preencha todos os campos obrigatórios.");
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    die("E-mail inválido.");
}

$para    = "contato@vetor256.com";

$assunto = "Nova mensagem pelo site - Vetor256.";

$corpo   = "
Nova mensagem recebida pelo site da Vetor256.

Nome: $nome
E-mail: $email
Telefone: $telefone

Mensagem:
$mensagem
";

$cabecalhos = "From: contato@vetor256.com\r\n";
$cabecalhos .= "Reply-To: $email\r\n";
$cabecalhos .= "Content-Type: text/plain; charset=UTF-8\r\n";

if (mail($para, $assunto, $corpo, $cabecalhos)) {
    echo "Mensagem enviada com sucesso!";
} else {
    echo "Não foi possível enviar a mensagem.";
}
