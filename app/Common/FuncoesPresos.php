<?php

namespace App\Common;

class FuncoesPresos
{
    public static function retornaDigitoMatricula($matricula): int
    {
        $matricula = strval($matricula);

        $mult = 2;
        $soma = 0;
        $s = "";

        for ($i = strlen($matricula) - 1; $i >= 0; $i--) {
            $s = ($mult * intval($matricula[$i])) . $s;
            if (--$mult < 1) {
                $mult = 2;
            }
        }

        for ($i = 0; $i < strlen($s); $i++) {
            $soma = $soma + intval($s[$i]);
        }

        $soma = $soma % 10;

        if ($soma != 0) {
            $soma = 10 - $soma;
        }

        return intval($soma);
    }

    public static function retornaMatriculaFormatada($matricula, $tipo = 1)
    {
        $matricula = strval($matricula);
        $digito = substr($matricula, -1);
        $matricula = substr($matricula, 0, -1);

        if ($tipo == 1) {
            return $matricula . '-' . $digito;
        } else if ($tipo == 2) {
            return $matricula;
        } else if ($tipo == 3) {
            return $digito;
        }
    }
}
