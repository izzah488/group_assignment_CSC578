// script.js
function showSuccessModal() {
    const modal = document.getElementById('successModal');
    if (modal) {
        modal.classList.remove('hidden');
    } else {
        console.error('Success modal element not found.');
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

// You might add event listeners here, e.g.:
// document.addEventListener('DOMContentLoaded', () => {
//     const someButton = document.getElementById('myButton');
//     if (someButton) {
//         someButton.addEventListener('click', showSuccessModal);
//     }
// });