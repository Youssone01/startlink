<?php session_start(); ?>
<?php include('include/header.php'); ?>
<link rel="stylesheet" href="assets/css/login.css">

<body>
<?php include('include/spinner.php'); ?>
<?php include('include/navbar.php'); ?>

<br><br>
<center>
    <div class="wrapper">
        <h2>Connexion</h2>
        <form id="loginForm" method="POST" onsubmit="return validateFormLogin(event)">
            <div class="input-box">
                <input id="email" name="email" type="text" placeholder="Enter your email" oninput="clearError('emailError')">
                <span id="emailError" class="error-message"></span>
            </div>

            <div class="input-box">
                <input id="password" name="password" type="password" placeholder="Password" oninput="clearError('passwordError')">
                <span id="passwordError" class="error-message"></span>
            </div>

            <div class="input-box button">
                <input type="submit" value="Se connecter">
            </div>

            <!-- Lien "Mot de passe oublié ?" -->
            <div class="input-box" style="margin-top: 10px;">
                <a href="forgot-password.php" style="color: #0a1b89; text-decoration: underline;">Mot de passe oublié ?</a>
            </div>

            <div id="responseMessage"></div>

            <div class="text">
                <h3>Vous n'avez pas un compte ? <a href="register.php">S'inscrire</a></h3>
            </div>
        </form>
    </div>
</center>

<?php include('include/footer.php'); ?>
<?php include('include/js.php'); ?>

<a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>

<script>
function validateFormLogin(event) {
    event.preventDefault();

    const email = document.getElementById("email").value.trim();
    const password = document.getElementById("password").value;
    const responseMessage = document.getElementById("responseMessage");

    responseMessage.innerHTML = "";
    document.getElementById("emailError").textContent = "";
    document.getElementById("passwordError").textContent = "";

    let valid = true;

    if (!email.includes("@")) {
        document.getElementById("emailError").textContent = "❌ Email invalide.";
        valid = false;
    }

    if (password.length < 4) {
        document.getElementById("passwordError").textContent = "❌ Mot de passe trop court.";
        valid = false;
    }

    if (!valid) return false;

    fetch("./check_login.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded",
        },
        body: `email=${encodeURIComponent(email)}&password=${encodeURIComponent(password)}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === "success") {
            window.location.href = data.redirect;
        } else {
            responseMessage.innerHTML = `<span style="color:red;">${data.message}</span>`;
        }
    })
    .catch(error => {
        console.error("Erreur JSON :", error);
        responseMessage.innerHTML = "<span style='color:red;'>Une erreur s'est produite : La réponse n'est pas en JSON. Vérifie ton fichier PHP.</span>";
    });

    return false;
}

function clearError(id) {
    document.getElementById(id).textContent = "";
}
</script>
</body>
