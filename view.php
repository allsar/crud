<?php
if (!isset($_COOKIE['token']) && empty($_COOKIE['token'])) {
    header('Location: /login.php');
}

$id=$_GET['id'];
$db= new mysqli('localhost', 'root', '', 'ecommerce');
$user=$db->query(query: "select id, firstname, image, lastname from users where id=$id")->fetch_assoc();
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
                <h1>View</h1>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-body">
                                <img width="100" height="150" src="./images/<?= $user['image']?>" alt="">
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card">
                            <div class="card-header">
                                <h3>First Name</h3>
                            </div>
                            <div class="card-body">
                                <h4><?= $user['firstname']?></h4>
                            </div>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="card">
                            <div class="card-header">
                                <h3>Last Name</h3>
                            </div>
                            <div class="card-body">
                                <h4><?= $user['lastname']?></h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>

</body>
</html>