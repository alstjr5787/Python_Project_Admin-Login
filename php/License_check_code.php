<?php
include 'db_config.php';

$code = $_POST['code'];

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// 코드 확인 쿼리
$sql = "SELECT * FROM License WHERE code = '$code' AND used = 0";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // 코드가 유효하고 사용 가능한 경우
    $response = array('valid' => true);
} else {
    // 코드가 없거나 이미 사용된 경우
    $response = array('valid' => false);
}


header('Content-Type: application/json');
echo json_encode($response);

$conn->close();
?>
