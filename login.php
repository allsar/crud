<?php
if (isset($_COOKIE['token']) && !empty($_COOKIE['token'])) {
    header('Location: /index.php');
}
if (isset($_GET['message']) && !empty($_GET['message'])) {
    $message = $_GET['message'];
}
if (($_SERVER['REQUEST_METHOD'] === 'POST') && isset($_POST['login']) && $_POST['login']) {
    if (isset($_POST['phone']) && !empty($_POST['phone'])) {
        $phone = $_POST['phone'];
        if (isset($_POST['password']) && !empty($_POST['password'])) {
            $password = base64_encode($_POST['password']);

            $db = new mysqli('localhost', 'root', '', 'ecommerce');
            $user = $db->query("select * from users where phone = '$phone' and password = '$password'")->fetch_assoc();
            if($db->error){
                die($db->error);
            }
            if($user){
                $id = $user['id'];
                $token =  $db->query("select token from oauth_tokens where user_id = '$id' and expired_at > now()" )->fetch_assoc();
                if ($db->error) {
                    die($db->error);
                }

                if($token) {
                    setcookie('token', $token['token'], time() + 60);
                    header('Location: /index.php');
                }else{
                    $token = bin2hex(random_bytes(127));
                    $date = new DateTime();
                    $date->add(new DateInterval('PT5M'));
                    $expire = $date->format('Y-m-d H:i:s');
                    $db->query("insert into oauth_tokens ( user_id, token, expired_at, created_at) values ('$id', '$token','$expire', now())");

                    if ($db->error) {
                        die($db->error);
                    }
                    setcookie('token', $token, time() + 60);
                }

                header('Location: /index.php');
            }
            else{
                header('Location: /reg.php');
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
            background-color: #3fbd45;
        }
    </style>
</head>
<body>
<?php if (isset($message) && !empty($message)) { ?>
    <div class="alert alert-danger text-center" role="alert">
        <?php echo $message; ?>
    </div>
<?php } ?>
<form method="post" action="/login.php">
    <input type="hidden" name="login" value="1">

    <label for="phone">Phone:</label>
    <input type="phone" id="phone" name="phone" required>

    <label for="password">Password:</label>
    <input type="password" id="password" name="password" required>

    <input type="submit" value="Login">
</form>
</body>
</html>

