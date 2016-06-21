<?php
// Сообщение
$message = "Test message\r\n";

$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'From: testing robot <robot@exzz.com>' . "\r\n";

// Отправляем
mail('yanki@exzz.com', 'This is test Subject', $message, $headers);
echo 'отправлено!';
?>
