<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wash All Laundry</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css" integrity="sha512-1sCRPdkRXhBV2PBLUdRb4tMg1w2YPf37qatUFeS7zlBy7jJI8Lf4VHwWfZZfpXtYSLy85pkm9GaYVYMfw5BC1A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.3.4/aos.css" integrity="sha512-1cK78a1o+ht2JcaW6g8OXYwqpev9+6GqOkz9xmBN9iUUhIndKtxwILGWYOSibOKjLsEdjyjZvYDq/cZwNeak0w==" crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>
<body>

    <!-- Navigation bar start -->

    <header>
        <a href="#" class="logo" data-aos="fade-down">Wash All Laundry</a>
        <div class="menuToggle" onclick="toggleMenu();"></div>
        <ul class="nav">
            <li data-aos="fade-down" data-aos-delay="50"><a href="#home"  onclick="toggleMenu();">Home</a></li>
            <li data-aos="fade-down" data-aos-delay="100"><a href="#about"  onclick="toggleMenu();">About</a></li>
            <li data-aos="fade-down" data-aos-delay="250"><a href="#service"  onclick="toggleMenu();">Services</a></li>
            <li data-aos="fade-down" data-aos-delay="250"><a href="#testimonial"  onclick="toggleMenu();">Testimonials</a></li>
            <li data-aos="fade-down" data-aos-delay="300"><a href="#contact"  onclick="toggleMenu();">Contact</a></li>
            <li data-aos="fade-down" data-aos-delay="50"><a onclick="location.href='../index.php'">Log Out</a></li>
        </ul>
        
    </header>

    <!-- Navigation bar end -->

    <!-- Landing Page start -->

    <section class="hero_section" id="home">
        <div class="content">
            <h2 data-aos="fade-down" data-aos-delay="50">Wash All Laundry</h2>
            <p data-aos="fade-down" data-aos-delay="100">
                WASH - DRY - FOLD - STREAM FRESH
                <br>
           WE PICK UP AND DELIVER!
            </p>
            <a href="#about" class="btn btn_1">Discover</a>

        </div>
    </section>

    <!-- Landing Page end -->

    <!-- About Section start -->

    <section class="about_section" id="about">
        <div class="container">
            <div class="row">
                <div class="content" data-aos="fade-right">
                    <div class="head_title">
                        <span>About Us</span>
                    </div>
                    <p>Welcome to <span>Wash All Laundry</span>, your trusted partner for all your laundry needs! We understand that life can get busy, and laundry often falls to the bottom of your to-do list. That’s where we come in. Our mission is to make laundry day effortless, convenient, and stress-free. Whether it’s your everyday clothes, delicate fabrics, or bulky bedding, we handle it all with care, precision, and a commitment to quality. Say goodbye to laundry hassles and hello to fresh, clean clothes delivered right to your doorstep.</p>
                </div>
                <div class="content" data-aos="fade-left">
                    <div class="img_box">
                        <img src="../User/image/about.jpg" alt="">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="content" data-aos="fade-left"><p>At <span>Wash All Laundry</span>, we specialize in convenient pick-up and delivery laundry services, providing quick and efficient solutions for busy customers. With multiple branches across Malaybalay City, we offer same-day service to ensure your laundry needs are met without hassle. Our facilities are equipped with modern amenities to guarantee comfort and satisfaction for all patrons.</p>
                </div>
                <div class="content" data-aos="fade-right">
                    <div class="img_box">
                        <img src="../User/image/about2.jpg" alt="">
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- About Section end -->

    <!-- Service Section start -->

    <!-- Service Section start -->

    <section class="service_section" id="service">

        <div class="title_container" data-aos="fade-down">
          <h2 class="head_title"><span>OUR SERVICE</span></h2>
          <p class="subtitle">Having fresh, neatly folded laundry delivered right to your doorstep is as simple as 1-2-3!</p>
        </div>
            
        <div style="margin-top: 90px; height: 700px;" class="container">
            <div class="collection">
                <div  style="gap: 25px;"  class="row" data-aos="fade-up">
                    <div class="card" style="max-width: 350px; height: 250px;" data-aos-delay="100">
                        <div class="icon-circle blue">1</div>
                        <h3 style="margin-left: 110px;">Wash</h3>
                        <div class="footer_card">
                            <p>Clothes are sorted by color and fabric, then thoroughly cleaned using quality detergents.</p>
                        </div>
                    </div>
        
                    <div class="card" style="max-width: 350px; height: 250px;" data-aos-delay="200">
                        <div class="icon-circle pink">2</div>
                        <h3 style="margin-left: 120px;">Dry</h3>
                        <div class="footer_card">
                            <p>Garments are dried at the right temperature to protect fabric and maintain freshness.</p>
                        </div>
                    </div>
        
                    <div class="card" style="max-width: 350px; height: 250px;" data-aos-delay="400">
                        <div class="icon-circle green">3</div>
                        <h3 style="margin-left: 115px;">Fold</h3>
                        <div class="footer_card">
                            <p>Clean and dry clothes are carefully folded, ready to be returned crisp and organized.

</p>
                        </div>
                    </div>
                    <a 
                        style="margin-top: 25px; border-radius: 15px; background-color: aqua;" 
                        onclick="location.href='../dashboard.php'" 
                        class="btn btn_1">
                        Get Started Today!
                        </a>
                </div>
            </div>
        </div>
        </div>
        
      </section>
      
    <!-- Service Section end -->

    <!-- Testimonials Section start -->

    <section class="testimonials_section" id="testimonial">

        <div class="title_container dark" data-aos="fade-down">
            <h2 class="head_title"><span>THEIR TESTIMONIALS</span></h2>
            <p class="subtitle">Hear from Happy Customers Who Trust Us!</p>
        </div>

        <div class="container">

            <div class="content" data-aos="fade-down" data-aos-delay="300">
                <div class="img_box">
                    <img src="../User/image/petancio.jpg" alt="">
                </div>
                <div class="text_box">
                    <p>Grabe ka humot ang sabon. Highly recommend!</p>
                    <h3>JEISHA LOU</h3>
                </div>
            </div>

            <div class="content" data-aos="fade-down" data-aos-delay="200">
                <div class="img_box">
                    <img src="../User/image/johndyl.png" alt="">
                </div>
                <div class="text_box">
                    <p>My clothes have never looked better. Love this service!</p>
                    <h3>JOHN DYLL</h3>
                </div>
            </div>

            <div class="content" data-aos="fade-down" data-aos-delay="300">
                <div class="img_box">
                    <img src="../User/image/von.png" alt="">
                </div>
                <div class="text_box">
                    <p>Everything comes back clean and neatly folded.</p>
                    <h3>VON ZILJAN</h3>
                </div>
            </div>
            
        </div>

    </section>

    <!-- Testimonials Section end -->

    <!-- Contact Section start -->

    <section style="margin-bottom: -15px;" class="contact_section" id="contact">

        <div class="title_container" data-aos="fade-down">
            <h2 class="head_title"><span>CONTACT US</span></h2>
            <p class="subtitle">Contact Us to Schedule a Pickup.</p>
        </div>  

        <div style="margin-top: -20px;" class="container">

            
            
            <!-- Popup Message -->
            
           <!-- Popup message (already centered) -->
<div id="popupMessage" style="display: none; background: rgba(0,0,0,0.8); color: white; position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); padding: 20px; border-radius: 8px; z-index: 9999;"></div>

<!-- Map wrapper -->
<div style="display: flex; justify-content: center; align-items: center; width: 100%; margin-top: 40px;" data-aos="fade-left">
    <div  class="map_box">
        <iframe 
        src="https://www.google.com/maps?q=8.144111,125.122250&hl=en&z=18&output=embed"  
            width="600" 
            height="450" 
            style="border:0;" 
            allowfullscreen="" 
            loading="lazy" 
            referrerpolicy="no-referrer-when-downgrade">
        </iframe>
    </div>
</div>

            <div class="footer_contact">

                <div class="item" data-aos="fade-down" data-aos-delay="100">
                    <span>Address</span>
                    <p>Wash All Laundry, Landing, Malaybalay, Bukidnon</p>
                </div>
                <div class="item" data-aos="fade-down" data-aos-delay="300">
                    <span>Phone</span>
                    <p>09971575614</p>
                </div>

            </div>

        </div>

    </section>

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
});

function showPopup(message) {
    const popup = document.getElementById('popupMessage');
    popup.textContent = message;
    popup.style.display = 'block';
    setTimeout(() => {
        popup.style.display = 'none';
    }, 3000);
}

document.getElementById('contactForm').addEventListener('submit', function(event) {
    event.preventDefault();

    const form = this;
    const formData = new FormData(form);

    fetch('send_email.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        console.log(data);  // Debugging
        if (data.status === 'success') {
            showPopup('✅ Message sent successfully!');
            form.reset();
        } else if (data.status === 'invalid_email') {
            showPopup('❌ Invalid email address.');
        } else {
            showPopup('❌ Failed to send message: ' + (data.message || 'Unknown error.'));
        }
    })
    .catch(error => {
        console.error(error);
        showPopup('❌ An error occurred while sending the message.');
    });
});
</script>

</body>
</html>