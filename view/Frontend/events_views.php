<div class="container mt-4">
    <!-- Barre de recherche -->
    <form method="GET" action="" class="mb-4">
        <div class="input-group">
            <input type="text" 
                   name="search" 
                   class="form-control" 
                   placeholder="Rechercher un événement..."
                   value="<?= isset($_GET['search']) ? htmlspecialchars($_GET['search']) : '' ?>">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i> Rechercher
            </button>
            <?php if(isset($_GET['search']) : ?>
                <a href="events.php" class="btn btn-outline-secondary">
                    <i class="fas fa-times"></i> Annuler
                </a>
            <?php endif; ?>
        </div>
    </form>

    <!-- Affichage des résultats -->
    <?php if(!empty($events)) : ?>
        <div class="row">
            <?php foreach($events as $event) : ?>
                <div class="col-md-4 mb-4">
                    <div class="card h-100">
                        <?php if($event['has_image']) : ?>
                            <img src="get_event_image.php?id=<?= $event['idEvenement'] ?>" 
                                 class="card-img-top" 
                                 alt="<?= htmlspecialchars($event['titre']) ?>">
                        <?php endif; ?>
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($event['titre']) ?></h5>
                            <p class="card-text">
                                <small class="text-muted">
                                    <?= date('d/m/Y', strtotime($event['dateEven'])) ?>
                                </small>
                            </p>
                            <p class="card-text"><?= htmlspecialchars($event['description']) ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else : ?>
        <div class="alert alert-info">
            Aucun événement trouvé.
        </div>
    <?php endif; ?>
</div>*