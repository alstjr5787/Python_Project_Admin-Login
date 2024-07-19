<?php
include 'db_config.php';

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("연결 실패: " . $conn->connect_error);
}

$sql = "
    SELECT License_user.username, License.expired_date 
    FROM License_user
    JOIN License ON License_user.use_code = License.code
";

// 쿼리 실행
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // 결과 출력
    while($row = $result->fetch_assoc()) {
        echo "Username: " . $row["username"] . " - Expired Date: " . $row["expired_date"] . "<br>";
    }
} else {
    echo "결과가 없습니다.";
}

$conn->close();
?>
