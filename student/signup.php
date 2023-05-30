<!DOCTYPE html>
<html>
    <head>
        <title>注册</title>
        <link rel="stylesheet" href="./css/style.css">
    </head>
    <body>
        <div class="edit">
            <h1>注册</h1>
            <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                <label for="student_name">姓名：</label>
                <input type="text" name="student_name" id="student_name" required>
                <span class="required">*</span><br><br>

                <label for="password">密码：</label>
                <input type="password" name="password" id="password" required>
                <span class="required">*</span><br><br>

                <label for="student_id">学生ID：</label>
                <input type="text" name="student_id" id="student_id" required>
                <span class="required">*</span>

                <label for="classmsg">班级：</label>
                <input type="text" name="classmsg" id="classmsg" required>
                <span class="required">*</span><br><br>

                <label for="phone">电话：</label>
                <input type="text" name="phone" id="phone"><br><br>

                <label for="email">邮箱：</label>
                <input type="text" name="email" id="email"><br><br>

                <label for="qq">QQ：</label>
                <input type="text" name="qq" id="qq"><br><br>

                <label for="weChat">微信：</label>
                <input type="text" name="weChat" id="weChat"><br><br>

                <input type="submit" name="submit" value="注册" onclick="return validateForm()">
                <input type="submit" value="返回登录" onclick="location.href='login.php'">
            </form>
        </div>

        <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                // 获取用户输入的值
                $studentName = $_POST["student_name"];
                $password = $_POST["password"];
                $studentId = $_POST["student_id"];
                $classmsg = $_POST["classmsg"];
                $phone = $_POST["phone"];
                $email = $_POST["email"];
                $qq = $_POST["qq"];
                $weChat = $_POST["weChat"];

                // 进行数据库保存操作
                $config = file_get_contents("../config.json");
                $config = json_decode($config, true);

                $conn = mysqli_connect($config['servername'], $config['user'], $config['password'], $config['dbName'], $config['port']);

                $selectSQL = "SELECT `_id` FROM student WHERE `student_id` = ? AND `password` = ?";
                $stmt = $conn->prepare($selectSQL);
                $stmt->bind_param("ss", $studentId, $password);
                $stmt->execute();
                $result = $stmt->get_result();
                $stmt->close();

                if ($result->num_rows > 0) {
                    // 学生已存在，跳转到注册失败页面
                    header("Location: signup_false.php");
                    exit();
                } else {
                    $insertSQL = "INSERT INTO student (
                        `student_name`, `password`,
                        `student_id`, `classmsg`,
                        `phone`, `email`,
                        `qq`, `weChat`
                    ) VALUES (
                        ?, ?, ?, ?, ?, ?, ?, ?
                    )";
                    $stmt = $conn->prepare($insertSQL);
                    $stmt->bind_param("ssssssss", $studentName, $password, $studentId, $classmsg, $phone, $email, $qq, $weChat);
                    $stmt->execute();
                    $stmt->close();

                    // 注册成功后显示欢迎弹窗
                    echo "<script>alert('欢迎你，{$studentName} 同学！');</script>";
                }
                $conn->close();
            }
        ?>

        <script>
            function validateForm() {
                // 获取输入框的值
                var studentName = document.getElementById("student_name").value;
                var password = document.getElementById("password").value;
                var studentId = document.getElementById("student_id").value;
                var classmsg = document.getElementById("classmsg").value;

                // 非空项验证
                if (studentName === "" || password === "" || studentId === "" || classmsg === "") {
                    alert("请填写必填项！");
                    return false;
                }
            }
        </script>
    </body>
</html>
