<div id="clientsHome" class="row mt-1">
    
    <div class="col-sm-6 p-1">
        <a href="/clients" class="text-decoration-none text-dark fs-5">
            <div class="alert alert-primary m-0" role="alert">
                <div class="d-flex justify-content-between align-items-center" title="Gerenciamento de clientes (Listar, incluir, editar, excluir)">
                    <div class="d-flex align-items-center">
                        <p class="mb-0">Clientes</p>
                    </div>
                    <p class="mb-0"><i class="bi bi-people"></i> <span id="clientsTotal">0</span></p>
                </div>
            </div>
        </a>
    </div>

    <div class="col-sm-6 p-1">
        <form action="/clients/new" method="post" class="fs-5">
            <input type="hidden" name="redirect-previous" value="/clients/home">
            <button type="submit" class="alert alert-primary w-100 m-0" role="alert">
                <div class="d-flex justify-content-between align-items-center" title="Incluir novo cliente">
                    <div class="d-flex align-items-center">
                        <p class="mb-0">Incluir cliente</p>
                    </div>
                    <p class="mb-0"><i class="bi bi-person-plus"></i></p>
                </div>
            </button>
        </form>
    </div>

</div>

<script type="module" src="/assets/js/clients/clientsHome.js"></script>