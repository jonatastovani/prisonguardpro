$(document).ready(function(){

    // Função para armazenar o estado do menu no Local Storage
    function setMenuState(menuId, state) {
        localStorage.setItem(`menuState-${menuId}`, state);
        alterIco(menuId, state)
    }

    // Função para obter o estado do menu do Local Storage
    function getMenuState(menuId) {
        // console.log(localStorage)
        return localStorage.getItem(`menuState-${menuId}`);
    }

    // Função para alterar o ícone do menu
    function alterIco(menuId, state) {
        const i = $(`#${menuId}`).children('a').children('i');
        if (state === 'expanded') {
            i.removeClass('bi-caret-down-fill');
            i.addClass('bi-caret-up-fill');
        } else {
            i.removeClass('bi-caret-up-fill');
            i.addClass('bi-caret-down-fill');
        }
    }

    // Evento de clique nos itens de menu
    document.querySelectorAll('.nav-link').forEach(function (menuLink) {
        menuLink.addEventListener('click', function () {
            const menuId = this.parentElement.id;
            const submenu = document.querySelector(`#${menuId} .nav-sub`);
            if (submenu) {
                if (getMenuState(menuId) === 'expanded') {
                    setMenuState(menuId, 'collapsed');
                } else {
                    setMenuState(menuId, 'expanded');
                }
            }
            $(this).siblings('.nav-sub').slideToggle();
        });
    });

    // Restaure o estado do menu ao carregar a página
    document.querySelectorAll('.nav-item').forEach(function (menuItem) {
        const menuId = menuItem.id;
        const submenu = document.querySelector(`#${menuId} .nav-sub`);
        const state = getMenuState(menuId);
        if (submenu && state === 'expanded') {
            submenu.style.display = 'block';
            alterIco(menuId, state)
        }
    });

});