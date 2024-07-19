<?php
include 'db_config.php';

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$username = $_POST["username"];
$password = $_POST["password"];
$code = $_POST["code"];

// 코드 검증 함수
function verifyCode($conn, $code) {
    // 코드 검증 로직 구현
    $sql = "SELECT * FROM License WHERE code = '$code' AND used = 0";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
        return true;
    } else {
        return false;
    }
}

// 코드 검증
if (!verifyCode($conn, $code)) {
    die("유효하지 않거나 이미 사용된 코드입니다.");
}

// 비밀번호 암호화
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// 회원가입 쿼리
$sql = "INSERT INTO License_user (username, password, use_code) VALUES ('$username', '$hashed_password', '$code')";
if ($conn->query($sql) === TRUE) {
    echo "회원가입이 성공적으로 완료되었습니다.";
    
    // 사용된 코드의 used 상태 변경
    $update_sql = "UPDATE License SET used = 1 WHERE code = '$code'";
    if ($conn->query($update_sql) === TRUE) {
        echo " 사용된 코드 상태가 업데이트 되었습니다.";
    } else {
        echo "사용된 코드 상태 업데이트 중 오류 발생: " . $conn->error;
    }
} else {
    echo "회원가입 중 오류 발생: " . $conn->error;
}

$conn->close();
?>
