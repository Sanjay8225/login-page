<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login1.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "database");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$userID = $_SESSION["user_id"];

$sql = "SELECT resume_name, resume_type, resume_data FROM resumes WHERE user_id = $userID";
$result = $conn->query($sql);

if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $resumeName = $row["resume_name"];
    $resumeType = $row["resume_type"];
    $resumeData = $row["resume_data"];
} else {
    echo "Resume not found.";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            height: 100vh;
        }

        .dashboard-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.2);
            width: 800px;
            max-width: 90%;
        }

        .dashboard-container h1, .dashboard-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .resume-embed {
            width: 100%;
            max-width: 800px;
            height: 1000px;
            border: none;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h1 align="center">Welcome to Your Dashboard</h1>
        <h2>Your Resume:</h2>
        <iframe class="resume-embed" src="data:<?php echo $resumeType; ?>;base64,<?php echo base64_encode($resumeData); ?>"></iframe>
    </div>
</body>
</html>

