<?php
session_start();
include "db.php";
if(!isset($_SESSION['user_id'])){
  header("Location: login.php");
}

include 'navbar.php';
include 'header.php';


$user_id = $_SESSION['user_id'];

// cart data
$sql = "SELECT 
            c.cart_id,
            p.product_id,
            p.name, 
            p.price, 
            p.image AS product_image,
            v.size, 
            v.color, 
            v.extra_price, 
            c.quantity
        FROM cart c
        JOIN products p ON c.product_id = p.product_id
        LEFT JOIN product_variants v ON c.variant_id = v.variant_id
        WHERE c.user_id = $user_id";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Your Cart - Cloth Nova</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <style>
    a{
        text-decoration: none;
        color: #111;
    }
  </style>
</head>
<body>

<div class="container my-5">
  <h2>Your Shopping Cart</h2>
  <hr>

  <?php if($result->num_rows == 0): ?>
      <p>No items in your cart.</p>
  <?php else: ?>
      <table class="table table-bordered align-middle">
        <tr>
          <th>Image</th>
          <th>Name</th>
          <th>Variant</th>
          <th>Price</th>
          <th>Quantity</th>
          <th>Total</th>
          <th>Action</th>
        </tr>
        <?php 
        $grand_total = 0;
        $total_items = 0;
        while($row = $result->fetch_assoc()): 
              $final_price = $row['price'] + ($row['extra_price'] ?? 0);
              $total = $final_price * $row['quantity'];
              $grand_total += $total;
              $total_items += $row['quantity'];
        ?>
        <tr>
          <td><img src="images/<?php echo $row['product_image']; ?>" width="80"></td>
          <td><?php echo $row['name']; ?></td>
          <td>
            <?php 
              echo $row['size'] ? "Size: ".$row['size']."<br>" : "";
              echo $row['color'] ? "Color: ".$row['color'] : "";
            ?>
          </td>
          <td>Rs. <?php echo $final_price; ?></td>
          <td><?php echo $row['quantity']; ?></td>
          <td>Rs. <?php echo $total; ?></td>
          <td>
            <a href="cart_delete.php?id=<?php echo $row['cart_id']; ?>" 
               class="btn btn-sm btn-danger">Remove</a>
          </td>
        </tr>
        <?php endwhile; ?>
      </table>

      <!-- Place Order Button -->
      <div class="text-end">
        <button id="placeOrderBtn" class="btn btn-primary btn-lg">
          Place Order
        </button>
      </div>

<script>
document.getElementById('placeOrderBtn').addEventListener('click', function(){
    Swal.fire({
        title: "Enter Your Details",
        html: `
            <input type="text" id="phone" class="swal2-input" placeholder="Phone Number">
            <input type="text" id="address" class="swal2-input" placeholder="Address">
        `,
        focusConfirm: false,
        preConfirm: () => {
            const phone = document.getElementById("phone").value.trim();
            const address = document.getElementById("address").value.trim();
            if (!phone || !address) {
                Swal.showValidationMessage("Please fill both fields!");
            }
            return { phone: phone, address: address };
        },
        confirmButtonText: "Place Order",
        showCancelButton: true
    }).then((result) => {
        if(result.isConfirmed){
            const data = result.value;

            // 🔹 Normal order request
            fetch("place_order.php", {
                method: "POST",
                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                body: `phone=${encodeURIComponent(data.phone)}&address=${encodeURIComponent(data.address)}`
            })
            .then(res => res.text())
            .then(msg => {
                msg = msg.trim(); // ✅ remove unwanted spaces/newlines

                // ✅ Check "already_pending"
                if(msg === "already_pending"){
                    Swal.fire({
                        title: "Pending Order Found",
                        text: "You already have a pending order. Do you want to place another order?",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonText: "Yes, Place Another"
                    }).then((res2) => {
                        if(res2.isConfirmed){
                            // 🔹 Force place another order
                            fetch("place_order.php?force=1", {
                                method: "POST",
                                headers: { "Content-Type": "application/x-www-form-urlencoded" },
                                body: `phone=${encodeURIComponent(data.phone)}&address=${encodeURIComponent(data.address)}`
                            })
                            .then(r => r.text())
                            .then(resp => {
                                resp = resp.trim();
                                let parts = resp.split("|");

                                if(parts[0] === "success"){
                                    Swal.fire("Order Placed!", 
                                        `<b>Tracking Code:</b> ${parts[1]}<br><b>Total:</b> Rs. ${parts[2]}`, 
                                        "success"
                                    ).then(() => location.href = "orders.php");
                                } else if(parts[0] === "error") {
                                    Swal.fire("Error", parts[1] ?? "Something went wrong", "error");
                                } else {
                                    Swal.fire("Error", resp, "error");
                                }
                            });
                        }
                    });
                } 
                else {
                    // ✅ Normal success/error cases
                    let parts = msg.split("|");

                    if(parts[0] === "success"){
                        Swal.fire("Order Placed!", 
                            `<b>Tracking Code:</b> ${parts[1]}<br><b>Total:</b> Rs. ${parts[2]}`, 
                            "success"
                        ).then(() => location.href = "orders.php");
                    } 
                    else if(parts[0] === "error"){
                        Swal.fire("Error", parts[1] ?? "Something went wrong", "error");
                    } 
                    else {
                        Swal.fire("Error", msg, "error");
                    }
                }
            })
            .catch(err => {
                console.error("Fetch Error: ", err);
                Swal.fire("Error", "Something went wrong. Please try again.", "error");
            });
        }
    });
});
</script>

  <?php endif; ?>
</div>
</body>
</html>
