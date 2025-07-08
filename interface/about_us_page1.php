<?php // For future PHP logic ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Money Mate</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: #fff;
        }
        .navbar {
            background: linear-gradient(90deg, #a259ff 0%, #e0b0ff 100%);
            box-shadow: 0 8px 32px 0 rgba(138,43,226,0.10), 0 1.5px 6px 0 rgba(138,43,226,0.08);
        }
        .navbar .active,
        .navbar a.bg-white {
            background: #d1b3ff;
            color: #6a11cb !important;
        }
        .navbar a {
            transition: background 0.2s, color 0.2s;
            border-radius: 1.5rem;
            padding: 0.5rem 1.5rem;
        }
        .navbar a:hover {
            background: #e0b0ff;
            color: #6a11cb !important;
        }
        .cta-btn {
            background: #c299fc;
            color: #fff;
            font-weight: 600;
            border-radius: 1.5rem;
            padding: 0.9rem 2.2rem;
            font-size: 1.1rem;
            box-shadow: 0 2px 8px 0 rgba(138,43,226,0.10);
            transition: filter 0.2s, transform 0.2s;
        }
        .cta-btn:hover {
            filter: brightness(1.08);
            background: #a259ff;
            color: #fff;
            transform: scale(1.04);
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
        .scroll-top {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            background: #a259ff;
            color: #fff;
            border-radius: 50%;
            width: 48px;
            height: 48px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 2px 8px 0 rgba(138,43,226,0.15);
            cursor: pointer;
            z-index: 50;
            transition: background 0.2s;
        }
        .scroll-top:hover {
            background: #6a11cb;
        }
        @media (max-width: 1024px) {
            .main-content {
                flex-direction: column;
                gap: 2rem;
            }
            .left, .right {
                width: 100%;
                max-width: 100%;
            }
        }
        @media (max-width: 640px) {
            .main-content {
                padding: 1rem;
            }
            .quote-card {
                padding: 1.2rem 0.7rem 1rem 0.7rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar w-full px-0 py-0">
        <div class="container mx-auto px-4 py-3 flex justify-between items-center">
            <div class="flex items-center space-x-3">
                <div class="bg-white p-2 rounded-full shadow-md">
                    <img src="https://img.icons8.com/color/48/000000/combo-chart--v2.png" alt="Logo" class="w-7 h-7">
                </div>
                <span class="text-white text-2xl font-semibold">Money Mate</span>
            </div>
            <div class="flex space-x-6">
                <a href="home.html" class="text-white text-lg font-medium hover:text-gray-200 transition-colors duration-200">HOME</a>
                <a href="about_us_page1.php" class="active text-white text-lg font-medium transition-colors duration-200">ABOUT US</a>
                <a href="login.html" class="text-white text-lg font-medium hover:text-gray-200 transition-colors duration-200">LOGIN</a>
                <a href="signup.html" class="bg-white text-[#a259ff] px-6 py-2 rounded-full font-semibold shadow-md hover:bg-gray-100 transition-colors duration-200">SIGN UP</a>
            </div>
        </div>
    </nav>
    <!-- Main Content -->
    <div class="main-content flex flex-row gap-8 justify-between items-start max-w-6xl mx-auto mt-10 px-8">
        <!-- Left Section -->
        <div class="left flex-1 max-w-xl">
            <p class="text-lg mb-6" style="line-height:1.7">
                In <span style="color:#a259ff;font-style:italic;font-weight:600;">Money Mate</span>, we all come to work every day to enable people make smart decisions about their money every day.
            </p>
            <p class="mb-6" style="line-height:1.7">
                What started as a simple expense tracker for a small group of people has grown into personal finance app that brings beauty to finance of hundreds of thousands users from almost every country in the world.
            </p>
            <p class="mb-8" style="line-height:1.7">
                We believe that managing finance should be as effortless as shopping online. It should be done anytime, anywhere and in few clicks.
            </p>
            <button class="cta-btn">GET IN TOUCH WITH US!</button>
        </div>
        <!-- Right Section -->
        <div class="right flex-1 flex flex-col items-center">
            <div class="quote-card w-full">
                <div class="quote-text mb-4">“<span style="font-family: 'Inter', cursive;">helps people worldwide to get their money into shape.</span>”</div>
                <button class="quote-btn">Track it. Save it. Master your money.</button>
                <!-- Illustration Placeholder -->
                <img src="https://img.freepik.com/vetores-gratis/ilustracao-do-conceito-de-discussao-em-grupo_114360-4716.jpg" alt="Team Illustration" class="w-full max-w-xs mt-2 rounded-xl shadow-md" style="background:#fff;">
            </div>
        </div>
    </div>
    <!-- Scroll to Top Button -->
    <div class="scroll-top" onclick="window.scrollTo({top:0,behavior:'smooth'});">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
        </svg>
    </div>
</body>
</html> 