    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/lib/wow/wow.min.js"></script>
    <script src="assets/lib/easing/easing.min.js"></script>
    <script src="assets/lib/waypoints/waypoints.min.js"></script>
    <script src="assets/lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Template Javascript -->
    <script src="assets/js/main.js"></script>


    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    
    
    <script>
function validateFormLogin(event) {
    event.preventDefault();

    const form = document.getElementById("loginForm");
    const formData = new FormData(form);
    const actionURL = form.getAttribute("action") || window.location.href; // par défaut, la page actuelle

    fetch(actionURL, {
        method: "POST",
        body: formData
    })
    .then(response => {
        // Vérifie que la réponse est bien en JSON
        const contentType = response.headers.get("content-type");
        if (!contentType || !contentType.includes("application/json")) {
            throw new Error("La réponse n'est pas en JSON. Vérifie ton fichier PHP.");
        }
        return response.json();
    })
    .then(data => {
        if (data.status === "success") {
            window.location.href = data.redirect;
        } else {
            document.getElementById("responseMessage").innerHTML = `<p style="color:red;">${data.message}</p>`;
        }
    })
    .catch(error => {
        document.getElementById("responseMessage").innerHTML =
            `<p style="color:red;">Une erreur s'est produite : ${error.message}</p>`;
    });

    return false;
}
</script>

