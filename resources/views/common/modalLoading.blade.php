<div class="modal" id="modalLoading" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLoadingLabel">Carregando...</h5>
            </div>
            <div class="modal-body d-flex">
                <div class="col-2 align-self-center">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Carregando...</span>
                    </div>
                </div>
                <div class="col">
                    <p class="mt-2">Aguarde enquanto os dados estão sendo carregados...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script type="module" src="{{ asset('js/common/modalLoading.js') }}"></script>
