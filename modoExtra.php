<?php ob_start();?>
<!DOCTYPE html>
<html lang='en'>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danmakudle</title>
    <?php include 'meta.php'; ?>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>

<body class="d-flex flex-column min-vh-100">
    <?php include 'header.php'; ?>
    <main class="container flex-grow-1">
        <!-- título parte arriba -->
        <div class="row mt-4 text-center">
            <div class="col">
                <h1>Hay un hombre</h1>
                <p class="subtitulo">Hay un hombre detrás del árbol</p>
            </div>
        </div>

        <div class="row g-4 justify-content-center mt-1">
            <div class="col-md-3 mt-4">
                <div class="card">
                    <div class="card-body d-flex flex-column text-center">
                        <a href="#" onclick="document.getElementById('n').innerText++; return false;">
                            <img src="img/boton.png" class="img-fluid">
                        </a>
                        <h2 id="n" class="mt-3">0</h2>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>

</html>