<?php

require_once __DIR__ . '/../vendor/autoload.php';

$feriados = new \Forseti\CalendarioFeriados\Feriado(['sp']);

$date = new DateTime('2018-04-20');

echo "\nLista de feriados: ";
print_r($feriados->listar());

echo "\nÉ dia útil?: ";
var_dump($feriados->ehDiaUtil($date));

echo "\nÉ feriado?: ";
var_dump($feriados->ehFeriado($date));


echo "\nPróximo dia útil: ";
var_dump($feriados->proximoDiaUtil($date, 8));

echo "\n\n";