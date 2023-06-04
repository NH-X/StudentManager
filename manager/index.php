<?php
$config = file_get_contents("../config.json");
$config = json_decode($config, true);

$conn = mysqli_connect($config['servername'], $config['user'], $config['password'], $config['dbName'], $config['port']);

$selectSQL = "SELECT `student_name`, `password`, `student_id`, `classmsg`, `phone`, `email`, `qq`, `weChat` FROM student";
$stmt = mysqli_prepare($conn, $selectSQL);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manager Homepage</title>
    <link rel="stylesheet" href="./css/style.css"/>
</head>
<body>
    <div class="edit">
        <table>
            <thead>
                <tr>
                    <th>Student Name</th>
                    <th>Password</th>
                    <th>Student ID</th>
                    <th>Class</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>QQ</th>
                    <th>WeChat</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) : ?>
                    <tr>
                        <td><?php echo $row['student_name']; ?></td>
                        <td><?php echo $row['password']; ?></td>
                        <td><?php echo $row['student_id']; ?></td>
                        <td><?php echo $row['classmsg']; ?></td>
                        <td><?php echo $row['phone']; ?></td>
                        <td><?php echo $row['email']; ?></td>
                        <td><?php echo $row['qq']; ?></td>
                        <td><?php echo $row['weChat']; ?></td>
                        <td>
                            <button onclick="showConfirmation('<?php echo $row['student_id']; ?>')">Delete</button>
                            <button onclick="editData('<?php echo $row['student_id']; ?>')">Edit</button>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <div id="confirmationDialog" style="display: none;">
        <p id="confirmationText"></p>
        <button onclick="deleteStudent()">Yes</button>
        <button onclick="cancelDelete()">No</button>
    </div>

    <script>
        var studentIdToDelete;

        function showConfirmation(studentId) {
            studentIdToDelete = studentId;
            var confirmationDialog = document.getElementById("confirmationDialog");
            var confirmationText = document.getElementById("confirmationText");
            confirmationText.innerText = "Are you sure you want to delete student ID: " + studentId + "?";
            confirmationDialog.style.display = "block";
        }

        function deleteStudent() {
            var confirmationDialog = document.getElementById("confirmationDialog");
            confirmationDialog.style.display = "none";

            // Perform deletion operation
            window.location.href = "index.php?delete_id=" + studentIdToDelete;
        }

        function cancelDelete() {
            var confirmationDialog = document.getElementById("confirmationDialog");
            confirmationDialog.style.display = "none";
        }

        function editData(studentId) {
            // Implement logic to edit student data
            // Redirect to edit_student.php or perform AJAX request to fetch and edit the data
            window.location.href = "edit_student.php?student_id=" + studentId;
        }
    </script>
</body>
</html>
