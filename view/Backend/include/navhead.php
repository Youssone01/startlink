<!-- Navbar Header -->
<nav class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom">
    <div class="container-fluid">
        <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">

            <li class="nav-item topbar-user dropdown hidden-caret">
                <a class="dropdown-toggle profile-pic" data-bs-toggle="dropdown" href="#" aria-expanded="false">
                    <div class="avatar-sm">
                        <img src="https://i.ibb.co/vQC8WPC/admin.png" alt="..." class="avatar-img rounded-circle" />
                    </div>
                    <span class="profile-username">
                        <span class="op-7">Bonjour,</span>
                        <span class="fw-bold">
                            <?php


                            if (isset($_SESSION['admin_email'])) {
                                echo $_SESSION['admin_email'];
                            } else {
                                echo 'Admin';
                            }
                            ?>
                        </span>
                    </span>
                </a>
                <ul class="dropdown-menu dropdown-user animated fadeIn">
                    <div class="dropdown-user-scroll scrollbar-outer">
                        <li>
                            <div class="user-box">
                                <div class="avatar-lg">
                                    <img src="https://i.ibb.co/vQC8WPC/admin.png" alt="image profile" class="avatar-img rounded" />
                                </div>
                                <div class="u-text">
                                    <p class="text-muted">
                                        <?php
                                        if (isset($_SESSION['admin_email'])) {
                                            echo $_SESSION['admin_email'];
                                        } else {
                                            echo 'No mail';
                                        }
                                        ?>
                                    </p>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="dropdown-divider"></div>
                            <div class="dropdown-divider"></div>
                            <a class="dropdown-item" href="../../view/Frontend/login.php">Déconnexion</a>
                        </li>
                    </div>
                </ul>
            </li>
        </ul>
    </div>
</nav>
<!-- End Navbar -->

<script src="assets/js/core/jquery-3.7.1.min.js"></script>
<script src="assets/js/core/popper.min.js"></script>
<script src="assets/js/core/bootstrap.min.js"></script>