<?php
session_start();
include 'config.php';

// ✅ Ensure user is logged in — works for both normal and Google sign-in users
if (!isset($_SESSION['customer_id']) || empty($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit();
}

// 🌏 Set timezone before using date()
date_default_timezone_set('Asia/Manila'); 

$customer_id = $_SESSION['customer_id'];
$receipt = [];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $service_package = mysqli_real_escape_string($conn, $_POST['service_package']);
    $weight = (int) $_POST['weight'];
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $order_date = date("Y-m-d H:i:s"); 
    $status = "Pending";

    $base_prices = [
        'Dry' => 70,
        'Wash' => 60,
        'Fold' => 30,
        'Dry + Fold' => 100,
        'Wash + Dry' => 130,
        'Wash + Fold' => 90,
        'Wash + Dry + Fold' => 160,
        'Regular Clothes' => 140,
        'Wash + Dry + Fold with Detergent and Fabcon' => 185
    ];

    $total = isset($base_prices[$service_package]) ? $base_prices[$service_package] : 0;
    $weight_factor = ceil($weight / 8);
    $total *= $weight_factor;

    $stmt = $conn->prepare("INSERT INTO orders (customer_id, service_type, weight, address, phone, order_date, status, total_amount) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("isissssd", $customer_id, $service_package, $weight, $address, $phone, $order_date, $status, $total);

    if ($stmt->execute()) {
        $success_message = "✅ Order placed successfully!";
        $receipt = [
            'Service Package' => $service_package,
            'Weight' => $weight . " kg",
            'Address' => $address,
            'Phone' => $phone,
            'Order Date' => $order_date,
            'Status' => $status,
            'Total Amount' => "₱" . number_format($total, 2)
        ];
    } else {
        $error_message = "❌ Error placing order. Please try again.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wash All Laundry</title>
    <link rel="stylesheet" href="User/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css" integrity="sha512-1sCRPdkRXhBV2PBLUdRb4tMg1w2YPf37qatUFeS7zlBy7jJI8Lf4VHwWfZZfpXtYSLy85pkm9GaYVYMfw5BC1A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" integrity="sha512-1cK78a1o+ht2JcaW6g8OXYwqpev9+6GqOkz9xmBN9iUUhIndKtxwILGWYOSibOKjLsEdjyjZvYDq/cZwNeak0w==" crossorigin="anonymous" referrerpolicy="no-referrer" />


    <style>
        .container {
            max-width: 600px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 6px 15px rgba(0,0,0,0.1);
        }
        h2 {
            margin-bottom: 20px;
        }
        label {
            font-weight: bold;
        }
        select, input[type="number"], input[type="text"], input[type="submit"] {
            width: 100%;
            padding: 10px;
            margin-top: 6px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 6px;
        }
        input[type="submit"] {
            background-color: #007bff;
            color: white;
            cursor: pointer;
            font-size: 16px;
        }
        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .back-link {
            display: inline-block;
            margin-top: 15px;
            color: #007bff;
            text-decoration: none;
        }
        .back-link:hover {
            text-decoration: underline;
        }
        .message {
            padding: 12px;
            margin-bottom: 15px;
            border-radius: 6px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
        }
    </style>
</head>
<body>

    <!-- Navigation bar start -->

    <header>
        <a href="#" class="logo" data-aos="fade-down">Wash All Laundry</a>
        <div class="menuToggle" onclick="toggleMenu();"></div>
        <ul class="nav">
            <li data-aos="fade-down" data-aos-delay="50"><a onclick="location.href='User/index.php'" >Back to Dashboard</a></li>
            <li data-aos="fade-down" data-aos-delay="50"><a onclick="location.href='index.php'">Log Out</a></li>
        </ul>
        
    </header>

    <!-- Navigation bar end -->

    <!-- Landing Page start -->

    <section class="hero_section" id="home">
        <div class="content">
            <h2 data-aos="fade-down" data-aos-delay="50">Place a New Order</h2>
            <p data-aos="fade-down" data-aos-delay="100">
            </p>


            <form data-aos="fade-down" data-aos-delay="50" style="background-color: white; padding: 20px; border-radius: 15px;" action="place_order.php" method="POST">
            <label>Service Package:</label>
            <select name="service_package" required>
                <option value="Dry">Dry (₱70)</option>
                <option value="Wash">Wash (₱60)</option>
                <option value="Fold">Fold (₱30)</option>
                <option value="Dry + Fold">Dry + Fold (₱100)</option>
                <option value="Wash + Dry">Wash + Dry (₱130)</option>
                <option value="Wash + Fold">Wash + Fold (₱90)</option>
                <option value="Wash + Dry + Fold">Wash + Dry + Fold - min 8kg (₱160)</option>
                <option value="Wash + Dry + Fold with Detergent and Fabcon">Wash + Dry + Fold with Detergent and Fabcon - min 8kg (₱185)</option>
            </select>

            <label>Weight (kg):</label>
            <select name="weight" required>
                <option value="8">8 kg</option>
                <option value="16">16 kg</option>
                <option value="24">24 kg</option>
                <option value="32">32 kg</option>
            </select>

            <label>Address:</label>
            <input type="text" name="address" required>

            <label>Phone Number:</label>
            <input type="text" name="phone" required>

            <input type="submit" value="Place Order">
        </form>

        <a style="color: white;" href="dashboard.php" class="back-link">⬅️ Back to Dashboard</a>
    </div>



        </div>
    </section>
    
    <?php if (!empty($success_message)): ?>
            <div class="message success"><?php echo $success_message; ?></div>

            <?php if (!empty($receipt)): ?>
            <div class="container" style="background-color:#f9f9f9; padding:15px; border:1px solid #ccc; margin-bottom: 15px;">
                <h3>🧾 Order Summary</h3>
                <ul style="list-style: none; padding-left: 0;">
                    <?php foreach ($receipt as $key => $value): ?>
                        <li><strong><?php echo $key; ?>:</strong> <?php echo $value; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>
        <?php elseif (!empty($error_message)): ?>
            <div class="message error"><?php echo $error_message; ?></div>
        <?php endif; ?>
        <div id="popupMessage" style="display: none; background: rgba(0,0,0,0.8); color: white; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); padding: 20px; border-radius: 8px; z-index: 9999;"></div>
    <div class="cp">
        <p>&copy; 2025 <a href="#">Splash Brothers</a>. All Right Reserved</p>
    </div>

    <script src="script.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.js" integrity="sha512-A7AYk1fGKX6S2SsHywmPkrnzTZHrgiVT7GcQkLGDe2ev0aWb8zejytzS8wjo7PGEXKqJOrjQ4oORtnimIRZBtw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script>
        AOS.init({
            duration: 600,
        })

         // Function to display the popup
    function showPopup() {
        var popup = document.getElementById('popupMessage');
        popup.style.display = 'block';

        // Hide the popup after 3 seconds
        setTimeout(function() {
            popup.style.display = 'none';
        }, 3000);
    }

    // Add an event listener to the form submission
    document.getElementById('contactForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Prevent the form from refreshing the page

        // Simulate a successful message sent action
        // Normally you would handle the form data here (e.g., send it via AJAX)
        
        // Show the popup
        showPopup();
    });
    </script>
</body>
</html>
