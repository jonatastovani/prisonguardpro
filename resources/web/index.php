<?php
session_start();
// $_SESSION['userId'] = 123;

require "config/config.php";

require "setSession.php";

$url = isset($_GET['url']) ? $_GET['url'] : null;
$url = $url != null ? explode('/', $_GET['url']) : null;

if ($url != null && $url[0] == "logout") {

    include_once "view/site/logout.php";
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= SYSTEM_DISPLAY_NAME ?></title>

    <link rel="stylesheet" href="/bootstrap-5.3.2-dist/css/bootstrap.css">
    <link rel="stylesheet" href="/bootstrap-icons-1.11.1/bootstrap-icons.css">
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/style-popup.css">
    <link rel="stylesheet" href="/assets/css/modal.css">
    <link rel="stylesheet" href="/assets/css/style-login.css">
    <link rel="shortcut icon" href="/favicon.ico" type="image/x-icon">

    <script src="/assets/js/jquery/jquery-3.7.0.min.js"></script>
    <script src="/assets/js/jquery/jquery.mask-1.14.16.min.js"></script>
    <script src="/assets/js/jquery/jquery.Jcrop.min.js"></script>
    <script src="/assets/js/jquery/jquery-maskmoney-v3.0.2.min.js"></script>
    <script src="/assets/js/jquery/notify.min.js"></script>
    <script src="/assets/js/jquery/moments-2.29.4.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.8/dist/umd/popper.min.js" integrity="sha384-I7E8VVD/ismYTF4hNIPjVp/Zjvgyol6VFvRkX/vR+Vc4jQkC+hVqc2pM8ODewa9r" crossorigin="anonymous"></script>
    <script src="/bootstrap-5.3.2-dist/js/bootstrap.min.js"></script>
    
</head>

<body>

    <div class="container-fluid">
        <div class="row vh-100">
            <div class="col-12 d-flex flex-column flex-nowrap mh-100">
                <?php

                if (!isset($_SESSION['username'])) {

                    include_once "view/site/login.php";
                } else {

                    include_once "view/site/nav.php"; ?>

                    <div class="row flex-fill overflow-auto">
                        <div class="col-12 mx-auto d-flex flex-column flex-nowrap mh-100" style="max-width: 1000px;">
                            <?php

                            try {

                                $dados = explode('.', $_SESSION['token']);
                                $claims = json_decode(base64_decode($dados[1]), true);
                                $_SESSION['claims'] =  explode(",", $claims['claims']);
                                // print_r($claims);
                                // echo "<p>Resultado: ". in_array('products:read',$_SESSION['claims']) ? 'Encontrado' : 'Não encontrou' . "</p>";

                            } catch (Exception $e) {
                                // echo 'Erro: ' . $e->getMessage();
                            ?>
                                <script>
                                    console.error('Não foi possível decodificar as permissões do usuário');
                                    console.error(<?= 'Erro: ' . $e->getMessage() ?>);
                                </script>
                            <?php
                            }

                            $url = isset($_GET['url']) ? $_GET['url'] : null;

                            if ($url !== null) {

                                $url = explode('/', $_GET['url']);
                                // var_dump($url);

                                $path = "view/";

                                switch ($url[0]) {
                                    case 'home':
                                        $path .= "site/home.php";

                                        include_once $path;
                                        break;

                                    case 'budgets':
                                        echo '<script>const sectorName = "Vendas"; </script>';
                                        $path .= "budgets/budgets.php";

                                        include_once $path;
                                        break;

                                    case 'clients':
                                        echo '<script>const sectorName = "Clientes"; </script>';
                                        $path .= "clients/clients.php";

                                        include_once $path;
                                        break;

                                    case 'employees':
                                        echo '<script>const sectorName = "Funcionários"; </script>';
                                        $path .= "employees/employees.php";

                                        include_once $path;
                                        break;

                                    case 'orders':
                                        echo '<script>const sectorName = "Vendas"; </script>';
                                        $path .= "orders/orders.php";

                                        include_once $path;
                                        break;

                                    case 'products':
                                        echo '<script>const sectorName = "Produtos"; </script>';
                                        $path .= "products/products.php";

                                        include_once $path;
                                        break;

                                    default:
                                        $path .= "site/page404.php";

                                        include_once $path;
                                }
                            } else {

                                include_once "view/site/home.php";
                            } ?>
                        </div>
                    </div> <?php
                        } ?>
            </div>
        </div>
    </div>

    <?php include_once "includesDefault.php"; ?>

</body>

</html>