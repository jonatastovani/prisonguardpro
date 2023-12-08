<?php
/*Formulário para incluir presos.
Todos presos que chegarem são inclusos aqui.*/

//Verifica se o usuário tem a permissão necessária
$permissoesNecessarias = array(9,11);
$blnPermitido = verificaPermissao($permissoesNecessarias);
if($blnPermitido!==true){
    include_once "acesso_negado.php";
    exit();
}

$idpresobancodados = isset($_POST['idpresobancodados'])?$_POST['idpresobancodados']:0;
$tipoacao = isset($_POST['tipoacao'])?$_POST['tipoacao']:'incluir';

if($idpresobancodados==0){
    echo "<h1>O IDPRESO não foi informado.</h1>";
    exit;
}?>

<div class="titulo-pagina">
    <h1 id="titulo">Incluir Preso</h1>
    <input type="hidden" name="tipoacao" id="tipoacao" value="<?=$tipoacao?>">
</div>
<div class="form">
    <div class="grupo">
        <h3>Informações da Entrada de Presos</h3>
        Entrada nº: <b><span id="identrada"></b></span>; 
        ID Preso nº: <b><span id="idpreso"></span></b>;<br>
        Origem Entrada nº: <b><span id="idorigem"></span></b>, Nome Origem: <b><span id="nomeorigem"></span></b>;<br>
        Data/Hora Entrada: <b><span id="datahoraentrada"></span></b><br>
    </div>
    <input type="hidden" name="idpresobancodados" id="idpresobancodados" value="<?=$idpresobancodados?>">
    <div style="display: flex; flex-direction: row; flex-wrap: wrap;">
        <div style="display: inline-block; max-width: 660px;">
            <div class="grupo">
                <div class="grupo">
                    <input type="hidden" name="matriculavinculada" id="matriculavinculada">
                    <label for="matricula">Matrícula: </label>
                    <input type="number" id="matricula" style="width: 90px; text-align: center;" require> -
                    <input type="number" id="digito" min="0" max="9" style="width: 25px; text-align: center" disabled>
                    <button id="limpar" hidden>Desvincular Matrícula</button>
                </div>
                <div>
                    <div class="grupo-block">
                        <div class="flex">
                            <div><label for="nome">Nome: </label></div>
                            <div class="largura-restante"><input type="text" name="nome" id="nome" class="largura-total"></div>
                        </div>
                        <div id="outronome" hidden>
                            Nome encontrado: <span id="nomeencontrado"></span>
                            <button id="usarnome" class="espaco-esq">Usar este</button>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="grupo">
                        <label for="rg">RG: </label>
                        <input type="text" name="rg" id="rg" autocomplete="off" class="inp-rg">
                        <br>
                        <div id="outrorg" hidden>
                            RG encontrado: <span id="rgencontrado"></span>
                            <button id="usarrg" class="espaco-esq">Usar este</button>
                        </div>
                    </div>
                    <div class="grupo">
                        <label for="cpf">CPF: </label>
                        <input type="text" name="cpf" id="cpf" autocomplete="off" class="inp-cpf">
                    </div>
                    <div class="grupo">
                        <label for="outrodoc">Outro Documento: </label>
                        <input type="text" name="outrodoc" id="outrodoc" autocomplete="off" class="tamanho-pequeno">
                    </div>
                </div>
                <div>
                    <div class="grupo-block">
                        <div class="flex">
                            <div><label for="pai">Pai: </label></div>
                            <div class="largura-restante"><input type="text" id="pai" autocomplete="off" class="largura-total"></div>
                        </div>
                        <div id="outropai" hidden>
                            Pai encontrado: <span id="paiencontrado"></span>
                            <button id="usarpai" class="espaco-esq">Usar este</button>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="grupo-block">
                        <div class="flex">
                            <div><label for="mae">Mãe: </label></div>
                            <div class="largura-restante"><input type="text" id="mae" autocomplete="off" class="largura-total"></div>
                        </div>
                        <div id="outromae" hidden>
                            Mãe encontrado: <span id="maeencontrado"></span>
                            <button id="usarmae" class="espaco-esq">Usar este</button>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="grupo">
                        <h4 class="titulo-grupo">Cidade do Nascimento</h4>
                        
                        <label for="selectnacionalidade">Nacionalidade</label>
                        <select name="selectnacionalidade" id="selectnacionalidade" autocomplete="TRUE"></select>

                        <div id="localnascimento" hidden>
                            <label for="ufnasc">UF</label>
                            <select name="ufnasc" id="ufnasc"></select>
                            <label for="searchcidadenasc" class="espaco-esq">Cod. Cidade</label>
                            <input type="search" name="searchcidadenasc" id="searchcidadenasc" list="listacidadenasc" class="cod-search" autocomplete="off">
                            <!-- Datalist pode ser usado para fazer a pesquisa e preencher o select e vice-versa, porém o valor do select que vai ser usado no momento de salvar -->
                            <datalist id="listacidadenasc"></datalist>
                            <div style="display: inline-block;">
                                <label for="selectcidadenasc" class="espaco-esq">Cidade</label>
                                <select name="selectcidadenasc" id="selectcidadenasc">
                                    <option value="0">Selecione o Estado (UF)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="grupo">
                        <label for="datanascimento">Data de Nascimento</label>
                        <input type="date" id="datanascimento" name="datanascimento" value="<?=date('Y-m-d')?>">
                    </div>
                </div>
            </div>
        </div>
        <div id="fotopreso1" class="divfotopreso" style="margin-left: 30px; margin-top: 20px;"><img src="imagens/sem-foto.png" alt="foto_preso"></div>
    </div>
    
    <div class="grupo largura-total">
        <div class="grupo">
            <label for="regime">Regime</label>
            <select name="regime" id="regime">
                <option value="AB">AB</option>
                <option value="SA">SA</option>
                <option value="FE">FE</option>
            </select>
        </div>
        <div class="grupo">
            <label for="provisorio">Provisório</label>
            <select name="provisorio" id="provisorio">
                <option value="1">Sim</option>
                <option value="0">Não</option>
            </select>
        </div>
        <div class="grupo">
            <label for="reincidente">Reincidente</label>
            <select name="reincidente" id="reincidente">
                <option value="1">Sim</option>
                <option value="0" selected>Não</option>
            </select>
        </div>
        
        <div class="grupo">
            <label for="dataprisao">Data da Prisão</label>
            <input type="date" id="dataprisao" name="dataprisao" value="<?=date('Y-m-d')?>">
        </div>
        <div class="grupo">
            <label for="dataentrada">Data da Inclusão</label>
            <input type="date" id="dataentrada" name="dataentrada" value="<?=date('Y-m-d')?>" disabled>
            <input type="time" name="horaentrada" id="horaentrada" disabled>
        </div>
        <div class="grupo">
            <label for="tipomovimentacao">Tipo de movimentação</label>        
            <select id="tipomovimentacao"></select>

            <label for="searchmotivo" class="espaco-esq">Cod. Motivo</label>
            <input type="search" id="searchmotivo" list="listamotivo" class="cod-search">
            <datalist id="listamotivo"></datalist>
            <br>

            <label for="selectmotivo">Motivo da movimentação</label>        
            <select id="selectmotivo"></select>
        </div>

        <textarea id="observacoes" cols="70" rows="4" placeholder="Observações da inclusão" class="largura-total" style="margin-top: 5px; border-radius: 3px;"></textarea>

        <div class="grupo-block" id="campoartigos">
            <h4 class="titulo-grupo">Artigos</h4>
            <label for="searchartigopreso">Cód. Artigo</label>
            <datalist id="listaartigos"></datalist>
            <input type="search" id="searchartigopreso" list="listaartigos" style="width: 90px;">
            
            <label for="selectartigopreso" style="padding-left: 5px;">Artigo</label>
            <select id="selectartigopreso" class="artigos"></select>
            
            <button id="incluirartigo" style="margin-left: 5px;">Incluir Artigo</button>
            <button id="novoartigo">Novo Artigo</button>
            <div id="artigopreso" class="container-flex max-height-100"></div>
        </div>

    </div>
    
    <div class="final-pagina">
        <button id="salvarinclusao">Salvar</button>
    </div>
</div>

<?php include_once "popups/novo_artigo_popup.php"; ?>

<script src="js/cimic/cim_incluir_presos.js"></script>

