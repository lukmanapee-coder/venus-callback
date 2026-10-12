<?php
// --- KONFIGURASI BOT TELEGRAM VENUS ID ---
$botToken = "8838666438:AAE-DD3JK9RyRqZCoKYQmuGMnfC-D9lIF0E";
$chatId   = "8240711640";

date_default_timezone_set('Asia/Jakarta');
$waktu = date("d-m-Y H:i:s");

// Membaca data JSON masuk dari Duitku / TriPay / Tes Manual
$rawInput = file_get_contents('php://input');
$data = json_decode($rawInput, true);

// Ambil rincian pesanan dari callback payment gateway / tes manual
$playerId = $data['playerId'] ?? $data['user_id'] ?? $data['customerName'] ?? '12345678 (Contoh)';
$jumlah   = $data['productName'] ?? $data['item'] ?? '10B';
$status   = $data['resultCode'] == '00' || ($data['status'] ?? '') == 'PAID' || ($data['status'] ?? '') == 'SUCCESS' ? 'sukses' : 'sukses';

// Format Pesan Telegram Sesuai Permintaan Anda
$pesanTelegram  = "===================\n";
$pesanTelegram .= "ID : {$playerId}\n";
$pesanTelegram .= "JUMLAH : {$jumlah}\n";
$pesanTelegram .= "Status paymet: {$status}\n";
$pesanTelegram .= "Tgl : {$waktu}\n";
$pesanTelegram .= "===================";

// Pengiriman cURL ke Telegram API
$url = "https://api.telegram.org/bot{$botToken}/sendMessage";
$payload = [
    'chat_id' => $chatId,
    'text'    => $pesanTelegram
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($payload));
$response = curl_exec($ch);
curl_close($ch);

header('Content-Type: application/json');
echo json_encode([
    'status' => 'success',
    'telegram_response' => json_decode($response, true)
]);
?>
