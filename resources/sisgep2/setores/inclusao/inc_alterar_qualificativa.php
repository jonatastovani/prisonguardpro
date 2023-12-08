<?php
//Verifica se o usuário tem a permissão necessária
$permissoesNecessarias = array(12,10);
$blnPermitido = verificaPermissao($permissoesNecessarias);
if($blnPermitido!==true){
    include_once "acesso_negado.php";
    exit();
}

//Verifica se o usuário tem a permissão necessária
//Se for pertencente a permissão de alterar Qualificativa do setor CIMIC ou Diretor da Inclusão então se libera mais campos para ser editado
$permissoesNecessarias = array(12);
$blnCIMIC = verificaPermissao($permissoesNecessarias);
$permissoesNecessarias = array(3);
$blnDirInclusao = verificaPermissao($permissoesNecessarias);

$matric = isset($_POST['matric'])?$_POST['matric']:0;

if($matric==0){
    echo "<h1>Matricula não foi informada.</h1>";
    exit();
}else{

    $conexaoStatus = conectarBD();
    if($conexaoStatus===true){
        try {
            $sql="SELECT CD.* FROM cadastros CD
            WHERE md5(MATRICULA) = :matric;";
    
            $stmt = $GLOBALS['conexao']->prepare($sql);
            $stmt->bindParam('matric',$matric,PDO::PARAM_STR);
            $stmt->execute();

            $resultado = $stmt->fetchAll();
            //unset($GLOBALS['conexao']);
    
            if(count($resultado)){
                $idpreso = $resultado[0]['IDPRESO'];
                $matric = $resultado[0]['MATRICULA'];
                //Baixa a foto do preso para o servidor local
                $caminhoFoto = baixarFotoServidor($idpreso,1);
            }
    
        } catch (PDOException $e) {
            echo "<h1>Ocorreu um erro. Erro: ". $e->getMessage()."</h1>";
            exit();
        }
    }else{
        echo "<h1>Ocorreu um erro. Erro: ". $conexaoStatus."</h1>";
        exit();
    }
}

?>

<div class="titulo-pagina">
    <h1 id="titulo">Alterar Qualificativa</h1>
</div>
<div class="form">
    <input type="hidden" id="matric" value="<?=$matric?>">
    <input type="hidden" id="idpreso" value="<?=$idpreso?>">
    <div style="display: flex; flex-direction: row; flex-wrap: wrap; align-items: center; justify-content: center;">
        <div style="display: inline-block; max-width: 660px;">
            <div class="grupo">
                <div class="grupo">
                    <h4 class="titulo-grupo">Matrícula</h4>
                    <span><?=midMatricula($matric,3)?></span>
                </div><br>
                
                <?php
                if($blnCIMIC===true || $blnDirInclusao===true){ ?>
                    <div class="grupo-block">
                        <h4 class="titulo-grupo"><label for="nome">Nome</label></h4>
                        <input type="text" id="nome" class="largura-total">
                    </div> <?php
                }else { ?>
                    <div class="grupo-block">
                        <h4 class="titulo-grupo">Nome</h4>
                        <span id="nomespan"></span><br>
                    </div> <?php
                } ?>

                <div class="grupo">
                    <h4 class="titulo-grupo"><label for="rg">RG</label></h4>
                    <input type="text" id="rg" autocomplete="off" class="inp-rg">
                </div>
                <div class="grupo">
                    <h4 class="titulo-grupo"><label for="cpf">CPF</label></h4>
                    <input type="text" id="cpf" autocomplete="off" class="inp-cpf">
                </div>
                <div class="grupo">
                    <h4 class="titulo-grupo"><label for="outrodoc">Outro Documento</label></h4>
                    <input type="text" id="outrodoc" autocomplete="off" class="tamanho-pequeno">
                </div>
                
                <?php
                if($blnCIMIC===true || $blnDirInclusao===true){ ?>
                    <div class="grupo-block">
                        <h4 class="titulo-grupo"><label for="pai">Pai</label></h4>
                        <input type="text" id="pai" autocomplete="off" class="largura-total">  
                    </div>
                    <div class="grupo-block">
                        <h4 class="titulo-grupo"><label for="mae">Mãe</label></h4>
                        <input type="text" id="mae" autocomplete="off" class="largura-total">
                    </div> <?php
                } ?>
            </div>
        </div>
        <div class="divfotopreso" style="margin-left: 30px; margin-top: 20px;">
            <img id="fotopreso1" class="fotopreso" src="<?=$caminhoFoto?>" alt="foto_preso">
            <form id="form2" method="POST" action="principal.php?menuop=inc_foto_preso" target="_blank">
                <input type="hidden" name="matric" value="<?=$matric?>">
                <input type="hidden" name="idpreso" value="<?=$idpreso?>">
                <button type="submit">Alterar foto</button>
            </form> <?php
            if($caminhoFoto=='imagens/sem-foto.png'){ ?>
                <button id="atualizarfoto">Atualizar</button> <?php
            } ?>
       </div>
    </div>

    <?php
        if($blnCIMIC===true){
            ?>
            <div class="grupo-block">
                <h4 class="titulo-grupo">Cidade do Nascimento</h4>
                
                <label for="selectnacionalidade">Nacionalidade</label>
                <select id="selectnacionalidade" autocomplete="TRUE">
                    <?php
                        echo inserirListaSimples(1)
                    ?>
                </select>

                <div id="localnascimento">
                    <label for="ufnasc">UF</label>
                    <select id="ufnasc">
                        <?php
                            echo inserirUF()
                        ?>
                    </select>
                    <label for="searchcidadenasc" class="espaco-esq">Cod. Cidade</label>
                    <input type="search" id="searchcidadenasc" list="listacidadenasc" class="cod-search" autocomplete="off">
                    <!-- Datalist pode ser usado para fazer a pesquisa e preencher o select e vice-versa, porém o valor do select que vai ser usado no momento de salvar -->
                    <datalist id="listacidadenasc"></datalist>

                    <div style="display: inline-block;">
                        <label for="selectcidadenasc" class="espaco-esq">Cidade</label>
                        <select id="selectcidadenasc"></select>
                    </div>
                </div>
                <label for="datanascimento">Data de Nascimento</label>
                <input type="date" id="datanascimento" value="<?=date('Y-m-d')?>">
            </div>            
            <?php
        }
    ?>
    
    <div class="grupo-block">
        <h4 class="titulo-grupo">Aspectos Físicos</h4>

        <div class="grupo">
            <h4 class="titulo-grupo">Cútis</h4>
            <select id="selectcutis">
                <?php
                    echo inserirListaSimples(6);
                ?>
            </select>
        </div>
        <div class="grupo">
            <h4 class="titulo-grupo">Tipo do Cabelo</h4>
            <select id="selecttipocabelo">
                <?php
                    echo inserirListaSimples(7);
                ?>
            </select>
        </div>
        <div class="grupo">
            <h4 class="titulo-grupo">Cor do Cabelo</h4>
            <select id="selectcorcabelo">
                <?php
                    echo inserirListaSimples(8);
                ?>
            </select>
        </div>
        <div class="grupo">
            <h4 class="titulo-grupo">Olhos</h4>
            <select id="selectolhos">
                <?php
                    echo inserirListaSimples(9);
                ?>
            </select>
        </div>
        <div class="grupo">
            <h4 class="titulo-grupo">Estatura</h4>
            <input type="doubleval" id="estatura" class="inp-estatura">
        </div>
        <div class="grupo">
            <h4 class="titulo-grupo">Peso</h4>
            <input type="number" id="peso" class="inp-peso">
        </div>
    </div>

    <div class="grupo-block">
        <h4 class="titulo-grupo">Profissional e Outros</h4>
        <div class="grupo">
            <h4 class="titulo-grupo">Profissão</h4>
            <datalist id="listaprofissao"></datalist>
            
            <label for="searchprofissao">Cod. Profissão</label>
            <input type="search" id="searchprofissao" list="listaprofissao" class="cod-search">
            
            <select id="selectprofissao" style="max-width: 250px;"></select>
        </div>
        <div class="grupo">
            <h4 class="titulo-grupo">Escolaridade</h4>
            <select id="selectescolaridade">
                <?php
                    echo inserirListaSimples(3);
                ?>
            </select>
        </div>
        <div class="grupo">
            <h4 class="titulo-grupo">Estado Civil</h4>
            <select id="selectestadocivil">
                <?php
                    echo inserirListaSimples(4);
                ?>
            </select>
        </div>
        <div class="grupo">
            <h4 class="titulo-grupo">Religião</h4>
            <select id="selectreligiao">
                <?php
                    echo inserirListaSimples(5);
                ?>
            </select>
        </div>
    </div>
    
    <div class="grupo">
        <h4 class="titulo-grupo">Endereço de moradia</h4>
        <div class="grupo">
            <h4 class="titulo-grupo"><label for="logradouro">Logradouro</label></h4>
            <input type="text" id="logradouro" class="tamanho-grande">
        </div>
        <div class="grupo">
            <h4 class="titulo-grupo"><label for="numero">Número</label></h4>
            <input type="text" id="numero" style="width: 50px;" maxlength="6">
        </div>
        <div class="grupo">
            <h4 class="titulo-grupo"><label for="complemento">Complemento</label></h4>
            <input type="text" id="complemento" class="tamanho-pequeno">
        </div>
         <div class="grupo">
            <h4 class="titulo-grupo"><label for="bairro">Bairro</label></h4>
            <input type="text" id="bairro" class="tamanho-medio">
        </div>
        <div class="grupo">
            <h4 class="titulo-grupo"><label for="ufmorad">UF</label></h4>
            <select id="ufmorad">
                <?php
                    echo inserirUF()
                ?>
            </select>
        </div>
        <div class="grupo">
            <h4 class="titulo-grupo">Cidade</h4>
            <label for="searchcidade">Cod. Cidade</label>
            <input type="search" id="searchcidade" list="listacidade" class="cod-search">
            <!-- Datalist pode ser usado para fazer a pesquisa e preencher o select e vice-versa, porém o valor do select que vai ser usado no momento de salvar -->
            <datalist id="listacidade"></datalist>

            <div style="display: inline-block;">
                <label for="selectcidade" class="espaco-esq">Cidade</label>
                <select id="selectcidade" autocomplete="TRUE"></select>
            </div>
        </div>
    </div>

    <div class="grupo-block">
        <h4 class="titulo-grupo">Telefone</h4>
        <label for="nometelefone">Novo contato: </label>
        <input type="text" id="nometelefone" autocomplete="off">
        <label for="numerotelefone">Número: </label>
        <input type="text" id="numerotelefone" class="ddd-tel" autocomplete="off">
        <button id="incluirtelefone" class="margin-espaco-esq">Adicionar</button>
        
        <div class="telefonespreso container-flex max-height-100"></div>
    </div>

    <div class="grupo-block">
        <h4 class="titulo-grupo">Vulgos</h4>
        <label for="novovulgo">Novo vulgo: </label>
        <input type="text" id="novovulgo" autocomplete="off">
        <button id="incluirvulgo" class="margin-espaco-esq">Adicionar</button>
        
        <div class="vulgospreso container-flex max-height-100"></div>
    </div>

    <div class="grupo-block">
        <h4 class="titulo-grupo">Cadastro de Sinais Particulares</h4>
        <textarea id="sinais" cols="30" rows="5" placeholder="Descreva os sinais. Ex: ANTEBRAÇO ESQ: PALHAÇO, 'AMOR SÓ DE MÃE'" class="largura-total"></textarea>
    </div>

    <div class="grupo-block" id="campoartigos">
        <h4 class="titulo-grupo">Artigos</h4>
            <?php
            if($blnCIMIC===true){ ?>
                <label for="searchartigopreso">Cód. Artigo</label>
                <input type="search" id="searchartigopreso" list="listaartigos" style="width: 90px;">
                <datalist id="listaartigos"></datalist>
               
                <label for="selectartigopreso" style="padding-left: 5px;">Artigo</label>
                <select id="selectartigopreso" class="artigos"></select>
                
                <button id="incluirartigo" style="margin-left: 5px;">Incluir Artigo</button>
                <button id="novoartigo">Novo Artigo</button> <?php
            } ?>

            <div class="artigopreso container-flex max-height-100"></div>
    </div>

    <?php
        if($blnCIMIC===true){ ?>
            <div class="grupo-block">
                <h4 class="titulo-grupo">Condenação</h4>
                <div class="grupo">
                    <h4 class="titulo-grupo"><label for="anos">Anos</label></h4>
                    <input type="number" id="anos" style="width: 50px;">
                </div>
                <div class="grupo">
                    <h4 class="titulo-grupo"><label for="meses">Meses</label></h4>
                    <input type="number" id="meses" style="width: 50px;">
                </div>
                <div class="grupo">
                    <h4 class="titulo-grupo"><label for="dias">Dias</label></h4>
                    <input type="number" id="dias" style="width: 50px;">
                </div>
            </div> <?php
        } ?>

    <div class="grupo-block">
        <h4 class="titulo-grupo"><label for="observacoes">Observações (Não serão impressas as informações deste campo)</label></h4>
        <textarea id="observacoes" cols="30" rows="5" placeholder="Observações" class="largura-total"></textarea>
    </div>

    <div class="final-pagina">
        <button id="salvarqualificativa">Salvar</button>
    </div>
</div>

<?php include_once "popups/novo_artigo_popup.php"; ?>
<script src="js/inclusao/inc_alterar_qualificativa.js"></script>

