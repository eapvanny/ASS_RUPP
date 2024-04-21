<?php
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
            <a class="active" href="index.php">Product</a>
            <a href="#">Category</a>
            <a href="checkout.php">Checkout</a>
        </div>
    </div>
    <div class="cart-icon">
        <a href="cart.php">
            <i class="fas fa-shopping-cart"><span id="cart-item" class="bg-badge"></span></i>
        </a>
    </div>
</header>
<section id="product1" class="section-p1">
    <h2>Feature Products</h2>
    <h6>Summer Colection New Modern Design</h6>
    <div class="pro-container">   
        <?php
            $sql = "SELECT * FROM products ORDER BY id DESC LIMIT 0,8";
            $rs = $conn->query($sql);
            while($row = $rs->fetch_array()){
                ?>
                    <div class="pro">
                        <img src="admin/img/<?php echo $row[3]; ?>" alt="">
                        <div class="des">
                            <span><?php echo $row[1]; ?></span>
                            <h5><?php echo $row[5]; ?></h5>
                            <div class="star">
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                                <i class="fa-solid fa-star"></i>
                            </div>
                            <h4>$<?php echo $row[2]; ?></h4>
                        </div>
                        <form action="" class="form-submit">
                            <input type="hidden" name="" class="pid" value="<?php echo$row[0]; ?>">
                            <input type="hidden" name="" class="pname" value="<?php echo$row[1]; ?>">
                            <input type="hidden" name="" class="pprice" value="<?php echo$row[2]; ?>">
                            <input type="hidden" name="" class="pimage" value="<?php echo$row[3]; ?>">
                            <input type="hidden" name="" class="pcode" value="<?php echo$row[4]; ?>">
                            <a class="addItemBtn"><i class="fa-solid fa-cart-shopping cart"></i></a>
                        </form>
                    </div>
                <?php
            }
        ?>         
       
    </div>
</section>
<!-- <div class="pro-body">
    <div class="all-pro">
        <div class="pro-box">
            <div class="product">
                <div class="sm-box">
                    <div class="box-img">
                        img
                    </div>
                    <div class="box-foot">
                        1
                    </div>
                </div>
            </div>
            <div class="product">
                a
            </div>
            <div class="product">
                a
            </div>
            <div class="product">
                a
            </div>
        </div>
    </div>
</div> -->
<script>
    $(document).ready(function() {
        $(".addItemBtn").click(function(e) {
            e.preventDefault();
            var $form = $(this).closest(".form-submit");
            var pid = $form.find(".pid").val();
            var pname = $form.find(".pname").val();
            var pprice = $form.find(".pprice").val();
            var pimage = $form.find(".pimage").val();
            var pcode = $form.find(".pcode").val();

            $.ajax({
                url: 'action.php',
                method: 'post',
                data: {
                    pid: pid,
                    pname: pname,
                    pprice: pprice,
                    pimage: pimage,
                    pcode: pcode
                },
                success: function(response) {
                    $("#message").html(response);
                    load_cart_item_number();
                    // Display message for 2 seconds
                    $("#message").fadeIn();
                    setTimeout(function() {
                        $("#message").fadeOut();
                    }, 1000);
                }
            });
        });
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
