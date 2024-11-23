// scripts.js

document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('contactForm');

    form.addEventListener('submit', function(event) {
        event.preventDefault();
        const phone = document.getElementById('phone').value;
        const email = document.getElementById('email').value;
        const address = document.getElementById('address').value;

        if (validatePhone(phone) && validateEmail(email) && validateAddress(address)) {
            alert('Form submitted successfully!');
        }
    });

    function validatePhone(phone) {
        const phoneRegex = /^\+?[1-9]\d{1,14}$/;
        if (!phoneRegex.test(phone)) {
            alert('Please enter a valid phone number.');
            return false;
        }
        return true;
    }

    function validateEmail(email) {
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (!emailRegex.test(email)) {
            alert('Please enter a valid email address.');
            return false;
        }
        return true;
    }

    function validateAddress(address) {
        if (address.trim() === '') {
            alert('Please enter a valid address.');
            return false;
        }
        return true;
    }
});

document.getElementById("bttn").onclick = () => {
    if(fname==null || fname==""){
        document.getElementById("txt").innerHTML = ("Name can't be Blank");
    }
    else if(email==null || email==""){
        document.getElementById("email").innerHTML = ("Email can't be blank")
    }
  }

  const themeToggleBtn = document.getElementById('theme-toggle');
const container = document.getElementById('container');

themeToggleBtn.addEventListener('click', () => {
  container.classList.toggle('theme-dark');
});