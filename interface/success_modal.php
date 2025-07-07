<?php
// Success Modal Component for Money Mate
// This file can be included in other PHP pages to show success messages

// Check if there's a success message in session
session_start();
$success_message = '';
$show_modal = false;

if (isset($_SESSION['success_message'])) {
    $success_message = $_SESSION['success_message'];
    $show_modal = true;
    // Clear the message after displaying
    unset($_SESSION['success_message']);
}

// Function to set success message (can be called from other pages)
function setSuccessMessage($message) {
    $_SESSION['success_message'] = $message;
}

// Function to clear success message
function clearSuccessMessage() {
    unset($_SESSION['success_message']);
}
?>

<!-- Success Modal Component -->
<div id="successModal" class="fixed inset-0 bg-purple-200 bg-opacity-60 flex items-center justify-center p-4 z-50 <?php echo $show_modal ? '' : 'hidden'; ?>">
    <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-sm w-full text-center transform transition-all duration-300 scale-100 opacity-100">
        <!-- Checkmark Icon -->
        <div class="flex justify-center mb-4">
            <div class="bg-green-100 rounded-full p-3">
                <i class="fas fa-check-circle text-green-500 text-5xl"></i>
            </div>
        </div>
        <!-- Success Message -->
        <p class="text-xl font-semibold text-gray-800 mb-6" id="successMessage">
            <?php echo htmlspecialchars($success_message ?: 'Saved successfully!'); ?>
        </p>
        <!-- Done Button -->
        <button onclick="hideSuccessModal()"
                class="bg-purple-100 text-purple-700 py-2 px-6 rounded-lg font-medium hover:bg-purple-200 transition-colors duration-200 shadow-sm">
            Done
        </button>
    </div>
</div>

<script>
    // Enhanced Success Modal Functions
    function showSuccessModal(message = 'Saved successfully!') {
        const modal = document.getElementById('successModal');
        const messageElement = document.getElementById('successMessage');
        
        if (modal && messageElement) {
            messageElement.textContent = message;
            modal.classList.remove('hidden');
            
            // Auto-hide after 3 seconds
            setTimeout(() => {
                hideSuccessModal();
            }, 3000);
        } else {
            console.error('Success modal elements not found.');
        }
    }

    function hideSuccessModal() {
        const modal = document.getElementById('successModal');
        if (modal) {
            modal.classList.add('hidden');
        } else {
            console.error('Success modal element not found.');
        }
    }

    // Close modal when clicking outside
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('successModal');
        if (modal) {
            modal.addEventListener('click', function(e) {
                if (e.target === modal) {
                    hideSuccessModal();
                }
            });
        }
    });

    // Keyboard support - ESC key to close modal
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            hideSuccessModal();
        }
    });
</script>

<style>
    /* Additional styles for better modal experience */
    #successModal {
        backdrop-filter: blur(4px);
    }
    
    #successModal > div {
        animation: modalSlideIn 0.3s ease-out;
    }
    
    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: scale(0.9) translateY(-20px);
        }
        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }
    
    #successModal.hidden > div {
        animation: modalSlideOut 0.2s ease-in;
    }
    
    @keyframes modalSlideOut {
        from {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
        to {
            opacity: 0;
            transform: scale(0.9) translateY(-20px);
        }
    }
</style>