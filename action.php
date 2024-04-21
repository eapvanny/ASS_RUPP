<?php
    session_start();
    require  'admin/db_conn.php';

    if(isset($_POST['pid'])){
        $pid = $_POST['pid'];
        $pname = $_POST['pname'];
        $pprice = $_POST['pprice'];
        $pimage = $_POST['pimage'];
        $pcode = $_POST['pcode'];
        $pqty = 1;
        // $total_price = $pprice * $pqty;

        $stmt = $conn->prepare("SELECT product_code FROM carts WHERE product_code=?");
        $stmt->bind_param('s',$pcode);
        $stmt->execute();
        $res = $stmt->get_result();
        $r = $res->fetch_assoc();
        $code = $r['product_code'] ?? '';

        if (!$code) {
            $query = $conn->prepare("INSERT INTO carts (product_name,product_price,product_image,qty,total_price,product_code) VALUES (?,?,?,?,?,?)");
            $query->bind_param('ssssss',$pname,$pprice,$pimage,$pqty,$pprice,$pcode);
            $query->execute();

            echo ' <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Item added to your cart!</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>';
        } else {
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Item already added to your cart!</strong>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                     </div>';
        }
    }

    if (isset($_GET['cartItem']) && isset($_GET['cartItem']) == 'cart_item') {
        $stmt = $conn->prepare('SELECT * FROM carts');
        $stmt->execute();
        $stmt->store_result();
        $rows = $stmt->num_rows;
  
        echo $rows;
    }
    if (isset($_GET['remove'])) {
        $id = $_GET['remove'];
  
        $stmt = $conn->prepare('DELETE FROM carts WHERE id=?');
        $stmt->bind_param('i',$id);
        $stmt->execute();
  
        $_SESSION['showAlert'] = 'block';
        $_SESSION['message'] = 'Item removed from the cart!';
        header('location:cart.php');
    }

    if (isset($_GET['clear'])) {
        $stmt = $conn->prepare('DELETE FROM carts');
        $stmt->execute();
        $_SESSION['showAlert'] = 'block';
        $_SESSION['message'] = 'All Item removed from the cart!';
        header('location:cart.php');
    }
    if(isset($_POST['id']) && isset($_POST['qty'])){
        $id = $_POST['id'];
        $qty = $_POST['qty'];
    
        // Update the quantity and calculate the total price in the database
        $stmt = $conn->prepare('UPDATE carts SET qty = ?, total_price = product_price * ? WHERE id = ?');
        $stmt->bind_param('iii', $qty, $qty, $id);
        $stmt->execute();
        $stmt->close();
    }
    
    if (isset($_POST['action']) && $_POST['action'] == 'order') {
        // Retrieve form data
        $name = $_POST['name'];
        $email = $_POST['email'];
        $phone = $_POST['phone'];
        $products = $_POST['products'];
        $grand_total = $_POST['grand_total'];
        $address = $_POST['address'];
        $pmode = $_POST['pmode'];
    
        // Insert order data into the database
        $stmt = $conn->prepare('INSERT INTO orders (name, email, phone, address, pmode, products, amounts_paid) VALUES (?, ?, ?, ?, ?, ?, ?)');
        $stmt->bind_param('sssssss', $name, $email, $phone, $address, $pmode, $products, $grand_total);
        $stmt->execute();
    
        // Clear cart items
        $stmt2 = $conn->prepare('DELETE FROM carts');
        $stmt2->execute();
    
        // Prepare response message
        $data = '<div class="success-message">
        <h1 class="display-4 text-danger">Thank You!</h1>
        <h2 class="text-success">Your Order Has Been Placed Successfully!</h2>
        <div class="order-details">
            <div class="row">
                <div class="col-md-4">
                    <h4>Items Purchased:</h4>
                </div>
                <div class="col-md-8">
                    <p>'. $products.'</p>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-4">
                    <h4>Your Name:</h4>
                </div>
                <div class="col-md-8">
                    <p>'. $name.' </p>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-4">
                    <h4>Your E-mail:</h4>
                </div>
                <div class="col-md-8">
                    <p>'.$email.' ?></p>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-4">
                    <h4>Your Phone:</h4>
                </div>
                <div class="col-md-8">
                    <p>.'.$phone.' ?></p>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-4">
                    <h4>Amount Paid:</h4>
                </div>
                <div class="col-md-8">
                    <p>'. number_format($grand_total, 2).'$'.'</p>
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-4">
                    <h4>Payment Mode:</h4>
                </div>
                <div class="col-md-8">
                    <p>'.$pmode.'</p>
                </div>
            </div>
        </div>
    </div>
    
    
    ';
    
        // Output response message
        echo $data;
    }
?>
