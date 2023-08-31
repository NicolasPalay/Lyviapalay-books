document.addEventListener("DOMContentLoaded", function() {
    const modal = document.getElementById("rgpdModal");
    const acceptButton = document.getElementById("acceptButton");

    // Vérifie si le visiteur a déjà donné son consentement
    const hasConsent = localStorage.getItem("rgpdConsent");

    if (!hasConsent) {
        modal.style.display = "flex";
    }

    // Vérifie si le cookie indique que la modal doit être cachée
    const cookieValue = readCookie("cookie1");
    if (cookieValue === "none") {
        modal.style.display = "none";
        modal.style.opacity = 0;
    }

    // Action lors du clic sur le bouton "Accepter"
    acceptButton.addEventListener("click", function() {
        createCookie("cookie1", "none", 365); // Le cookie expire après 365 jours
        localStorage.setItem("rgpdConsent", "true");
        modal.style.display = "none";
        modal.style.opacity = 0;
    });

    function createCookie(name, value, days) {
        let expires = "";
        if (days) {
            let date = new Date();
            date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
            expires = "; expires=" + date.toGMTString();
        }
        document.cookie = name + "=" + value + expires + "; path=/";
    }

    function readCookie(name) {
        let nameEQ = name + "=";
        let ca = document.cookie.split(';');
        for(let i = 0; i < ca.length; i++) {
            let c = ca[i];
            while (c.charAt(0) === ' ') c = c.substring(1, c.length);
            if (c.indexOf(nameEQ) === 0) return c.substring(nameEQ.length, c.length);
        }
        return null;
    }
});