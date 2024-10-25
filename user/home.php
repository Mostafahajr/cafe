
<?php
session_start();
require_once '../includes/db_connect.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id']; 

echo "<script>var userId = $user_id;</script>";

$query = $conn->prepare("SELECT username, profile_image FROM users WHERE id = ?");
if ($query == false) {
    die('Error preparing the SQL statement: ' . $conn->error);
}

$query->bind_param('i', $user_id);
$query->execute();
$result = $query->get_result();
if ($result == false) {
    die('Error executing the SQL statement: ' . $query->error);
}

$user = $result->fetch_assoc();
if ($user == null) {
    die('User not found.');
}

$profile_image = $user['profile_image'] ? '../uploads/' . $user['profile_image'] : 'https://via.placeholder.com/32';
$username = $user['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Coffee Shop Slider</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
body {
    background-color: #fdf4e4; /* Light beige background color */
}

.header {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    z-index: 10; /* Ensure the header is above the carousel */
    background: transparent; /* Make header background transparent */
    color: white; /* Ensure text color is readable */
    padding: 1rem 2rem;
}

.carousel-section {
    position: relative;
    height: 100vh; /* Full viewport height */
}
.carousel-section::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.6); /* Dark overlay */
    z-index: 1; /* Places the overlay above the background image */
}

.carousel-item {
    position: relative;
    height: 100vh; /* Full viewport height */
    overflow: hidden; /* Ensure the content doesn’t overflow */
}

.carousel-item img {
    width: 100%;
    height: 100%;
    object-fit: cover; /* Ensures the image covers the area without stretching */
}

.carousel-caption {
    position: absolute;
    bottom: 20%;
    left: 50%;
    transform: translateX(-50%);
    color: white;
    text-align: center;
}

.animated-text {
    font-size: 2rem;
    opacity: 1;
    animation: fadeOut 5s ease-out forwards;
}

@keyframes fadeOut {
    0% {
        opacity: 1;
    }
    100% {
        opacity: 0;
    }
}

.about-section {
    padding: 50px 0;
    text-align: center;
}

.about-title {
    color: #b47b3b; /* Light brown color */
    font-size: 1.2rem;
    margin-bottom: 10px;
    font-weight: bold;
}

.about-heading {
    font-size: 2.5rem;
    font-weight: bold;
    margin-bottom: 30px;
    color: #50342e; /* Dark brown color */
}

.about-text {
    font-size: 1rem;
    color: #50342e; /* Dark brown color */
    text-align: left;
}

.about-text h5 {
    font-weight: bold;
    margin-bottom: 20px;
}

.about-image {
    display: flex;
    justify-content: center;
    align-items: center;
}

.about-image img {
    max-width: 100%;
    height: auto;
}

.about-btn {
    background-color: #50342e; /* Dark brown color */
    border: none;
    padding: 10px 20px;
    color: white;
    font-weight: bold;
    border-radius: 5px;
    margin-top: 20px;
}

.about-btn:hover {
    background-color: #6d4b44;
}

.list-group-item {
    border: none;
    padding-left: 0;
    padding-right: 0;
}

.list-group-item::before {
    content: "✓";
    color: #b47b3b; /* Light brown color */
    margin-right: 10px;
}

.gold-divider {
    border: none; /* Remove default border */
    border-top: 5px solid gold; /* Bold, gold line */
    width: 10%; /* Full width */
    margin: 20px 0;
    text-align: center;
}
.testimonial-section {
  background-color: #fdf5f0;
  padding: 40px 0;
  text-align: center;
}

.section-title {
  font-size: 18px;
  color: #e4b17c;
  letter-spacing: 2px;
  text-transform: uppercase;
  margin-bottom: 5px;
}

.section-subtitle {
  font-size: 32px;
  color: #40210f;
  margin-bottom: 30px;
}

.testimonials {
  display: flex;
  justify-content: center;
  gap: 40px;
  flex-wrap: wrap;
}

.testimonial-item {
  background-color: #fff;
  border-radius: 10px;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  padding: 20px;
  width: 300px;
  text-align: left;
}

.testimonial-item img {
  border-radius: 50%;
  width: 50px;
  height: 50px;
  object-fit: cover;
  margin-bottom: 15px;
}

.testimonial-content h4 {
  font-size: 20px;
  margin-bottom: 5px;
  color: #333;
}

.testimonial-content .profession {
  font-size: 14px;
  color: #a8a8a8;
  margin-bottom: 15px;
  font-style: italic;
}

.testimonial-content .testimonial-text {
  font-size: 16px;
  color: #666;
  line-height: 1.6;
}

.testimonial-indicators {
  display: flex;
  justify-content: center;
  margin-top: 20px;
}

.testimonial-indicators .indicator {
  width: 15px;
  height: 15px;
  background-color: #e4b17c;
  border-radius: 50%;
  margin: 0 5px;
  cursor: pointer;
}

.testimonial-indicators .indicator.active {
  background-color: #40210f;
}
.reservation-container {
    position: relative;
    background-color: #4b3832;
    background-image: url(../assets/images/banner/bg.jpg);
    background-size: cover;
    background-position: center;
    color: #f8f4f0;
    padding: 40px;
    border-radius: 10px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    overflow: hidden; /* Ensures the overlay covers the container */
}

.reservation-container::before {
    content: "";
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.6); /* Dark overlay */
    z-index: 1; /* Places the overlay above the background image */
}

.reservation-container * {
    position: relative;
    z-index: 2; /* Ensures content is above the overlay */
}

.reservation-container h2 {
    font-size: 36px;
    font-weight: bold;
}

.reservation-container p {
    font-size: 16px;
}

.reservation-container .info {
    flex: 1;
    margin-right: 40px;
}

.reservation-container .info .discount {
    font-size: 48px;
    font-weight: bold;
    color: #ffb400;
}

.reservation-container .info .desc {
    margin: 20px 0;
}

.reservation-container .info .features {
    list-style: none;
    padding-left: 0;
}

.reservation-container .info .features li {
    margin-bottom: 10px;
}

.reservation-container .info .features li:before {
    content: "✔";
    color: #ffb400;
    margin-right: 10px;
}

.reservation-container form {
    flex: 1;
}

.reservation-container form input, 
.reservation-container form select {
    background-color: #3c2f2e;
    border: 1px solid #ffb400;
    color: #f8f4f0;
}

.reservation-container form input::placeholder, 
.reservation-container form select::placeholder {
    color: #f8f4f0;
}

.reservation-container form .form-label {
    color: #ffb400;
}

.reservation-container form .btn-primary {
    background-color: #ffb400;
    border: none;
}

.reservation-container form .btn-primary:hover {
    background-color: #ff9500;
}
.category-section .row {
    display: flex;
    flex-wrap: wrap;
    justify-content: flex-start; /* Align items to the start */
}

.category-card {
    width: 150%; /* Full width of the column */
    height: 250px;
    position: relative;
    overflow: hidden;
    border-radius: 10px;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    transition: transform .1s ease;
}

.category-card img {
    width: 100%;
    height: 100%; /* Ensure image covers the height of the card */
    object-fit: cover; /* Ensure the image covers the card without distortion */
}

.category-card .category-name {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    background-color: rgba(0, 0, 0, 0.7);
    color: #fff;
    text-align: center;
    padding: 10px;
    font-size: 1.1rem;
    font-weight: bold;
}

.category-card:hover {
    transform: scale(1.1,1.1);
}

  
.product-card {
        border: 1px solid #ddd;
        border-radius: 4px;
        overflow: hidden;
    }
    
    .product-card img {
        max-height: 200px;
        object-fit: cover;
    }
    
    .product-card .card-body {
        padding: 15px;
    }
    
    </style>
</head>
<body>

<!-- Header with transparent background -->
<header class="header">
    <div class="container-fluid px-4">
        <div class="d-flex align-items-center justify-content-between py-3 w-100">
            <!-- Left Section: Logo and Nav Links -->
            <div class="d-flex align-items-center">
                <a href="##" class="navbar-brand px-lg-4 m-0">
                    <h1 class="m-0 display-4 text-uppercase text-white font-weight-bold">KO艾弗EE</h1>
                </a>
                <ul class="nav me-lg-auto mb-2 mb-lg-0  font-weight-bold">
                    <li><a href="#" class="nav-link px-2 text-white ">HOME</a></li>
                    
                    <li><a href="view_orders.php" class="nav-link px-2 text-white">MY ORDER</a></li>
                </ul>
            </div>

            <!-- Right Section: Profile and Cart -->
            <div class="d-flex align-items-center">
                <!-- Cart Wheel Button -->
                <div class="cart-button-container me-3">
                    <button type="button" class="btn btn-light" data-toggle="modal" data-target="#orderModal">
                        My order
                    </button>
                </div>

                <!-- Profile Dropdown -->
                <div class="dropdown text-end">
                    <a href="#" class="d-block link-body-emphasis text-decoration-none  text-white dropdown-toggle " data-bs-toggle="dropdown" aria-expanded="false">
                        <img src="<?php echo $profile_image; ?>" alt="<?php echo htmlspecialchars($username); ?>" width="32" height="32" class="rounded-circle">
                        <?php echo htmlspecialchars($username); ?>
                    </a>
                    <ul class="dropdown-menu text-large">
                        <li><a class="dropdown-item" href="signup.php">new account</a></li>
                        <li><a class="dropdown-item" href="change_image.php">change_photo</a></li>
                        <li><a class="dropdown-item" href="myprofile.php">Profile</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="logout.php">Sign out</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</header>
   <!-- Order Modal -->
   <div class="modal fade" id="orderModal" tabindex="-1" aria-labelledby="orderModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="orderModalLabel">My Order</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Note</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody id="orderTable">
                        <!-- Products will be dynamically added here -->
                    </tbody>
                </table>
                <div class="total">
                    <h5>Total: $<span id="totalPrice">0.00</span></h5>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="checkoutButton">Checkout</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" aria-label="Close">Close</button>
            </div>
        </div>
    </div>
</div>
<!-- Carousel Section -->
<section class="carousel-section">
    <div id="carouselExample" class="carousel slide">
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="../assets/images/banner/carousel-1 (1).jpg" class="d-block w-100" alt="Place Image">
                <div class="carousel-caption d-none d-md-block">
                    <h1 class="animated-text"> <h2 class="text-primary font-weight-medium m-0">We Have Been Serving</h2>
                <h1 class="display-1 text-white m-0">CO艾弗EE</h1>
                <h2 class="text-white m-0">* SINCE 1950 *</h2></h1>
                </div>
            </div>
        </div>
    </div>
</section>

<hr class="gold-divider">
<!-- About Section -->
<section>
    <div class="container about-section">
        <div class="row mb-4">
            <div class="col-12">
                <p class="about-title">ABOUT US</p>
                <h2 class="about-heading">Serving Since 1950</h2>
            </div>
        </div>
        <div class="row align-items-center">
            <div class="col-md-4">
                <div class="about-text">
                    <h5>Our Story</h5>
                    <p>Eos kasd eos dolor vero vero, lorem stet diam rebum. Ipsum amet sed vero dolor sea.</p>
                    <p>Takimata sed vero vero vero, justo clita duo no duo amet et, nonumy kasd sed dolor eos diam lorem eirmod. Amet sit amet amet no. Est nonumy sed labore eirmod sit magna. Erat nonumy sed labore eirmod sit magna. Erat justo sed sed diam.</p>
                    <a href="#" class="about-btn">Learn More</a>
                </div>
            </div>
            <div class="col-md-4 about-image">
                <img src="../assets/images/banner/about.png" alt="Coffee Cup Image">
            </div>
            <div class="col-md-4">
                <div class="about-text">
                    <h5>Our Vision</h5>
                    <p>Invidunt lorem justo sanctus clita. Erat lorem labore ea, justo dolor lorem ipsum ut sed eos, ipsum et dolor kasd sit ea justo.</p>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">Lorem ipsum dolor sit amet</li>
                        <li class="list-group-item">Lorem ipsum dolor sit amet</li>
                        <li class="list-group-item">Lorem ipsum dolor sit amet</li>
                    </ul>
                    <a href="#" class="about-btn">Learn More</a>
                </div>
            </div>
        </div>
    </div>
</section>
<hr class="gold-divider">
<section class="category-section py-5">
    <div class="container">
        <div class="text-center mb-4">
            <h2 id="section-title">Categories</h2>
        </div>
        <div class="row">
            <!-- Category 1 -->
            <div class="col-md-4 mb-4 d-flex">
                <div class="category-card" data-category-id="1">
                    <img src="../assets/images/categories/images (6).jpg" class="img-fluid" alt="Category 1">
                    <div class="category-name">Hot Drink</div>
                </div>
            </div>
            <!-- Category 2 -->
            <div class="col-md-4 mb-4 d-flex">
                <div class="category-card" data-category-id="2">
                    <img src="../assets/images/categories/download.jpg" class="img-fluid" alt="Category 2">
                    <div class="category-name">Coffee</div>
                </div>
            </div>
            <!-- Category 3 -->
            <div class="col-md-4 mb-4 d-flex">
                <div class="category-card" data-category-id="3">
                    <img src="../assets/images/categories/2021_7_6_2_9_50_937.webp" class="img-fluid" alt="Category 3">
                    <div class="category-name">Fresh juices</div>
                </div>
            </div>
            <!-- Category 4 -->
            <div class="col-md-4 mb-4 d-flex">
                <div class="category-card" data-category-id="4">
                    <img src="../assets/images/categories/images (2).jpg" class="img-fluid" alt="Category 4">
                    <div class="category-name">Cold Drinks</div>
                </div>
            </div>
            <!-- Category 5 -->
            <div class="col-md-4 mb-4 d-flex">
                <div class="category-card" data-category-id="5">
                    <img src="../assets/images/categories/image.jpg" class="img-fluid" alt="Category 5">
                    <div class="category-name">Ice cream</div>
                </div>
            </div>
            <!-- Category 6 -->
            <div class="col-md-4 mb-4 d-flex">
                <div class="category-card" data-category-id="6">
                    <img src="../assets/images/categories/image23.jpg" class="img-fluid" alt="Category 6">
                    <div class="category-name">Sweets</div>
                </div>
            </div>
        </div>
    </div>



<hr class="gold-divider">
<h1>my products</h1>
    <section class="product-section py-5">
    <div class="container">
        <div id="product-list" class="row">
         
        </div>
    </div>
</section>

<hr class="gold-divider">
<div class="container mt-5">
        <div class="reservation-container">
            <div class="info">
                <div class="discount">30% OFF</div>
                <h2>For Online Reservation</h2>
                <p class="desc">Lorem justo clita erat lorem labore ea, justo dolor lorem ipsum ut sed eos, ipsum et dolor kasd sit ea justo. Erat justo sed sed diam. Ea et erat ut sed diam sea</p>
                <ul class="features">
                    <li>Lorem ipsum dolor sit amet</li>
                    <li>Lorem ipsum dolor sit amet</li>
                    <li>Lorem ipsum dolor sit amet</li>
                </ul>
            </div>
            <form action="home.php" method="POST">
                <div class="mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter your name" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
                </div>
                <div class="mb-3">
                    <label for="num_people" class="form-label">Number of People</label>
                    <input type="number" class="form-control" id="num_people" name="num_people" placeholder="Enter number of people" required>
                </div>
                <div class="mb-3">
                    <label for="reservation_time" class="form-label">Reservation Time</label>
                    <input type="datetime-local" class="form-control" id="reservation_time" name="reservation_time" required>
                </div>
                <div class="mb-3">
                    <label for="table_number" class="form-label">Table Number</label>
                    <select class="form-select" id="table_number" name="table_number" required>
                        <?php
                        include('../includes/db_connect.php');
                        $query = "SELECT table_number FROM rooms WHERE status = 'available'";
                        $result = $conn->query($query);
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo '<option value="' . htmlspecialchars($row['table_number']) . '">' . htmlspecialchars($row['table_number']) . '</option>';
                            }
                        } else {
                            echo '<option value="">No available tables</option>';
                        }
                        $conn->close();
                        ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary w-100">Reserve Table</button>
            </form>
        </div>
    </div>
<hr class="gold-divider">
<div class="testimonial-section">
  <div class="container">
    <h2 class="section-title">TESTIMONIAL</h2>
    <h3 class="section-subtitle">Our Clients Say</h3>
    <div class="testimonials">
      <div class="testimonial-item">
        <img src="../assets/images/USERS/21.jpg" alt="Client Image">
        <div class="testimonial-content">
          <h4>Client Name</h4>
          <p class="profession">Profession</p>
          <p class="testimonial-text">
            Sed ea amet kasd elit stet, stet rebum et ipsum est duo elirmod clita lorem. Dolor tempor ipsum sanct clita
          </p>
        </div>
      </div>
      <div class="testimonial-item">
        <img src="../assets/images/USERS/20.jpg" alt="Client Image">
        <div class="testimonial-content">
          <h4>Client Name</h4>
          <p class="profession">Profession</p>
          <p class="testimonial-text">
            Sed ea amet kasd elit stet, stet rebum et ipsum est duo elirmod clita lorem. Dolor tempor ipsum sanct clita
          </p>
        </div>
      </div>
      <div class="testimonial-item">
        <img src="../assets/images/USERS/22.jpg" alt="Client Image">
        <div class="testimonial-content">
          <h4>Client Name</h4>
          <p class="profession">Profession</p>
          <p class="testimonial-text">
            Sed ea amet kasd elit stet, stet rebum et ipsum est duo elirmod clita lorem. Dolor tempor ipsum sanct clita
          </p>
        </div>
      </div>
    </div>
   
  </div>
</div>
<div class="container">
  <footer class="py-5">
    <div class="row">
      <div class="col-6 col-md-2 mb-3">
        <h5>Section</h5>
        <ul class="nav flex-column">
          <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-body-secondary">Home</a></li>
          <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-body-secondary">Features</a></li>
          <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-body-secondary">Pricing</a></li>
          <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-body-secondary">FAQs</a></li>
          <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-body-secondary">About</a></li>
        </ul>
      </div>

      <div class="col-6 col-md-2 mb-3">
        <h5>Section</h5>
        <ul class="nav flex-column">
          <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-body-secondary">Home</a></li>
          <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-body-secondary">Features</a></li>
          <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-body-secondary">Pricing</a></li>
          <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-body-secondary">FAQs</a></li>
          <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-body-secondary">About</a></li>
        </ul>
      </div>

      <div class="col-6 col-md-2 mb-3">
        <h5>Section</h5>
        <ul class="nav flex-column">
          <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-body-secondary">Home</a></li>
          <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-body-secondary">Features</a></li>
          <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-body-secondary">Pricing</a></li>
          <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-body-secondary">FAQs</a></li>
          <li class="nav-item mb-2"><a href="#" class="nav-link p-0 text-body-secondary">About</a></li>
        </ul>
      </div>

      <div class="col-md-5 offset-md-1 mb-3">
        <form>
          <h5>Subscribe to our newsletter</h5>
          <p>Monthly digest of what's new and exciting from us.</p>
          <div class="d-flex flex-column flex-sm-row w-100 gap-2">
            <label for="newsletter1" class="visually-hidden">Email address</label>
            <input id="newsletter1" type="text" class="form-control" placeholder="Email address">
            <button class="btn btn-primary" type="button">Subscribe</button>
          </div>
        </form>
      </div>
    </div>

    <div class="d-flex flex-column flex-sm-row justify-content-between py-4 my-4 border-top">
      <p>© 2024 Company, Inc. All rights reserved.</p>
      <ul class="list-unstyled d-flex">
        <li class="ms-3"><a class="link-body-emphasis" href="#"><svg class="bi" width="24" height="24"><use xlink:href="#twitter"></use></svg></a></li>
        <li class="ms-3"><a class="link-body-emphasis" href="#"><svg class="bi" width="24" height="24"><use xlink:href="#instagram"></use></svg></a></li>
        <li class="ms-3"><a class="link-body-emphasis" href="#"><svg class="bi" width="24" height="24"><use xlink:href="#facebook"></use></svg></a></li>
      </ul>
    </div>
  </footer>
</div>
<!-- Bootstrap Scripts -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="../assets/js/home.js"></script>
<script src="../assets/js/home2.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    
$(document).ready(function() {
    // Fetch products based on the selected category
    $('.category-card').click(function() {
        var categoryId = $(this).data('category-id');
        $.ajax({
            url: 'fetch_products.php',
            type: 'GET',
            data: { category_id: categoryId },
            success: function(response) {
                $('#product-list').html(response);
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', status, error);
            }
        });
    });

    // Handle adding a product to the order
    $('#product-list').on('click', '.add-to-order', function() {
        var productId = $(this).data('product-id');
        var productName = $(this).data('product-name');
        var productPrice = $(this).data('product-price');
        var productImage = $(this).data('product-image');
        
        addToCart(productId, productName, productPrice, productImage);
    });

    // Handle dark mode toggle
    $('#darkModeToggle').on('change', function() {
        $('body').toggleClass('dark-mode');
    });

    // Initialize total price
    let totalPrice = 0;

    // Function to update the total price
    function updateTotalPrice() {
        $('#totalPrice').text(totalPrice.toFixed(2));
    }

    // Function to add product to the cart
    function addToCart(productPrice) {
    // تأكد من تحويل productPrice إلى عدد
    productPrice = parseFloat(productPrice);
    
    if (isNaN(productPrice)) {
        console.error("Invalid productPrice:", productPrice);
        return;
    }
    
    // الآن يمكن استخدام toFixed بدون مشاكل
    let formattedPrice = productPrice.toFixed(2);
    
    console.log("Formatted Price:", formattedPrice);

        // Create a table row for the product
        var row = $(`<tr data-product-id="${productId}"></tr>`);
        row.append(`<td><img src="${productImage}" alt="${productName}" class="img-thumbnail" style="width: 50px; height: auto;"></td>`);
        row.append(`<td>${productName}</td>`);
        row.append(`<td>$${productPrice.toFixed(2)}</td>`);
        
        // Add input fields for notes and quantity
        var quantityInput = `<input type="number" class="form-control w-50" value="1" min="1" />`;
        var notesInput = `<input type="text" class="form-control w-75" placeholder="Notes" />`;
        
        row.append(`<td>${quantityInput}</td>`);
        row.append(`<td>${notesInput}</td>`);
        row.append('<td><button class="btn btn-sm btn-danger remove-item">Remove</button></td>');

        // Append the row to the table
        $('#cart-items').append(row);

        // Update the total price
        totalPrice += productPrice;
        updateTotalPrice();
        
        // Show the modal
        $('#orderModal').modal('show');
    }

    // Click event handler for removing an item from the cart
    $('#cart-items').on('click', '.remove-item', function() {
        var row = $(this).closest('tr');
        var price = parseFloat(row.find('td:eq(2)').text().replace('$', ''));
        
        // Remove the row
        row.remove();
        
        // Update the total price
        totalPrice -= price;
        updateTotalPrice();
    });

    
});
$(document).ready(function() {
 
$('#checkoutButton').on('click', function() {
    
    const orderItems = [];
    $('#orderTable tr').each(function() {
        const productName = $(this).find('td:eq(1)').text();
        const productPrice = parseFloat($(this).find('td:eq(2)').text().replace('$', ''));
        const quantity = parseInt($(this).find('td:eq(3)').text());
        const note = $(this).find('td:eq(4)').text();
        
        if (productName) {
            orderItems.push({
                name: productName,
                price: productPrice,
                quantity: quantity,
                note: note
            });
        }
    });

    
    const orderData = {
        user_id:userId, 
        total_price: $('#totalPrice').text(),
        items: orderItems
    };

   
    $.ajax({
        type: 'POST',
        url: 'save_order.php', 
        data: {
            orderData: JSON.stringify(orderData)
        },
        success: function(response) {
            console.log('Order saved successfully:', response);
            
            $('#orderTable').empty();
            $('#totalPrice').text('0.00');
            $('#orderModal').modal('hide');
        },
        error: function(error) {
            console.error('Error saving order:', error);
        }
    });
});

});

</script>
<SCRIPT>

let index = 0;
const items = document.querySelectorAll('.opinion-item');
const totalItems = items.length;

document.querySelector('.next').addEventListener('click', () => {
    index = (index + 1) % totalItems;
    updateSlider();
});

document.querySelector('.prev').addEventListener('click', () => {
    index = (index - 1 + totalItems) % totalItems;
    updateSlider();
});

function updateSlider() {
    const offset = -index * 100;
    document.querySelector('.opinion-slider').style.transform = `translateX(${offset}%)`;
}

</SCRIPT>


</body>
</html>





</body>
</html>
