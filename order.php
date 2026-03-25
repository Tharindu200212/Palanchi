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

            /*----------adding products to cart------------*/ 
        if(isset($_POST['add_to_cart'])){
            $product_id = $_POST['product_id'];
            $product_name = $_POST['product_name'];
            $product_category = $_POST['product_category'];
            $product_location = $_POST['product_location'];
            $product_image = $_POST['product_image'];
    
            $cart_number = mysqli_query($conn, "SELECT * FROM `request_service_providers` WHERE name = '$product_name' AND user_id='$user_id'") or die('query failed');
            if(mysqli_num_rows($cart_number) > 0){
                $message[] = 'Product already in cart';
            }
            else{
                mysqli_query($conn, "INSERT INTO `cart`(`user_id`, `pid`, `name`, `price`, `quantity` `image`) VALUES ('$user_id', '$product_id', '$product_name', '$product_price', '$product_quantity', '$product_image')") or die('query failed');
                $message[] = 'Product successfully added into cart';
            }
            header("Location: ".$_SERVER['PHP_SELF']);
            
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
    <title>palanchi</title>
    <link rel="stylesheet" href="style.css">
    </head>

<body>
    <?php include 'header.php'; ?>

        

        <div class="box-container">
            <?php
                $select_products = mysqli_query($conn, "SELECT * FROM `gigs`") or die('query failed');
                if(mysqli_num_rows($select_products) > 0){
                    while($fetch_products = mysqli_fetch_assoc($select_products)){
            ?>
            <div class="box">
                <img src="images/<?php echo $fetch_products['image']; ?>" alt="">
                <h3><?php echo $fetch_products['name']; ?></h3>
                <p><?php echo $fetch_products['product_detail']; ?></p>
                <p>Location: <span><?php echo $fetch_products['location']; ?></span></p>
                <p>Category: <span><?php echo $fetch_products['category']; ?></span></p>
                <a href="cart.php?product_id=<?php echo $fetch_products['id']; ?>" class="btn">--Hire--</a>
            </div>
            <?php
                    }
                } else {
                    echo '<p class="empty">no products added yet!</p>';
                }
            ?>
        </div>
        </div>



	<?php include 'footer.php'; ?>
    <script type="text/javascript" src="script.js"></script>
    </body>
    </html>