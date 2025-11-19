const _URL_ADD_BAB_PEMBAHASAN = "/manage/materi/pembahasan/bab/add";

function createCard(data = {}) {
    let id = $('#id_materi').val();

    return `
    <div class="col-md-4 mb-4 card-item" data-id="${id}">
        <div class="card h-100">
            <div class="card-body" style="margin-left: 15px;">
                <input type="checkbox" class="form-check-input selected-card mb-2">

                <input type="text" class="form-control mb-2 judul" 
                       placeholder="Judul Bab" value="${data.judul ?? ''}">

                <textarea class="form-control mb-2 deskripsi" 
                          placeholder="Deskripsi">${data.deskripsi ?? ''}</textarea>

                <p class="text-muted">
                    Pembahasan: <span class="jumlah">${data.jumlah ?? 0}</span> data
                </p>

                <button class="btn btn-sm btn-danger delete-card" data-id="${id}">
                    Delete
                </button>

                <button class="btn btn-sm btn-success float-end save-card" data-id="${id}">
                    Save
                </button>
            </div>
        </div>
    </div>`;
}

$(document).ready(function() {  

    $("#add-card").on("click", function(e){
        e.preventDefault();
        $("#card-mode").show();     // show section
        $("#card-mode").append(createCard());
    });

    $(document).on("click", ".save-card", function(){
        let card = $(this).closest(".card-item");
        let id_userna = $('#id_user').val();
        let id_materina = card.data("id");
        let judul_na = card.find(".judul").val();
        let deskripsi_na = card.find(".deskripsi").val();
        let btn_save = card.find('.save-card');

        let dataNa = {
            id_materi: id_materina,
            id_user: id_userna,
            judul: judul_na,
            deskripsi: deskripsi_na
        };
    
        // AJAX kirim ke backend
        printOut("Saving card:", dataNa);
        // ajax post ke route _URL_ADD_BAB_PEMBAHASAN dengan data {id, judul, deskripsi}
        $.ajax({
            url: _URL_ADD_BAB_PEMBAHASAN,
            method: "POST",
            data: dataNa,
            dataType: "json",
            success: function(response) {
            console.log("Card saved successfully:", response);
            // Optionally, you can update the UI or show a success message here

            btn_save.hide();

            },
            error: function(xhr, status, error) {
            console.error("Error saving card:", error);
            // Optionally, you can show an error message here

            btn_save.show();

            }
        });

    });
    
    // DELETE
    $(document).on("click", ".delete-card", function(){
        if(!confirm("Yakin hapus?")) return;
        $(this).closest(".card-item").remove();
    });
    
    // EDIT
    $(document).on("click", ".edit-card", function(){
        alert("Proses edit sesuai backend lu nanti");
    });
    
    // PEMBAHASAN
    $(document).on("click", ".view-pembahasan", function(){
        let id = $(this).data("id");
    
        // Buka modal pembahasan yang sudah ada
        console.log("OPEN PEMBAHASAN:", id);
    });

});
