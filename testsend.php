<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';  // Load PHPMailer via Composer

$mail = new PHPMailer(true);

try {
    // Pengaturan Server
    $mail->isSMTP();                                            // Gunakan protokol SMTP
    $mail->Host       = 'smtp.gmail.com';                       // Alamat server SMTP Gmail
    $mail->SMTPAuth   = true;                                   // Aktifkan autentikasi SMTP
    $mail->Username   = 'tatsuarieyu@gmail.com';                // Alamat email Gmail kamu
    $mail->Password   = 'ogpi egzo tznr vawk';                  // Kata sandi Gmail atau App Password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         // Gunakan enkripsi TLS
    $mail->Port       = 587;                                    // Port Gmail untuk TLS (587)

    // Pengaturan Penerima
    $mail->setFrom('tatsuarieyu@gmail.com', 'Nama Pengirim');   // Alamat email pengirim
    $mail->addAddress('fiqrifirmansyah15@gmail.com');           // Alamat email penerima

    // Konten Email
    $mail->isHTML(true);                                        // Format email dalam HTML
    $mail->Subject = 'Kode OTP Anda';
    $mail->Body    = 'Ini adalah kode OTP Anda: <b>123456</b>';  // Isi email HTML
    $mail->AltBody = 'Ini adalah kode OTP Anda: 123456';         // Isi email jika HTML tidak didukung

    $mail->send();
    echo 'Email berhasil dikirim';
} catch (Exception $e) {
    echo "Pesan tidak dapat dikirim. Kesalahan: {$mail->ErrorInfo}";
}
