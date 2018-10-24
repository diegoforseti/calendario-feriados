<?php

namespace Forseti\CalendarioFeriados;

final class Feriado
{
    private $feriados = [];
    private $ufs = [];

    public function __construct(array $ufs = [])
    {
        $this->ufs = $ufs;
        $this->ufs[] = 'nacional';
        $this->feriados['nacional'] = include_once __DIR__.'/../resources/feriados/nacional.php';

        foreach ($ufs as $uf) {
            $municipal = __DIR__."/../resources/feriados/{$uf}.php";
            if (!file_exists($municipal)) {
                throw new \RuntimeException("{$uf} não implementada");
            }

            $this->feriados[$uf] = include_once $municipal;
        }
    }

    /**
     * Verifica se a data é um dia útil.
     *
     * @param \DateTime $data
     *
     * @return bool
     */
    public function ehDiaUtil(\DateTime $data)
    {
        $diaSemana = intval($data->format('w'));
        if ($diaSemana === 0 || $diaSemana === 6) {
            return false;
        }

        $ehFeriado = $this->ehFeriado($data);
        $ehDiaUtil = ($ehFeriado) ? false : true;

        return $ehDiaUtil;
    }

    /**
     * É feriado?
     *
     * @param \DateTime $data
     *
     * @return bool
     */
    public function ehFeriado(\DateTime $data)
    {
        $ano = $data->format('Y');
        $feriados = $this->listarFeriadosLinear($ano);

        return isset($feriados[$data->format('m-d')]);
    }

    /**
     * Lista de feriados.
     *
     * @param int $anoBase
     *
     * @return array
     */
    public function listar($anoBase = null)
    {
        $anoBase = $anoBase ?: date('Y');

        $feriadoCalculado = new FeriadoCalculado($anoBase);

        $this->feriados['nacional'][$feriadoCalculado->getCarnaval()] = 'Carnaval';
        $this->feriados['nacional'][$feriadoCalculado->getCorpusCristi()] = 'Corpus Cristi';
        $this->feriados['nacional'][$feriadoCalculado->getSextaSanta()] = 'Sexta-Feira Santa';

        ksort($this->feriados);

        return [
            'ano_base' => $anoBase,
            'feriados' => $this->feriados,
        ];
    }

    /**
     * Retorna a lista de feriados sem o ano base no header.
     *
     * @param int $anoBase
     *
     * @return array
     */
    private function listarFeriadosLinear($anoBase = null)
    {
        $feriados = $this->listar($anoBase)['feriados'];

        $resultado = [];

        foreach ($this->ufs as $uf) {
            $resultado = array_merge($resultado, $feriados[$uf]);
        }

        //se usar a função publica, podemos usar abaixo o ksort para exibir ordenado
        //por enquando não há necessidade
        //ksort($resultado);
        return $resultado;
    }

    /**
     * Retorna o próximo dia útil inclusive a data atual.
     *
     * @param \DateTime $dataBase
     *
     * @throws \Exception
     *
     * @return \DateTime
     */
    public function diaUtilMaisProximo(\DateTime $dataBase)
    {
        while ($this->ehDiaUtil($dataBase) === false) {
            $dataBase = $dataBase->add(new \DateInterval('P1D'));
        }

        return $dataBase;
    }

    public function proximoDiaUtil(\DateTime $dataBase, $qtdDiasUteis = 1)
    {
        $interval = new \DateInterval('P1D');

        for ($i = 0; $i < $qtdDiasUteis; $i++) {
            $dataBase = $dataBase->add($interval);
            $dataBase = $this->diaUtilMaisProximo($dataBase);
        }

        return $dataBase;
    }
}
