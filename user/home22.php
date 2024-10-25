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

// Fallback for profile image if not set
$profile_image = $user['profile_image'] ? '../uploads/' . $user['profile_image'] : 'https://via.placeholder.com/32';
$username = $user['username'];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Home</title>
    <link rel="stylesheet" href="../assets/css/user/home.css">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
   <style>
        body,
    html {
        margin: 0;
        padding: 0;
        height: 100%;
        background-color: antiquewhite;
    }
  
    
    .navbar {
        background-color: #f8f9fa;
    }
    /* Style for the logo */
    
    .navbar-brand img {
        max-height: 70px;
    }
    /* Center the navigation links */
    
    .navbar-nav {
        margin: 0 auto;
    }
    /* Style for the dark mode toggle button */
    
    
    /* Style for the cart button */
    
    #cartButton {
        font-size: 1.25rem;
    }
    
    .form-control {
        border-radius: 0.25rem;
        /* Slightly rounded corners for the input */
    }
    
    .cart-button-container .btn {
        background-color: #007bff;
        /* Primary button color */
        border: none;
        /* Remove default border */
    }
    
    .cart-button-container .btn:hover {
        background-color: #0056b3;
        
    }
    
    .dropdown-menu {
        min-width: 10rem;
       
    }
    
    .dropdown-item {
        color: #000;
      
    }
    
    .dropdown-item:hover {
        background-color: #f1f1f1;
        /* Background color on hover */
    }
    
    .form-check-input:checked {
        background-color: #007bff;
        /* Dark mode toggle color */
    }
    
    .content {
        height: 100%;
        background: url('') no-repeat center center;
        background-size: cover;
        position: relative;
        z-index: 1;
    }
    
    .navbar-nav a {
        color: white !important;
    }
    
    .dropdown-submenu {
        position: relative;
    }
    
    .dropdown-submenu .dropdown-menu {
        top: 0;
        left: 100%;
        margin-top: -1px;
    }
    
    section {
        padding: 2rem 0;
    }
    
    .counter-section {
        background-color: #f8f9fa;
        /* Light background for the counter section */
    }
    
    .category-section {
        background-color: #ffffff;
        /* White background for the category section */
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
    /* Apply the animation to the carousel-caption */
    
    .carousel-caption {
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        text-align: center;
        animation: fadeInUp 1s ease-out;
    
    }
    
    .carousel-caption h2,
    .carousel-caption h1 {
        margin-bottom: 0.5rem;
    
        opacity: 0;
    
        animation: fadeInUp 1s ease-out forwards;
       
    }
    
    .carousel-caption h1 {
        color: rgb(213, 182, 9);
    }
   
    
    .carousel-caption h2:first-of-type {
        animation-delay: 0.5s;
    }
    
    .carousel-caption h1 {
        animation-delay: 1s;
    }
    
    .carousel-caption h2:last-of-type {
        animation-delay: 1.5s;
    }
    
    .category-section {
        position: relative;
        padding: 3rem 0;
       background-color: antiquewhite;
    }
    
    .section-title {
        font-size: 2rem;
        /* Adjust font size as needed */
        font-weight: bold;
        color: #23a708;
        /* Adjust text color as needed */
        margin: 0;
        text-align: center;
        font-family: Impact, Haettenschweiler, 'Arial Narrow Bold', sans-serif;
    }
    
    .category-card {
        position: relative;
        overflow: hidden;
        border-radius: .375rem;
        box-shadow: 0 4px 8px rgba(94, 3, 3, 0.767);
        margin-bottom: 1rem;
        height: 300px;
        width: 300px;
    }
    
    .category-card img {
        width: 240px;
        height: 290px;
        object-fit: cover;
        transition: transform 0.3s ease;
    }
    
    .category-card:hover img {
        transform: scale(1.1);
    }
    
    .category-name {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        padding: 1rem;
        background: rgba(0, 0, 0, 0.5);
        color: #fff;
        text-align: center;
        font-size: 1.25rem;
        transform: translateY(100%);
        transition: transform 0.5s ease;
    }
    
    .category-card:hover .category-name {
        transform: translateY(0);
    }
    /* Flexbox to ensure categories fit in a single row */
    
    .category-section .row {
        display: flex;
        flex-wrap: nowrap;
        overflow-x: auto;
    }
    
    .category-section .col-md-4 {
        flex: 0 0 auto;
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
    
    .reservation-container {
        padding: 40px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(246, 246, 246, 0.973);
        width: 400px;
        background-image: url("../assets/images/banner/bg.jpg");
       
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
        background-blur: 10px;
        /* Optional: adds a blur effect to the background image */
        color: white;
        /* Ensures text is visible on top of the image */
    }
    
    .reservation-container h2 {
        text-align: center;
        margin-bottom: 20px;
        font-size: 40px;
        color: azure;
    }
    
    .reservation-container label {
        display: block;
        margin-bottom: 5px;
        color: rgb(255, 255, 255);
        font-size: 18px;
        /* Adjusted for better readability */
        font-family: Impact, 'Arial Narrow Bold', sans-serif;
    }
    
    .reservation-container input,
    .reservation-container select {
        width: 100%;
        padding: 10px;
        margin-bottom: 10px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }
    
    .reservation-container button {
        width: 100%;
        padding: 10px;
        border: none;
        border-radius: 5px;
        background-color: #007BFF;
        color: white;
        font-size: 16px;
    }
    
    .reservation-container button:hover {
        background-color: #0056b3;
    }
    
    .reservation-container .error {
        color: red;
        margin-bottom: 10px;
        text-align: center;
    }
    
    .reservation-container a {
        display: block;
        text-align: center;
        margin-top: 10px;
        color: #007BFF;
        text-decoration: none;
    }
    
    .reservation-container a:hover {
        text-decoration: underline;
    }
    
    .modal-dialog-fullscreen {
        max-width: 100vw;
        height: 100vh;
        margin: 0;
    }
    
    .modal-content {
        height: 100%;
        border-radius: 0;
    }
    
    .modal-header,
    .modal-body,
    .modal-footer {
        height: auto;
    }
    
    .container {
        background-color: #ffffff;
        padding: 30px;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    
    h2 {
        margin-bottom: 20px;
        color: #343a40;
    }
    
    .form-label {
        font-weight: 600;
    }
    
    .btn-primary {
        background-color: #007bff;
        border-color: #007bff;
    }
    
    .btn-primary:hover {
        background-color: #0056b3;
        border-color: #004085;
    }




    .opinion-slider-container {
    position: relative;
    width: 80%;
    margin: auto;
    overflow: hidden;
}

.opinion-slider {
    display: flex;
    transition: transform 0.5s ease-in-out;
    flex-direction: column; /* Stack items vertically */
}

.opinion-item {
    display: flex;
    align-items: center;
    width: 100%;
    padding: 20px;
    box-sizing: border-box;
    border-bottom: 1px solid #ddd; /* Optional: add a border between items */
}

.photo-container {
    flex: 0 0 auto;
    width: 120px; /* Fixed width for images */
    text-align: center;
}

.profile-image {
    width: 100px;
    height: 100px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #ccc; /* Optional: adds a border around the image */
}

.feedback-container {
    flex: 1;
    padding-left: 20px;
}

.feedback-banner {
    background-color: #f8f8f8;
    padding: 10px;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1); /* Optional: adds a subtle shadow */
}

.btn {
    margin-top: 10px;
    padding: 10px 20px;
    border: none;
    background-color: #007bff;
    color: white;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.btn:hover {
    background-color: #0056b3;
}

.prev, .next {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    background-color: #007bff;
    color: white;
    border: none;
    padding: 10px;
    border-radius: 5px;
    cursor: pointer;
    transition: background-color 0.3s ease;
}

.prev:hover, .next:hover {
    background-color: #0056b3;
}

.prev {
    left: 10px;
}

.next {
    right: 10px;
}
.footer-background {
    width: 100%;
  background-image: url('../assets/images/banner/115c224b-fb13-4b20-b9bd-526d54ae9666.jpg'); 
  background-size: cover;
  background-position: center;
  background-repeat: no-repeat;
  color: black; 
  font-family: 'Lucida Sans', 'Lucida Sans Regular', 'Lucida Grande', 'Lucida Sans Unicode', Geneva, Verdana, sans-serif;
  font-size: larger;
  text-align: center;
font-style: oblique;
}
.footer-background h5 {
  color: blue; 
}

.footer-background .nav-link {
  color:blak; /* Ensure nav links are visible on the background */
}

.footer-background .btn-primary {
  background-color: #23a708; /* Adjust the button color if needed */
  border: solid;
}

.footer-background .border-top {
  border-color: rgba(255, 255, 255, 0.1); /* Adjust the border color for better visibility */
}



   </style>
   
</head>
<body>
<div class="container-fluid p-0">
    <!-- Header -->
    <header class="header">
        <div class="container-fluid px-4">
            <div class="d-flex align-items-center justify-content-between py-3 w-100">
                <!-- Left Section: Logo and Nav Links -->
                <div class="d-flex align-items-center">
                <a href="##" class="navbar-brand px-lg-4 m-0">
                <h1 class="m-0 display-4 text-uppercase text-gold font-weight-bold ">KO艾弗EE</h1>
            </a>
                    <ul class="nav me-lg-auto mb-2 mb-lg-0 text-gold font-weight-bold" >
                        <li><a href="#" class="nav-link px-2">HOME</a></li>
                        <li><a href="view_orders.php" class="nav-link px-2">MY ORDER</a></li>
                    </ul>
                </div>

               

                <!-- Right Section: Profile and Cart -->
                <div class="d-flex align-items-center">
               
                    
                    <!-- Cart Wheel Button -->
                    <div class="cart-button-container me-3">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#orderModal">
                        My order
                    </button>
                    </div>

                    <!-- Profile Dropdown -->
                    <div class="dropdown text-end">
                        <a href="#" class="d-block link-body-emphasis text-decoration-none dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
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
</div>

<!-- Full-Width Full-Height Slider (Carousel) -->
<div id="userCarousel" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
        <div class="carousel-item active">
            <img src="../assets/images/slider/360_F_315403482_MVo1gSOOfvwCwhLZ9hfVSB4MZuQilNrx.jpg" class="d-block w-100 img-fluid" alt="Slide 1">
            <div class="carousel-caption d-flex h-100 align-items-center justify-content-center flex-column">
                <h2 class="text-primary font-weight-medium m-0">We Have Been Serving</h2>
                <h1 class="display-1 text-white m-0">CO艾弗EE</h1>
                <h2 class="text-white m-0">* SINCE 1950 *</h2>
            </div>
        </div>
    </div>
</div>

<div class="container-fluid py-5">
        <div class="container">
            <div class="section-title">
                <h4 class="text-primary text-uppercase" style="letter-spacing: 5px;">About Us</h4>
                <h1 class="display-4"display="center">Serving Since 1950</h1>
            </div>
            <div class="row">
                <div class="col-lg-4 py-0 py-lg-5">
                    <h1 class="mb-3">Our Story</h1>
                    <h5 class="mb-3">Eos kasd eos dolor vero vero, lorem stet diam rebum. Ipsum amet sed vero dolor sea</h5>
                    <p>Takimata sed vero vero no sit sed, justo clita duo no duo amet et, nonumy kasd sed dolor eos diam lorem eirmod. Amet sit amet amet no. Est nonumy sed labore eirmod sit magna. Erat at est justo sit ut. Labor diam sed ipsum et eirmod</p>
                    <a href="" class="btn btn-secondary font-weight-bold py-2 px-4 mt-2">Learn More</a>
                </div>
                <div class="col-lg-4 py-5 py-lg-0" style="min-height: 500px;">
                    <div class="position-relative h-100">
                        <img class="position-absolute w-100 h-100" src="../assets/images/banner/about.png" style="object-fit: cover;">
                    </div>
                </div>
                <div class="col-lg-4 py-0 py-lg-5">
                    <h1 class="mb-3">Our Vision</h1>
                    <p>Invidunt lorem justo sanctus clita. Erat lorem labore ea, justo dolor lorem ipsum ut sed eos, ipsum et dolor kasd sit ea justo. Erat justo sed sed diam. Ea et erat ut sed diam sea ipsum est dolor</p>
                    <h5 class="mb-3"><i class="fa fa-check text-primary mr-3"></i>Lorem ipsum dolor sit amet</h5>
                    <h5 class="mb-3"><i class="fa fa-check text-primary mr-3"></i>Lorem ipsum dolor sit amet</h5>
                    <h5 class="mb-3"><i class="fa fa-check text-primary mr-3"></i>Lorem ipsum dolor sit amet</h5>
                    <a href="" class="btn btn-primary font-weight-bold py-2 px-4 mt-2">Learn More</a>
                </div>
            </div>
        </div>
    </div>

    <section class="category-section py-5">
    <div class="container">
        <div class="text-center mb-4">
            <h2 id="section-title">Categories</h2>
        </div>
        <div class="row">
            <!-- Category 1 -->
            <div class="col-md-3 mb-4 d-flex">
                <div class="category-card" data-category-id="1">
                    <img src="../assets/images/categories/images (6).jpg" class="img-fluid" alt="Category 1">
                    <div class="category-name">Hot Drink</div>
                </div>
            </div>
            <!-- Category 2 -->
            <div class="col-md-3 mb-4 d-flex">
                <div class="category-card" data-category-id="2">
                    <img src="../assets/images/categories/download.jpg" class="img-fluid" alt="Category 2">
                    <div class="category-name">Coffee</div>
                </div>
            </div>
            <!-- Category 3 -->
            <div class="col-md-3 mb-4 d-flex">
                <div class="category-card" data-category-id="3">
                    <img src="../assets/images/categories/2021_7_6_2_9_50_937.webp" class="img-fluid" alt="Category 3">
                    <div class="category-name">Fresh juices</div>
                </div>
            </div>
            <!-- Category 4 -->
            <div class="col-md-3 mb-4 d-flex">
                <div class="category-card" data-category-id="4">
                    <img src="../assets/images/categories/images (2).jpg" class="img-fluid" alt="Category 4">
                    <div class="category-name">Cold Drinks</div>
                </div>
            </div>
            <!-- Category 5 -->
            <div class="col-md-3 mb-4 d-flex">
                <div class="category-card" data-category-id="5">
                    <img src="../assets/images/categories/image.jpg" class="img-fluid" alt="Category 5">
                    <div class="category-name">Ice cream</div>
                </div>
            </div>
            <!-- Category 6 -->
            <div class="col-md-3 mb-4 d-flex">
                <div class="category-card" data-category-id="6">
                    <img src="../assets/images/categories/image23.jpg" class="img-fluid" alt="Category 6">
                    <div class="category-name">Sweets</div>
                </div>
            </div>
        </div>
    </div>
</section>


    <section class="product-section py-5">
    <div class="container">
        <div id="product-list" class="row">
         <h1>my products</h1>
        </div>
    </div>
</section>




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

<section class="category-section py-5">
    <div class="container mt-5">
        <h2 text-alignCENTER>Reserve a Table</h2>
        <form action="reserve_table.php" method="POST">
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="num_people" class="form-label">Number of People</label>
                <input type="number" class="form-control" id="num_people" name="num_people" required>
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
            <button type="submit" class="btn btn-primary CENTER">Reserve Table</button>
        </form>
    </div>
</section>



    
 
    

<section>
  <footer class="py-5 footer-background">
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

</section>
<!-- Bootstrap JS Bundle with Popper -->

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
   // Add an event listener for the Checkout button
$('#checkoutButton').on('click', function() {
    // Collect order details from the modal
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

    // Send data to the server
    $.ajax({
        type: 'POST',
        url: 'save_order.php', // Server-side script to handle the data
        data: {
            orderData: JSON.stringify(orderData)
        },
        success: function(response) {
            console.log('Order saved successfully:', response);
            // Clear the modal
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


