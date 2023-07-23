<?php
header("Access-Control-Allow-Origin: http://localhost:4200");
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

//include 'database.php';

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    // ตรวจสอบเมื่อมีการส่งคำขอตัวอย่างสำหรับตรวจสอบ CORS
    // ตอบกลับเฉพาะ header และส่งออกจากไฟล์นี้เท่านั้น
    header('Content-Length: 0');
    header('Content-Type: text/plain');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $content = file_get_contents('php://input');
    $dataFrm = json_decode($content, true);

    $std_id = $dataFrm['std_id'];
    $std_password = $dataFrm['std_password'];

    $hostAuth = "localhost";
    $userAuth = "root";
    $passAuth = "";
    $database = "studycheck";

    $link = new mysqli($hostAuth, $userAuth, $passAuth, $database);

    if ($link->connect_error) {
        die("Connection failed: " . $link->connect_error);
    } else {
        mysqli_set_charset($link, "utf8");
    }

    $sql = "SELECT std_id , std_name , std_prefix , std_lastname , std_phone , std_email
                          , pro_id ,pro_name ,  fac_id , fac_name
     FROM student WHERE std_id = $std_id AND std_password = '$std_password'";

    if ($result = $link->query($sql)) {
        if ($result->num_rows > 0) {
          $row = $result->fetch_assoc();
            echo json_encode($row);
        } else {
            echo json_encode('Login failed');
        }
        $result->close();
    } else {
        echo json_encode('Error querying the database');
    }

    $link->close();
}
?>
