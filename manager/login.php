<?php
    // 检查是否提交了表单
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // 获取输入值
        $student_id = $_POST["student_id"];
        $password = $_POST["password"];

        // 执行数据库查询以检查登录凭据
        $config = file_get_contents("../config.json");
        $config = json_decode($config, true);

        $conn = mysqli_connect($config['servername'], $config['user'], $config['password'], $config['dbName'], $config['port']);

        $selectSQL = "SELECT `_id` FROM student WHERE `student_id` = ? AND `password` = ?";
        $stmt = mysqli_prepare($conn, $selectSQL);
        mysqli_stmt_bind_param($stmt, "ss", $student_id, $password);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        // 检查登录凭据是否匹配
        if(mysqli_num_rows($result) > 0){
            // 从结果中获取学生ID
            $row = mysqli_fetch_assoc($result);
            $student_id = $row["_id"];

            // 重定向到index.php并携带学生ID
            header("Location: index.php?student_id=" . $student_id);
            exit();
        } else {
            // 重定向到login_false.php
            header("Location: login_false.php");
            exit();
        }

        mysqli_close($conn);
    }
?>

<!DOCTYPE html>
<html>
    <head>
        <title>登录</title>
        <link rel="stylesheet" href="./css/style.css">
    </head>
    <body>
        <div class="edit">
            <h1>学生登录</h1>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <label for="student_id">学生ID：</label>
                <input class="studentId" type="text" name="student_id" id="student_id" required><br><br>

                <label for="password">密码：</label>
                <input class="password" type="password" name="password" id="password" required><br><br>

                <input type="submit" value="登录">
            </form>
        </div>
        <div class="links">
            <p>
                <a href="../student/login.php">学生登录</a>
            </p>
        </div>
    </body>
</html>