<?php

class Desafio
{
    /**
     * @var int[][]
     */
    private array $sementes;

    /**
     * @var Mapeamento[]
     */
    private array $mapas;

    /**
     * @var Mapeamento[]
     */
    private array $mapas2;

    private float $inicio;
    /**
     * @var FaixaDeSemente[]
     */
    private array $faixasDeSementes = [];

    public function __construct(private readonly string $caminhoDoArquivo)
    {
        $this->inicio = microtime(true);
    }

    public function imprimirTempoGasto(): void
    {
        $agora = microtime(true);

        echo sprintf('%.4f segundos se passaram', $agora - $this->inicio) . PHP_EOL;
    }

    public function lerArquivo(): void
    {
        $linhas = array_filter(explode(PHP_EOL, file_get_contents($this->caminhoDoArquivo)));

        $sementes = explode(' ', str_replace('seeds: ', '', array_shift($linhas)));

        $this->sementes = array_chunk($sementes, 2);
        $this->faixasDeSementes = [];
        foreach ($this->sementes as $sementesEQuantidades) {
            $this->faixasDeSementes[] = new FaixaDeSemente($sementesEQuantidades[0], $sementesEQuantidades[1]);
        }

        $this->mapas = [];
        $this->mapas2 = [];
        $mapeamento = new Mapeamento('', '');
        foreach ($linhas as $linha) {
            if (str_ends_with($linha, ':')) {
                $tipoDeMapeamento = str_replace(' map:', '', $linha);
                list($origem, $destino) = explode('-to-', $tipoDeMapeamento);

                $mapeamento = new Mapeamento($origem, $destino);
                $this->mapas[$origem] = $mapeamento;
                $this->mapas2[$destino] = $mapeamento;

                continue;
            }

            $mapeamento->adicionarFaixa($linha);
        }
    }

    public function mapear(): int
    {
        $localizacaoMaisProxima = -1;
        foreach ($this->sementes as $sementesEQuantidades) {
            list($listNumeroDaSemente, $quantidadeDeSementes) = $sementesEQuantidades;

            for ($semente = $listNumeroDaSemente; $semente < $listNumeroDaSemente + $quantidadeDeSementes; $semente++) {
                $origem = 'seed';
                $valorOrigem = $semente;
                $valorDestino = -1;
                while ($mapeamento = ($this->mapas[$origem] ?? null)) {
                    $valorDestino = $mapeamento->mapear($valorOrigem);

                    $origem = $mapeamento->getDestino();
                    $valorOrigem = $valorDestino;
                }

                if ($valorDestino < $localizacaoMaisProxima || $localizacaoMaisProxima === -1) {
                    $localizacaoMaisProxima = $valorDestino;
                }

//                echo sprintf('Semente %d => Localização %d', $semente, $valorDestino) . PHP_EOL;
            }
            $this->imprimirTempoGasto();
        }

        return $localizacaoMaisProxima;
    }

    public function mapear2(): int
    {
        $localizacaoMaisProxima = -1;
        for ($localizacao = 0; $localizacao < 60000000; $localizacao++) {
            if (($localizacao % 200000) === 0) {
                echo 'Localização ' . $localizacao . PHP_EOL;
                $this->imprimirTempoGasto();
            }

            $destino = 'location';
            $valorDestino = $localizacao;
            $valorOrigem = -1;
            while ($mapeamento = ($this->mapas2[$destino] ?? null)) {
                $valorOrigem = $mapeamento->mapearOrigem($valorDestino);

                $destino = $mapeamento->getOrigem();
                $valorDestino = $valorOrigem;
            }

            $faixa = current(
                array_filter($this->faixasDeSementes, function (FaixaDeSemente $faixa) use ($valorOrigem) {
                    return $faixa->contemValorSemente($valorOrigem);
                })
            );

//            echo sprintf('A semente é %d', $valorOrigem) . PHP_EOL;
//            echo sprintf('A localização é %d', $localizacao) . PHP_EOL;

            if ($faixa) {
                echo sprintf('A semente é %d', $valorOrigem) . PHP_EOL;

                $this->imprimirTempoGasto();

                return $localizacao;
            }
        }

        return $localizacaoMaisProxima;
    }
}