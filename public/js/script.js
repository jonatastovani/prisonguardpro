import instanceManager from "./common/instanceManager.js";

document.onkeydown = function(e) {

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

$(window).on('resize', function() {

    const activePopups = $(".popup.active");

    if (activePopups.length) {

        activePopups.each(function() {

            const idPop = $(this).attr('id');
            const obj = instanceManager.instanceVerification(idPop);
            
            if (obj !== false){

                if (typeof obj.adjustTableHeight === 'function') {
                    obj.adjustTableHeight();
                }

            }

        });
        
    }

});

$('.dropend').on('click', function(e) {
    e.stopPropagation();

    const dropdownMenu = $(this).siblings('li.dropend');
    if (dropdownMenu.find('a').hasClass('show')) {
        dropdownMenu.children().removeClass('show');
    }

});

/* Fullscreen */
// $('#fullscreen').click(function () {   
//     if (!document.fullscreenElement) {
//         document.documentElement.requestFullscreen();
//     } else {
//         document.exitFullscreen();
//     }
// })
