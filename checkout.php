<?php
    require ("admin/db_conn.php");
        
	$grand_total = 0;
	$allItems = '';
	$items = [];

	$sql = "SELECT CONCAT(product_name, '(',qty,')') AS ItemQty, total_price FROM carts";
	$stmt = $conn->prepare($sql);
	$stmt->execute();
	$result = $stmt->get_result();
	while ($row = $result->fetch_assoc()) {
	  $grand_total += $row['total_price'];
	  $items[] = $row['ItemQty'];
	}
	$allItems = implode(', ', $items);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
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
                <a class="active" href="checkout.php">Checkout</a>
            </div>
        </div>
        <div class="cart-icon">
            <a href="cart.php">
                <i class="fas fa-shopping-cart"><span id="cart-item" class="bg-badge"></span></i>
            </a>
        </div>
    </header>

    <div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-6 px-4 pb-4" id="order">
            <div class="jumbotron bg-light p-4 mb-4 text-center">
                <h4 class="mb-4"><b>Your Order Summary</b></h4>
                <div class="row justify-content-center">
                    <div class="col-md-8">
                        <h6 class="lead"><b>Product(s) :</b> <span id="product-list"><?= $allItems; ?></span></h6>
                        <h6 class="lead"><b>Delivery Charge :</b> Free</h6>
                        <h5 class="mt-4"><b>Total Amount Payable :</b> <span id="total-amount"><?= number_format($grand_total, 2) ?>/-</span></h5>
                    </div>
                </div>
            </div>

            <form action="" method="post" id="placeOrder">
                <input type="hidden" name="products" value="<?= $allItems; ?>">
                <input type="hidden" name="grand_total" value="<?= $grand_total; ?>">
                
                <!-- Name -->
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" id="name" name="name" class="form-control" placeholder="Enter Name" required>
                </div>

                <!-- Email -->
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" id="email" name="email" class="form-control" placeholder="Enter E-Mail" required>
                </div>

                <!-- Phone -->
                <div class="mb-3">
                    <label for="phone" class="form-label">Phone</label>
                    <input type="tel" id="phone" name="phone" class="form-control" placeholder="Enter Phone" required>
                </div>

                <!-- Address -->
                <div class="mb-3">
                    <label for="address" class="form-label">Delivery Address</label>
                    <textarea id="address" name="address" class="form-control" rows="3" placeholder="Enter Delivery Address Here..." required></textarea>
                </div>

                <!-- Payment Mode -->
                <div class="mb-3">
                    <label class="form-label">Select Payment Mode</label>
                    <select name="pmode" class="form-select">
                        <option value="" selected disabled>-Select Payment Mode-</option>
                        <option value="cod">Cash On Delivery</option>
                        <option value="netbanking">Net Banking</option>
                        <option value="cards">Debit/Credit Card</option>
                    </select>
                </div>

                <!-- Submit Button -->
                <div class="mb-3">
                    <button type="submit" name="submit" class="btn btn-danger btn-block">Place Order</button>
                </div>
            </form>
        </div>
    </div>
</div>


    <script src="https://code.jquery.com/jquery-3.7.1.js" integrity="sha256-eKhayi8LEQwp4NKxN+CfCh+3qOVUtJn3QNZ0TciWLP4=" crossorigin="anonymous"></script>
    <script>
        $(document).ready(function() {
            // Sending Form data to the server
            $("#placeOrder").submit(function(e) {
                e.preventDefault();
                $.ajax({
                    url: 'action.php',
                    method: 'post',
                    data: $('form').serialize() + "&action=order",
                    success: function(response) {
                        $("#order").html(response);
                        load_cart_item_number();
                    }
                });
            });

            // Load total no.of items added in the cart and display in the navbar
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
