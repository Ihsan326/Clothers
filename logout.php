<?php
session_start();
session_unset(); // Hapus semua data session
session_destroy(); // Hancurkan session

// Arahkan balik ke halaman utama
header("Location: index.php");
exit();
