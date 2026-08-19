<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/vendor/autoload.php';

header('Content-Type: application/json; charset=utf-8');

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode([
        "sucesso" => false,
        "mensagem" => "Método não permitido."
    ]);
    exit;
}

$nome       = trim($_POST["nome"] ?? "");
$email      = trim($_POST["email"] ?? "");
$telefone   = trim($_POST["telefone"] ?? "");
$mensagem   = trim($_POST["mensagem"] ?? "");

if ($nome === "" || $email === "" || $mensagem === "") {
    echo json_encode([
        "sucesso" => false,
        "mensagem" => "Preencha todos os campos obrigatórios."
    ]);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    echo json_encode([
        "sucesso" => false,
        "mensagem" => "E-mail inválido."
    ]);
    exit;
}

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();

    $mail->Host = 'smtp.titan.email';
    $mail->SMTPAuth = true;
    $mail->Username = 'contato@vetor256.com';
    $mail->Password = 'Vetor256@Empresa';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port = 465;

    $mail->CharSet = 'UTF-8';

    $mail->setFrom('contato@vetor256.com', 'Site Vetor256');
    $mail->addAddress('contato@vetor256.com', 'Vetor256');
    $mail->addReplyTo($email, $nome);

    $mail->isHTML(true);
    $mail->Subject = 'Novo contato pelo site - ' . $nome;

    $mail->Body = '
        <h2>Novo contato pelo site Vetor256</h2>
        <p><strong>Nome:</strong> ' . htmlspecialchars($nome) . '</p>
        <p><strong>E-mail:</strong> ' . htmlspecialchars($email) . '</p>
        <p><strong>Telefone:</strong> ' . htmlspecialchars($telefone) . '</p>
        <p><strong>Mensagem:</strong><br>' . nl2br(htmlspecialchars($mensagem)) . '</p>
    ';

    $mail->AltBody =
        "Novo contato pelo site Vetor256\n\n" .
        "Nome: " . $nome . "\n" .
        "E-mail: " . $email . "\n" .
        "Telefone: " . $telefone . "\n\n" .
        "Mensagem:\n" . $mensagem;

    $mail->send();

    echo json_encode([
        "sucesso" => true,
        "mensagem" => "Mensagem enviada com sucesso!"
    ]);

} catch (Exception $e) {
    echo json_encode([
        "sucesso" => false,
        "mensagem" => "Não foi possível enviar a mensagem."
    ]);
}
