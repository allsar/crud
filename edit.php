<?php
$db = new mysqli('localhost', 'root', '', 'ecommerce');
if (isset($_POST['edit']) && $_POST['edit']) if (isset($_POST['firstname']) && !empty($_POST['firstname'])) {
/*asdasdadasd*/
    $firstname = $_POST['firstname'];
    if (isset($_POST['lastname']) && !empty($_POST['lastname'])) {
        $lastname = $_POST['lastname'];
        if (isset($_FILES['images']) && !empty($_FILES['images']) && $_FILES['images']['error'] === 0) {
            $user_id = $_POST['id'];
            $images = $_FILES['images'];
            $images_name = $images['name'];
            $format = pathinfo($images_name, PATHINFO_EXTENSION);
            $newImageName = uniqid('', true) . '.' . $format;
            $path = './images/' . $newImageName;
            if (in_array($format, ['jpg', 'png', 'gif', 'bmp', 'jpeg'])) {
                if ($images['size'] < 500000) {
                    if (move_uploaded_file($images['tmp_name'], $path)) {
                        $db = new mysqli('localhost', 'root', '', 'ecommerce');
                        $db->query(query: "update users set firstname ='$firstname', lastname = '$lastname', image='$newImageName' where id = $user_id");
                        if ($db->error) {
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
        die("lastname is empty");
    }
} else {
    die('Name is required');
}

$id = (int)$_GET['id'];
$user = $db->query(query: "select id, firstname, lastname , image from users where id=$id")->fetch_assoc();
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
    <div class="row">
        <div class="card">
            <div class="card-header">
                <h1>Edit</h1>
            </div>
            <form action="edit.php" method="post" enctype="multipart/form-data">
                <input type="hidden" name="id" value="<?= $user['id'] ?>">
                <input type="hidden" name="edit" value="1">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <div class="card">
                                <div class="card-header">
                                    <h3>First Name</h3>
                                </div>
                                <div class="card-body">
                                    <input type="text" name="firstname" value="<?= $user['firstname'] ?>">
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card">
                                <div class="card-header">
                                    <h3>Last Name</h3>
                                </div>
                                <div class="card-body">
                                    <input type="text" name="lastname" value="<?= $user['lastname'] ?>">
                                </div>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="card">
                                <div class="card-header">
                                    <h3>Image</h3>
                                </div>
                                <div class="card-body">
                                    <input type="file" name="images" value="<?= $user['image'] ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card_footer">
                    <button type="submit" class="btn btn-success">save</button>
                </div>
            </form>

        </div>

    </div>
</div>

</body>
</html>

