<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>회원가입</title>
</head>
<body>
    <h2>회원가입</h2>
    <form action="License_register_process.php" method="post">
        <label for="username">아이디:</label>
        <input type="text" id="username" name="username" required><br><br>
        
        <label for="password">비밀번호:</label>
        <input type="password" id="password" name="password" required><br><br>
        
        <label for="code">코드:</label>
        <input type="text" id="code" name="code" required>
        <button type="button" onclick="checkCode()">코드 확인</button><br><br>
        
        <input type="submit" name="submit" value="회원가입">
    </form>

    <script>
        function checkCode() {
            var code = document.getElementById('code').value;
            if (code.trim() === '') {
                alert("코드를 입력하세요.");
                return;
            }

            // AJAX를 사용하여 서버로 코드 확인 요청
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'License_check_code.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.onload = function() {
                if (xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    if (response.valid) {
                        alert("사용 가능한 코드입니다.");
                    } else {
                        alert("사용할 수 없는 코드입니다.");
                    }
                } else {
                    alert('오류 발생: ' + xhr.status);
                }
            };
            xhr.send('code=' + code);
        }
    </script>
</body>
</html>
