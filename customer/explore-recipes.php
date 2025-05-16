<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Find Meal For Your Ingredients</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" />
  <link rel="stylesheet" href="../assets/style.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
<style>

/* Sidebar */
.sidebar {
    width: 250px;
    background-color: #d65108;
    color: #fff;
    position: fixed;
    top: 0;
    left: 0;
    height: 100%;
    padding: 20px 0;
    box-shadow: 2px 0 10px rgba(0, 0, 0, 0.1);
}

.sidebar .logo {
    width: 150px;
    margin: 0 auto;
    display: block;
}

.sidebar ul {
    list-style: none;
    padding: 0;
}

.sidebar ul li {
    margin: 20px 0;
    text-align: center;
}

.sidebar ul li a {
    color: #fff;
    text-decoration: none;
    font-size: 18px;
    display: block;
    padding: 10px 20px;
    border-radius: 5px;
    transition: background-color 0.3s ease;
}

.sidebar ul li a:hover {
    background-color: #b54507;
}
/* Main Content */
.main-content {
    margin-left: 250px; 
    padding: 30px;
    width: calc(100% - 270px); 
    min-height: 100vh; 
    background-color: rgba(255, 255, 255, 0.9);
    border-radius: 15px;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    overflow: hidden;
}

/* Main Header */
header h2 {
    font-size: 28px;
    margin-bottom: 20px;
    color: #34495e;
}

/* Main Message Section */
#main-message h3 {
    font-size: 36px;
    color: #2c3e50;
    margin-top: 20px;
}

#main-message p {
    font-size: 18px;
    color: #7f8c8d;
    margin-top: 10px;
    line-height: 1.6;
}

/* Image Styling */
.image-item {
    width: 100%;
    max-width: 400px;
    height: auto;
    object-fit: cover;
    margin-top: 20px;
}

/* Adjustments for Small Screens (Mobile Devices) */
@media (max-width: 768px) {
    body {
        flex-direction: column; 
    }

    .sidebar {
        width: 100%; 
        position: static; 
        border-radius: 0;
        box-shadow: none;
    }

    .main-content {
        margin-left: 0; 
        width: 100%; 
        padding: 20px;
    }
}

</style>
</head>
<body>

  <div class="container">
    <div class="meal-wrapper">
      <div class="meal-search">
        <h2 class="title">Find Meals For Your Ingredients</h2>
        <blockquote>Real food doesn't have ingredients, real food is ingredients.<br>
          <cite>- Jamie Oliver</cite>
        </blockquote>

        <div class="meal-search-box">
          <input type="text" class="search-control" placeholder="Enter an ingredient" id="search-input">
          <button type="submit" class="search-btn btn" id="search-btn">
            <i class="fas fa-search"></i>
          </button>
        </div>
      </div>

 <div class="container">
    <nav class="sidebar">
        <a href="index.php">
            <img src="../assets/images/logo.png" alt="Food Tiger Logo" class="logo">
        </a>
            <ul>
             <li>
                <a href="food-menu.php">
                    <i class="fas fa-utensils nav-icon"></i> 
                    Food Menu
                </a>
            </li>
            <li>
                <a href="order-history.php">
                    <i class="fas fa-history nav-icon"></i> 
                    Order History
                </a>
            </li>
            <li>
                <a href="cart.php">
                    <i class="fas fa-shopping-cart nav-icon"></i> 
                    Cart
                </a>
            </li>
            <li>
                <a href="explore-recipes.php">
                    <i class="fas fa-book-open nav-icon"></i>  
                    Explore Recipes
                </a>
            </li>
                <li><a href="logout.php">Logout</a></li>
            </ul>
        </nav>

      <div class="meal-result">
        <h2 class="title">Your Search Results:</h2>
        <div id="meal">
          <!-- Results injected via JavaScript -->
        </div>
      </div>

      <div class="meal-details">
        <button type="button" class="btn recipe-close-btn" id="recipe-close-btn">
          <i class="fas fa-times"></i>
        </button>
        <div class="meal-details-content">
          <!-- Meal Details injected via JavaScript -->
        </div>
      </div>
    </div>
  </div>

  <script src="script.js"></script>
</body>
</html>
