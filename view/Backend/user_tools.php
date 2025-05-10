<!-- user_tools.php -->

<style>
.container {
    max-width: 800px;
    margin: 50px auto;
    padding: 40px;
    background-color: #fff;
    border-radius: 25px;
    box-shadow: 0 0 20px rgba(0,0,0,0.1);
    font-family: 'Poppins', sans-serif;
}

h2 {
    text-align: center;
    color: #0a1b89;
    margin-bottom: 40px;
}
.section-title {
    font-size: 18px;
    margin-top: 30px;
    font-weight: bold;
    color: #0a1b89;
}
select, input[type="file"] {
    width: 100%;
    padding: 10px;
    margin: 10px 0 20px;
    border: 1px solid #ccc;
    border-radius: 12px;
}
button {
    background-color: #12cbe8;
    color: white;
    border: none;
    padding: 12px 20px;
    border-radius: 12px;
    cursor: pointer;
    margin-right: 10px;
    transition: background-color 0.3s ease;
}
button:hover {
    background-color: #0a1b89;
}
.icon {
    margin-right: 8px;
}
.return-btn {
    display: inline-block;
    background-color:rgb(198, 28, 48);
    color: white;
    padding: 10px 18px;
    border-radius: 10px;
    text-decoration: none;
    font-weight: bold;
    margin-top: 20px;
    transition: background-color 0.3s ease;
}
.return-btn:hover {
    background-color:rgb(165, 4, 4);
}
</style>

<div class="container">
    <h2>🛠 Centre des Utilisateurs</h2>

    <!-- Export -->
    <div class="section-title">📤 Exporter les utilisateurs</div>
    <label>Filtrer par rôle :</label>
    <select id="filtreRole">
        <option value="">Tous les rôles</option>
        <option value="investisseur">Investisseur</option>
        <option value="entrepreneur">Entrepreneur</option>
    </select>

    <div>
        <button onclick="exportUsers('csv')">Exporter CSV</button>
        
    </div>

    <!-- Import -->
    <div class="section-title">📥 Importer des utilisateurs</div>
    <form action="../../controller/importUsers.php" method="POST" enctype="multipart/form-data" onsubmit="return validateImport()">
        <label>Choisir un fichier (.csv ou .xlsx ou .pdf)</label>
        <input type="file" name="file" id="importFile" accept=".csv,.xlsx">
        <button type="submit">Importer</button>
    </form>

    <!-- Bouton de retour -->
    <a href="dashboard.php" class="return-btn">⬅ Retour au tableau de bord</a>
</div>

<script>
function exportUsers(format) {
    const role = document.getElementById("filtreRole").value;
    if (!format) return;
    window.location.href = `../../controller/exportusers.php?format=${format}&role=${role}`;
}

function validateImport() {
    const fileInput = document.getElementById("importFile");
    if (!fileInput.value) {
        alert("Veuillez choisir un fichier à importer (.csv ou .xlsx)");
        return false;
    }
    const allowedExtensions = /(\.csv|\.xlsx)$/i;
    if (!allowedExtensions.exec(fileInput.value)) {
        alert("Format invalide. Veuillez choisir un fichier .csv ou .xlsx");
        return false;
    }
    return true;
}
</script>
