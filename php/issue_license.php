<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>코드 발급 페이지</title>
</head>
<body>
    <h2>코드 발급</h2>
    <form action="issue_license.php" method="post">
        <label for="code">코드:</label>
        <input type="text" id="code" name="code" value="<?php echo generateCode(); ?>" readonly>
        <button type="button" onclick="generateNewCode()">새로고침</button><br><br>
        
        <label for="duration">기간:</label>
        <select id="duration" name="duration">
            <option value="7">7일</option>
            <option value="15">15일</option>
            <option value="30">30일</option>
        </select><br><br>
        
        <input type="submit" name="submit" value="코드 발급">
    </form>

    <?php
    include 'db_config.php';

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // 코드 생성 함수
    function generateCode($length = 16) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $randomString;
    }

    // 코드 발급 처리
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["submit"])) {
        $code = $_POST["code"];
        $duration = $_POST["duration"];

        $code_date = $duration . '일';

        $stmt = $conn->prepare("INSERT INTO License (code, code_date) VALUES (?, ?)");
        $stmt->bind_param("ss", $code, $code_date);

        if ($stmt->execute()) {
            echo "새로운 코드가 성공적으로 발급되었습니다: $code";
        } else {
            echo "코드 발급 중 오류 발생: " . $conn->error;
        }
    }

    // 코드 리스트 조회
    $sql = "SELECT * FROM License";
    $result = $conn->query($sql);

    // 결과 출력
    if ($result->num_rows > 0) {
        echo "<h2>코드 리스트</h2>";
        echo "<table border='1'><tr><th>코드</th><th>발급 기간</th><th>사용 여부</th></tr>";
        while($row = $result->fetch_assoc()) {
            echo "<tr><td>" . $row["code"] . "</td><td>" . $row["code_date"] . "</td><td>" . ($row["used"] ? "사용됨" : "미사용") . "</td></tr>";
        }
        echo "</table>";
    } else {
        echo "코드가 없습니다.";
    }

    $conn->close();
    ?>

    <script>
        function generateNewCode() {
            var chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
            var codeLength = 16;
            var randomCode = '';
            for (var i = 0; i < codeLength; i++) {
                randomCode += chars.charAt(Math.floor(Math.random() * chars.length));
            }
            document.getElementById('code').value = randomCode;
        }
    </script>
</body>
</html>
