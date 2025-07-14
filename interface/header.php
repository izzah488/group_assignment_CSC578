<?php
// header.php
// This file contains the common HTML document start, head section, and the navigation bar.

// Ensure session is started if this file is included directly somewhere without session_start()
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Get the current page's filename for active link highlighting
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $pageTitle ?? 'Money Mate'; ?></title> <!-- Dynamic title -->
    <!-- Tailwind CSS CDN for utility classes -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts for Inter and Roboto -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Roboto:wght@400;500;700;900&display=swap" rel="stylesheet"/>

    <!-- ALL YOUR GLOBAL CSS GOES HERE -->
    <style>
        body {
            font-family: 'Inter', sans-serif;
            /* Background gradient for the body */
            background: linear-gradient(135deg, #f0f2f5 0%, #e0b0ff 100%);
            min-height: 100vh; /* Ensure body takes at least full viewport height */
            overflow-x: hidden; /* Prevent horizontal scrolling */
            display: flex; /* Use flexbox for overall layout */
            flex-direction: column; /* Stack children vertically */
        }

        h1, h2, .hero-text-gradient {
            font-family: 'Roboto', serif; /* Custom font for headings and hero text */
        }

        /* Navbar specific CSS */
        .navbar-gradient {
            /* Gradient background for the navigation bar */
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            backdrop-filter: blur(20px); /* Frosted glass effect */
            border-bottom: 1px solid rgba(255, 255, 255, 0.1); /* Subtle bottom border */
        }

        .nav-link {
            position: relative;
            transition: all 0.3s ease; /* Smooth transition for hover effects */
            color: #fff !important; /* Ensure text color is white for nav links */
        }

        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -5px; /* Position the underline below the text */
            left: 0;
            width: 0; /* Initially no width for the underline */
            height: 2px;
            background: white;
            transition: width 0.3s ease; /* Animate underline width on hover/active */
        }

        .nav-link:hover::after,
        .nav-link.active::after {
            width: 100%; /* Full width underline on hover or active */
        }

        /* Wrapper for the logo image with hover effect */
        .logo-image-wrapper {
            position: relative;
            overflow: hidden;
            /* Tailwind classes bg-white, rounded-full, p-2, shadow-md are applied directly in HTML */
        }

        .logo-image-wrapper::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            /* Gradient for the hover shimmer effect */
            background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transform: rotate(45deg);
            transition: all 0.6s ease; /* Smooth transition for the shimmer */
        }

        .logo-image-wrapper:hover::before {
            transform: rotate(45deg) translate(50%, 50%); /* Move shimmer on hover */
        }

        /* CTA Button (used in navbar and hero sections) */
        .cta-button {
            background: linear-gradient(135deg, #a259ff 0%, #6a11cb 100%); /* Purple gradient */
            color: #fff;
            font-weight: 700;
            font-size: 1.15rem;
            border-radius: 9999px; /* Fully rounded corners */
            box-shadow: 0 4px 16px 0 rgba(138,43,226,0.10); /* Subtle shadow */
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); /* Smooth transition for hover effects */
            position: relative;
            overflow: hidden; /* Hide overflow for shimmer effect */
        }

        .cta-button::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%; /* Start shimmer off-screen to the left */
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.18), transparent); /* Shimmer gradient */
            transition: left 0.5s; /* Animate shimmer across the button */
        }

        .cta-button:hover::before {
            left: 100%; /* Move shimmer across the button on hover */
        }

        .cta-button:hover {
            transform: scale(1.05) translateY(-2px); /* Slightly enlarge and lift on hover */
            box-shadow: 0 8px 24px rgba(138,43,226,0.18); /* Enhanced shadow on hover */
        }

        /* Hero section specific CSS (can be moved to page-specific CSS if not global) */
        .hero-text-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            font-weight: 700;
        }

        .hero-content {
            animation: fadeInUp 1s ease-out; /* Animation for hero text */
        }

        .hero-image {
            animation: fadeInRight 1s ease-out 0.3s both; /* Animation for hero image */
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeInRight {
            from { opacity: 0; transform: translateX(30px); }
            to { opacity: 1; transform: translateX(0); }
        }

        .image-container {
            position: relative;
            overflow: hidden;
            border-radius: 2rem;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
        }

        .image-container::before {
            content: '';
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
            z-index: 1;
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .image-container:hover::before {
            opacity: 1;
        }

        .image-container img {
            transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .image-container:hover img {
            transform: scale(1.05);
        }

        .feature-card {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 1.5rem;
            transition: all 0.3s ease;
        }

        .feature-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }

        .text-shadow {
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        /* Login card specific CSS (can be moved to page-specific CSS if not global) */
        .login-card {
            background: linear-gradient(135deg, #fff 80%, #f3e8ff 100%);
            padding: 2.5rem 2.5rem 2rem 2.5rem;
            border-radius: 2rem;
            box-shadow: 0 8px 32px 0 rgba(138,43,226,0.10), 0 1.5px 6px 0 rgba(138,43,226,0.08);
            max-width: 26rem;
            width: 100%;
            margin-top: 7rem; /* Adjusted for fixed header */
            transition: transform 0.3s;
        }
        .login-card:hover {
            transform: scale(1.03);
        }
        .login-btn {
            background: linear-gradient(to right, #a259ff, #6a11cb);
            color: white;
            font-weight: 700;
            border-radius: 1.2rem;
            box-shadow: 0 2px 12px 0 rgba(138,43,226,0.13);
            transition: filter 0.2s, transform 0.2s;
            font-size: 1.15rem;
            padding-top: 1rem;
            padding-bottom: 1rem;
        }
        .login-btn:hover {
            filter: brightness(1.08);
            transform: scale(1.04);
        }
        input:focus {
            outline: none;
            border-color: #a259ff;
            box-shadow: 0 0 0 2px #e0b0ff;
        }
        .back-btn {
            background: #f3e8ff;
            color: #a259ff;
            border: none;
            transition: background 0.2s, color 0.2s;
        }
        .back-btn:hover {
            background: #e0b0ff;
            color: #6a11cb;
        }

        /* About Us page specific styles (can be moved to external CSS or page-specific CSS) */
        .main-content {
            animation: fadeInUp 1s ease-out;
        }
        .team-card, .quote-card {
            animation: fadeInRight 1s ease-out 0.3s both;
        }
        .quote-card {
            background: #f9d6f7;
            border-radius: 1.5rem;
            padding: 2.5rem 2rem 2rem 2rem;
            box-shadow: 0 8px 32px 0 rgba(138,43,226,0.10);
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        .quote-text {
            color: #a259ff;
            font-style: italic;
            font-size: 1.4rem;
            text-align: center;
            margin-bottom: 1.5rem;
        }
        .quote-btn {
            background: #b57be4;
            color: #fff;
            font-weight: 500;
            border-radius: 1rem;
            padding: 0.7rem 2rem;
            font-size: 1rem;
            margin-bottom: 1.5rem;
            box-shadow: 0 2px 8px 0 rgba(138,43,226,0.10);
            transition: filter 0.2s, transform 0.2s;
        }
        .quote-btn:hover {
            filter: brightness(1.08);
            background: #a259ff;
            color: #fff;
            transform: scale(1.04);
        }
        .team-card {
            background: #e5e7eb;
            border-radius: 2rem;
            padding: 2rem 1.5rem 1.5rem 1.5rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            box-shadow: 0 4px 16px 0 rgba(138,43,226,0.08);
            min-width: 220px;
        }
        .team-img {
            width: 120px;
            height: 120px;
            object-fit: cover;
            border-radius: 50%;
            margin-bottom: 1.2rem;
            border: 4px solid #fff;
            box-shadow: 0 2px 8px rgba(138,43,226,0.10);
        }
        .contact-gradient {
            background: linear-gradient(90deg, #a259ff 0%, #f472b6 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            text-fill-color: transparent;
            font-weight: 800;
        }
        .scroll-top-btn {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            background: #111;
            color: #fff;
            border-radius: 50%;
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 2rem;
            box-shadow: 0 4px 16px 0 rgba(0,0,0,0.12);
            cursor: pointer;
            z-index: 50;
            transition: background 0.2s;
        }
        .scroll-top-btn:hover {
            background: #6a11cb;
        }

        /* Responsive adjustments for various sections */
        @media (max-width: 1024px) { /* For main-content layout on medium screens */
            .main-content.flex-row {
                flex-direction: column;
                gap: 2rem;
            }
            .main-content .left, .main-content .right {
                width: 100%;
                max-width: 100%;
            }
        }
        @media (max-width: 768px) { /* Adjust for typical tablet sizes */
            /* Mobile menu styles */
            .mobile-menu {
                display: none; /* Hidden by default */
                flex-direction: column;
                width: 100%;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                position: absolute;
                top: 100%; /* Position below the main navbar */
                left: 0;
                padding: 1rem 0;
                box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
                border-top: 1px solid rgba(255, 255, 255, 0.1);
                z-index: 9; /* Ensure it's above other content but below the fixed header */
            }
            .mobile-menu.open {
                display: flex; /* Show when open */
            }
            .mobile-menu a {
                padding: 0.75rem 1.5rem;
                text-align: center;
                color: #fff;
                font-size: 1.1rem;
                transition: background 0.2s ease;
            }
            .mobile-menu a:hover {
                background: rgba(255, 255, 255, 0.1);
            }
            .mobile-menu .cta-button {
                margin: 1rem auto; /* Center the button in mobile menu */
                width: calc(100% - 3rem); /* Adjust width to fit padding */
            }
        }

        @media (max-width: 640px) { /* For smaller screens (mobile) */
            .navbar-gradient .container {
                flex-direction: row; /* Keep logo and hamburger in a row */
                justify-content: space-between;
                align-items: center;
            }
            /* Adjust padding for login card and main content on small screens */
            .login-card {
                padding: 1.5rem 1rem 1rem 1rem;
                margin-top: 4rem;
            }
            .main-content {
                padding: 1rem;
            }
            .quote-card {
                padding: 1.2rem 0.7rem 1rem 0.7rem;
            }
        }
    </style>
    <!-- END OF ALL YOUR GLOBAL CSS -->
</head>
<body class="min-h-screen flex flex-col">
    <!-- Your Navigation Bar HTML -->
    <nav class="navbar-gradient w-full fixed top-0 left-0 z-50 shadow-lg">
        <div class="container mx-auto flex justify-between items-center px-4 py-3">
            <div class="flex items-center space-x-4">
                <!-- Wrapper for the logo image only -->
                <div class="logo-image-wrapper bg-white rounded-full p-2 shadow-md">
                    <img src="https://img.icons8.com/color/48/000000/combo-chart--v2.png" alt="Money Mate Logo" class="w-7 h-7">
                </div>
                <!-- Money Mate text is now outside the white background div -->
                <span class="text-2xl font-semibold text-white ml-2">Money Mate</span>
            </div>
            <!-- Desktop Navigation Links -->
            <div class="hidden md:flex items-center space-x-10">
                <!-- Dynamic active link highlighting with PHP -->
                <a href="home.php" class="nav-link text-lg font-medium <?php echo ($currentPage == 'home.php') ? 'active' : ''; ?>">HOME</a>
                <a href="about_us_page2.php" class="nav-link text-lg font-medium <?php echo ($currentPage == 'about_us_page2.php') ? 'active' : ''; ?>">ABOUT US</a>
                <a href="login.php" class="nav-link text-lg font-medium <?php echo ($currentPage == 'login.php') ? 'active' : ''; ?>">LOGIN</a>
                <button class="cta-button px-8 py-2 rounded-full shadow-xl font-semibold text-lg ml-4">
                    <a href="signup.php">SIGN UP</a>
                </button>
            </div>
            <!-- Mobile Menu Toggle Button (Hamburger) -->
            <div class="md:hidden">
                <button id="mobile-menu-button" class="text-white hover:text-gray-200 transition duration-300">
                    <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Mobile Navigation Menu (hidden by default) -->
        <div id="mobile-menu" class="mobile-menu md:hidden">
            <!-- Dynamic active link highlighting with PHP for mobile links -->
            <a href="home.php" class="nav-link <?php echo ($currentPage == 'home.php') ? 'active' : ''; ?>">HOME</a>
            <a href="about_us_page2.php" class="nav-link <?php echo ($currentPage == 'about_us_page2.php') ? 'active' : ''; ?>">ABOUT US</a>
            <a href="login.php" class="nav-link <?php echo ($currentPage == 'login.php') ? 'active' : ''; ?>">LOGIN</a>
            <button class="cta-button px-8 py-2 rounded-full shadow-xl font-semibold text-lg">
                <a href="signup.php">SIGN UP</a>
            </button>
        </div>
    </nav>
    <!-- The closing </body> and </html> tags, along with the JavaScript, will be added after your main content in a separate footer/script file. -->

    <!-- JavaScript for Mobile Menu Toggle -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const mobileMenuButton = document.getElementById('mobile-menu-button');
            const mobileMenu = document.getElementById('mobile-menu');

            // Toggle mobile menu visibility when the hamburger icon is clicked
            mobileMenuButton.addEventListener('click', function() {
                mobileMenu.classList.toggle('open');
            });

            // Close mobile menu when a link inside it is clicked (optional, but good UX)
            mobileMenu.querySelectorAll('a').forEach(link => {
                link.addEventListener('click', function() {
                    mobileMenu.classList.remove('open');
                });
            });

            // Close mobile menu if clicked outside of it (optional)
            document.addEventListener('click', function(event) {
                const isClickInsideNav = mobileMenu.contains(event.target) || mobileMenuButton.contains(event.target);
                if (!isClickInsideNav && mobileMenu.classList.contains('open')) {
                    mobileMenu.classList.remove('open');
                }
            });
        });
    </script>
