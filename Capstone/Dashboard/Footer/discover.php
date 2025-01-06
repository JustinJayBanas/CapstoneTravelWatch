<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TravelWatch - Albay</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header class="header">
        <div class="logo">
            <img src="logo.png" alt="TravelWatch Logo">
        </div>
        <div class="search-bar">
            <input type="text" placeholder="Search...">
        </div>
    </header>

    <section class="main-image">
        <img src="../images/cagsawa1.jpg" alt="Albay Landmark">
    </section>

    <nav class="navbar">
        <a href="../Header/home.php">Home</a>
        <a href="#">About</a>
        <a href="#">Destination</a>
        <a href="#">Activities</a>
        <a href="#">Tips</a>
    </nav>

    <section class="content">
        <div class="welcome-section">
            <h1>Welcome to Albay!</h1>
            <p>Explore the beauty and wonder of Albay, a province in the Bicol Region of the Philippines, known for its stunning natural landscapes, vibrant culture, and historic landmarks. Whether you are an adventure seeker, a history enthusiast, or a nature lover, Albay has something special for everyone.</p>
        </div>

        <div class="monthly-report">
            <h2>Monthly Recap Report</h2>
            <p>Stay informed with the latest tourism statistics in Albay. Our monthly recap report provides detailed insights into the number of visitors via various attractions, including both local and foreign tourists.</p>
            <div class="chart">
                <canvas id="touristChart"></canvas>
            </div>
        </div>

        <div class="trending-section">
            <h2>Trending with Tourists</h2>
            <div class="cards">
                <div class="card">
                    <img src="mayon-photography.jpg" alt="Mayon Photography">
                    <h3>Mayon Skyline Photography</h3>
                    <p>5.0 (210 reviews)</p>
                    <button>Learn More</button>
                </div>
                <div class="card">
                    <img src="atv-tour.jpg" alt="ATV Tour">
                    <h3>Mayon ATV Tours</h3>
                    <p>4.8 (180 reviews)</p>
                    <button>Learn More</button>
                </div>
                <div class="card">
                    <img src="cagsawa-ruins.jpg" alt="Cagsawa Ruins">
                    <h3>Cagsawa Ruins</h3>
                    <p>4.9 (145 reviews)</p>
                    <button>Learn More</button>
                </div>
            </div>
        </div>
    </section>

    <footer class="footer">
        <div class="footer-menu">
            <a href="#">Discover</a>
            <a href="#">Map</a>
            <a href="#">Review</a>
            <a href="#">Profile</a>
        </div>
    </footer>

    <script src="scripts.js"></script>
</body>
</html>
