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
                    function calcularRendimento($valor_inicial, $aporte_mensal, $rendimento_mensal)
                    {
                        $rendimento = ($valor_inicial + $aporte_mensal) * ($rendimento_mensal / 100);
                        $total = $valor_inicial + $aporte_mensal + $rendimento;
                        return array($rendimento, $total);
                    }

                    $str = "\t\t\t<table>\n";
                    $str .= "\t\t\t\t<thead>\n\t\t\t\t\t<tr>\n\t\t\t\t\t\t<th>Mês</th>\n\t\t\t\t\t\t<th>Valor Inicial (R$)</th>\n\t\t\t\t\t\t<th>Aporte Mensal (R$)</th>\n\t\t\t\t\t\t<th>Rendimento (R$)</th>\n\t\t\t\t\t\t<th>Total (R$)</th>\n\t\t\t\t\t</tr>\n\t\t\t\t</thead>\n";

                    $valor_inicial = $invest->aporte_inicial;

                    list($rendimento, $total) = calcularRendimento($valor_inicial, 0, $invest->rendimento);

                    $str .= "\t\t\t\t<tbody>\n\t\t\t\t\t<tr>\n";
                    $str .= "\t\t\t\t\t\t<td>1</td>\n";
                    $str .= "\t\t\t\t\t\t<td>" . number_format($valor_inicial, 2, ",", ".") . "</td>\n";
                    $str .= "\t\t\t\t\t\t<td>---</td>\n";
                    $str .= "\t\t\t\t\t\t<td>" . number_format($rendimento, 2, ",", ".") . "</td>\n";
                    $str .= "\t\t\t\t\t\t<td>" . number_format($total, 2, ",", ".") . "</td>\n";
                    $str .= "\t\t\t\t\t</tr>\n";

                    $valor_inicial = $total;

                    for ($mes = 2; $mes <= $invest->periodo; $mes++) {
                        list($rendimento, $total) = calcularRendimento($valor_inicial, $invest->aporte_mensal, $invest->rendimento);

                        $str .= "\t\t\t\t\t<tr>\n";
                        $str .= "\t\t\t\t\t\t<td>$mes</td>\n";
                        $str .= "\t\t\t\t\t\t<td>" . number_format($valor_inicial, 2, ",", ".") . "</td>\n";
                        $str .= "\t\t\t\t\t\t<td>" . number_format($invest->aporte_mensal, 2, ",", ".") . "</td>\n";
                        $str .= "\t\t\t\t\t\t<td>" . number_format($rendimento, 2, ",", ".") . "</td>\n";
                        $str .= "\t\t\t\t\t\t<td>" . number_format($total, 2, ",", ".") . "</td>\n";
                        $str .= "\t\t\t\t\t</tr>\n";

                        $valor_inicial = $total;
                    }

                    $str .= "\t\t\t\t</tbody>\n\t\t\t</table>\n";
                    echo $str;
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