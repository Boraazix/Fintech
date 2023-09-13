<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trabalho</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <header>
        <hr>
        <h2>Russell's FinTech</h2>
        <hr>
    </header>
    <main>
        <h1>Histórico</h1>
        <form action="#" method="get">
            <fieldset>
                <legend>Simulação a se recuperar</legend>
                <label for="id">Id: </label>
                <input id="id" type="number" min="1" name="simulacao" value="<?= (isset($_GET['simulacao']) ? $_GET['simulacao'] : '') ?>" required><br>
                <input type="hidden" name="submit">
                <button type="submit">Recuperar</button>
            </fieldset>
        </form>
        <?php
        require_once 'classes/autoloader.class.php';

        R::setup(
            'mysql:host=localhost;dbname=fintech',
            'root',
            ''
        );

        if (isset($_GET['submit'])) {
            if (isset($_GET['simulacao'])) {
                $id = $_GET['simulacao'] + 0;
                if (R::findOne('investimento', 'id = ?', [$id])) {
                    $invest = R::load('investimento', $id);

                    // dados
                    $html = "
        <fieldset>
            <legend>Dados</legend>
    
            <label>Id: <b>$id </b></label><br>
            <label>Cliente: <b>$invest->cliente</b></label><br>
            <label>Aporte inicial (R$): <b>$invest->aporte_inicial</b></label><br>
            <label>Período (meses): <b>$invest->periodo</b></label><br>
            <label>Rendimento (%): <b>$invest->rendimento</b></label><br>
            <label>Aporte mensal (R$): <b>$invest->aporte_mensal</b></label><br>
        </fieldset>
    ";
                    echo $html;

                    // tabela
                    Util::gerarTabela($invest);
                    
                } else {
                    echo '<p class="red">ID inválida.</p>';
                }
            } else {
                echo '<p class="red">ID não informado.</p>';
            }
        }

        R::close();
        ?>
        <p id="back"><a href="./index.html">Página inicial</a></p>
    </main>
    <footer>
        <hr>
        <p>Russell Edward & Vitor Gabriel - &copy; 2023 - all rights reserved</p>
        <hr>
    </footer>
</body>

</html>