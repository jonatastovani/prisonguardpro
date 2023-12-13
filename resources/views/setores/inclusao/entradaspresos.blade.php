@extends('site.layout')
@section('title','Inclusão')
    
@section('conteudo')
    <div class="row">
        <div class="col-12 titulo-pagina">
            <h4>Gerenciar Entradas de Presos</h4>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-7 border border-secondary rounded">
            <div class="row">
                <div class="col-12">
                    <label for="data_inicio" class="form-label">Intervalo de busca</label>
                </div>
            </div>
            <div class="row">
                <div class="col-6">
                    <div class="input-group">
                        <div class="input-group-text">
                            <label for="data_inicio">Data início</label>
                        </div>
                        <input type="date" class="form-control" id="data_inicio" aria-describedby="data_inicio" value="<?= Carbon\Carbon::now()->subDays(2)->toDateString() ?>">
                    </div>
                </div>
                <div class="col-6">
                    <div class="input-group">
                        <div class="input-group-text">
                            <label for="data_final">Data Final</label>
                        </div>
                        <input type="date" class="form-control" id="data_final" aria-describedby="data_final">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <label for="data_inicio" class="form-label">Situação de inclusão</label>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="rbs_situacao" id="rb_pendentes" checked>
                        <label class="form-check-label" for="rb_pendentes">
                            Pendentes
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="rbs_situacao" id="rb_encerrados">
                        <label class="form-check-label" for="rb_encerrados">
                            Encerrados
                        </label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="rbs_situacao" id="rb_todos">
                        <label class="form-check-label" for="rb_todos">
                            Todos
                        </label>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-5 border border-secondary rounded">
            <div class="row">
                <div class="col-12">
                    <label for="texto_consulta" class="form-label">Busca Personalizada</label>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="input-group" title="Texto a ser pesquisado na consulta">
                        <div class="input-group-text">
                            <label for="texto_consulta">Texto: </label>
                        </div>
                        <input type="text" class="form-control" id="texto_consulta" aria-describedby="texto_consulta" placeholder="Digite o texto para filtro na consulta">
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-12">
                    <div class="row">
                        <div class="col-12">
                            <label for="rb_dividir" class="form-label">Sobre o texto</label>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="form-check form-check-inline" title="Esta opção divide as palavras do texto efetuando uma busca onde contenha correspondência a qualquer uma">
                                <input class="form-check-input" type="radio" name="rbs_texto" id="rb_dividir" checked>
                                <label class="form-check-label" for="rb_dividir">
                                    Dividir na busca
                                </label>
                            </div>
                            <div class="form-check form-check-inline" title="Esta opção busca correspondência com o texto todo digitado">
                                <input class="form-check-input" type="radio" name="rbs_texto" id="rb_completo">
                                <label class="form-check-label" for="rb_completo">
                                    Texto completo
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12 border border-secondary rounded">
            <h6>Sobre a busca</h6>
            <div class="row">
                <div class="col-12">
                    <div class="form-check form-check-inline" title="Esta opção filtra correspondência em qualquer parte do registro">
                        <input class="form-check-input" type="radio" name="rbs_busca" id="rb_qualquer_parte" value="1" checked>
                        <label class="form-check-label" for="rb_qualquer_parte">
                            Qualquer parte
                        </label>
                    </div>
                    <div class="form-check form-check-inline" title="Esta opção filtra correspondência íntegra com o registro">
                        <input class="form-check-input" type="radio" name="rbs_busca" id="rb_exato" value="2">
                        <label class="form-check-label" for="rb_exato">
                            Busca exata
                        </label>
                    </div>
                    <div class="form-check form-check-inline" title="Esta opção filtra correspondência com a parte inicial do registro">
                        <input class="form-check-input" type="radio" name="rbs_busca" id="rb_iniciado_por" value="3">
                        <label class="form-check-label" for="rb_iniciado_por">
                            Iniciado por
                        </label>
                    </div>
                    <div class="form-check form-check-inline" title="Esta opção filtra correspondência com a parte final do registro">
                        <input class="form-check-input" type="radio" name="rbs_busca" id="rb_encerrado_por" value="4">
                        <label class="form-check-label" for="rb_encerrado_por">
                            Encerrado por
                        </label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row mt-2">
        <div class="col-12 p-0">
            <button class="btn btn-primary" title="Inserir uma nova entrada de presos"><i class="bi bi-plus"></i> Nova Entrada</button>
            <button class="btn btn-secondary" title="Imprimir recibo de presos dos presos selecionados"><i class="bi bi-printer-fill"></i> Recibo de Presos</button>
        </div>
    </div>

    <div class="row">
        <div class="table-responsive mt-2 p-0 rounded">
            <table class="table table-stripet">
                <thead>
                    <tr>
                        <th scope="col">#</th>
                        <th scope="col"></th>
                        <th scope="col">Matrícula</th>
                        <th scope="col">Nome</th>
                        <th scope="col">RG</th>
                        <th scope="col">Data Entrada</th>
                        <th scope="col">Origem</th>
                        <th scope="col">Situação</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                    <th scope="row">1</th>
                    <td>Cell</td>
                    <td>Cell</td>
                    <td>Cell</td>
                    <td>Cell</td>
                    <td>Cell</td>
                    <td>Cell</td>
                    <td>Cell</td>
                    <td>Cell</td>
                    <td>Cell</td>
                    </tr>
                    <tr>
                    <th scope="row">2</th>
                    <td>Cell</td>
                    <td>Cell</td>
                    <td>Cell</td>
                    <td>Cell</td>
                    <td>Cell</td>
                    <td>Cell</td>
                    <td>Cell</td>
                    <td>Cell</td>
                    <td>Cell</td>
                    </tr>
                    <tr>
                    <th scope="row">3</th>
                    <td>Cell</td>
                    <td>Cell</td>
                    <td>Cell</td>
                    <td>Cell</td>
                    <td>Cell</td>
                    <td>Cell</td>
                    <td>Cell</td>
                    <td>Cell</td>
                    <td>Cell</td>
                    </tr>
                    
                </tbody>
            </table>
        </div>
    </div>

    <script type="module" src="{{asset('js/site/home.js')}}"></script>
    
@endsection