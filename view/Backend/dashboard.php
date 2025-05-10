<?php
session_start();

if (!isset($_SESSION['admin_id'])) {
    header('Location: ../../View/Backend/index.php');
    exit;
}
?>

<?php include('include/head.php') ?>

<body>
    <div class="wrapper">
        <?php include('include/sidebar.php') ?>

        <div class="main-panel">
            <div class="main-header">
                <div class="main-header-logo">
                    <!-- Logo Header -->
                    <div class="logo-header" data-background-color="dark">
                        <a href="index.html" class="logo">
                            <img
                                src="assets\img\kaiadmin\logo_dark.png"
                                alt="navbar brand"
                                class="navbar-brand"
                                height="20" />
                        </a>
                        <div class="nav-toggle">
                            <button class="btn btn-toggle toggle-sidebar">
                                <i class="gg-menu-right"></i>
                            </button>
                            <button class="btn btn-toggle sidenav-toggler">
                                <i class="gg-menu-left"></i>
                            </button>
                        </div>
                        <button class="topbar-toggler more">
                            <i class="gg-more-vertical-alt"></i>
                        </button>
                    </div>
                    <!-- End Logo Header -->
                </div>
                <!-- Navbar Header -->
                <?php include('include/navhead.php') ?>
                <!-- End Navbar -->
            </div>

            <div class="container">

                <div class="page-inner">

                    <div class="col-md-12">

                        <div class="card ">
                            <div class="card-header">
                                <h4 class="card-title">Liste Des Utilisateurs</h4>
                                <a href="adduser.php">
    <button class="btn btn-secondary d-flex align-items-center ms-auto">
        <span class="btn-label">
            <i class="fa fa-plus"></i>
        </span>
        Add user
        
    </button>
</a>
                                </a>
                            </div>

                            <div class="card-body">
    <div class="table-responsive">
        <table id="basic-datatables" class="display table table-striped table-hover">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom Complet</th>
                    <th>Date de Naissance</th>
                    <th>Adresse</th>
                    <th>Bio</th>
                    <th>Mot de passe</th>
                    <th>Email</th>
                    <th>Rôle</th>
                    <th>Actions</th>
                </tr>
            </thead>

            
            <tbody>
<?php
require_once __DIR__ . '/../../Controllers/config.php';
$db = config::getConnexion();

$query = $db->query("SELECT * FROM users ORDER BY id ASC"); // pour ordre cohérent
$users = $query->fetchAll();

foreach ($users as $user) {
    echo "<tr>";
    echo "<td>{$user['id']}</td>";
    echo "<td>{$user['fullname']}</td>";
    echo "<td>{$user['date_n']}</td>";
    echo "<td>{$user['adresse']}</td>";
    echo "<td>{$user['bio']}</td>";
    echo "<td>{$user['password']}</td>";
    echo "<td>{$user['email']}</td>";
    echo "<td>{$user['role']}</td>";
    echo "<td>
<a href='updateuser.php?id={$user['id']}' class='btn btn-sm' style='background-color:#056ed1; color:white;'>
    <i class='fa fa-edit'></i> Modifier
</a>
    <a href='deleteuser.php?id={$user['id']}' class='btn btn-danger btn-sm me-2' onclick=\"return confirm('Voulez-vous vraiment supprimer cet utilisateur ?');\">Supprimer</a>

</td>";

    echo "</tr>";
}

?>
</tbody>

        </table>
    </div>
</div>


            <?php include('include/footer.php') ?>
        </div>
        

        <!-- Custom template | don't include it in your project! -->
        <div class="custom-template">
            <div class="title">Paramètres</div>
            <div class="custom-content">
                <div class="switcher">
                    <div class="switch-block">
                        <h4>Logo Header</h4>
                        
                        <div class="btnSwitch">
                            <button
                                type="button"
                                class="selected changeLogoHeaderColor"
                                data-color="dark"></button>
                            <button
                                type="button"
                                class="changeLogoHeaderColor"
                                data-color="blue"></button>
                            <button
                                type="button"
                                class="changeLogoHeaderColor"
                                data-color="purple"></button>
                            <button
                                type="button"
                                class="changeLogoHeaderColor"
                                data-color="light-blue"></button>
                            <button
                                type="button"
                                class="changeLogoHeaderColor"
                                data-color="green"></button>
                            <button
                                type="button"
                                class="changeLogoHeaderColor"
                                data-color="orange"></button>
                            <button
                                type="button"
                                class="changeLogoHeaderColor"
                                data-color="red"></button>
                            <button
                                type="button"
                                class="changeLogoHeaderColor"
                                data-color="white"></button>
                            <br />
                            <button
                                type="button"
                                class="changeLogoHeaderColor"
                                data-color="dark2"></button>
                            <button
                                type="button"
                                class="changeLogoHeaderColor"
                                data-color="blue2"></button>
                            <button
                                type="button"
                                class="changeLogoHeaderColor"
                                data-color="purple2"></button>
                            <button
                                type="button"
                                class="changeLogoHeaderColor"
                                data-color="light-blue2"></button>
                            <button
                                type="button"
                                class="changeLogoHeaderColor"
                                data-color="green2"></button>
                            <button
                                type="button"
                                class="changeLogoHeaderColor"
                                data-color="orange2"></button>
                            <button
                                type="button"
                                class="changeLogoHeaderColor"
                                data-color="red2"></button>
                        </div>
                    </div>
                    <div class="switch-block">
                        <h4>Navbar Header</h4>
                        <div class="btnSwitch">
                            <button
                                type="button"
                                class="changeTopBarColor"
                                data-color="dark"></button>
                            <button
                                type="button"
                                class="changeTopBarColor"
                                data-color="blue"></button>
                            <button
                                type="button"
                                class="changeTopBarColor"
                                data-color="purple"></button>
                            <button
                                type="button"
                                class="changeTopBarColor"
                                data-color="light-blue"></button>
                            <button
                                type="button"
                                class="changeTopBarColor"
                                data-color="green"></button>
                            <button
                                type="button"
                                class="changeTopBarColor"
                                data-color="orange"></button>
                            <button
                                type="button"
                                class="changeTopBarColor"
                                data-color="red"></button>
                            <button
                                type="button"
                                class="selected changeTopBarColor"
                                data-color="white"></button>
                            <br />
                            <button
                                type="button"
                                class="changeTopBarColor"
                                data-color="dark2"></button>
                            <button
                                type="button"
                                class="changeTopBarColor"
                                data-color="blue2"></button>
                            <button
                                type="button"
                                class="changeTopBarColor"
                                data-color="purple2"></button>
                            <button
                                type="button"
                                class="changeTopBarColor"
                                data-color="light-blue2"></button>
                            <button
                                type="button"
                                class="changeTopBarColor"
                                data-color="green2"></button>
                            <button
                                type="button"
                                class="changeTopBarColor"
                                data-color="orange2"></button>
                            <button
                                type="button"
                                class="changeTopBarColor"
                                data-color="red2"></button>
                        </div>
                    </div>
                    <div class="switch-block">
                        <h4>Sidebar</h4>
                        <div class="btnSwitch">
                            <button
                                type="button"
                                class="changeSideBarColor"
                                data-color="white"></button>
                            <button
                                type="button"
                                class="selected changeSideBarColor"
                                data-color="dark"></button>
                            <button
                                type="button"
                                class="changeSideBarColor"
                                data-color="dark2"></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="custom-toggle">
                <i class="icon-settings"></i>
            </div>
        </div>
        <!-- End Custom template -->
    </div>
    <?php include('include/js.php') ?>


    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const tableBody = document.querySelector("#basic-datatables tbody");

            tableBody.addEventListener("click", function(e) {
                if (e.target.closest(".view-course")) {
                    e.preventDefault();


                    const row = e.target.closest("tr");


                    const courseDetails = {
                        title: row.children[1].textContent.trim(),
                        description: row.children[2].textContent.trim(),
                        startDate: row.children[3].textContent.trim(),
                        endDate: row.children[4].textContent.trim(),
                        price: row.children[5].textContent.trim(),
                        capacity: row.children[6].textContent.trim()
                    };


                    document.getElementById("TitreEvenement").textContent = courseDetails.title;
                    document.getElementById("courseDescription").textContent = courseDetails.description;
                    document.getElementById("courseStartDate").textContent = courseDetails.startDate;
                    document.getElementById("courseEndDate").textContent = courseDetails.endDate;
                    document.getElementById("coursePrice").textContent = courseDetails.price;
                    document.getElementById("courseCapacity").textContent = courseDetails.capacity;


                    const modal = new bootstrap.Modal(document.getElementById("viewCourseModal"));
                    modal.show();


                    document.querySelector("#viewCourseModal .btn-secondary").addEventListener("click", function() {
                        modal.hide();
                    });
                    document.querySelector("#viewCourseModal .close").addEventListener("click", function() {
                        modal.hide();
                    });
                }
            });
        });
    </script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const deleteButtons = document.querySelectorAll(".delete-course");

            deleteButtons.forEach(button => {
                button.addEventListener("click", function(e) {
                    e.preventDefault();
                    const courseId = this.getAttribute("data-id");

                    if (confirm("Are you sure you want to delete this course?")) {

                        fetch(`../../Model/admin/delete_course.php?id=${courseId}`, {
                                method: "GET",
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.status === "success") {
                                    $.notify({
                                        message: data.message
                                    }, {
                                        type: "success",
                                        delay: 2000
                                    });


                                    this.closest("tr").remove();
                                } else {
                                    $.notify({
                                        message: data.message
                                    }, {
                                        type: "danger",
                                        delay: 2000
                                    });
                                }
                            })
                            .catch(() => {
                                $.notify({
                                    message: "An unexpected error occurred."
                                }, {
                                    type: "danger",
                                    delay: 3000
                                });
                            });
                    }
                });
            });
        });
    </script>
</body>