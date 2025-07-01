// Add this JavaScript to your login.php file (requires bootstrap.min.js)

function showSpinner() {
    const loginBtn = document.querySelector('button[type="submit"]');
    const spinner = document.getElementById('spinner');
    const btnText = document.getElementById('btnText');
    
    // Disable the button using Bootstrap method
    loginBtn.setAttribute('disabled', true);
    loginBtn.classList.add('disabled');
    
    // Show spinner using Bootstrap classes
    spinner.classList.remove('d-none');
    spinner.classList.add('d-inline-block');
    
    // Update button text
    btnText.textContent = 'Logging in...';
    
    // Add minimum delay to show spinner (2 seconds)
    // This ensures users can see the loading state
    setTimeout(() => {
        // The form will submit after the delay
        // You can adjust this delay as needed
        document.querySelector('form').submit();
    }, 2000);
    
    // Prevent immediate form submission
    return false;
}

// Alternative version if you want the spinner to show during actual form submission
function showSpinnerInstant() {
    const loginBtn = document.querySelector('button[type="submit"]');
    const spinner = document.getElementById('spinner');
    const btnText = document.getElementById('btnText');
    
    // Disable the button using Bootstrap method
    loginBtn.setAttribute('disabled', true);
    loginBtn.classList.add('disabled');
    
    // Show spinner using Bootstrap classes
    spinner.classList.remove('d-none');
    spinner.classList.add('d-inline-block');
    
    // Update button text
    btnText.textContent = 'Logging in...';
    
    // Allow normal form submission to proceed
    return true;
}

// Optional: Reset function if you need to reset the button state
function resetSpinner() {
    const loginBtn = document.querySelector('button[type="submit"]');
    const spinner = document.getElementById('spinner');
    const btnText = document.getElementById('btnText');
    
    // Re-enable button using Bootstrap method
    loginBtn.removeAttribute('disabled');
    loginBtn.classList.remove('disabled');
    
    // Hide spinner using Bootstrap classes
    spinner.classList.add('d-none');
    spinner.classList.remove('d-inline-block');
    
    // Reset button text
    btnText.textContent = 'LOGIN';
}