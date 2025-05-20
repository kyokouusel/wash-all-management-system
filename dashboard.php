<?php
session_start();

if (!isset($_SESSION['customer_id'])) {
    header("Location: login.php");
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
            <h2 data-aos="fade-down" data-aos-delay="50" >Welcome, <?php echo $_SESSION['customer_name']; ?>!</h2>
            <p data-aos="fade-down" data-aos-delay="100">
           Select an action below:
            </p>
            <a data-aos="fade-down" data-aos-delay="50" style="background-color: rgb(228, 228, 228); width: 50%; margin-bottom: 15px;" onclick="location.href='place_order.php'"  class="btn btn_1">Place Order</a>
            <a data-aos="fade-down" data-aos-delay="50" style="background-color: rgb(228, 228, 228); width: 50%; margin-bottom: 15px;" onclick="location.href='order_history.php'" class="btn btn_1">View Order History</a>
            <a data-aos="fade-down" data-aos-delay="50" style="background-color: rgb(228, 228, 228); width: 50%; margin-bottom: 15px;" onclick="location.href='customer_edit_profile.php'" class="btn btn_1">Edit Profile</a>
        </div>
    </section>

    <!-- Landing Page end -->
    

    <!-- Contact Section end -->
    
    <!-- Copyright -->
    <div class="cp">
        <p>&copy; 2025 <a href="#">Splash Brothers</a>. All Right Reserved</p>
    </div>

    <!-- JS Link -->
    <script src="script.js"></script>

    <!-- AOS JS -->
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
