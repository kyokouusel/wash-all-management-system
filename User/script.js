// ========== Navbar Function
window.addEventListener("scroll", function(){
    const header = document.querySelector('header');
    header.classList.toggle("sticky", window.scrollY > 0);
})

// ========== Toggle Menu
function toggleMenu(){
    const menuBar = document.querySelector(".menuToggle");
    const nav = document.querySelector(".nav");
    menuBar.classList.toggle('active');
    nav.classList.toggle('active');
}

function toggleLogoutMenu() {
    var logoutMenu = document.getElementById('logoutMenu');
    // Toggle visibility of the logout menu
    logoutMenu.style.display = logoutMenu.style.display === 'none' ? 'block' : 'none';
}

function logout() {
    // Add logout functionality here, e.g., redirecting to a logout page or clearing session cookies
    alert("You have logged out!");
    // Redirect to logout page or perform necessary logout actions
    window.location.href = '../main.html'; 
}

    document.getElementById('orderForm1').addEventListener('submit', function(event) {
        event.preventDefault();  // Prevent form submission and page refresh
        alert('Your order has been placed!');
        // Optional: Close the form or reset it after order submission
        // document.getElementById('orderForm1').reset(); // To reset the form
    });

    document.getElementById('orderForm2').addEventListener('submit', function(event) {
        event.preventDefault();  // Prevent form submission and page refresh
        alert('Your order has been placed!');
        // Optional: Close the form or reset it after order submission
        // document.getElementById('orderForm2').reset(); // To reset the form
        window.location.href = '../main.html'; 
    });

  function openTrackModal() {
    document.getElementById('trackModal').style.display = 'flex';
  }

  function closeTrackModal() {
    document.getElementById('trackModal').style.display = 'none';
  }

  function submitTracking() {
    const orderId = document.getElementById('trackingInput').value;
    if (!orderId.trim()) {
      alert('Please enter your Order ID.');
      return;
    }
    alert(`Tracking Order ID: ${orderId}... (Feature in progress)`);
    closeTrackModal();
  }
