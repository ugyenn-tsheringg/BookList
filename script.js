// function validateForm() {
//     // Get form values
//     const name = document.getElementById('name').value;
//     const email = document.getElementById('email').value;
//     const password = document.getElementById('password').value;
//     const confirmPassword = document.getElementById('confirm_password').value;
//     const errorMessage = document.getElementById('error-message');

//     // Clear previous error message
//     errorMessage.textContent = '';

//     // Name validation
//     if (name.length < 1) {
//         errorMessage.textContent = "Name cannot be empty.";
//         return false;
//     }

//     // Email validation (simple regex pattern)
//     const emailPattern = /^[^ ]+@[^ ]+\.[a-z]{2,3}$/;
//     if (!email.match(emailPattern)) {
//         errorMessage.textContent = "Please enter a valid email address.";
//         return false;
//     }

//     // Password validation
//     if (password.length < 6) {
//         errorMessage.textContent = "Password must be at least 6 characters long.";
//         return false;
//     }

//     // Confirm password validation
//     if (password !== confirmPassword) {
//         errorMessage.textContent = "Passwords do not match.";
//         return false;
//     }

//     // If all checks pass
//     alert("Form submitted successfully!");
//     return true;
// }

function validateForm() {
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    const errorMessage = document.getElementById('error-message');

    errorMessage.textContent = '';

    if (password !== confirmPassword) {
        errorMessage.textContent = "Passwords do not match.";
        return false;
    }

    return true;
}

//Contact HTML form validation


//Dashboard highlight 
const currentLocation = window.location.href;

// Get all navbar links
const navLinks = document.querySelectorAll('.dashboard-nav-menu ul li a');

// Loop through the links
navLinks.forEach(link => {
    if (link.href === currentLocation) {
        link.classList.add('dashboard-active');
    }
});

//