<?php
/*Formulário para incluir presos.
Todos presos que chegarem são inclusos aqui.*/

//Verifica se o usuário tem a permissão necessária
// $permissoesNecessarias = array(9,11);
// $blnPermitido = verificaPermissao($permissoesNecessarias);
// if($blnPermitido!==true){
//     include_once "acesso_negado.php";
//     exit();
// }

$idbancovisi = isset($_POST['idbancovisi'])?$_POST['idbancovisi']:0;

if($idbancovisi==0){
    echo "<h1>O ID da Visita não foi informado.</h1>";
    exit;
}?>

<div class="titulo-pagina">
    <h1 id="titulo">Alterar Visitante</h1>
</div>
<div class="form">
    <input type="hidden" id="idbancovisi" value="<?=$idbancovisi?>">
    <div class="grupo-block">
        <h4 class="titulo-grupo">Dados do Preso</h4>
        <span>Matrícula: <b><span id="matricula"></span></b></span>
        <span class="margin-espaco-esq">Nome: <b><span id="nomepreso"></span></b></span><br>
        <span>Mãe: <b><span id="maepreso"></span></b></span>
        <span class="margin-espaco-esq">Pai: <b><span id="paipreso"></span></b></span><br>
        <span>Raio/Cela: <b><span id="raiocela"></span></b></span>
    </div>
    
    <div class="grupo-block">
        <h4 class="titulo-grupo">Informações do Visitante</h4>

        <span>Nome Visitante: <b><span id="nomevisitante"></span></b></span><br>
        <span>Data da Cadastro: <b><span id="datacadastro"></span></b></span>
        <span class="margin-espaco-esq">Parentesco: <b><span id="parentesco"></span></b></span>

        <div class="flex">
            <div class="grupo">
                <h4 class="titulo-grupo">Dados pessoais</h4>
                <fieldset class="grupo-block">
                    <legend><label for="nome">Nome</label></legend>
                    <input type="text" id="nome" class="largura-total">
                </fieldset>
                <fieldset class="grupo-block">
                    <legend><label for="nomesocial">Nome social</label></legend>
                    <input type="text" id="nomesocial" class="largura-total">
                </fieldset>
                <fieldset class="grupo">
                    <legend><label for="rg">RG</label></legend>
                    <input type="text" id="rg" autocomplete="off" class="inp-rg">
                </fieldset>
                <fieldset class="grupo">
                    <legend><label for="selectemissorrg">Emissor RG</label></legend>
                    <select id="selectemissorrg"></select> /
                    <select id="selectufrg"></select>
                </fieldset>
                <fieldset class="grupo">
                    <legend><label for="cpf">CPF</label></legend>
                    <span id="cpf"></span>
                </fieldset>
            
               <fieldset class="grupo-block">
                    <legend><label for="pai">Pai</label></legend>
                    <input type="text" id="pai" autocomplete="off" class="largura-total">
                </fieldset>
                <fieldset class="grupo-block">
                    <legend><label for="mae">Mãe</label></legend>
                    <input type="text" id="mae" autocomplete="off" class="largura-total">
                </fieldset>
            </div>

            <div class="largura-restante centralizado">
                <div class="divfotovisita inline">
                    <img id="fotovisita1" class="fotovisita block" alt="foto_visitante">
                </div>
                <div id="divbtnsfotovisita" class="nowrap">
                    <button id="atualizarfoto">Atualizar</button>
                </div>
            </div>
        </div>

        <div class="grupo-block">
            <h4 class="titulo-grupo">Dados do Nascimento</h4>
            
            <label for="selectnacionalidade">Nacionalidade</label>
            <select id="selectnacionalidade"></select>

            <div id="localnascimento" hidden>
                <label for="selectufnasc">UF</label>
                <select id="selectufnasc"></select>
                <label for="searchcidadenasc" class="margin-espaco-esq">Cod. Cidade</label>
                <input type="search" id="searchcidadenasc" list="listacidadenasc" class="cod-search" autocomplete="off">
                <datalist id="listacidadenasc"></datalist>

                <div style="display: inline-block;">
                    <label for="selectcidadenasc" class="margin-espaco-esq">Cidade</label>
                    <select id="selectcidadenasc"></select>
                </div>
            </div>

            <label for="datanascimento">Data de Nascimento</label>
            <input type="date" id="datanascimento">

            <fieldset id="divresponsavel" class="grupo-block">
                <legend>Responsável pelo(a) menor de idade</legend>
                <div id="divpesqresponsavel">
                    <fieldset class="grupo">
                        <label for="searchresponsavel">Resp.: </label>
                        <input type="search" id="searchresponsavel" list="listaresponsavel" class="cod-search" autocomplete="off">
                        
                        <select id="selectresponsavel"></select>
                    </fieldset>
                    
                    <fieldset class="grupo">
                        <label for="searchparentresp">Parentesco: </label>
                        <input type="search" id="searchparentresp" list="listagrau" class="cod-search tempsearch" autocomplete="off">
                        <select id="selectparentresp" class="selectgrau"></select>
                    </fieldset>
                </div>
                <div>
                    <input type="checkbox" id="ckbemancipado" title="Selecione caso o(a) visitante é menor de idade, porém entregou documento de emancipação.">
                    <label for="ckbemancipado" title="Selecione caso o(a) visitante é menor de idade, porém entregou documento de emancipação.">Emancipado(a)</label>
                </div>
            </fieldset>
            <datalist id="listaresponsavel"></datalist>

        </div>

        <div class="grupo">
            <h4 class="titulo-grupo">Endereço de moradia</h4>
            <fieldset class="grupo">
                <legend><label for="logradouro">Logradouro</label></legend>
                <input type="text" id="logradouro" class="tamanho-grande">
            </fieldset>
            <fieldset class="grupo">
                <legend><label for="numero">Número</label></legend>
                <input type="text" id="numero" style="width: 50px;" maxlength="6">
            </fieldset>
            <fieldset class="grupo">
                <legend><label for="complemento">Complemento</label></legend>
                <input type="text" id="complemento" class="tamanho-pequeno">
            </fieldset>
            <fieldset class="grupo">
                <legend><label for="bairro">Bairro</label></legend>
                <input type="text" id="bairro" class="tamanho-medio">
            </fieldset>
            <fieldset class="grupo">
                <legend><label for="selectufmorad">UF</label></legend>
                <select id="selectufmorad"></select>
            </fieldset>
            <fieldset class="grupo">
                <legend>Cidade</legend>
                <label for="searchcidademorad">Cod.: </label>
                <input type="search" id="searchcidademorad" list="listacidademorad" class="cod-search" autocomplete="off">
                <!-- Datalist pode ser usado para fazer a pesquisa e preencher o select e vice-versa, porém o valor do select que vai ser usado no momento de salvar -->
                <datalist id="listacidademorad"></datalist>

                <div style="display: inline-block;">
                    <select id="selectcidademorad" autocomplete="TRUE"></select>
                </div>
            </fieldset>
        </div>

        <div class="grupo-block">
            <h4 class="titulo-grupo">Observações</h4>
            <textarea id="observacoes" rows="5" class="largura-total"></textarea>
        </div>
    </div>

    <div class="grupo-block">
        <h4 class="titulo-grupo">Dados da visita</h4>
        <fieldset class="grupo">
            <legend><label for="selectparentesco">Parentesco com o Preso</label></legend>
            <label for="searchgrau">Cod.: </label>
            <input type="search" id="searchgrau" list="listagrau" class="cod-search tempsearch" autocomplete="off">
            <select id="selectparentesco" class="margin-espaco-esq selectgrau"></select>
        </fieldset>
        <datalist id="listagrau"></datalist>

        <div class="flex">
            <div id="situacaovisitante" class="div-metade-aling-esquerda">
                <fieldset class="grupo-block">
                    <legend><label for="selectsitvisitante">Situação Visitante</label></legend>
                    <div>
                        <select id="selectsitvisitante" class="selectsituacao" style="width: 100%;"></select>
                    </div>
                    <div>
                        <label>Comentário</label><br>
                        <textarea class="txtcomentario largura-total" rows="5"></textarea>
                    </div>

                    <div id="mensagens-visitante" class="flex">
                        <button id="msgvisiok" title="Visitante Autorizado">Visitante Autorizado</button>
                    </div>
                    <div class="final-pagina">
                        <button id="inserirsitvisitante">Inserir Situação</button>
                    </div>

                    <fieldset id="sitvisitantenova" class="grupo-block relative" style="background-color: lightgreen;" hidden>
                        <legend style="background-color: lightgreen; border-radius: 5px;">Nova Situação</legend>
                        <span>Situação: <b><span class="situacao htmlvisitante">Visitante Pendente</span></b></span><br>
                        <span>Comentário: <b><span class="comentario htmlvisitante">Comentário visitante</span></b></span><br>
                        <span>Data: <b><span class="data"></span></b></span>
                        <button class="fechar-absolute">&times;</button>
                    </fieldset>

                    <fieldset id="sitvisitanteatual" class="grupo-block">
                        <legend>Situação atual</legend>
                        <span>Situação: <b><span class="situacao"></span></b></span><br>
                        <span>Comentário: <b><span class="comentario"></span></b></span><br>
                        <span>Data: <b><span class="data"></span></b></span>
                    </fieldset>

                </fieldset>
            </div>
            <div class="div-metade-aling-direita">
                <fieldset id="situacaovisita" class="grupo-block">
                    <legend><label for="selectsitvisita">Situação Visita</label></legend>
                    <div>
                        <select id="selectsitvisita" class="selectsituacao" style="width: 100%;"></select>
                    </div>
                    <div class="align-lef">
                        <label>Comentário</label><br>
                        <textarea class="txtcomentario largura-total" rows="5"></textarea>
                    </div>

                    <div id="mensagens-visita" class="flex">
                        <button id="msgdocsok" title="Documentos OK, liberado visitação.">Documentos OK</button>
                        <button id="msgfaltadoc" title="Documentação devolvida em: xxxxx por falta do(s) documento(s): yyyy">Falta documentos</button>
                    </div>
                    <div class="final-pagina">
                        <button id="inserirsitvisita">Inserir Situação</button>
                    </div>

                    <fieldset id="sitvisitanova" class="grupo-block align-lef relative" style="background-color: lightgreen;" hidden>
                        <legend style="background-color: lightgreen; border-radius: 5px;">Nova Situação</legend>
                        <span>Situação: <b><span class="situacao htmlvisita">Visitante Pendente</span></b></span><br>
                        <span>Comentário: <b><span class="comentario htmlvisita">Comentário visitante</span></b></span><br>
                        <span>Data: <b><span class="data"></span></b></span>
                        <button class="fechar-absolute">&times;</button>
                    </fieldset>

                    <fieldset id="sitvisitaatual" class="grupo-block align-lef">
                        <legend>Situação atual</legend>
                        <span>Situação: <b><span class="situacao"></span></b></span><br>
                        <span>Comentário: <b><span class="comentario"></span></b></span><br>
                        <span>Data: <b><span class="data"></span></b></span>
                    </fieldset>
                </fieldset>
            </div>
        </div>
    </div>
    
    <div class="final-pagina">
        <button id="salvarvisitante">Salvar</button>
    </div>
</div>

<?php include_once "popups/rol_foto_visitante_popup.php"; ?>

<script src="js/rol/rol_alt_visitante.js"></script>

