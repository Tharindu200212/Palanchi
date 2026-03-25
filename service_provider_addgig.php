<?php
    include 'connection.php';
    session_start();

    $service_provider_id = $_SESSION['service_provider_id'];
    if(!isset($service_provider_id)){
        header('location:login.php');
    }
    if(isset($_POST['logout'])){
        session_destroy();
        header('location:login.php');
    }
    /*------------adding products to database----------------------*/ 

    if(isset($_POST['add_product'])){
        $product_name = mysqli_real_escape_string($conn, $_POST['name']);
        $product_category = mysqli_real_escape_string($conn, $_POST['category']);
        $product_location = mysqli_real_escape_string($conn, $_POST['location']);  
        $product_detail = mysqli_real_escape_string($conn, $_POST['detail']);
        $image = $_FILES['image']['name'];
        $image_size = $_FILES['image']['size'];
        $image_tmp_name = $_FILES['image']['tmp_name'];
        $image_folder = 'images/'.$image;

        $select_product_name = mysqli_query($conn, "SELECT name FROM `gigs` WHERE name = '$product_name'") or die('query failed');
        if(mysqli_num_rows($select_product_name) > 0){
            $message[] = 'gig name already exist';
        }else{
            $insert_product = mysqli_query($conn, "INSERT INTO `gigs`(name, category, location, product_detail, image) VALUES('$product_name', '$product_category', '$product_location', '$product_detail', '$image')") or die('query failed');
            if($insert_product){
                if($image_size > 20000000){
                    $message[] = 'gig image is too large';
                }
                else{
                    move_uploaded_file($image_tmp_name, $image_folder);
                    $message[] = 'gig added successfully';
                }
            }
        }
}


    /*------------deleting products from database----------------------*/ 
        if(isset($_GET['delete'])){
            $delete_id = $_GET['delete'];
            $select_delete_image = mysqli_query($conn, "SELECT image from `gigs` WHERE id = $delete_id") or die('query failed');
            $fetch_delete_image = mysqli_fetch_assoc($select_delete_image);
            unlink('images/'.$fetch_delete_image['image']);

            mysqli_query($conn, "DELETE FROM `gigs` WHERE id = '$delete_id'")or die('query failed');
            mysqli_query($conn, "DELETE FROM `requested_services` WHERE pid = '$delete_id'")or die('query failed');
            

            header('location:service_provider_addgig.php');

        }




/*------------updating the product----------------------*/
if (isset($_POST['update_product'])) {
    $update_p_id = $_POST['update_p_id'];
    $update_p_name = $_POST['update_p_name'];
    $update_p_category = $_POST['update_p_category'];
    $update_p_location = $_POST['update_p_location'];
    $update_p_detail = $_POST['update_p_detail'];
    $update_p_img = $_FILES['update_p_image']['name'];
    $update_p_img_tmp_name = $_FILES['update_p_image']['tmp_name'];
    $update_p_img_folder = 'images/' . $update_p_img;

    $update_query = mysqli_query($conn, "UPDATE `gigs` SET id='$update_p_id', name='$update_p_name', category='$update_p_category', location='$update_p_location', product_detail='$update_p_detail', image='$update_p_img' WHERE id='$update_p_id'") or die('query failed');

    if ($update_query) {
        move_uploaded_file($update_p_img_tmp_name, $update_p_img_folder);
        echo 'gig updated successfully!';
    } else {
        echo 'Failed to update gig!';
    }

    header('Location:service_provider_addgig.php');
    // exit();
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
<?php include 's_header.php'; ?>



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
            <h1 class="title">add new Gig</h1>
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
                <label>location</label>
                <input type="text" name="location" required>
            </div>

            <div class="input-field">
                <label>Gig detail</label>
                <textarea name="detail" required></textarea>
            </div>

            <div class="input-field">
                <label>Add image</label>
                <input type="file" name="image" accept="image/jpg, image/png, image/jpeg, image/webp" required>
            
            </div>

            <input type="submit" name="add_product" value="add_product" class="btn" >
        </form> 


    </section>
    <!---------------show product section---------------->
    <section class="show-products">
    <div class="box-container">
        <?php
        $select_products = mysqli_query($conn, "SELECT * FROM `gigs`") or die('query failed');
        if(mysqli_num_rows($select_products) > 0){
            while($fetch_products = mysqli_fetch_assoc($select_products)){
        ?>
        <div class="box">
            <img src="images/<?php echo $fetch_products['image']; ?>" >
            <h4><?php echo $fetch_products['name']; ?></h4>
            <p class="category">category : <span><?php echo $fetch_products['category']; ?></span></p>
            <p class="location">location : <span><?php echo $fetch_products['location']; ?></span></p>
            <p class="detail"><?php echo $fetch_products['product_detail']; ?></p>
            <a href="service_provider_addgig.php?edit=<?php echo $fetch_products['id']; ?>" class="edit">edit</a>
            <a href="service_provider_addgig.php?delete=<?php echo $fetch_products['id']; ?>" class="delete" onclick="return confirm('delete this product');">delete</a>
        </div>
        <?php
            }
        }
        ?>
    </div>
</section>
<section class="update-container">
        <?php 
            if(isset($_GET['edit'])){
                $edit_id = $_GET['edit'];
                $edit_query = mysqli_query($conn, "SELECT * FROM `gigs` WHERE id = '$edit_id'")or die('query failed');
                if(mysqli_num_rows($edit_query)>0){
                    while($fetch_edit = mysqli_fetch_assoc($edit_query)){
        ?>
        <form method="post" action="" enctype="multipart/form-data">
            <img src="images/<?php echo $fetch_edit['image'];?>">
            <input type="hidden" name="update_p_id" value="<?php echo $fetch_edit['id'];?>">
            <input type="text" name="update_p_name" value="<?php echo $fetch_edit['name'];?>">
            <input type="number" min="0" name="update_p_price" value="<?php echo $fetch_edit['price'];?>">

            <select name="update_p_category" id="category" required>
                <option value="<?php echo $fetch_edit['category'];?>"><?php echo $fetch_edit['category'];?></option>
                <option value="Electrical">Electrical</option>
                <option value="Plumbing">Plumbing</option>
                <option value="Painting">Painting</option>
                <option value="Carpentry">Carpentry</option>
                <!-- Add more categories as needed -->
            </select>
            <input type="text" name="update_p_location" value="<?php echo $fetch_edit['location'];?>"> 
            <textarea name="update_p_detail" ><?php echo $fetch_edit['product_detail'];?></textarea>
            <input type="file" name="update_p_image" accept="image/jpg, image/png, image/jpeg, image/webp">
            <input type="submit" name="update_product" value="update" class="edit">
            <input type="reset" value="cancel" class="option-btn btn" id="close-edit">
        </form> 
        <?php               
                    }
                }
                echo "<script>document.querySelector('.update-container').style.display='block';</script>";
            }
        ?>
</section>
<script type="text/javascript" src="script.js"></script>
</body>
</html>