<?php
// about_us_page2.php

// Set a page title for the header
$pageTitle = "Money Mate - About Us";

// Include the common header (which contains <!DOCTYPE html>, <head>, and the navbar)
// Ensure the path is correct relative to this file.
// If header.php is in 'includes' folder at the same level as 'interface', then 'includes/header.php' is correct.
require_once 'header.php';
?>

    <!-- Main content container. Added pt-24 to push content below the fixed header. -->
    <!-- flex-grow is crucial here to push the footer to the bottom -->
    <main class="flex-grow pt-24 pb-12">
        <div class="main-content flex flex-col lg:flex-row gap-8 justify-between items-start max-w-6xl mx-auto px-8">
            <div class="left flex-1 max-w-full lg:max-w-xl">
                <h1 class="text-4xl font-bold mb-6 hero-text-gradient">About Us</h1>
                <p class="text-lg mb-6 leading-relaxed">
                    In <span style="color:#a259ff;font-style:italic;font-weight:600;">Money Mate</span>, we all come to work every day to enable people make smart decisions about their money every day.
                </p>
                <p class="mb-6 leading-relaxed">
                    What started as a simple expense tracker for a small group of people has grown into personal finance app that brings beauty to finance of hundreds of thousands users from almost every country in the world.
                </p>
                <p class="mb-8 leading-relaxed">
                    We believe that managing finance should be as effortless as shopping online. It should be done anytime, anywhere and in few clicks.
                </p>
                <!-- Corrected cta-btn to cta-button to match the global style -->
                <button class="cta-button" onclick="document.getElementById('contact-section').scrollIntoView({behavior: 'smooth'});">GET IN TOUCH WITH US!</button>
            </div>
            <div class="right flex-1 flex flex-col items-center">
                <div class="quote-card w-full">
                    <div class="quote-text mb-4">“<span style="font-family: 'Inter', cursive;">helps people worldwide to get their money into shape.</span>”</div>
                    <button class="quote-btn">Track it. Save it. Master your money.</button>
                    <img src="https://img.freepik.com/vetores-gratis/ilustracao-do-conceito-de-discussao-em-grupo_114360-4716.jpg" alt="Team Illustration" class="w-full max-w-xs mt-2 rounded-xl shadow-md" onerror="this.onerror=null;this.src='https://placehold.co/300x200/E0E0E0/333333?text=Image+Not+Found';"/>
                </div>
            </div>
        </div>

        <section class="py-12 px-4 max-w-7xl mx-auto">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8 justify-items-center">
                <div class="team-card">
                    <img src="images/farhana.jpg" alt="Farhana" class="team-img" onerror="this.onerror=null;this.src='https://placehold.co/120x120/E0E0E0/333333?text=F';" />
                    <div class="text-lg font-semibold mt-2 mb-1">FARHANA</div>
                    <div class="text-gray-700 text-sm font-medium">CEO & FOUNDER</div>
                </div>
                <div class="team-card">
                    <img src="images/huwayda.jpg" alt="Huwayda" class="team-img" onerror="this.onerror=null;this.src='https://placehold.co/120x120/E0E0E0/333333?text=H';" />
                    <div class="text-lg font-semibold mt-2 mb-1">HUWAYDA</div>
                    <div class="text-gray-700 text-sm font-medium">CSO & CO FOUNDER</div>
                </div>
                <div class="team-card">
                    <img src="images/arissa.jpg" alt="Arissa" class="team-img" onerror="this.onerror=null;this.src='https://placehold.co/120x120/E0E0E0/333333?text=A';" />
                    <div class="text-lg font-semibold mt-2 mb-1">ARISSA</div>
                    <div class="text-gray-700 text-sm font-medium">COO</div>
                </div>
                <div class="team-card">
                    <img src="images/izzah.jpg" alt="Izzah" class="team-img" onerror="this.onerror=null;this.src='https://placehold.co/120x120/E0E0E0/333333?text=I';" />
                    <div class="text-lg font-semibold mt-2 mb-1">IZZAH</div>
                    <div class="text-gray-700 text-sm font-medium">CTO</div>
                </div>
            </div>
        </section>

        <section id="contact-section" class="py-12 px-4 max-w-5xl mx-auto">
            <h2 class="text-5xl font-extrabold text-center mb-2 hero-text-gradient">CONTACT US</h2>
            <p class="text-center text-gray-500 text-lg mb-10">If you want to get in touch with us, use these e-mails.</p>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-base text-gray-800">
                <div>
                    <div class="mb-6">
                        <span class="font-semibold">For Support</span><br/>
                        <a href="mailto:hello@moneyTracker.com" class="underline">hello@moneyTracker.com</a>
                    </div>
                    <div>
                        <span class="font-semibold">For Business Opportunities & Marketing Purposes, contact Arissa</span><br/>
                        <a href="mailto:Arissa@moneyTracker.com" class="underline">Arissa@moneyTracker.com</a>
                    </div>
                </div>
                <div>
                    <div class="mb-6">
                        <span class="font-semibold">For Media & PR Opportunities</span><br/>
                        <a href="mailto:media@moneyTracker.com" class="underline">media@moneyTracker.com</a>
                    </div>
                    <div>
                        <span class="font-semibold">For Other Opportunities, contact our CEO Farhana</span><br/>
                        <a href="mailto:Farhana@moneyTracker.com" class="underline">Farhana@moneyTracker.com</a>
                    </div>
                </div>
            </div>
        </section>
    </main>