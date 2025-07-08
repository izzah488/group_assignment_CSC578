<?php // navbar.php ?>
<style>
.navbar-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    backdrop-filter: blur(20px);
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
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
</style>

<nav class="navbar-gradient text-white p-4 shadow-md">
    <div class="container mx-auto flex justify-between items-center">
        <!-- Logo and Name -->
        <div class="flex items-center space-x-4">
            <div class="logo-container bg-white rounded-full p-3 shadow-xl">
                <img src="https://img.icons8.com/color/48/000000/combo-chart--v2.png" alt="Money Mate Logo" class="w-8 h-8">
            </div>
            <span class="text-3xl font-bold tracking-wider">Money Mate</span>
        </div>
    </div>
</nav>
