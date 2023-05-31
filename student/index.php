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
        $classmsg = $_POST["classmsg"];
        $phone = $_POST["phone"];
        $email = $_POST["email"];
        $qq = $_POST["qq"];
        $weChat = $_POST["weChat"];

        // Prepare and execute the UPDATE query
        $updateSQL = "UPDATE student SET `password` = ?, `classmsg` = ?, `phone` = ?, `email` = ?, `qq` = ?, `weChat` = ? WHERE `student_id` = ?";
        $stmt = mysqli_prepare($conn, $updateSQL);
        mysqli_stmt_bind_param($stmt, "sssssss", $password, $classmsg, $phone, $email, $qq, $weChat, $studentId);
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
        <div>
            <h1>Welcome, <?php echo $student['student_name']; ?></h1>
            <p>Student ID: <?php echo $student['student_id']; ?></p>
            <p>Class: <?php echo $student['classmsg']; ?></p>

            <!-- Update Form -->
            <form method="post" action="">
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" value="<?php echo $student['password']; ?>" required>

                <label for="classmsg">Class:</label>
                <input type="text" name="classmsg" id="classmsg" value="<?php echo $student['classmsg']; ?>" required disabled>

                <label for="phone">Phone:</label>
                <input type="text" name="phone" id="phone" value="<?php echo $student['phone']; ?>" required>

                <label for="email">Email:</label>
                <input type="email" name="email" id="email" value="<?php echo $student['email']; ?>" required>

                <label for="qq">QQ:</label>
                <input type="text" name="qq" id="qq" value="<?php echo $student['qq']; ?>" required>

                <label for="weChat">WeChat:</label>
                <input type="text" name="weChat" id="weChat" value="<?php echo $student['weChat']; ?>" required>

                <input type="submit" value="Update">
            </form>
        </div>
    </body>
</html>
