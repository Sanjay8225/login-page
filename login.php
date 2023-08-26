<?php
session_start();


$hostname = "localhost";
$username = "root";
$password = "";
$db_name = "database";

$conn = new mysqli($hostname, $username, $password, $db_name);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST["username"];
    $password = $_POST["password"];
    $resumeName = $_FILES["resume"]["name"];
    $resumeType = $_FILES["resume"]["type"];
    $resumeData = file_get_contents($_FILES["resume"]["tmp_name"]);
    $insertQuery = "INSERT INTO users (username, password) VALUES ('$username', '$password')";
    if ($conn->query($insertQuery) === TRUE) {
        $userID = $conn->insert_id;

        $insertResumeQuery = "INSERT INTO resumes (user_id, resume_name, resume_type, resume_data) VALUES ($userID, '$resumeName', '$resumeType', ?)";
        $stmt = $conn->prepare($insertResumeQuery);
        $stmt->bind_param("s", $resumeData);
        $stmt->execute();
        $stmt->close();

        $_SESSION["user_id"] = $userID; 
        header("Location: dashboard.php"); 
    } else {
        echo "Error: " . $conn->error;
    }
}

$conn->close();
?>
