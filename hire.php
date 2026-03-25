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
    /*------------adding products to database----------------------*/ 

    if(isset($_POST['submit'])){
        $product_name = mysqli_real_escape_string($conn, $_POST['name']);
        $product_category = mysqli_real_escape_string($conn, $_POST['category']);
        $product_location = mysqli_real_escape_string($conn, $_POST['location']);  
        $product_description = mysqli_real_escape_string($conn, $_POST['detail']);

       

        $select_product_name = mysqli_query($conn, "SELECT name FROM `user_details` WHERE name = '$product_name'") or die('query failed');
        if(mysqli_num_rows($select_product_name) > 0){
            $message[] = 'gig name already exist';
        }else{
            $insert_product = mysqli_query($conn, "INSERT INTO `user_details`(name, category, location, detail) VALUES('$product_name', '$product_category', '$product_location', '$product_description')") or die('query failed');
        }
}


    /*------------deleting products from database----------------------*/ 
        if(isset($_GET['delete'])){
            $delete_id = $_GET['delete'];

            mysqli_query($conn, "DELETE FROM `user_details` WHERE id = '$delete_id'")or die('query failed');
            mysqli_query($conn, "DELETE FROM `requested_services` WHERE pid = '$delete_id'")or die('query failed');
            

            header("Location: ".$_SERVER['PHP_SELF']);

        }




/*------------updating the product----------------------*/
if (isset($_POST['update_product'])) {
    $update_p_id = $_POST['update_p_id'];
    $update_p_name = $_POST['update_p_name'];
    $update_p_category = $_POST['update_p_category'];
    $update_p_location = $_POST['update_p_location'];
    $update_p_description = $_POST['update_p_detail'];
    
    $update_query = mysqli_query($conn, "UPDATE `user_details` SET name='$update_p_name', category='$update_p_category', location='$update_p_location', product_description='$update_p_description' WHERE id='$update_p_id'") or die('query failed');



    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="style.css"> 
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

    <title>Document</title>
</head>
<body>
<?php include 'header.php'; ?>



<?php
        if(isset($message)){
            foreach ($message as $message){
                echo '    <div class="message">
        <span>'.$message.'</span>
<i class="bi bi-x-circle" onclick="this.parentElement.remove()"></i>
    </div>';
            }
        }
    ?>

    <section class="add-products">
        <form method="post" action="" enctype="multipart/form-data">
            <h1 class="title">Data Collect Form</h1>
            <div class="input-field">
                <label>Full name</label>
                <input type="text" name="name" required>
            </div>

            <div class="input-field">
                <label for="category">Select Category</label>
                <select name="category" id="category" required>
                    <option value="">-- Select a category --</option>
                    <option value="Electrical">Electrical</option>
                    <option value="Plumbing">Plumbing</option>
                    <option value="Painting">Painting</option>
                    <option value="Carpentry">Carpentry</option>
                    <!-- Add more categories as needed -->
                </select>
            </div>
            <div class="input-field">
                <label for="category">Select</label>
                <select name="category" id="category" required>
                    <option value="Indoor">Indoor</option>
                    <option value="Outdoor">Outdoor</option>
                    <option value="Both">Both</option>
                </select>
            </div>        
 
            <div class="input-field">
                <label>location</label>
                <input type="text" name="location" required>
            </div>

            <div class="input-field">
                <label>Details</label>
                <textarea name="detail" required></textarea>
            </div>

            <input type="submit" name="submit" value="Submit" class="btn" >
        </form> 


    </section>
    <!---------------show product section---------------->
    
</section>
<script type="text/javascript" src="script.js"></script>
</body>
</html>