<?php
$strMessage404 = isset($strMessage404) ? $strMessage404 : 'Voltar à página inicial';
$strUrlReturn404 = isset($strUrlReturn404) ? $strUrlReturn404 : '/';
?>

<div class="container">
    <div class="row">
        <div class="col-md-6 offset-md-3 text-center mt-5">
            <h1 class="display-4">Página não encontrada</h1>
            <p class="lead">A página que você está tentando acessar não existe.</p>
            <a class="btn btn-primary" href="<?= $strUrlReturn404 ?>"><?= $strMessage404 ?></a>
        </div>
    </div>
</div>