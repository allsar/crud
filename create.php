<?php
if (!isset($_COOKIE['token']) && empty($_COOKIE['token'])) {
    header('Location: /login.php');
}

if (isset($_POST['create']) && $_POST['create']) {
    if (isset($_POST['name']) && !empty($_POST['name'])) {
        $name = $_POST['name'];
        if (isset($_POST['lastname']) && !empty($_POST['lastname'])) {
            $lastname = $_POST['lastname'];
            if (isset($_POST['phone']) && !empty($_POST['phone'])) {
                $phone = $_POST['phone'];
                if (isset($_POST['password']) && !empty($_POST['password'])) {
                    $password = $_POST['password'];
                    if (isset($_FILES['images']) && !empty($_FILES['images']) && $_FILES['images']['error'] === 0) {
                        $images = $_FILES['images'];
                        $images_name = $images['name'];
                        $format = pathinfo($images_name, PATHINFO_EXTENSION);
                        $newImageName = uniqid('', true) . '.' . $format;
                        $path = './images/' . $newImageName;
                        if (in_array($format, ['jpg', 'png', 'gif', 'bmp', 'jpeg'])) {
                            if ($images['size'] < 500000) {
                                if (move_uploaded_file($images['tmp_name'], $path)) {
                                    $db = new mysqli('localhost', 'root', '', 'ecommerce');
                                    $db->query("insert into users (image, firstname, lastname, password, phone) values ('$newImageName','$name', '$lastname', '$password', '$phone' ) ");
                                    if ($db->error){
                                        die($db->error);
                                    }
                                    header('Location: /index.php');

                                } else {
                                    die("file not uploaded");
                                }
                            } else {
                                die('Image size is too big');
                            }
                        } else {
                            die('format not supported');
                        }
                    } else {
                        die('Image is not selected');
                    }
                } else {
                    die('Password is empty');
                }
            } else {
                die("phone is required");
            }
        } else {
            die("lastname is empty");
        }
    } else {
        die('Name is required');
    }
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">
    <title>Document</title>
</head>
<body>
<div class="container">
    <div class="row m-5">
        <form action="/create.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="create" value="1">
            <div class="form-group">
                <label for="file">Image</label>
                <input type="file" class="form-control" id="file" name="images" placeholder="Upload image">
            </div>
            <div class="form-group">
                <label for="firstname">First Name</label>
                <input type="text" class="form-control" id="firstname" name="name" placeholder="Enter First Name">
            </div>
            <div class="form-group">
                <label for="lastname">Last Name</label>
                <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Enter Last Name">
            </div>
            <div class="form-group">
                <label for="firstname">phone</label>
                <input type="number" class="form-control" id="phone" name="phone" placeholder="Enter  Phone">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Enter Password">
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</div>

</body>

</html>
