<?php
session_start();
include 'db_config.php';

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
    $username = $_POST["username"];
    $password = $_POST["password"];

    // 사용자 이름과 패스워드를 이용하여 검색 쿼리
    $stmt = $conn->prepare("SELECT id, username, password FROM License_user WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows == 1) {
        $row = $result->fetch_assoc();
        // 패스워드 일치 여부 확인
        if (password_verify($password, $row["password"])) {
            // 로그인 성공: 세션 설정
            $_SESSION["user_id"] = $row["id"];
            $_SESSION["username"] = $row["username"];
            
            $login_time = date('Y-m-d H:i:s');
            $ip_address = $_SERVER['REMOTE_ADDR'];
            
            $log_stmt = $conn->prepare("INSERT INTO License_log (user_id, username, login_time, ip_address) VALUES (?, ?, ?, ?)");
            $log_stmt->bind_param("isss", $row["id"], $username, $login_time, $ip_address);
            $log_stmt->execute();
            $log_stmt->close();
			
			$update_stmt = $conn->prepare("UPDATE License_user SET ip_address = ?, last_login = ? WHERE id = ?");
            $update_stmt->bind_param("ssi", $ip_address, $login_time, $row["id"]);
            $update_stmt->execute();
            $update_stmt->close();

            echo "로그인 성공! 환영합니다, " . $row["username"] . "님!";
        } else {
            echo "비밀번호가 올바르지 않습니다.";
        }
    } else {
        echo "사용자를 찾을 수 없습니다.";
    }
}

$conn->close();
?>
