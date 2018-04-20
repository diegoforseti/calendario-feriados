<?php

namespace Forseti\CalendarioFeriados;

use InvalidArgumentException;

final class FeriadoCalculado
{

    const DIA = 86400;
    const DIAS_DO_CARNAVAL_ANTES_DA_PASCOA = 47;
    const DIAS_CORPUSCRISTI_DEPOIS_PASCOA = 60;
    const DIAS_SEXTASANTA_ANTES_PASCOA = 2;
    const FORMATO_DATA_KEY = 'm-d';

    private $pascoa;

    public function __construct($anoBase)
    {
        $this->pascoa = @easter_date($anoBase);

        if ($this->pascoa === false) {
            throw new InvalidArgumentException(error_get_last()['message']);
        }
    }

    /**
     * Calcular o feriado do carnaval
     * @return string com dia / mes
     */
    public function getCarnaval()
    {
        return $this->formatarTime($this->pascoa - (self::DIAS_DO_CARNAVAL_ANTES_DA_PASCOA * self::DIA));
    }

    /**
     * Calcular o feriado de Sexta Santa
     * @return string com dia / mes
     */
    public function getSextaSanta()
    {
        return $this->formatarTime($this->pascoa - (self::DIAS_SEXTASANTA_ANTES_PASCOA * self::DIA));
    }

    /**
     * Calcular o feriado de Corpus Cristi
     * @return string com dia / mes
     */
    public function getCorpusCristi()
    {
        return $this->formatarTime($this->pascoa + (self::DIAS_CORPUSCRISTI_DEPOIS_PASCOA * self::DIA));
    }

    /**
     * Formatação padrão para o array de feriados
     * @return string
     */
    private function formatarTime($timestamp)
    {
        return date(self::FORMATO_DATA_KEY, $timestamp);
    }

}