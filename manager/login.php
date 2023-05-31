<?php
    // 检查是否提交了表单
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // 获取输入值
        $admin_id = $_POST["admin_id"];
        $password = $_POST["password"];

        // 执行数据库查询以检查登录凭据
        $config = file_get_contents("../config.json");
        $config = json_decode($config, true);

        $conn = mysqli_connect($config['servername'], $config['user'], $config['password'], $config['dbName'], $config['port']);

        $selectSQL = "SELECT `_id`,`admin_id` FROM manager WHERE `admin_id` = ? AND `password` = ?";
        $stmt = mysqli_prepare($conn, $selectSQL);
        mysqli_stmt_bind_param($stmt, "ss", $admin_id, $password);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        // 检查登录凭据是否匹配
        if(mysqli_num_rows($result) > 0){
            // 从结果中获取学生ID
            $row = mysqli_fetch_assoc($result);
            $admin_id = $row["admin_id"];

            // 重定向到index.php并携带学生ID
            header("Location: index.php?admin=" . $admin_id);
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
        <link rel="stylesheet" href="../student/css/style.css">
    </head>
    <body>
        <div class="edit">
            <h1>管理员登录</h1>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <label for="admin_id">账号：</label>
                <input class="username" type="text" name="admin_id" id="admin_id" required><br><br>

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