<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saving - Money Mate</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts - Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #e2e8f0; /* Light gray background */
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            overflow: hidden; /* Prevent scrolling on the body when modal is open */
        }
        /* Custom styles for the input fields */
        .custom-input {
            background-color: #edf2f7; /* Lighter gray for input background */
            border: 1px solid #cbd5e0; /* Light border */
            padding: 0.75rem 1rem;
            border-radius: 0.375rem; /* Rounded corners */
            width: 100%;
            font-size: 1rem;
            color: #2d3748; /* Dark text color */
            transition: border-color 0.2s ease-in-out, box-shadow 0.2s ease-in-out; /* Smooth transition for focus */
        }
        .custom-input:focus {
            outline: none;
            border-color: #805ad5; /* Purple border on focus */
            box-shadow: 0 0 0 3px rgba(128, 90, 213, 0.3); /* Subtle purple glow on focus */
        }

        /* Custom button styles */
        .btn {
            padding: 0.6rem 1.5rem; /* Reduced padding for smaller buttons */
            border-radius: 9999px; /* Fully rounded */
            font-weight: 600;
            transition: all 0.2s ease-in-out;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            white-space: nowrap; /* Prevent text wrapping inside buttons */
            cursor: pointer; /* Indicate it's clickable */
        }
        .btn-delete {
            background-color: #fbd38d; /* Soft orange */
            color: #975a16; /* Darker orange text */
            border: 1px solid #dd6b20;
        }
        .btn-delete:hover {
            background-color: #dd6b20; /* Deeper orange on hover */
            color: white;
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.15);
            transform: translateY(-2px); /* Slight lift on hover */
        }
        .btn-cancel {
            background-color: #cbd5e0; /* Gray */
            color: #4a5568; /* Dark gray text */
            border: 1px solid #a0aec0;
        }
        .btn-cancel:hover {
            background-color: #a0aec0;
            color: #2d3748;
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.15);
            transform: translateY(-2px);
        }
        .btn-save {
            background-color: #805ad5; /* Purple */
            color: white;
            border: 1px solid #6b46c1;
        }
        .btn-save:hover {
            background-color: #6b46c1; /* Darker purple on hover */
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.15);
            transform: translateY(-2px);
        }

        /* Modal specific styles */
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent black overlay */
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
            opacity: 0;
            visibility: hidden;
            transition: opacity 0.3s ease-in-out, visibility 0.3s ease-in-out;
        }
        .modal-overlay.show {
            opacity: 1;
            visibility: visible;
        }
        .modal-content {
            position: relative;
            transform: translateY(-20px); /* Initial slight offset for animation */
            transition: transform 0.3s ease-in-out;
        }
        .modal-overlay.show .modal-content {
            transform: translateY(0); /* Slide into place */
        }
        .close-button {
            position: absolute;
            top: 0.75rem;
            right: 0.75rem;
            background: none;
            border: none;
            font-size: 1.5rem;
            color: #4a5568;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 50%;
            transition: background-color 0.2s, transform 0.2s;
        }
        .close-button:hover {
            background-color: #e2e8f0;
            transform: rotate(90deg); /* Spin on hover */
        }
    </style>
</head>
<body>

    <!-- Modal Overlay -->
    <div id="savingModal" class="modal-overlay show">
        <div class="bg-white p-6 md:p-8 rounded-lg shadow-xl w-full max-w-sm modal-content">
            <!-- Close Button -->
            <button class="close-button" onclick="closeModal()">
                &times;
            </button>

            <h1 class="text-2xl font-bold text-center text-gray-800 mb-6 flex items-center justify-center">
                SAVING
                <!-- Stack of Coins icon (SVG) -->
                <svg class="w-7 h-7 ml-2 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM6.293 9.293a1 1 0 011.414 0L10 11.586l2.293-2.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414zM10 5a1 1 0 011 1v2a1 1 0 11-2 0V6a1 1 0 011-1z" clip-rule="evenodd" />
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-9a1 1 0 000 2h2a1 1 0 100-2H9z" clip-rule="evenodd" />
                </svg>
            </h1>

            <div class="space-y-5 mb-6">
                <div>
                    <label for="savingFor" class="block text-gray-700 text-base font-medium mb-1">SAVING FOR:</label>
                    <input type="text" id="savingFor" name="savingFor" class="custom-input" placeholder="e.g., New Car, Vacation Fund">
                </div>
                <div>
                    <label for="budget" class="block text-gray-700 text-base font-medium mb-1">BUDGET:</label>
                    <input type="number" id="budget" name="budget" class="custom-input" placeholder="e.g., 5000">
                </div>
                <div>
                    <label for="dateTarget" class="block text-gray-700 text-base font-medium mb-1">DATE TARGET:</label>
                    <div class="relative">
                        <input type="date" id="dateTarget" name="dateTarget" class="custom-input pr-10">
                        <!-- Calendar icon (using SVG) -->
                        <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="flex justify-center space-x-2">
                <button class="btn btn-delete">Delete</button>
                <button class="btn btn-cancel">Cancel</button>
                <button class="btn btn-save">Save</button>
            </div>
        </div>
    </div>

    <script>
        // Function to close the modal
        function closeModal() {
            const modal = document.getElementById('savingModal');
            modal.classList.remove('show');
            // Optionally, you might want to redirect or do something else after closing
            // For now, it just hides the modal.
        }

        // To make it appear as a pop-up on page load, the 'show' class is already added to the modal-overlay div.
        // If you want to trigger it with a button, you would remove 'show' from the HTML and add a function like:
        /*
        function openModal() {
            const modal = document.getElementById('savingModal');
            modal.classList.add('show');
        }
        */
    </script>

</body>
</html>

