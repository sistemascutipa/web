<?php
declare(strict_types=1);

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
  header('Location: ../index.html#contacto'); exit;
}

$name = trim((string)($_POST['name'] ?? ''));
$email = trim((string)($_POST['email'] ?? ''));
$subject = trim((string)($_POST['subject'] ?? 'Consulta desde la web'));
$message = trim((string)($_POST['message'] ?? ''));

if ($name === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || $message === '') {
  http_response_code(422);
  echo '<!doctype html><html lang="es"><meta charset="utf-8"><title>Formulario incompleto</title><body style="font-family:Arial;max-width:620px;margin:80px auto;padding:20px"><h1>Revisa los datos</h1><p>Completa tu nombre, correo válido y mensaje.</p><a href="../index.html#contacto">Volver al formulario</a></body></html>';
  exit;
}

$recipients = 'maurodelta9@gmail.com, operacionesTI@sistemascutipa.com';
$safeSubject = str_replace(["\r", "\n"], '', $subject);
$headers = "From: formulario@sistemascutipa.com\r\n";
$headers .= "Reply-To: " . str_replace(["\r", "\n"], '', $email) . "\r\n";
$headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
$body = "Nuevo mensaje desde sistemascutipa.com\n\nNombre: {$name}\nCorreo: {$email}\nAsunto: {$safeSubject}\n\nMensaje:\n{$message}\n";
$sent = mail($recipients, $safeSubject, $body, $headers);

if (!$sent) { http_response_code(500); }
?>
<!doctype html><html lang="es"><meta charset="utf-8"><title><?= $sent ? 'Mensaje enviado' : 'No se pudo enviar' ?></title><body style="font-family:Arial;max-width:620px;margin:80px auto;padding:20px"><h1><?= $sent ? 'Gracias por contactarnos' : 'No se pudo enviar el mensaje' ?></h1><p><?= $sent ? 'Recibimos tu solicitud y te responderemos pronto.' : 'El servidor no pudo procesar el correo. Escríbenos por WhatsApp al +51 987 639 368.' ?></p><a href="../index.html">Volver a Sistemas Cutipa</a></body></html>
