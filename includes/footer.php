<?php
// footer.php
// This file contains the common footer section and closing HTML tags.
?>
<style>
    /* Styles specific to the main-footer */
    .main-footer {
        background: linear-gradient(90deg, #500da8ff 0%, #9f71beff 100%);
        color: white;
        padding: 1.5rem 1rem; /* Responsive padding */
        text-align: center;
        margin-top: auto; /* Pushes the footer to the bottom if body is flex column */
        box-shadow: 0 -2px 8px 0 rgba(138,43,226,0.10);
        border-top-left-radius: 1rem;
        border-top-right-radius: 1rem;
        width: 100%; /* Ensure it spans full width */
        flex-shrink: 0; /* Prevent it from shrinking in a flex container */
    }
    .main-footer a {
        color: #fff;
        font-weight: 600;
        text-decoration: none;
    }
    .main-footer a:hover {
        text-decoration: underline;
    }

    /* Responsive adjustments for smaller screens */
    @media (max-width: 768px) {
        .main-footer {
            padding: 1rem 0.5rem; /* Slightly less padding on small screens */
            font-size: 0.9rem; /* Adjust font size */
        }
    }
</style>

<footer class="main-footer">
    <strong>Copyright &copy; <script>document.write(new Date().getFullYear());</script> <a href="#">MoneyMate</a>.</strong>
    All rights reserved.
</footer>

</body>
</html>