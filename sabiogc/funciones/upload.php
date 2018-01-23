<?php
function subirArchivo($archivo, $ruta) {

    $target_dir = $ruta;
    $target_file = $target_dir . basename($_FILES["upload"]["name"]);
    $uploadOk = 1;
    $fileType = pathinfo($target_file,PATHINFO_EXTENSION);

    // Check if file already exists
    if (file_exists($target_file)) {
        //echo "Sorry, file already exists.";
        $uploadOk = 0;
    }

    // Check file size
    if ($_FILES["upload"]["size"] > 500000) {
        //echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    /*// Allow certain file formats
    if($fileType != "txt") {
        //echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }*/

    if ($uploadOk == 1) {
        chmod($_FILES["upload"]["tmp_name"], 777);
        move_uploaded_file($_FILES["upload"]["tmp_name"], $target_dir.$archivo);
    }

}