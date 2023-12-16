<?php

class Desafio6
{
    private float $inicio;

    /**
     * @var int[]
     */
    private array $tempos;

    /**
     * @var int[]
     */
    private array $distancias;

    public function __construct(private readonly string $caminhoDoArquivo)
    {
        $this->inicio = microtime(true);
    }

    public function lerArquivo(): void
    {
        list($tempos, $distancias) = array_filter(explode(PHP_EOL, file_get_contents($this->caminhoDoArquivo)));

        $this->tempos = $this->extrairDados($tempos);
        $this->distancias = $this->extrairDados($distancias);

        $this->imprimirTempoGasto('ler arquivo');
    }

    public function calcularPossibilidadesDeVitoria(): int
    {
        $possibilidades = 1;
        foreach ($this->tempos as $k => $tempo) {
            $distancia = $this->distancias[$k];

            $corrida = new Corrida($tempo, $distancia);
            $possibilidades *= $corrida->calcularPossibilidadesDeVitoria();
        }

        $this->imprimirTempoGasto('calcular possibilidades de vitória');

        return $possibilidades;
    }

    private function imprimirTempoGasto(string $acao): void
    {
        $agora = microtime(true);

        echo sprintf('%.4f segundos se passaram para %s', $agora - $this->inicio, $acao) . PHP_EOL;
    }

    /**
     * @return int[]
     */
    private function extrairDados(string $linha): array
    {
        $dados = array_filter(
            explode(
                ' ',
                str_replace(['Time:', 'Distance:'], '', $linha)
            )
        );

        array_walk($dados, function (&$dado) {
            $dado = (int)$dado;
        });

        return array_values($dados);
    }
}