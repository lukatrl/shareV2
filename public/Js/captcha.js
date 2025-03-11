document.addEventListener("DOMContentLoaded", function () {
    let captchaSolution = document.getElementById("captcha-solution").value;

    function generateCaptcha() {
        let num1 = Math.floor(Math.random() * 10) + 1;
        let num2 = Math.floor(Math.random() * 10) + 1;
        let captchaElement = document.getElementById("captcha-question");

        if (captchaElement) {
            captchaElement.textContent = `Combien font ${num1} + ${num2} ?`;
            captchaSolution = num1 + num2; 
            document.getElementById("captcha-solution").value = captchaSolution;
        }
    }

    document.getElementById("login-form").addEventListener("submit", function (event) {
        let userInput = document.getElementById("captcha-input").value;
        console.log("Formulaire soumis avec captcha :", userInput);

        if (parseInt(userInput) !== parseInt(captchaSolution)) {
            event.preventDefault();
            alert("Le captcha est incorrect !");
            console.log("Captcha incorrect, formulaire bloqué.");
            generateCaptcha();
        } else {
            console.log("Captcha correct, formulaire envoyé !");
        }
    });
});