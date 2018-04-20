# Feriados nacionais e de algumas cidades brasileiras

Implementação de verificação offline de feriados nacionais e de algumas cidades brasileiras

# instalação

`composer install diegoforseti/calendario-feriados`

# utilização

```

<?php

require_once __DIR__ . '/../vendor/autoload.php';

$feriados = new \Forseti\CalendarioFeriados\Feriado(['sp', 'rj']);

$date = new DateTime('2018-05-31');

echo "\nLista de feriados: ";
print_r($feriados->listar());

echo "\nÉ dia útil?: ";
var_dump($feriados->ehDiaUtil($date));

echo "\nÉ feriado?: ";
var_dump($feriados->ehFeriado($date));

echo "\nPróximo dia útil: ";
var_dump($feriados->proximoDiaUtil($date));

```
