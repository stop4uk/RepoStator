function generateToast(status, message)
{
    const statusToBackgroup = {
        'error': 'bg-danger',
        'danger': 'bg-danger',
        'success': 'bg-success',
        'info': 'bg-info',
        'warning': 'bg-warning'
    };

    $("#btn_closeMainToast").removeClass("d-none");
    $("#mainToastBody").html(message);
    $("#mainToast").addClass("toast " + statusToBackgroup[status]).toast("show");

    document.getElementById('mainToast').addEventListener("hidden.bs.toast", () => {
        $("#mainToastBody").html("");
        $("#btn_closeMainToast").addClass("d-none");
        $("#mainToast").removeClass("toast " + statusToBackgroup[status]);
    });
}