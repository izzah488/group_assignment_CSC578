<?php // about_us_page2.php ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About Us - Money Mate</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f0f2f5 0%, #e0b0ff 100%);
            min-height: 100vh;
            overflow-x: hidden;
        }
        h1, h2, .hero-text-gradient {
            font-family: 'Playfair Display', serif;
        }
        .navbar-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
        }
        .nav-link {
            position: relative;
            transition: all 0.3s ease;
        }
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 2px;
            background: white;
            transition: width 0.3s ease;
        }
        .nav-link:hover::after,
        .nav-link.active::after {
            width: 100%;
        }
        .logo-container {
            position: relative;
            overflow: hidden;
        }
        .logo-container::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: linear-gradient(45deg, transparent, rgba(255, 255, 255, 0.1), transparent);
            transform: rotate(45deg);
            transition: all 0.6s ease;
        }
        .logo-container:hover::before {
            transform: rotate(45deg) translate(50%, 50%);
        }
        .cta-btn {
            background: linear-gradient(135deg, #a259ff 0%, #6a11cb 100%);
            color: #fff;
            font-weight: 700;
            font-size: 1.15rem;
            border-radius: 9999px;
            box-shadow: 0 4px 16px 0 rgba(138,43,226,0.10);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative;
            overflow: hidden;
            padding: 0.9rem 2.2rem;
        }
        .cta-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.18), transparent);
            transition: left 0.5s;
        }
        .cta-btn:hover::before {
            left: 100%;
        }
        .cta-btn:hover {
            transform: scale(1.05) translateY(-2px);
            box-shadow: 0 8px 24px rgba(138,43,226,0.18);
        }
        .quote-card {
            background: rgba(255, 255, 255, 0.15);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: 1.5rem;
            box-shadow: 0 8px 32px 0 rgba(138,43,226,0.10);
            display: flex;
            flex-direction: column;
            align-items: center;
            margin-bottom: 1.5rem;
            animation: fadeInRight 1s ease-out 0.3s both;
            padding: 2.5rem 2rem 2rem 2rem;
            max-width: 350px;
            width: 100%;
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
        .main-content {
            animation: fadeInUp 1s ease-out;
            display: flex;
            flex-direction: row;
            gap: 3rem;
            justify-content: center;
            align-items: center;
            max-width: 1100px;
            margin: 4rem auto 0 auto;
            padding: 2rem 2rem 3rem 2rem;
            background: rgba(255,255,255,0.10);
            border-radius: 2rem;
            box-shadow: 0 8px 32px 0 rgba(138,43,226,0.08);
        }
        .left {
            flex: 1;
            max-width: 480px;
            padding: 0 1rem;
        }
        .right {
            flex: 1;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 0 1rem;
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        @keyframes fadeInRight {
            from { opacity: 0; transform: translateX(30px); }
            to { opacity: 1; transform: translateX(0); }
        }
        @media (max-width: 1024px) {
            .main-content {
                flex-direction: column;
                gap: 2rem;
                padding: 1.5rem 0.5rem 2rem 0.5rem;
            }
            .left, .right {
                width: 100%;
                max-width: 100%;
                padding: 0;
            }
            .quote-card {
                max-width: 100%;
            }
        }
        @media (max-width: 640px) {
            .main-content {
                padding: 0.5rem;
                margin-top: 2rem;
            }
            .quote-card {
                padding: 1.2rem 0.7rem 1rem 0.7rem;
            }
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Left Section -->
        <div class="left">
            <h1 class="text-4xl font-bold mb-6 hero-text-gradient">About Us</h1>
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
        <div class="right">
            <div class="quote-card">
                <div class="quote-text mb-4">“<span style="font-family: 'Inter', cursive;">helps people worldwide to get their money into shape.</span>”</div>
                <button class="quote-btn">Track it. Save it. Master your money.</button>
                <!-- Illustration Placeholder -->
                <img src="https://img.freepik.com/vetores-gratis/ilustracao-do-conceito-de-discussao-em-grupo_114360-4716.jpg" alt="Team Illustration" class="w-full max-w-xs mt-2 rounded-xl shadow-md" style="background:#fff;">
            </div>
        </div>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html> 