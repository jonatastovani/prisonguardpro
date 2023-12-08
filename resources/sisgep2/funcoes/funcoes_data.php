<?php
    //https://www.youtube.com/watch?v=xWKHeD-3JkU - SOBRE DATA E HORA

//Função para adicionar Data ou Hora em uma data informada
//$tipo = $tipo de dados a ser adicionado
//$strPeriodo = caso informado, informar a string de adição, e definir true para soma ou false para subtração
function retornaSomaDataEHora($dataAdicionar,$intQuantidade, $tipo, $strPeriodo = '',$blnsoma = true){
    
    /*
        iniciar com P (de periodo) e seguido de um número por último a unidade. 
        Y | Ano
        M | Mês
        D | Dias
        W | Semanas
        iniciar com T (de time) e seguido de um número por último a unidade. 
        H | Horas
        M | Minutos
        S | Segundos 
    */
    
    if($strPeriodo!=''){
        $dataAdicionar = DateTime::createFromFormat('Y-m-d', $dataAdicionar);
        if($blnsoma===false){
            $dataAdicionar->sub(new DateInterval($strPeriodo));
        }else{
            $dataAdicionar->add(new DateInterval($strPeriodo));
        }
    }
    else{
        //$tipo 1 = Dia
        if($tipo===1){
            $dataAdicionar = DateTime::createFromFormat('Y-m-d', $dataAdicionar);
            if($intQuantidade<0){
                $intQuantidade = abs($intQuantidade);
                $dataAdicionar->sub(new DateInterval('P'.$intQuantidade.'D'));
            }else{
                $dataAdicionar->add(new DateInterval('P'.$intQuantidade.'D'));
            }
        }
        //$tipo 2 = Mês
        elseif($tipo===2){
            $dataAdicionar = DateTime::createFromFormat('Y-m-d', $dataAdicionar);
            if($intQuantidade<0){
                $intQuantidade = abs($intQuantidade);
                $dataAdicionar->sub(new DateInterval('P'.$intQuantidade.'M'));
            }else{
                $dataAdicionar->add(new DateInterval('P'.$intQuantidade.'M'));
            }
        }
        //$tipo 3 = Ano
        elseif($tipo===3){
            $dataAdicionar = DateTime::createFromFormat('Y-m-d', $dataAdicionar);
            if($intQuantidade<0){
                $intQuantidade = abs($intQuantidade);
                $dataAdicionar->sub(new DateInterval('P'.$intQuantidade.'Y'));
            }else{
                $dataAdicionar->add(new DateInterval('P'.$intQuantidade.'Y'));
            }
        }
        //$tipo 4 = Horas
        elseif($tipo===4){
            $dataAdicionar = DateTime::createFromFormat('Y-m-d', $dataAdicionar);
            if($intQuantidade<0){
                $intQuantidade = abs($intQuantidade);
                $dataAdicionar->sub(new DateInterval('T'.$intQuantidade.'H'));
            }else{
                $dataAdicionar->add(new DateInterval('T'.$intQuantidade.'H'));
            }
        }
        //$tipo 5 = Minutos
        elseif($tipo===5){
            $dataAdicionar = DateTime::createFromFormat('Y-m-d', $dataAdicionar);
            if($intQuantidade<0){
                $intQuantidade = abs($intQuantidade);
                $dataAdicionar->sub(new DateInterval('T'.$intQuantidade.'M'));
            }else{
                $dataAdicionar->add(new DateInterval('T'.$intQuantidade.'M'));
            }
        }
        //$tipo 6 = Segundos
        elseif($tipo===6){
            $dataAdicionar = DateTime::createFromFormat('Y-m-d', $dataAdicionar);
            if($intQuantidade<0){
                $intQuantidade = abs($intQuantidade);
                $dataAdicionar->sub(new DateInterval('T'.$intQuantidade.'S'));
            }else{
                $dataAdicionar->add(new DateInterval('T'.$intQuantidade.'S'));
            }
        }   
    }
    return $dataAdicionar->format('Y-m-d H:i:s');
}

//Função para retornar dados de Data e Hora
//Tipo = tipo de dados a ser retornado
function retornaDadosDataHora($DataHora,$tipo){
    // $datanasc = isset($DataHora)?"123":"456";
    // echo $datanasc;
    
    $data = new DateTime($DataHora);
    
    //Tipo 1 = YYYY-mm-dd Ano, mes e Dia
    if($tipo===1){
        return $data->format('Y-m-d');
    }
    //Tipo 2 = dd/mm/YYYY Dia, mes e ano
    elseif($tipo===2){
        return $data->format('d/m/Y');
    }
    //Tipo 3 = dd Dia
    elseif($tipo===3){
        return $data->format('d');
    }
    //Tipo 4 = mm Mês
    elseif($tipo===4){
        return $data->format('m');
    }
    //Tipo 5 = YYYY Ano
    elseif($tipo===5){
        return $data->format('Y');
    }
    //Tipo 6 = H:i Horas e minutos
    elseif($tipo===6){
        return $data->format('H:i');
    }
    //Tipo 7 = H:i:s Horas, minutos e segundos
    elseif($tipo===7){
        return $data->format('H:i:s');
    }
    //Tipo 8 = H Horas
    elseif($tipo===8){
        return $data->format('H');
    }
    //Tipo 9 = i Minutos
    elseif($tipo===9){
        return $data->format('i');
    }
    //Tipo 10 = s Segundos
    elseif($tipo===10){
        return $data->format('s');
    }
    //Tipo 11 = YYYY-mm-dd H:i:s Ano, mes, Dia, Horas, minutos e segundos
    elseif($tipo===11){
        return $data->format('Y-m-d H:i:s');
    }
    //Tipo 12 = dd/mm/YYYY H:i Dia, mes, Ano, Horas, minutos
    elseif($tipo===12){
        return $data->format('d/m/Y H:i');
    }
    //Tipo 13 = YYYY-mm-dd H:i Ano, mes, Dia, Horas e minutos
    elseif($tipo===13){
        return $data->format('Y-m-d H:i');
    }
}

//Função para retornar a diferença entre datas
//Tipo = Tipo de dados a ser retornado
function retornaDiferencaDeDataEHora($DataHoraMenor,$DataHoraMaior,$tipo){
    if(strlen($DataHoraMenor)<19){
        $DataHoraMenor = DateTime::createFromFormat('Y-m-d', substr($DataHoraMenor,0,10));
    }else{
        $DataHoraMenor = DateTime::createFromFormat('Y-m-d H:i:s', $DataHoraMenor);
    }
    if(strlen($DataHoraMaior)<19){
        $DataHoraMaior = DateTime::createFromFormat('Y-m-d', substr($DataHoraMaior,0,10));
    }else{
        $DataHoraMaior = DateTime::createFromFormat('Y-m-d H:i:s', $DataHoraMaior);
    }
    $diferença = $DataHoraMenor->diff($DataHoraMaior);

    //Tipo 1 = Dias (Somente o dia, independente do mês)
    if($tipo==1){
        return $diferença->d;
    }
    //Tipo 2 = Mês
    elseif($tipo===2){
        return $diferença->m;
    }
    //Tipo 3 = Ano
    elseif($tipo==3){
        return $diferença->y;
    }
    //Tipo 4 = Horas
    elseif($tipo==4){
        return $diferença->h;
    }
    //Tipo 5 = Minutos
    elseif($tipo==5){
        return $diferença->i;
    }
    //Tipo 6 = Segundos
    elseif($tipo==6){
        return $diferença->s;
    }
    //Tipo 7 = Representa o número total de dias entre as duas datas (data inicial e data final).
    elseif($tipo==7){
        return $diferença->days;
    }
    //Tipo 8 = Será 1 se o intervalo representa um período negativo de tempo e 0 (zero) caso contrário.
    elseif($tipo==8){
        return $diferença->invert;
    }
}