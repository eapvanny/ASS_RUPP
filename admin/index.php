<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="style/style.css">
    <title>All Product</title>
</head>

<body>

    <div class="text-center mt-2">
        <h3> All Product </h3>
        <p class="text-muted">Explore the details of our products below</p>
    </div>
    <div class="container">

    <?php
if (isset($_GET['msg'])) {
    $msg = $_GET['msg'];
    echo '<div id="alertMsg" class="alert alert-success alert-dismissible fade show" role="alert msg">
                ' . $msg . '
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
              </div>';
}
?>

        <a href="add_new.php" class="btn btn-primary mb-3 p-2"><i class="fa-solid fa-plus"></i> Add New </a>
        <table class="table table-hover text-center">
            <thead class="bg-primary text-white">
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Code</th>
                    <th scope="col">Name</th>
                    <th scope="col">Price</th>
                    <th scope="col">Des</th>
                    <th scope="col">Photo</th>
                    <th scope="col">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php
                include "db_conn.php";
                $sql = "SELECT * FROM `products`";
                $result = mysqli_query($conn, $sql);
                while ($row = mysqli_fetch_assoc($result)) {
                ?>
                    <tr class="text-center">
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['product_code']; ?></td>
                        <td><?php echo $row['product_name']; ?></td>
                        <td><?php echo $row['product_price']; ?></td>
                        <td><?php echo $row['des']; ?></td>
                        <td><img src="img/<?php echo $row['product_image']; ?>" alt="" style="width: 50px; height: 50px; object-fit: cover;"></td>
                        <td>
                            <a href="edit.php?id=<?php echo $row['id'] ?>"><i class="fa-solid fa-pen-to-square fs-5 me-3"></i></a>
                            <a href="#" onclick="openPopup(<?php echo $row['id']; ?>)"><i class="fa-solid fa-trash fs-5 text-danger"></i></a>
                        </td>
                    </tr>
                <?php
                }
                ?>
            </tbody>
        </table>
    </div>

    <div id="confirmationPopup" class="confirmation-popup">
        <div class="popup-content">
            <p>Are you sure you want to delete this item?</p>
            <button onclick="cancelDelete()" class="btn btn-secondary">Cancel</button>
            <button id="deleteButton" class="btn btn-danger">Delete</button>
        </div>
    </div>

    <script>
       $(document).ready(function() {
        $(".msg").fadeIn();
        setTimeout(function() {
            $("#alertMsg").fadeOut(); // Change '.message' to '#alertMsg' to target the specific alert
        }, 1500);
    });

        function openPopup(id) {
            document.getElementById('confirmationPopup').style.display = 'block';
            // Set the delete URL with the item's id
            document.getElementById('deleteButton').setAttribute('onclick', 'confirmDelete(' + id + ')');
        }

        function cancelDelete() {
            document.getElementById('confirmationPopup').style.display = 'none';
        }

        function confirmDelete(id) {
            window.location.href = 'delete.php?id=' + id;
        }
        
    </script>


</body>

</html>
