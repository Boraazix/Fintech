<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trabalho</title>
    <link rel="stylesheet" href="style.css">
    <style>
      
    </style>
</head>

<body>
    <header>
        <hr>
        <h2>Russell's FinTech</h2>
        <hr>
    </header>
    <main>
        <h1>Investimento</h1>

        <?php
        require_once 'classes/autoloader.class.php';

        R::setup(
            'mysql:host=localhost;dbname=fintech',
            'root',
            ''
        );

        $invest = R::dispense('investimento');

        //conferindo valores recebidos no get
        if (isset($_GET['cliente']))
            $invest->cliente = $_GET['cliente'];
        else
            $invest->cliente = 'não especificado';
        if (isset($_GET['aporte_inicial']) && ($_GET['aporte_inicial'] + 0) >= 0 && ($_GET['aporte_inicial'] + 0) < 1000000)
            $invest->aporte_inicial = $_GET['aporte_inicial'];
        else
            $invest->aporte_inicial = 500;
        if (isset($_GET['periodo']) && ($_GET['periodo'] + 0) >= 1 && ($_GET['periodo'] + 0) <= 480)
            $invest->periodo = $_GET['periodo'];
        else
            $invest->periodo = 12;
        if (isset($_GET['rendimento']) && ($_GET['rendimento'] + 0) >= 0 && ($_GET['rendimento'] + 0) <= 20)
            $invest->rendimento = $_GET['rendimento'];
        else
            $invest->rendimento = 0.7;
        if (isset($_GET['aporte_mensal']) && ($_GET['aporte_mensal'] + 0) >= 0 && ($_GET['aporte_mensal'] + 0) < 1000000)
            $invest->aporte_mensal = $_GET['aporte_mensal'];
        else
            $invest->aporte_mensal = 350;


        $id = Util::registrarInvestimento($invest);

        R::close();

        ?>
        <fieldset>
            <legend>Dados</legend>

            <label>Id: <b><?= $id ?></b></label><br>
            <label>Cliente: <b><?= $invest->cliente ?></b></label><br>
            <label>Aporte inicial (R$): <b><?= $invest->aporte_inicial ?></b></label><br>
            <label>Período (meses): <b><?= $invest->periodo ?></b></label><br>
            <label>Rendimento (%): <b><?= $invest->rendimento ?></b></label><br>
            <label>Aporte mensal (R$): <b><?= $invest->aporte_mensal ?></b></label><br>

        </fieldset>

        <?php
        

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
        ?>

        <p id="back"><a href="./entrada.html">Voltar</a></p>
    </main>
    <footer>
        <hr>
        <p>Russell Edward & Vitor Gabriel - &copy; 2023 - all rights reserved</p>
        <hr>
    </footer>
</body>

</html>