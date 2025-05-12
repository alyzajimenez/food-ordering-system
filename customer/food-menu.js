// DOM Elements
const searchBtn = document.getElementById('search-btn');
const mealList = document.getElementById('meal');
const showAllBtn = document.getElementById('show-all-btn'); // Optional "Show All" button

// Event Listeners
document.addEventListener('DOMContentLoaded', getAllMeals); // Load all meals on page load
searchBtn.addEventListener('click', getMealList); // Search functionality
mealList.addEventListener('click', addToCart); // Add to cart functionality

// Optional: Show all meals button
if (showAllBtn) {
    showAllBtn.addEventListener('click', getAllMeals);
}

// Get all meals when page loads
function getAllMeals() {
    fetch('https://www.themealdb.com/api/json/v1/1/search.php?s=')
        .then(response => response.json())
        .then(data => {
            displayMeals(data.meals);
        })
        .catch(error => {
            console.error('Error:', error);
            mealList.innerHTML = "Failed to load meals. Please try again later.";
            mealList.classList.add('notFound');
        });
}

// Get meal list that matches with the ingredients
function getMealList() {
    let searchInputTxt = document.getElementById('search-input').value.trim();
    
    if (!searchInputTxt) {
        getAllMeals();
        return;
    }

    fetch(`https://www.themealdb.com/api/json/v1/1/filter.php?i=${searchInputTxt}`)
        .then(response => response.json())
        .then(data => {
            displayMeals(data.meals);
        })
        .catch(error => {
            console.error('Error:', error);
            mealList.innerHTML = "Error searching for meals. Please try again.";
            mealList.classList.add('notFound');
        });
}

// Display meals in the DOM
function displayMeals(meals) {
    let html = "";
    
    if (meals) {
        meals.forEach(meal => {
            html += `
            <div class="meal-item" data-id="${meal.idMeal}">
                <div class="meal-img">
                    <img src="${meal.strMealThumb}" alt="${meal.strMeal}">
                </div>
                <div class="meal-name">
                    <h3>${meal.strMeal}</h3>
                    <a href="#" class="add-to-cart-btn" 
                        data-id="${meal.idMeal}" 
                        data-name="${meal.strMeal}" 
                        data-img="${meal.strMealThumb}">
                        Add to Cart
                    </a>
                </div>
            </div>
            `;
        });
        mealList.classList.remove('notFound');
    } else {
        html = "Sorry, we didn't find any meals!";
        mealList.classList.add('notFound');
    }
    
    mealList.innerHTML = html;
}

// Add to cart functionality
function addToCart(e) {
    e.preventDefault();
    
    if (e.target.classList.contains('add-to-cart-btn')) {
        const mealId = e.target.getAttribute('data-id');
        const mealName = e.target.getAttribute('data-name');
        const mealImg = e.target.getAttribute('data-img');
        
        // Send data to server
        fetch('add-to-cart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                meal_id: mealId,
                meal_name: mealName,
                meal_img: mealImg
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message
                const notification = document.createElement('div');
                notification.className = 'cart-notification';
                notification.textContent = `${mealName} added to cart!`;
                document.body.appendChild(notification);
                
                // Remove notification after 2 seconds
                setTimeout(() => {
                    notification.remove();
                }, 2000);
                
                // Update cart count in the sidebar if it exists
                const cartCount = document.querySelector('.cart-count');
                if (cartCount) {
                    const currentCount = parseInt(cartCount.textContent) || 0;
                    cartCount.textContent = currentCount + 1;
                }
            } else {
                alert('Failed to add to cart: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error adding to cart. Please try again.');
        });
    }
}

// Optional: Add this CSS for the notification
const style = document.createElement('style');
style.textContent = `
    .cart-notification {
        position: fixed;
        bottom: 20px;
        right: 20px;
        background-color: #4CAF50;
        color: white;
        padding: 15px;
        border-radius: 5px;
        z-index: 1000;
        animation: fadeIn 0.5s, fadeOut 0.5s 1.5s;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
    @keyframes fadeOut {
        from { opacity: 1; }
        to { opacity: 0; }
    }
`;
document.head.appendChild(style);