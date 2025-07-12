<main class="flex-grow flex items-center justify-center p-6"> <div class="bg-white p-8 rounded-2xl shadow-xl w-full max-w-md">
      <h1 class="text-3xl font-bold text-center text-gray-800 mb-6">Sign Up</h1>
      <form id="signupForm" onsubmit="handleSignup(event)">
        <div class="mb-6 text-center">
          <label for="profilePic" class="cursor-pointer">
            <img id="previewImage" src="https://placehold.co/100x100?text=Preview" alt="Profile Preview"
                  class="w-24 h-24 rounded-full mx-auto object-cover border-4 border-indigo-300 shadow-md mb-3">
            <span class="text-indigo-600 hover:text-indigo-800 font-medium">Upload Profile Picture</span>
          </label>
          <input type="file" id="profilePic" name="profilePic" accept="image/*" class="hidden">
        </div>

        <div class="mb-4">
          <label for="firstName" class="block text-sm font-medium text-gray-700 mb-1">First Name</label>
          <input type="text" id="firstName" name="firstName" placeholder="Enter your first name"
                  class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500" required>
        </div>

        <div class="mb-4">
          <label for="lastName" class="block text-sm font-medium text-gray-700 mb-1">Last Name</label>
          <input type="text" id="lastName" name="lastName" placeholder="Enter your last name"
                  class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500" required>
        </div>

        <div class="mb-4">
          <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
          <input type="email" id="email" name="email" placeholder="you@example.com"
                  class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500" required>
        </div>

        <div class="mb-4">
          <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
          <input type="password" id="password" name="password" placeholder="Minimum 6 characters"
                  class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500" required minlength="6">
        </div>

        <div class="mb-6">
          <label for="confirmPassword" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
          <input type="password" id="confirmPassword" name="confirmPassword" placeholder="Confirm Password"
                  class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-2 focus:ring-blue-500" required>
        </div>

        <button type="submit"
                class="w-full signup-btn py-3 rounded-lg font-semibold shadow-md">
          Sign-Up to Money Mate
        </button>
      </form>
    </div>
</main>