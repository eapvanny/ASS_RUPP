<?php
include "db_conn.php";

$pro_code = null;
$pro_code_error = null;
$pro_name = null;
$pro_name_error = null;
$pro_price = null;
$pro_price_error = null;
$pro_img_error = null;
$pro_img = null;

if (isset($_POST['submit'])) {
    $pro_name = $_POST['name'];
    $pro_code = $_POST['code'];
    $pro_price = $_POST['price'];
    $des = $_POST['des'];
    // File upload handling
    $pro_img = $_FILES['photo']['name'];
    $temp_name = $_FILES['photo']['tmp_name'];
    $folder = "img/";
    // Move uploaded file to the designated folder
    move_uploaded_file($temp_name, $folder . $pro_img);

    // Check for empty fields
    if (empty(trim($pro_code))) {
        $pro_code_error = "Field code is empty";
    } if (empty(trim($pro_name))) {
        $pro_name_error = "Field name is empty";
    } if (empty(trim($pro_price))) {
        $pro_price_error = "Field price is empty";
    } if (empty(trim($pro_img))) {
        $pro_img_error = "Field photo is empty";
    } else {
        // If no empty fields, proceed with insertion
        $sql = "INSERT INTO `products`(`id`, `product_name`, `product_price`, `product_image`, `product_code`,`des`)
                VALUES (NULL, '$pro_name', '$pro_price', '$pro_img', '$pro_code','$des')";
        $result = mysqli_query($conn, $sql);
        if ($result) {
            header("Location: index.php?msg=New record created successfully");
            // Reset field values after successful insertion
            // $pro_name = null;
            // $pro_code = null;
            // $pro_price = null;
            // $pro_img = null;
        } else {
            echo "Error: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="style/style.css">
    <?php
    if ($pro_code_error != null || $pro_name_error != null || $pro_price_error != null || $pro_img_error != null) {
        ?>
        <style>
            .error-filed {
                display: block;
            }
        </style>
    <?php
    }
    ?>
    <title>Add New Product</title>
</head>

<body>

    <div class="container">
            <div class="text-center">
                <h3> Add New Product </h3>
                <p class="text-muted">Compile the form below to add a new product</p>
            </div>
            <div class="container d-flex justify-content-center">
                <form action="" method="post" enctype="multipart/form-data" style="width: 50vw; min-width: 300px;">
                    <div class="row">
                        <div class="col">
                            <label class="form-label">Code <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="code" id="txt-code" value="<?php echo $pro_code ?>">
                            <p class="error error-filed">
                                <?php echo $pro_code_error ?>
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label class="form-label">Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="name" id="txt-name" value="<?php echo $pro_name ?>">
                            <p class="error error-filed">
                                <?php echo $pro_name_error ?>
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label class="form-label">Price <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="price" id="txt-price" value="<?php echo $pro_price ?>">
                            <p class="error error-filed">
                                <?php echo $pro_price_error ?>
                            </p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label class="form-label">Description <span class="text-danger">*</span></label>
                            <textarea class="form-control" name="des" id="des" cols="10" rows="3" value="<?php echo $des ?>"></textarea>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col">
                            <label class="form-label">Photo <span class="text-danger">*</span></label>
                            <input type="file" class="form-control" name="photo" accept=".jpg, .jpeg, .png" id="txt-photo" value="<?php echo $pro_img; ?>">
                            <p class="error error-filed">
                                <?php echo $pro_img_error ?>
                            </p>
                        </div>
                    </div>
                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary" name="submit"><i class="fa-solid fa-floppy-disk"></i> Save</button>
                        <a href="index.php" class="btn btn-danger"><i class="fa-solid fa-ban"></i> Cancel</a>
                    </div>
                </form>
            </div>
    </div>

    <script>
        // Set cursor focus on the "Code" field after form submission
        <?php if ($result): ?>
            document.getElementById("txt-code").focus();
        <?php endif; ?>
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>
</body>

</html>
