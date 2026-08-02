<?php
session_start();


$loggedIn = isset($_SESSION["user_id"]);
$username = $loggedIn ? $_SESSION["user_name"] : "";


require 'config/database.php';

$query = "SELECT
            tools.id,
            tools.name,
            tools.description,
            categories.name AS category
          FROM tools
          JOIN categories
          ON tools.category_id = categories.id
          ORDER BY tools.id";

$stmt = $conn->prepare($query);
$stmt->execute();

$tools = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AIMatrix - Discover the Best AI Tools</title>
    <link rel="stylesheet" href="/aimatrix/css/style.css?v=2">
    <link rel="icon" type="image/x-icon" href="favicon.ico">
</head>
<body>
    <!-- Header -->
    <header class="header">
        <div class="container">
            <div class="header__content">
                <div class="header__logo">
                    <h1 class="logo">AIMatrix</h1>
                </div>
                
                <nav class="header__nav">
                    <a href="#" class="nav-link active" data-page="home">Home</a>
                    <a href="#" class="nav-link" data-page="categories">Categories</a>
                    <a href="#" class="nav-link" data-page="bookmarks">My Bookmarks</a>
                    <!-- <a href="#" class="nav-link" data-page="about">About</a> -->
                    <!-- <a href="#" class="nav-link" data-page="admin">Admin</a> -->
                </nav>

                <div class="header__actions">
                    <div class="search-container">
                        <input type="text" class="search-input form-control" placeholder="Search AI tools..." id="searchInput">
                        <span class="search-icon">🔍</span>
                    </div>
                    
<?php if ($loggedIn): ?>
<span style="margin-right:15px;font-weight:bold;">Welcome, <?php echo htmlspecialchars($username); ?></span>
<a href="/aimatrix/user/logout.php" class="btn btn--primary">Logout</a>
<?php else: ?>
<a href="/aimatrix/user/login.php" class="btn btn--primary">Login</a>
<?php endif; ?>
                    
                    <div class="user-dropdown hidden" id="userDropdown">
                        <button class="user-avatar" id="userInitials">U</button>
                        <div class="dropdown-menu">
                            <div class="dropdown-item" id="profileBtn">Profile</div>
                            <div class="dropdown-divider"></div>
                            <div class="dropdown-item" id="logoutBtn">Logout</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main>
        <!-- Home Page -->
        <div class="page active" id="homePage">
            <!-- Hero Section -->
            <section class="hero">
                <div class="container">
                    <h1 class="hero__title">Discover the Best AI Tools</h1>
                    <p class="hero__subtitle">Explore, bookmark, and rate the most powerful AI tools for your workflow</p>
                    
                    <div class="hero__stats">
                        <div class="stat">
                            <span class="stat__number" id="statsTools">15</span>
                            <span class="stat__label">AI Tools</span>
                        </div>
                        <div class="stat">
                            <span class="stat__number" id="statsCategories">6</span>
                            <span class="stat__label">Categories</span>
                        </div>
                        <div class="stat">
                            <span class="stat__number" id="statsUsers">247</span>
                            <span class="stat__label">Users</span>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Filters Section -->
            <section class="filters-section">
                <div class="container">
                    <div class="filters">
                        <div class="filter-group">
                            <label class="filter-label">Category:</label>
                            <select class="filter-select form-control" id="categoryFilter">
                                <option value="">All Categories</option>
                            </select>
                        </div>
                        
                        <div class="filter-group">
                            <label class="filter-label">Pricing:</label>
                            <select class="filter-select form-control" id="pricingFilter">
                                <option value="">All Pricing</option>
                                <option value="free">Free</option>
                                <option value="freemium">Freemium</option>
                                <option value="paid">Paid</option>
                            </select>
                        </div>

                        <div class="filter-group">
                            <label class="filter-label">Sort by:</label>
                            <select class="filter-select form-control" id="sortFilter">
                                <option value="rating">Highest Rated</option>
                                <option value="name">Name (A-Z)</option>
                                <option value="popularity">Most Popular</option>
                            </select>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Tools Section -->
            <section class="tools-section">
                <div class="container">
                    <div class="section-header">
                        <h2>Featured AI Tools</h2>
                        <span class="results-count" id="resultsCount">0 tools found</span>
                    </div>
                    
                    <div class="tools-grid" id="toolsGrid">
                        <!-- Tools will be loaded here -->
                    </div>
                </div>
            </section>
        </div>

        <!-- Categories Page -->
        <div class="page" id="categoriesPage">
            <section class="container">
                <div class="page-header">
                    <h1>Categories</h1>
                    <p>Explore AI tools by category</p>
                </div>
                
                <div class="categories-grid" id="categoriesGrid">
                    <!-- Categories will be loaded here -->
                </div>
            </section>
        </div>

        <!-- Bookmarks Page -->
        <div class="page" id="bookmarksPage">
            <section class="container">
                <div class="page-header">
                    <h1>My Bookmarks</h1>
                    <p>Your saved AI tools</p>
                </div>
                
                <div class="tools-grid" id="bookmarksGrid">
                    <!-- Bookmarked tools will be loaded here -->
                </div>
                
                <div class="no-results hidden" id="noBookmarks">
                    <p>You haven't bookmarked any tools yet. Start exploring!</p>
                </div>
            </section>
        </div>

        <!-- About Page -->
        <div class="page" id="aboutPage">
            <section class="container">
                <div class="about-content">
                    <div class="page-header">
                        <h1>About AIMatrix</h1>
                        <p>Your comprehensive AI tools directory</p>
                    </div>
                    
                    <div class="about-section">
                        <h3>Our Mission</h3>
                        <p>AIMatrix is dedicated to helping users discover, evaluate, and utilize the best AI tools available. We provide a comprehensive directory with user reviews, ratings, and detailed information.</p>
                    </div>

                    <div class="about-section">
                        <h3>Features</h3>
                        <ul>
                            <li>🔍 Comprehensive search and filtering</li>
                            <li>❤️ Bookmark your favorite tools</li>
                            <li>⭐ Rate and review tools</li>
                            <li>📱 Mobile-responsive design</li>
                            <li>🔐 Secure user authentication</li>
                        </ul>
                    </div>
                </div>
            </section>
        </div>

        <!-- Admin Page -->
        <!-- <div class="page" id="adminPage">
            <section class="container">
                <div class="page-header">
                    <h1>Admin Panel</h1>
                    <p>Manage tools and users</p>
                </div>
                
                <div class="admin-stats">
                    <div class="stat-card">
                        <h3>Total Tools</h3>
                        <p id="adminStatsTools">0</p>
                    </div>
                    <div class="stat-card">
                        <h3>Total Users</h3>
                        <p id="adminStatsUsers">0</p>
                    </div>
                    <div class="stat-card">
                        <h3>Total Reviews</h3>
                        <p id="adminStatsReviews">0</p>
                    </div>
                </div>

                <div class="admin-actions">
                    <button class="btn btn--primary" id="addToolBtn">Add New Tool</button>
                    <button class="btn btn--secondary" id="exportDataBtn">Export Data</button>
                    <button class="btn btn--secondary" id="importDataBtn">Import Sample Data</button>
                </div>
            </section>
        </div>
    </main> -->

    <!-- Auth Modal -->
    <div class="modal" id="authModal">
        <div class="modal__backdrop"></div>
        <div class="modal__content">
            <div class="modal__header">
                <h2 id="authModalTitle">Login</h2>
                <button class="modal__close" id="closeAuthModal">×</button>
            </div>
            <div class="modal__body">
                <form id="authForm">
                    <div class="form-group">
                        <label class="form-label" for="authEmail">Email</label>
                        <input type="email" class="form-control" id="authEmail" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="authPassword">Password</label>
                        <input type="password" class="form-control" id="authPassword" required>
                    </div>
                    <button type="submit" class="btn btn--primary btn--full-width" id="authSubmitBtn">Login</button>
                </form>
                
                <div class="auth-switch">
                    <p>Don't have an account? <a href="#" id="switchToSignup">Sign up</a></p>
                </div>
            </div>
        </div>
    </div>

    <!-- Feedback Modal -->
    <div class="modal" id="feedbackModal">
        <div class="modal__backdrop"></div>
        <div class="modal__content">
            <div class="modal__header">
                <h2>Rate & Review Tool</h2>
                <button class="modal__close" id="closeFeedbackModal">×</button>
            </div>
            <div class="modal__body">
                <div id="reviewsContainer">

</div>

<hr>
                <form id="feedbackForm">
                    <div class="form-group">
                        <label class="form-label">Rating</label>
                        <div class="star-rating" id="starRating">
                            <span class="star" data-rating="1">★</span>
                            <span class="star" data-rating="2">★</span>
                            <span class="star" data-rating="3">★</span>
                            <span class="star" data-rating="4">★</span>
                            <span class="star" data-rating="5">★</span>
                        </div>
                        <input type="hidden" id="feedbackRating" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label" for="feedbackComment">Comment</label>
                        <textarea class="form-control" id="feedbackComment" rows="4" maxlength="500" placeholder="Share your experience with this tool..."></textarea>
                        <div class="character-count">0/500</div>
                    </div>
                    <button type="submit" class="btn btn--primary btn--full-width">Submit Review</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Tool Detail Modal -->
    <div class="modal" id="toolModal">
        <div class="modal__backdrop"></div>
        <div class="modal__content tool-modal__content">
            <div class="modal__header">
                <h2 id="toolModalTitle">Tool Details</h2>
                <button class="modal__close" id="closeToolModal">×</button>
            </div>
            <div class="modal__body tool-modal__body">
                <!-- Tool content will be loaded here -->
            </div>
        </div>
    </div>

    <!-- Loading Spinner -->
    <div class="loading-spinner hidden" id="loadingSpinner">
        <div class="spinner"></div>
        <p>Loading...</p>
    </div>

    <!-- Toast Container -->
    <div class="toast-container" id="toastContainer">
        <!-- Toasts will be added here -->
    </div>

    <!-- Footer -->
    <footer class="footer">
        <div class="container">
            <div class="footer__content">
                <div class="footer__section">
                    <h4>AIMatrix</h4>
                    <p>Discover the best AI tools for your needs.</p>
                </div>
                <div class="footer__section">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="#" data-page="home">Home</a></li>
                        <li><a href="#" data-page="categories">Categories</a></li>
                        <li><a href="#" data-page="about">About</a></li>
                    </ul>
                </div>
                <div class="footer__section">
                    <h4>Support</h4>
                    <ul>
                        <li><a href="#">Help Center</a></li>
                        <li><a href="#">Contact Us</a></li>
                        <li><a href="#">Privacy Policy</a></li>
                    </ul>
                </div>
            </div>
            <div class="footer__bottom">
                <p>&copy; 2025 AIMatrix. All rights reserved.<br>
                         Made with ❤️ by Aimatrix Team.   
                </p>
            </div>
        </div>
    </footer>

    <!-- Supabase JS CDN -->
    <script src="https://unpkg.com/@supabase/supabase-js@2"></script>
    
    <!-- Application Script -->
    <script src="/aimatrix/js/app.js"></script>
</body>
</html>
