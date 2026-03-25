<?php
    include 'connection.php';
    session_start();

    $user_id = $_SESSION['user_id'];
    if(!isset($user_id)){
        header('location:login.php');
    }
    if(isset($_POST['logout'])){
        session_destroy();
        header('location:login.php');
    }

    /*---------------------------- Adding products to cart ----------------*/
    if(isset($_POST['add_to_cart'])){
        $product_id = $_POST['product_id'];
        $product_name = $_POST['product_name'];
        $product_detail = $_POST['product_detail'];
        $product_category = $_POST['product_category'];
        $product_location = $_POST['product_location'];
        $product_image = $_POST['product_image'];

        $cart_number = mysqli_query($conn, "SELECT * FROM `requested_services` WHERE name='$product_name' AND user_id='$user_id'") or die('query failed');

        if(mysqli_num_rows($cart_number) > 0){
            $message[] = 'Product already exists in cart';
        } else {
            mysqli_query($conn, "INSERT INTO `requested_services`(`user_id`, `pid`, `name`, `category`, `location`, `image`) VALUES('$user_id', '$product_id', '$product_name', '$product_category', '$product_location', '$product_image')") or die('query failed');
            $message[] = 'Product successfully added to cart';
        }
    }
 ?>

<style type="text/css">
    <?php include 'main.css'; ?>

   
</style>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <title>palanchi</title>
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="banner-shop">
        <h1>palanchi</h1>
        <p>about us. </p>
    </div>
    <?php include 'footer.php'; ?>
    <script type="text/javascript" src="script.js"></script>
</body>
</html>