<?php
// Konfigurasi bot Telegram dan URL server
$botToken = "6566115624:AAE0Q-VR4AFjkyC1dsewYioquDxnOzsQjtY";

// Konfigurasi koneksi ke database MySQL
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "barang_db";

// Membuat koneksi ke database MySQL
$conn = new mysqli($servername, $username, $password, $dbname);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Mengatur request ke bot API Telegram
$request = file_get_contents("php://input");
$update = json_decode($request, true);

// Memeriksa apakah pesan dimulai dengan "/input"
if (strpos($update["message"]["text"], "/input") === 0) {
    // Mengambil data dari pesan
    $pesan = explode(",", trim(substr($update["message"]["text"], 7))); // Mengambil kata setelah "/input", menghapus spasi di awal dan akhir pesan, dan membagi pesan dengan tanda koma
    $sto = trim($pesan[0]);
    $nama_barang = trim($pesan[1]);
    $jumlah = trim($pesan[2]);

    // Memasukkan data ke database MySQL
    $sql = "INSERT INTO barang (sto, nama_barang, jumlah) VALUES ('$sto', '$nama_barang', '$jumlah')";
    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Mengirim pesan balasan ke pengguna
    $reply = "Terima kasih, data barang telah tersimpan.";
    $url = "https://api.telegram.org/bot" . $botToken . "/sendMessage?chat_id=" . $update["message"]["chat"]["id"] . "&text=" . urlencode($reply);
    file_get_contents($url);
}