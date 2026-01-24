<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'libs/PHPMailer/src/Exception.php';
require 'libs/PHPMailer/src/PHPMailer.php';
require 'libs/PHPMailer/src/SMTP.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Получаем данные из формы
    $formName = isset($_POST['FormName']) ? htmlspecialchars(trim($_POST['FormName'])) : 'Без имени';
    $nameUser = isset($_POST['Имя']) ? htmlspecialchars(trim($_POST['Имя'])) : '';
    $formPhone = isset($_POST['Телефон']) ? htmlspecialchars(trim($_POST['Телефон'])) : '';

    // Создаем тело письма
    $body = "<h3>Заявка с формы: {$formName}</h3>";

    // Добавляем Имя, если не пусто
    if (!empty($nameUser)) {
        $body .= "<p><strong>Имя:</strong> {$nameUser}</p>";
    }

    // Добавляем Телефон, если не пусто
    if (!empty($formPhone)) {
        $body .= "<p><strong>Телефон:</strong> {$formPhone}</p>";
    }

    $mail = new PHPMailer(true);

    try {
        // Настройки PHPMailer
        $mail->CharSet = 'UTF-8';
        $mail->isSMTP();
        $mail->Host = 'smtp.timeweb.ru'; // SMTP-сервер Timeweb
        $mail->SMTPAuth = true;
        $mail->Username = 'zakaz@uslugialpinista.ru'; // Логин для SMTP
        $mail->Password = 'gvPjYcU19'; // Пароль для SMTP
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Используем SSL (для порта 465)
        $mail->Port = 465; // Порт для SSL

        // Отправка письма
        $mail->setFrom('zakaz@uslugialpinista.ru', 'UslugiAlpinista заявка');
        $mail->addAddress('alekcei.9@mail.ru', 'Получатель'); // Адрес получателя

        // Настройки письма
        $mail->isHTML(true);
        $mail->Subject = 'UslugiAlpinista - заявка';
        $mail->Body = $body;
        $mail->AltBody = strip_tags($body); // Текстовое тело для клиентов, не поддерживающих HTML

        // Отправка письма
        $mail->send();

        // Перенаправление на страницу благодарности
        header('Location: zayavka-otpravlena/');
        exit;
    } catch (Exception $e) {
        echo "Ошибка отправки: {$mail->ErrorInfo}";
    }
} else {
    echo 'Доступ запрещен';
}
?>