<?php
// Function to check the file type
function checkFileType($file) {
    $allowedTypes = array("jpg", "jpeg", "png", "gif", "pdf"); // Add more allowed file types if needed
    $fileExtension = strtolower(pathinfo($file, PATHINFO_EXTENSION));
    return in_array($fileExtension, $allowedTypes);
}

// Function to check the file size
function checkFileSize($file, $maxSize) {
    return filesize($file) <= $maxSize;
}

if (isset($_POST["submit"])) {
    $targetDirectory = "uploads/";
    $targetFile = $targetDirectory . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    // Check if the file already exists
    // if (file_exists($targetFile)) {
    //     echo "File already exists.";
    //     $uploadOk = 0;
    // }

    // Check file type
    if (!checkFileType($targetFile)) {
        echo "Sorry, only JPG, JPEG, PNG, GIF, and PDF files are allowed.";
        $uploadOk = 0;
    }

    // Check file size (max size is 5MB)
    $maxFileSize = 5 * 1024 * 1024; // 5MB in bytes
    if (!checkFileSize($_FILES["fileToUpload"]["tmp_name"], $maxFileSize)) {
        echo "File is too large. Maximum size allowed is 5MB.";
        $uploadOk = 0;
    }

    if ($uploadOk == 0) {
        // echo "File upload failed.";
    } else {
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $targetFile)) {
            // File uploaded successfully, now store the file information in MySQL
           
            
            $fileName = basename($_FILES["fileToUpload"]["name"]);

            $sql = "INSERT INTO uploaded_files (file_name, file_type, file_size) VALUES ('$fileName', '$imageFileType', " . filesize($targetFile) . ")";
            include 'connect.php';
            if ($con->query($sql) === TRUE) {
                echo "File uploaded and record inserted into the database successfully.";
            } else {
                echo "Error: " . $sql . "<br>" . $con->error;
            }

            $con->close();
        } else {
            echo "Error uploading file.";
        }
    }
}
?>
