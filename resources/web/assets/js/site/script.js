import { commonFunctions } from "../commons/commonFunctions.js";
import instanceManager from "../commons/instanceManager.js";

document.onkeydown = function (e) {

    if (e.key === 'Escape') {

        const modal = $("#modalMessage");
        if (modal.length > 0 && modal.is(':visible')) {

            modal.find(".confirmNo").trigger('click');
            return;

        }

        const activePopups = $(".popup.active");
        if (activePopups.length > 0) {

            const lastActivePopup = activePopups.last();
            lastActivePopup.find(".close-btn").trigger('click');
        }

    }
}

$(window).on('resize', function () {

    const activePopups = $(".popup.active");

    if (activePopups.length) {

        activePopups.each(function () {

            const idPop = $(this).attr('id');
            const obj = instanceManager.instanceVerification(idPop);

            if (obj !== false) {

                if (typeof obj.adjustTableHeight === 'function') {
                    obj.adjustTableHeight();
                }

            }

        });

    }

    adjustClass();

});

function adjustClass() {
    if ($(window).width() <= 992) {
        if ($('#ulUser').hasClass('justify-content-end')) {
            $('#ulUser').removeClass('justify-content-end');
        }
    } else {
        if (!$('#ulUser').hasClass('justify-content-end')) {
            $('#ulUser').addClass('justify-content-end');
        }
    }
}

adjustClass();

function adjustElementHeight(elem) {

    const screenHeight = $(window).height();
    const arrElements = [
        { name: 'table-budgets', height: 110 }
    ]

    const index = arrElements.findIndex((item) => item.name == $(elem).attr('id'));
    if (index !== -1) {

        const maxHeight = screenHeight - arrElements[index].height;
        $(elem).css('max-height', maxHeight + 'px');

    }

}

$(document).ready(function () {

    try {
        if (typeof sectorName !== undefined) {
            document.title = `${systemDisplayName} • ${sectorName}`;
        } else {
            document.title = `${systemDisplayName}`;
        }
    } catch (error) {
        document.title = `${systemDisplayName}`;
    }

    $('.dropend').on('click', function (e) {
        e.stopPropagation();

        const dropdownMenu = $(this).siblings('li.dropend');
        if (dropdownMenu.find('a').hasClass('show')) {
            dropdownMenu.children().removeClass('show');
        }

    });

    function verifyToken() {

        if (commonFunctions.getItemLocalStorage('token_stylus') == null) {
            logout();
        }
    }

    $('#btn_logout').on('click', function (e) {
        e.stopPropagation();

        logout();

    });

    function logout() {

        localStorage.clear();

        $.ajax({
            url: `${window.location.origin}/setSession`,
            method: 'DELETE',
            contentType: "application/json",
            success: function (response, status, xhr) {

                if (xhr.status === 200) {

                    window.location.reload();

                }

            },
            error: function (xhr) {
                console.error('Erro inesperado', xhr);
            }
        });

    }

    verifyToken();
});
