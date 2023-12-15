<?php

class Desafio
{
    /**
     * @var int[]
     */
    private array $sementes;

    /**
     * @var Mapeamento[]
     */
    private array $mapas;

    private float $inicio;

    public function __construct(private readonly string $caminhoDoArquivo)
    {
        $this->inicio = microtime(true);
    }

    public function imprimirTempoGasto(): void{
        $agora = microtime(true);

        echo sprintf('%.4f segundos se passaram', $agora - $this->inicio) . PHP_EOL;
    }

    public function lerArquivo(): void
    {
        $linhas = array_filter(explode(PHP_EOL, file_get_contents($this->caminhoDoArquivo)));

        $this->sementes = explode(' ', str_replace('seeds: ', '', array_shift($linhas)));

        $this->mapas = [];
        $mapeamento = new Mapeamento('', '');
        foreach ($linhas as $linha) {
            if (str_ends_with($linha, ':')) {
                $tipoDeMapeamento = str_replace(' map:', '', $linha);
                list($origem, $destino) = explode('-to-', $tipoDeMapeamento);

                $mapeamento = new Mapeamento($origem, $destino);
                $this->mapas[$origem] = $mapeamento;

                continue;
            }

            $mapeamento->adicionarFaixa($linha);
        }
    }

    public function mapear(): int
    {
        $localizacaoMaisProxima = -1;
        foreach ($this->sementes as $semente) {
//            list($listNumeroDaSemente, $quantidadeDeSementes) = $semente;

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

            echo sprintf('Semente %d => Localização %d', $semente, $valorDestino) . PHP_EOL;

            $this->imprimirTempoGasto($inicio);
        }

        return $localizacaoMaisProxima;
    }
}