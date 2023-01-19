<?php

if (isset($_COOKIE['token']) && !empty($_COOKIE['token'])) {
    header('Location: /index.php');
}
if (($_SERVER['REQUEST_METHOD'] === 'POST') && isset($_POST['register']) && $_POST['register']) {
    if (isset($_POST['firstname']) && !empty($_POST['firstname'])) {
        $firstname = $_POST['firstname'];
        if (isset($_POST['phone']) && !empty($_POST['phone'])) {
            $phone = $_POST['phone'];
            if (isset($_POST['password']) && !empty($_POST['password'])) {

                $password = base64_encode($_POST['password']);
                $token = bin2hex(random_bytes(127));
                $db = new mysqli('localhost', 'root', '', 'ecommerce');

                $user= $db->query("select * from users where phone = '$phone'")->fetch_assoc();
                if ($db->error) {
                    die($db->error);
                }
                if($user){
                    $message =  "User already exists";
                    sleep(5);
                    header('Location: /login.php?message='.$message);
                }
                $db->query("insert into users (firstname, password, phone) values ('$firstname', '$password', '$phone')");
                if ($db->error) {
                    die($db->error);
                }
                $user = $db->query("select * from users where phone = '$phone'")->fetch_assoc();
                $user_id = $user['id'];
                $date = new DateTime();
                $date->add(new DateInterval('PT5M'));
                $expire = $date->format('Y-m-d H:i:s');
                $db->query("insert into oauth_tokens ( user_id, token, expired_at, created_at) values ('$user_id', '$token','$expire', now())");

                if ($db->error) {
                    die($db->error);
                }

                //set cookie add 1 minute
                setcookie('token', $token, time() + 60);
                header('Location: /index.php');
            }

        }
    }

}


?>
<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css">

    <title>Registration Form</title>
    <style>
        form {
            width: 300px;
            margin: 20% auto;

        }

        label {
            display: block;
            margin-bottom: 8px;
        }

        input[type="text"], input[type="phone"], input[type="password"] {
            width: 100%;
            padding: 12px 20px;
            margin-bottom: 20px;
            box-sizing: border-box;
            border: 2px solid #ccc;
            border-radius: 4px;
        }

        input[type="submit"] {
            width: 100%;
            background-color: #4CAF50;
            color: white;
            padding: 14px 20px;
            margin-bottom: 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
<form method="post" action="/reg.php">
    <?php if (isset($message) && !empty($message)) { ?>
        <div class="alert alert-danger" role="alert">
            <?php echo $message; ?>
        </div>
    <?php } ?>
    <input type="hidden" name="register" value="1">
    <label for="name">Name:</label>
    <input type="text" id="name" name="firstname" required>

    <label for="phone">Phone:</label>
    <input type="phone" id="phone" name="phone" required>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>

    <input type="submit" value="Register">
</form>
</body>
</html>

