<?php
include 'config.php';
session_start();

// Check if customer is logged in
if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
    exit();
}

$customer_id = $_SESSION['customer_id'];
$success_message = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);

    $update_sql = "UPDATE customers SET name=?, email=?, phone=?, address=? WHERE id=?";
    $stmt = $conn->prepare($update_sql);
    $stmt->bind_param("ssssi", $name, $email, $phone, $address, $customer_id);

    if ($stmt->execute()) {
        $success_message = "Profile updated successfully!";
        $_SESSION['customer_name'] = $name; // Update session name if changed
    } else {
        $success_message = "Failed to update profile.";
    }
}

// Fetch customer data
$sql = "SELECT * FROM customers WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $customer_id);
$stmt->execute();
$result = $stmt->get_result();
$customer = $result->fetch_assoc();

if (!$customer) {
    echo "Customer not found.";
    exit();
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
            background: white;
            padding: 20px;
            margin: auto;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.1);
        }
        h2 {
            margin-bottom: 20px;
            color: #4CAF50;
        }
        label {
            display: block;
            margin-top: 10px;
        }
        input[type="text"], input[type="email"] {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border-radius: 5px;
            border: 1px solid #ccc;
        }
        button {
            margin-top: 20px;
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        button:hover {
            background-color: #45a049;
        }
        .success {
            color: green;
            margin-top: 10px;
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
            <h2 data-aos="fade-down" data-aos-delay="50">Edit Profile</h2>
            <p data-aos="fade-down" data-aos-delay="100">
            </p>


        <?php if ($success_message): ?>
            <p class="success"><?= htmlspecialchars($success_message) ?></p>
        <?php endif; ?>

        <form data-aos="fade-down" data-aos-delay="50" style="background-color: white; padding: 20px; border-radius: 15px;" action="" method="POST">
            <label for="name">Full Name</label>
            <input type="text" name="name" required value="<?= htmlspecialchars($customer['name']) ?>">

            <label for="email">Email</label>
            <input type="email" name="email" required value="<?= htmlspecialchars($customer['email']) ?>">

            <label for="phone">Phone</label>
            <input type="text" name="phone" required value="<?= htmlspecialchars($customer['phone']) ?>">

            <label for="address">Address</label>
            <input type="text" name="address" required value="<?= htmlspecialchars($customer['address']) ?>">

            <button type="submit">💾 Save Changes</button>
            
        </form>
    </div>
           

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
