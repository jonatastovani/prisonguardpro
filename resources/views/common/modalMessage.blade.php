<div class="modal fade" id="modalMessage" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Confirmação de ação</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="message text-start">Mensagem para o usuário</p>
            </div>
            <div class="modal-footer">
                <div class="col-12">
                    <div class="d-flex justify-content-end">
                        <button class="btn btn-primary w-50 confirmYes">Sim</button>
                        <div style="width: 20px;"></div>
                        <button class="btn btn-danger w-50 confirmNo">Não</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</div>

<script type="module" src="{{ asset('js/common/modalMessage.js') }}"></script>