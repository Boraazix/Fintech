<?php


class Util
{
    public static function registrarInvestimento($invest)
    {
        require_once 'classes/autoloader.class.php';

        // garantindo que o banco tenha os devidos type
        $invest->cliente = "$invest->cliente";
        $invest->periodo += 0;
        $invest->aporte_inicial += 0.0;
        $invest->rendimento += 0.0;
        $invest->aporte_mensal += 0.0;

        $id = R::store($invest);

        return $id;
    }

    public static function gerarTabela($invest)
    {
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
    }
}
