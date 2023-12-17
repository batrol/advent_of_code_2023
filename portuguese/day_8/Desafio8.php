<?php

class Desafio8
{
    private float $inicio;

    /**
     * @var No[]
     */
    private array $mapa;

    /**
     * @var string[]
     */
    private array $instrucoes;

    public function __construct(private readonly string $caminhoDoArquivo)
    {
        $this->inicio = microtime(true);
    }

    public function lerArquivo(): void
    {
        $linhas = array_filter(explode(PHP_EOL, file_get_contents($this->caminhoDoArquivo)));

        $this->instrucoes = str_split(array_shift($linhas));

        $this->mapear($linhas);

        $this->imprimirTempoGasto('ler arquivo');
    }

    public function mapear(array $linhas): void
    {
        $this->mapa = [];

        foreach ($linhas as $linha) {
            list($coordenada, $esquerda, $direita) = explode(' ', str_replace([' =', '(', ')', ','], '', $linha));
            $this->mapa[$coordenada] = new No($esquerda, $direita);
        }
    }

    public function calcularCaminho(string $proximoNo, string $noDestino): int
    {
        $passos = 0;
        while (!str_ends_with($proximoNo, $noDestino)) {
            foreach ($this->instrucoes as $instrucao) {
                $no = $this->mapa[$proximoNo];

                $proximoNo = $no->getNo($instrucao);
                $passos++;

                if (str_ends_with($proximoNo, $noDestino)) {
                    break;
                }
            }
        }

        $this->imprimirTempoGasto('calcular caminho');

        return $passos;
    }

    public function calcularCaminhoSimultaneamente(): int|GMP
    {
        $nosIniciais = array_filter(array_keys($this->mapa), function ($no) {
            return str_ends_with($no, 'A');
        });

        $todosOsPassos = [];
        foreach ($nosIniciais as $no) {
            $todosOsPassos[] = $this->calcularCaminho($no, 'Z');
        }

        $todosOsPassos = array_unique($todosOsPassos);

        while (count($todosOsPassos) > 1) {
            var_dump($todosOsPassos);

            $num1 = array_shift($todosOsPassos);
            $num2 = array_shift($todosOsPassos);

            $mmc = gmp_lcm($num1, $num2);
            $todosOsPassos[] = $mmc;
        }

        $this->imprimirTempoGasto('calcular caminho todos os passos');

        return array_shift($todosOsPassos);
    }

    private function imprimirTempoGasto(string $acao): void
    {
        $agora = microtime(true);

        echo sprintf('%.4f segundos se passaram para %s', $agora - $this->inicio, $acao) . PHP_EOL;
    }
}