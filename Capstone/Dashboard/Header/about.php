<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TravelWatch - Albay</title>
    <link rel="stylesheet" href="css/about.css">
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
        <a href="#">Home</a>
        <a href="#">About</a>
        <a href="#">Destination</a>
        <a href="#">Activities</a>
        <a href="#">Tips</a>
    </nav>

    <section class="about-albay">
        <h2>About Albay</h2>
        <div class="about-cards">
            <div class="about-card" onclick="showInfo('albay')">
                <img src="../images/albay.jpg" alt="This is Albay">
                <p>This is Albay</p>
            </div>
            <div class="about-card" onclick="showInfo('geography')">
                <img src="../images/albaymap.png" alt="Geography">
                <p>Geography</p>
            </div>
            <div class="about-card" onclick="showInfo('history')">
                <img src="../images/history.JPG" alt="History">
                <p>History</p>
            </div>
            <div class="about-card" onclick="showInfo('culture')">
                <img src="../images/cultural.jpg" alt="Language & Culture">
                <p>Language & Culture</p>
            </div>
        </div>

        <div class="about-text" id="about-text">
            <!-- This will be dynamically updated based on the clicked section -->
        </div>
        <script>
            const content = {
                albay: "Albay, located in the Bicol region of the Philippines, is renowned for its stunning natural landscapes and rich cultural heritage. The province is home to the majestic Mayon Volcano, known for its near-perfect cone shape and breathtaking beauty, making it a popular destination for hikers and nature enthusiasts.",
                geography: "Geographically, Albay features a diverse landscape that includes lush mountains, fertile plains, and coastal areas. The region is volcanic, with Mayon Volcano being the most prominent feature, which impacts the land and its inhabitants.",
                history: "Albay has a rich historical background that traces back to pre-colonial times. The province played significant roles during the Spanish colonization and the American occupation, making it an important part of Philippine history.",
                culture: "The culture of Albay is deeply rooted in the traditions of the Bicolanos, who are known for their distinct language and rich cultural practices. Festivals, cuisine, and strong family values are key aspects of life in Albay."
            };

            function showInfo(section) {
                // Remove active state from all cards
                const allCards = document.querySelectorAll('.about-card');
                allCards.forEach(c => c.classList.remove('active'));

                // Get the clicked card and set it as active
                const clickedCard = document.querySelector(`.about-card[onclick="showInfo('${section}')"]`);
                clickedCard.classList.add('active');

                // Update and show the about text section with corresponding content
                const aboutText = document.querySelector('#about-text');
                aboutText.innerHTML = `<p>${content[section]}</p>`;
                aboutText.classList.add('active');

                // Show the Fun Facts section only if the 'This is Albay' card is clicked
                const funFacts = document.querySelector('#fun-facts');
                if (section === 'albay') {
                    funFacts.style.display = 'block';  // Show fun facts for 'This is Albay'
                } else {
                    funFacts.style.display = 'none';   // Hide fun facts for other sections
                }
            }
        </script>
    </section>

    <!-- Fun Facts Section (Initially hidden) -->
    <section class="fun-facts" id="fun-facts" style="display: none;">
        <h2>Fun Facts</h2>
        <div class="fact-cards">
            <div class="fact-card">
                <p>Population: 1.3M</p>
            </div>
            <div class="fact-card">
                <p>Area: 2,552 km²</p>
            </div>
            <div class="fact-card">
                <p>Top Product: Abaca Fiber</p>
            </div>
            <div class="fact-card">
                <p>Main Language: Bikol</p>
            </div>
            <div class="fact-card">
                <p>Main Landmark: Mayon Volcano</p>
            </div>
        </div>
    </section>

    <!-- Culture and Language Section (Initially hidden) -->
    <section class="culture-language" id="culture-language" style="display: none;">
        <h2>Culture & Language</h2>
        <div class="culture-gallery">
            <div class="culture-item">
                <img src="../images/festival.jpg" alt="Ibalong Festival">
                <p>Ibalong Festival</p>
            </div>
            <div class="culture-item">
                <img src="../images/traditional_dance.jpg" alt="Traditional Dance">
                <p>Traditional Dance</p>
            </div>
            <div class="culture-item">
                <img src="../images/bikol_food.jpg" alt="Bikol Cuisine">
                <p>Bikol Cuisine</p>
            </div>
            <div class="culture-item">
                <img src="../images/crafts.jpg" alt="Handicrafts">
                <p>Local Handicrafts</p>
            </div>
            <div class="culture-item">
                <img src="../images/language.jpg" alt="Bikol Language">
                <p>Bikol Language</p>
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
