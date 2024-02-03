<div id="login" class="d-flex justify-content-center align-items-center h-100 w-100">

    <div class="container-login">

        <div class="container">

            <form id="form_login">

                <div class="row">
                    <div class="col-12">
                        <h2 class="mb-2 text-center"><?= SYSTEM_DISPLAY_NAME ?></h2>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <h4 class="mb-2 text-center">Login</h4>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="username">Usuário</label>
                            <input type="text" class="form-control" id="username" name="username" placeholder="Digite seu nome de usuário" required autofocus>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="form-group">
                            <label for="password">Senha</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password" name="password" placeholder="Digite sua senha" required>
                                <div class="input-group-append">
                                    <button class="btn btn-outline-secondary" type="button" id="show-password">
                                        <i class="bi bi-eye-fill"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row mt-2">
                    <div class="col-12 text-center">
                        <button type="submit" class="btn btn-primary w-50" id="send">
                            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                            <span>Entrar</span>
                        </button>
                    </div>
                </div>

            </form>

        </div>

    </div>

</div>

<script type="module" src="/assets/js/site/login.js"></script>