const initializeWeb = () => {
    initializeSearch();
    initializeSimplebar();
    initializeSidebarCollapse();
    initializeBootstrapElements();
}

const initializeSimplebar = () => {
    const simplebarElement = document.getElementsByClassName("js-simplebar")[0];

    if(simplebarElement){
        const simplebarInstance = new SimpleBar(document.getElementsByClassName("js-simplebar")[0]);

        /* Recalculate simplebar on sidebar dropdown toggle */
        const sidebarDropdowns = document.querySelectorAll(".js-sidebar [data-bs-parent]");

        sidebarDropdowns.forEach(link => {
            link.addEventListener("shown.bs.collapse", () => {
                simplebarInstance.recalculate();
            });
            link.addEventListener("hidden.bs.collapse", () => {
                simplebarInstance.recalculate();
            });
        });
    }
}

const initializeSidebarCollapse = () => {
    const sidebarElement = document.getElementsByClassName("js-sidebar")[0];
    const sidebarToggleElement = document.getElementsByClassName("js-sidebar-toggle")[0];

    if(sidebarElement && sidebarToggleElement) {
        sidebarToggleElement.addEventListener("click", () => {
            sidebarElement.classList.toggle("collapsed");

            sidebarElement.addEventListener("transitionend", () => {
                window.dispatchEvent(new Event("resize"));
            });
        });
    }
}

const initializeBootstrapElements = () => {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
}

const initializeModalClose = () => {
    document.getElementById('modalWindow_closeButton').addEventListener('click', () => {
        document.getElementById('modalWindow_content').innerHTML = '';
        document.getElementById('modalWindow_header').innerHTML = '';
    });
}

const initializeSearch = () => {
    $("#searchCardButton").on("click", () => {
        if ($("#searchCard").attr("data-show") == "0") {
            $("#searchCard").attr("data-show", "1").removeClass('d-none');
        } else {
            $("#searchCard").attr("data-show", "0").addClass('d-none');
        }
    });
}

document.addEventListener("DOMContentLoaded", () => initializeWeb());
$(document).on('pjax:success pjax:complete', () => {
    initializeSearch();
    initializeBootstrapElements();
});

function showModal(element, type, message) {
    let org = element.attr("data-org") ?? null,
        id = element.attr("data-id") ?? null,
        link = element.attr("data-link") ?? null,
        user = element.attr("data-user") ?? null,
        typeOperation = element.attr("data-type") ?? null,
        container = element.attr("data-container") ?? null,
        typeData = element.attr("data-type") ?? null,
        typeToUrl = {
        },
        typeToClass = {
            'map': 'modal-md',
            'categoryMap': 'modal-md',
            'addRelation': 'modal-md',
            'addOrgRekRaspContact': 'modal-md',
            'changeEmail': 'modal-sm',
            'changePassword': 'modal-sm'
        }

    $("#hidescreen, #loadingData").fadeIn(10);

    $("#modalWindow_content").load(typeToUrl[type], function(response, status, xhr) {
        $("#hidescreen, #loadingData").fadeOut(10);

        if ( status == 'error' && xhr.status == 403 ) {
            generateToast('error', langMessages.forbiddenTemplate);
        } else {
            $("#modalWindow_dialog").addClass(typeToClass[type] ? typeToClass[type] : 'modal-lg');
            $("#modalWindow_header").html("<h4>"+ message +"</h4>");


            var modalWindow = bootstrap.Modal.getInstance((document.getElementById("modalWindow")));
            if ( !modalWindow )
                var modalWindow = new bootstrap.Modal(document.getElementById("modalWindow"));

            modalWindow.show();
        }
    });
}

function workWithRecord(element)
{
    if ( confirm(element.data('message')) ) {
        var container = element.attr('data-pjaxContainer');

        $.ajax({
            url: element.data('url'),
            method: "GET",
            beforeSend: () => { $("#hidescreen, #loadingData").fadeIn(10); },
            complete: function(xhr, textStatus) {
                $("#hidescreen, #loadingData").fadeOut(10);
                if ( xhr.status == 403 ) {
                    generateToast('error', langMessages.forbiddenTemplate);
                }
            },
            success: function (data) {
                generateToast(data.status, data.message);

                if (data.status == "success") {
                    $.pjax.reload({container: container, method: "POST", async: true, push: false , data: $("#searchForm").serialize()});
                }
            },
        });
    }
}