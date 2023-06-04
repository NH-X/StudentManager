<?php
// Check if student ID is provided
if (isset($_GET['student_id'])) {
    $studentId = $_GET['student_id'];

    // Execute database query to retrieve student information
    $config = file_get_contents("../config.json");
    $config = json_decode($config, true);

    $conn = mysqli_connect($config['servername'], $config['user'], $config['password'], $config['dbName'], $config['port']);

    // Prepare and execute the SELECT query
    $selectSQL = "SELECT * FROM student WHERE `student_id` = ?";
    $stmt = mysqli_prepare($conn, $selectSQL);
    mysqli_stmt_bind_param($stmt, "s", $studentId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    // Check if student exists
    if (mysqli_num_rows($result) > 0) {
        $student = mysqli_fetch_assoc($result);
        mysqli_free_result($result);
    } else {
        // Redirect to an error page if student does not exist
        mysqli_close($conn);
        header("Location: error.php");
        exit();
    }

    // Update student information if the form is submitted
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        // Retrieve the updated information from the form
        $password = $_POST["password"];
        $phone = $_POST["phone"];
        $email = $_POST["email"];
        $qq = $_POST["qq"];
        $weChat = $_POST["weChat"];

        // Prepare and execute the UPDATE query
        $updateSQL = "UPDATE student SET ";
        $params = array();
        $types = "";

        if (!empty($phone)) {
            $updateSQL .= "`phone` = ?, ";
            $params[] = $phone;
            $types .= "s";
        }

        if (!empty($email)) {
            $updateSQL .= "`email` = ?, ";
            $params[] = $email;
            $types .= "s";
        }

        if (!empty($qq)) {
            $updateSQL .= "`qq` = ?, ";
            $params[] = $qq;
            $types .= "s";
        }

        if (!empty($weChat)) {
            $updateSQL .= "`weChat` = ?, ";
            $params[] = $weChat;
            $types .= "s";
        }

        $updateSQL = rtrim($updateSQL, ", "); // Remove trailing comma and space

        $updateSQL .= " WHERE `student_id` = ?";
        $params[] = $studentId;
        $types .= "s";

        $stmt = mysqli_prepare($conn, $updateSQL);
        mysqli_stmt_bind_param($stmt, $types, ...$params);
        mysqli_stmt_execute($stmt);

        // Check if the update was successful
        if (mysqli_affected_rows($conn) > 0) {
            // Redirect to the updated student homepage
            mysqli_close($conn);
            header("Location: index.php?student_id=" . $studentId);
            exit();
        } else {
            // Redirect to an error page if the update failed
            mysqli_close($conn);
            header("Location: error.php");
            exit();
        }
    }

    mysqli_close($conn);
} else {
    // Redirect to an error page if student ID is not provided
    header("Location: error.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Student Homepage</title>
    <link rel="stylesheet" href="./css/style.css"/>
</head>
<body>
    <div class="edit">
        <h1>Welcome, <?php echo $student['student_name']; ?></h1>
        <p>Student ID: <?php echo $student['student_id']; ?></p>
        <p>Class: <?php echo $student['classmsg']; ?></p>

        <!-- Update Form -->
        <form method="post" action="">

            <label for="phone">Phone:</label>
            <input type="text" name="phone" id="phone" value="<?php echo $student['phone']; ?>">
            <br>

            <label for="email">Email:</label>
            <input type="email" name="email" id="email" value="<?php echo $student['email']; ?>">
            <br>

            <label for="qq">QQ:</label>
            <input type="text" name="qq" id="qq" value="<?php echo $student['qq']; ?>">
            <br>

            <label for="weChat">WeChat:</label>
            <input type="text" name="weChat" id="weChat" value="<?php echo $student['weChat']; ?>">
            <br>

            <input type="submit" value="Update">
        </form>
    </div>
</body>
</html>
