const loginModal = document.getElementById("loginModal");
        const registerModal = document.getElementById("registerModal");
        const loginBtn = document.getElementById("loginBtn");
        const registerBtn = document.getElementById("registerBtn");
        const closeLogin = document.getElementById("closeLogin");
        const closeRegister = document.getElementById("closeRegister");
       

        loginBtn.onclick = function() { loginModal.style.display = "block"; }
        registerBtn.onclick = function() { registerModal.style.display = "block"; }
        closeLogin.onclick = function() { loginModal.style.display = "none"; }
        closeRegister.onclick = function() { registerModal.style.display = "none"; }
       
        
        window.onclick = function(event) {
            if (event.target == loginModal) loginModal.style.display = "none";
            if (event.target == registerModal) registerModal.style.display = "none";
            
            
        }

// for slide 
document.addEventListener("DOMContentLoaded", function () {
    const slideshows = document.querySelectorAll('.slideshow');

    slideshows.forEach(slideshow => {
        let index = 0;
        const images = slideshow.querySelectorAll('img');
        const totalImages = images.length;

        setInterval(() => {
            images[index].classList.remove('active'); // Remove active class from the current image
            index = (index + 1) % totalImages; // Cycle through the images
            images[index].classList.add('active'); // Add active class to the next image
        }, 3000); // Change image every 3 seconds
    });
});
