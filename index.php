<?php
if (!isset($_COOKIE['token']) && empty($_COOKIE['token'])) {
    header('Location: /login.php');
}

$db = new mysqli('localhost', 'root', '', 'ecommerce');
$users = $db->query(query: 'select id, firstname, image , lastname from users order by id desc limit 10')->fetch_all(MYSQLI_ASSOC);
if (isset($_POST['delete']) && $_POST['delete']) {
    $id = $_POST['id'];
    $db->query("delete from users where id=$id");
    if ($db->error) {
        echo $db->error;
        die();
    }

    header("Location: /index.php");
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
    <style>
        td {
            text-align: center;
            padding: 5px;
        }
    </style>
    <title>Document</title>
</head>
<body>

<div class="container">
    <div class="row m-5">
        <div class="create-btn">
            <a class="btn btn-success" href="./create.php">Create</a>
        </div>
        <table class="table-bordered">
            <thead>
            <tr>
                <th scope="col">ID</th>
                <th scope="col">First Name</th>
                <th scope="col">Last Name</th>
                <th scope="col">images</th>
                <th scope="col">Action</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($users as $user) {
                ?>
                <tr>
                    <th scope="row"><?php echo $user['id'] ?></th>
                    <td><?= $user['firstname'] ?></td>
                    <td><?= $user['lastname'] ?></td>
                    <td><img width="50px" height="50px" src="./images/<?= $user['image'] ?>" alt=""></td>
                    <td>
                        <a href="view.php?id=<?= $user['id'] ?>" class="btn btn-primary">View</a>
                        <a href="edit.php?id=<?= $user['id'] ?> " class="btn btn-secondary">Edit</a>
                        <form action="index.php" method="post" class="d-inline-block">
                            <input type="hidden" name="id" value="<?= $user['id'] ?>">
                            <input type="hidden" name="delete" value="1">
                            <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
