let arrpresospendentes = [];
const divpresospendentes = $('#divpresospendentes');

function buscaPresosPendentes(){
    let result = consultaBanco('cim_busca_gerenciar',{tipo:3});
    // console.log(result);

    if(result.length){
        let contador = 1;

        result.forEach(presos => {
            
            let novoID = 'presopendente'+gerarID('presopendente');
            let matricula = presos.MATRICULA!=null?midMatricula(presos.MATRICULA,3):'N/C';
            let rg = presos.RG!=null?presos.RG:'N/C';

            let botaoexcluir = '';
            if(presos.IDENTRADA>0){
                botaoexcluir = '<form action="principal.php?menuop=inc_incluiralterar_presos" class="inline" method="post"><input type="hidden" name="identradabuscar" value="'+presos.IDENTRADA+'"><button type="excluir" id="excluir'+novoID+'" title="Ir para Entrada de Presos">Excluir</button></form>';
            }

            divpresospendentes.append('<div class=" item-flex presopendente form-preso-pendente"><div style="display: flex;"><div class="div-metade-aling-esquerda"><h2 class="titulo-grupo">Preso '+contador+'</span></h2></div><div class="div-metade-aling-direita"><span class="spanvinculada" hidden>Mat. Vinculada</span></div></div><div><p>Nome: <b>'+presos.NOME+'</b></p><p>Matrícula: <b>'+matricula+'</b></p><p>RG: <b>'+rg+'</b></p><p>Origem: <b>'+presos.ORIGEM+'</b></p></div><div style="text-align: right;"><form id="'+novoID+'" class="inline" action="principal.php?menuop=cim_incluir_presos" method="post"><input type="hidden" name="idpresobancodados" value="'+presos.ID+'"><input type="hidden" name="tipoacao" value="incluir"><button type="submit" ="incluir'+novoID+'" title="Incluir preso">Incluir</button></form>'+botaoexcluir+'</div></div>');

            arrpresospendentes.push({
                tr: $('#'+novoID),
                idtr: novoID,
                idpreso: presos.ID,
                nome: presos.NOME,
                matricula: presos.MATRICULA,
                matriculavinculada: presos.MATRICULAVINCULADA,
                rg: presos.RG,
                dataentrada: presos.DATAENTRADA,
                origem: presos.ORIGEM,
                blnPresoVinculado: presos.MATRICULAVINCULADA,
                contador: contador
            });

            contador++;

        });
    }else{
        arrpresospendentes=[];
    }

}

buscaPresosPendentes();
