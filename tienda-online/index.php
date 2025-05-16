<?php
$pageTitle = "Inicio";
include 'includes/header.php';
?>
<section class="hero">
    <div class="hero-content">
        <h1>Bienvenidos a Agro insumos El Jarillo</h1>
        <p>Todo lo que necesitas para el campo a un click de distancia</p>
        <a href="productos.php" class="btn">Ver Productos</a>
    </div>
</section>

<section class="featured-categories">
    <h2>Categorías Destacadas</h2>
    <div class="categories-grid">
        <div class="category-card">
            <img src="assets/images/semillas.jpg" alt="Semillas">
            <h3>Semillas</h3>
            <a href="productos.php?categoria=semillas" class="btn">Ver más</a>
        </div>
        <div class="category-card">
            <img src="assets/images/fertilizantes.jpg" alt="Fertilizantes">
            <h3>Fertilizantes</h3>
            <a href="productos.php?categoria=fertilizantes" class="btn">Ver más</a>
        </div>
        <div class="category-card">
            <img src="assets/images/alimentos.jpg" alt="Alimentos">
            <h3>Alimentos</h3>
            <a href="productos.php?categoria=alimentos" class="btn">Ver más</a>
        </div>
        <div class="category-card">
            <img src="assets/images/insecticidas.jpg" alt="Inseticidas">
            <h3>Inseticidas</h3>
            <a href="productos.php?categoria=insecticidas" class="btn">Ver más</a>
        </div>
        <div class="category-card">
            <img src="assets/images/herramientas.jpg" alt="Herramientas">
            <h3>Herramientas</h3>
            <a href="productos.php?categoria=herramientas" class="btn">Ver más</a>
        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>