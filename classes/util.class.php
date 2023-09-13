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
}