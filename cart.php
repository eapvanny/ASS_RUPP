<?php
    session_start();
    include "admin/db_conn.php";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Website</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">

</head>
<body>
<header>
<div id="message"></div>
    <div class="header-content">
        <span class="title">7𝓔𝓿𝓮𝓷11𝓛𝓮𝓿𝓮𝓷 <i class="fab fa-shopify"></i></span>
        <div class="category">
            <a href="index.php">Product</a>
            <a href="#">Category</a>
            <a href="checkout.php">Checkout</a>
        </div>
    </div>
    <div class="cart-icon">
        <a class="active" href="cart.php">
            <i class="fas fa-shopping-cart"><span id="cart-item" class="bg-badge"></span></i>
        </a>
    </div>
</header>
<div class="container">
    <div class="row justify-content-center">
      <div class="col-lg-10">
       <div class="message">
       <div style="display:<?php if (isset($_SESSION['showAlert'])) {
            echo $_SESSION['showAlert'];
            } else {
            echo 'none';
            } unset($_SESSION['showAlert']); ?>" class="alert alert-success alert-dismissible mt-3">
                     <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    <strong><?php if (isset($_SESSION['message'])) {
            echo $_SESSION['message'];
            } unset($_SESSION['showAlert']); ?></strong>
        </div>
       </div>
        <div class="table-responsive mt-2">
          <table class="table table-bordered table-striped text-center">
            <thead>
              <tr>
                <td colspan="7">
                  <h4 class="text-center text-info m-0">Products in your cart!</h4>
                </td>
              </tr>
              <tr>
                <th>ID</th>
                <th>Image</th>
                <th>Product</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total Price</th>
                <th>
                  <a href="action.php?clear=all" class="badge bg-danger p-2" onclick="return confirm('Are your Sure Want to clear your cart?')"><i class="fas fa-trash"></i><span class="ms-1">Clear Cart</span></a>
                </th>
              </tr>
            </thead>
            <tbody>
                    <?php
                    require 'admin/db_conn.php';
                    $stmt = $conn->prepare('SELECT * FROM carts');
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $grand_total = 0;
                    while ($row = $result->fetch_assoc()){
                    ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <input type="hidden" class="pid" value="<?php echo $row['id']; ?>">
                        <td><img src="admin/img/<?php echo $row['product_image']; ?>" alt=""style="width: 50px; height: 50px; object-fit: cover;"></td>
                        <td><?php echo $row['product_name']; ?></td>
                        <td><i class="fa-solid fa-dollar-sign"></i><?php echo number_format( $row['product_price'],2); ?></td>
                        <td><input type="number" class="form-control itemQty" data-id="<?php echo $row['id']; ?>" value="<?php echo $row['qty']; ?>" style="width: 75px;"></td>
                        <input type="hidden" class="pprice" value="<?php echo $row['product_price']; ?>">
                        <td><i class="fa-solid fa-dollar-sign"></i><span class="product-total"><?php echo number_format( $row['total_price'],2); ?></span></td>    
                        <td>
                            <a href="action.php?remove=<?= $row['id'] ?>" class="text-danger lead" onclick="return confirm('Are you sure want to remove this item?');"><i class="fas fa-trash-alt"></i></a>
                        </td>
                    </tr>
                    <?php $grand_total +=$row['total_price']; ?>
                    <?php } ?>
                    <tr>
                        <td colspan="3">
                            <a href="index.php" class="btn btn-success"><i class="fas fa-cart-plus"></i><span class="ms-2">Continue Shopping</span></a>
                        </td>
                        <td colspan="2"><b>Grand Total</b></td>
                        <td><b><i class="fa-solid fa-dollar-sign"></i><span id="grand-total"><?php echo number_format( $grand_total,2); ?></span></b></td>
                        <td>
                            <a href="checkout.php" class="btn btn-info text-white <?= ($grand_total > 1) ? '' : 'disabled'; ?>"><i class="far fa-credit-card"></i>&nbsp;&nbsp;Checkout</a>
                        </td>  
                    </tr>
                </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>


<script>

    $(document).ready(function(){
        $(".itemQty").on('change', function(){
    var id = $(this).data("id");
    var qty = $(this).val();
    var price = $(this).closest('tr').find('.pprice').val(); // Get the product price
    var total = (qty * price).toFixed(2); // Calculate total price
    $(this).closest('tr').find('.product-total').text(total); // Update total price in the HTML
    $.ajax({
        url: "action.php",
        method: "POST",
        data: {id:id, qty:qty},
        success:function(data){
            location.reload(); // Reload the page after successful update
        }
    }); 
});


        $(".message").fadeIn();
        setTimeout(function() {
            $(".message").fadeOut();
        }, 1000);

        load_cart_item_number();

        function load_cart_item_number() {
            $.ajax({
                url: 'action.php',
                method: 'get',
                data: {
                cartItem: "cart_item"
                },
                success: function(response) {
                    $("#cart-item").html(response);
                }
            });
        }   
    });

</script>
</body>
</html>
