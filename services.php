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
        <h1>Services</h1>
        <p>Palanchi connects you with skilled service providers across every industry making it easy to find trusted professionals who turn your everyday needs into exceptional experiences. </p>
    </div>

    <div class="shop">
        <h1 class="title">Services</h1>
        <?php
          if (isset($message)) {
            foreach ($message as $msg) {
                echo '
                    <div class="message">
                        <span>' . htmlspecialchars($msg) . '</span>
                        <i onclick="this.parentElement.style.display=\'none\'">&times;</i>
                    </div>
                ';
            }
        }
        ?>

        <div class="box-container">
            <?php
                $select_products = mysqli_query($conn, "SELECT * FROM `gigs`") or die('query failed');
                if (mysqli_num_rows($select_products) > 0) {
                    while ($fetch_products = mysqli_fetch_assoc($select_products)) {
            ?>
            <form action="" method="post" class="box">
                <a href="view_page.php?pid=<?php echo $fetch_products['id']; ?>">
                    <img src="images/<?php echo $fetch_products['image']; ?>" alt="Product Image">
                </a>

                <div class="name"><?php echo $fetch_products['name']; ?></div>
                <div class="location">Location: <?php echo $fetch_products['location']; ?></div>
                <div class="category">Category: <?php echo $fetch_products['category']; ?></div>

                <input type="hidden" name="product_id" value="<?php echo $fetch_products['id']; ?>">
                <input type="hidden" name="product_name" value="<?php echo $fetch_products['name']; ?>">
                <input type="hidden" name="product_detail" value="<?php echo $fetch_products['product_detail']; ?>">
                <input type="hidden" name="product_category" value="<?php echo $fetch_products['category']; ?>">
                <input type="hidden" name="product_location" value="<?php echo $fetch_products['location']; ?>">
                <input type="hidden" name="product_quantity" value="1">
                <input type="hidden" name="product_image" value="<?php echo $fetch_products['image']; ?>">

                
            </form>
            <?php
                    }
                } else {
                    echo '<p class="empty">No products yet</p>';
                }
            ?>
        </div>
    </div>

    <?php include 'footer.php'; ?>
    <script type="text/javascript" src="script.js"></script>
</body>
</html>
