<?php
// konfigurasi bot Telegram dan URL server
$botToken = "6566115624:AAE0Q-VR4AFjkyC1dsewYioquDxnOzsQjtY";

// konfigurasi koneksi ke database MySQL
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "barang_db";

// membuat koneksi ke database MySQL
$conn = new mysqli($servername, $username, $password, $dbname);

// memeriksa koneksi
if ($conn->connect_error) {
die("Connection failed: " . $conn->connect_error);
}

// mengatur request ke bot API Telegram
$request = file_get_contents("php://input");
$update = json_decode($request, true);

// memeriksa apakah pesan dimulai dengan "/input"
if (strpos($update["message"]["text"], "/input") === 0) {
// mengambil data dari pesan
$cek = trim(substr($update["message"]["text"], 6)); // mengambil kata setelah "/input" dan menghapus spasi di awal dan akhir pesan
$chat_id = $update["message"]["chat"]["id"];
$message_id = $update["message"]["message_id"];
$date = $update["message"]["date"];

// memasukkan data ke database MySQL
$sql = "INSERT INTO chat_data (chat_id, message, message_id, date) VALUES (‘$chat_id’, ‘$cek’, ‘$message_id’, ‘$date’)";
if ($conn->query($sql) === TRUE) {
echo "New record created successfully";
} else {
echo "Error: " . $sql . "
" . $conn->error;
}

// mengirim pesan balasan ke pengguna
$reply = "Terima kasih, pesan Anda telah tersimpan.";
$url = "https://api.telegram.org/bot" . $botToken . "/sendMessage?chat_id=" . $chat_id . "&text=" . urlencode($reply);
file_get_contents($url);
}