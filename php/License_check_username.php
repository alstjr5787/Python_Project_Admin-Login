<?php
include 'db_config.php';

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    
    // 아이디 중복 체크 쿼리
    $stmt = $conn->prepare("SELECT COUNT(*) FROM Users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    header('Content-Type: application/json');
    if ($count > 0) {
        echo json_encode(["valid" => false]);
    } else {
        echo json_encode(["valid" => true]);
    }
}

$conn->close();
?>
