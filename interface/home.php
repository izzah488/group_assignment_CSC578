<?php // home.php ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Money Mate - Smart Financial Management</title>

  <!-- Tailwind CSS CDN -->
  <script src="https://cdn.tailwindcss.com"></script>

  <!-- Google Fonts -->
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=Playfair+Display:wght@400;500;600;700&display=swap" rel="stylesheet"/>

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

    .hero-text-gradient {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      background-clip: text;
      font-weight: 700;
    }

    .cta-button {
      background: linear-gradient(135deg, #a259ff 0%, #6a11cb 100%);
      color: #fff;
      font-weight: 700;
      font-size: 1.15rem;
      border-radius: 9999px;
      box-shadow: 0 4px 16px 0 rgba(138,43,226,0.10);
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      position: relative;
      overflow: hidden;
    }

    .cta-button::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.18), transparent);
      transition: left 0.5s;
    }

    .cta-button:hover::before {
      left: 100%;
    }

    .cta-button:hover {
      transform: scale(1.05) translateY(-2px);
      box-shadow: 0 8px 24px rgba(138,43,226,0.18);
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

    .hero-content {
      animation: fadeInUp 1s ease-out;
    }

    .hero-image {
      animation: fadeInRight 1s ease-out 0.3s both;
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

    @keyframes fadeInRight {
      from {
        opacity: 0;
        transform: translateX(30px);
      }
      to {
        opacity: 1;
        transform: translateX(0);
      }
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
  </style>
</head>
<body class="min-h-screen flex flex-col">
  <?php include 'navbar.php'; ?>

  <!-- Hero Section -->
  <main class="flex-grow flex items-center justify-center px-6 py-20">
    <div class="container mx-auto flex flex-col-reverse lg:flex-row items-center justify-between gap-16 max-w-7xl">
      
      <!-- Left Side: Content -->
      <div class="text-center lg:text-left lg:w-1/2 hero-content">
        <h1 class="text-4xl sm:text-5xl lg:text-7xl font-bold text-gray-800 leading-tight mb-4 text-shadow">
          Manage your money with
        </h1>

        <h2 class="hero-text-gradient text-5xl sm:text-6xl font-extrabold leading-none mb-4">
          us
        </h2>
        
        <p class="text-xl text-gray-600 mb-10 max-w-2xl leading-relaxed">
          Track your income, expenses, and savings — all in one simple and beautiful platform designed for modern financial management.
        </p>
        
        <button class="cta-button font-bold py-4 px-10 rounded-full shadow-xl text-lg" onclick="window.location.href='about_us_page2.html'">
          Read More
        </button>

        <!-- Feature Highlights -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6 mt-16">
          <div class="feature-card p-6 text-center">
            <div class="w-12 h-12 bg-gradient-to-r from-blue-500 to-purple-600 rounded-full flex items-center justify-center mx-auto mb-4">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
              </svg>
            </div>
            <h3 class="font-semibold text-gray-800 mb-2">Smart Analytics</h3>
            <p class="text-sm text-gray-600">Get insights into your spending patterns</p>
          </div>
          
          <div class="feature-card p-6 text-center">
            <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-blue-600 rounded-full flex items-center justify-center mx-auto mb-4">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
              </svg>
            </div>
            <h3 class="font-semibold text-gray-800 mb-2">Secure & Private</h3>
            <p class="text-sm text-gray-600">Bank-level security for your data</p>
          </div>
          
          <div class="feature-card p-6 text-center">
            <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-pink-600 rounded-full flex items-center justify-center mx-auto mb-4">
              <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
              </svg>
            </div>
            <h3 class="font-semibold text-gray-800 mb-2">Real-time Sync</h3>
            <p class="text-sm text-gray-600">Access your data anywhere, anytime</p>
          </div>
        </div>
      </div>

      <!-- Right Side: Image -->
      <div class="lg:w-1/2 flex justify-center hero-image">
        <div class="image-container w-full max-w-lg">
          <img src="https://i.pinimg.com/736x/72/17/4e/72174ef251d0438cbf00d62975727d4a.jpg"
               alt="Smart Financial Management Illustration"
               class="w-full h-auto rounded-2xl"
               onerror="this.onerror=null;this.src='https://placehold.co/600x400/E0E0E0/333333?text=Image+Not+Found';"/>
        </div>
      </div>
    </div>
  </main>
</body>
</html>