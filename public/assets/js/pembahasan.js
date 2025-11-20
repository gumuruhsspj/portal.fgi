const _URL_ADD_BAB_PEMBAHASAN       = "/manage/materi/pembahasan/bab/add";
const _URL_UPDATE_BAB_PEMBAHASAN    = "/manage/materi/pembahasan/bab/update";
const _URL_DELETE_BAB_PEMBAHASAN    = "/manage/materi/pembahasan/bab/delete";
const _URL_ADD_PEMBAHASAN           = "/manage/materi/pembahasan/add";
const _URL_DELETE_PEMBAHASAN        = "/manage/materi/pembahasan/delete";
const _URL_NEXT_NO_URUT_PEMBAHASAN  = "/manage/materi/pembahasan/no-urut/next";

function inputOnBab(){

    // deteck enter on input judul
    $(document).on("keypress", ".judul, .deskripsi", function(e){ 

            $(this).parent().find('.save-card').show();

    });

}

let _jumlah_bab = 0;
let _card_used = null;

function createCard() {
   
let id_materi = $('#id_materi').val();

incrementTotalData();

    return `
     <div class="col-md-4 mb-4 card-item" data-id-materi="${id_materi}" data-id="">
            <div class="card h-100">
                <div class="card-body" style="margin-left: 15px;">
                    <input type="checkbox" class="form-check-input selected-card mb-2">

                    <input type="text" class="form-control mb-2 judul" 
                          placeholder="Judul Bab" value="">

                    <textarea class="form-control mb-2 deskripsi" 
                              placeholder="Deskripsi"></textarea>

                    <p class="text-muted">
                    
                    <a href="#" class="add-pembahasan" data-bs-toggle="modal" data-bs-target="#modalPembahasan" >Add Pembahasan</a>
                    
                    <div class="list-group">
                         
                        
                    </div>
                    </p>

                    <button class="btn btn-sm btn-danger delete-card" data-id="${id_materi}">
                        Delete
                    </button>

                    <button class="btn btn-sm btn-success float-end save-card" data-id="${id_materi}">
                        Save
                    </button>
                </div>
            </div>
        </div>`;

}

function incrementTotalData(){
    _jumlah_bab++;
    $('#total_data').text(_jumlah_bab + ' bab.');
}

function decrementTotalData(){
    _jumlah_bab--;
    $('#total_data').text(_jumlah_bab + ' bab.');
}

$(document).ready(function() {  

    _jumlah_bab = $('#total_data').attr('data-jumlah');

    // showing save button when user changes data inform
    inputOnBab();

    $("#add-card").on("click", function(e){
        e.preventDefault();
        $("#card-mode").show();     
        $("#card-mode").append(createCard());
    });

    $(document).on("click", ".save-card", function(){
        let card = $(this).closest(".card-item");
        let id_userna = $('#id_user').val();
        let id_babna = card.data("id");
        let id_materina = card.data("id-materi");
        let judul_na = card.find(".judul").val();
        let deskripsi_na = card.find(".deskripsi").val();
        let btn_save = card.find('.save-card');

        let dataNa = null;

        if(id_babna){
            // update
             dataNa = {
                id: id_babna,
                id_materi : id_materina,
                id_user: id_userna,
                judul: judul_na,
                deskripsi: deskripsi_na
            };

            urlTarget = _URL_UPDATE_BAB_PEMBAHASAN;
        }else{
            // new insert
            dataNa = {
                id_materi : id_materina,
                id_user: id_userna,
                judul: judul_na,
                deskripsi: deskripsi_na
            };

            urlTarget = _URL_ADD_BAB_PEMBAHASAN;
        }

        
    
        // AJAX kirim ke backend
        printOut("Saving card:", dataNa);
        // ajax post ke route _URL_ADD_BAB_PEMBAHASAN dengan data {id, judul, deskripsi}
        $.ajax({
            url: urlTarget,
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
        
        let el =  $(this).closest(".card-item");

        let idna = $(this).data("id");
        

        // pake swal menunggu 1500ms lalu lanjut
        Swal.fire({
            title: 'Processing...',
            text: 'Tunggu sebentar!',
            timer: 1500,
            onBeforeOpen: () => {
            Swal.showLoading()
            },
            willClose: () => {
            Swal.hideLoading()
            }
        }).then(() => {
            // AJAX delete ke backend jika id ada
            if(idna){
                // AJAX delete ke backend jika id ada
                $.ajax({
                    url: _URL_DELETE_BAB_PEMBAHASAN,
                    method: "POST",
                    data: {id: idna},
                    dataType: "json",
                    success: function(response) {
                        console.log("Card deleted successfully:", response);
                        
                       el.remove();
                       decrementTotalData();     
    
                    },
                    error: function(xhr, status, error) {
                        console.error("Error deleting card:", error);
                        // Optionally, you can show an error message here
                    }
                });
            }

        });


        
        
    });
  
    // saat modal pembahasan muncul
    $('#modalPembahasan').on('show.bs.modal', function(event) {
       
        $('#formPembahasan')[0].reset();

    });

    // delete pembahasan yg dipilih
    $(document).on('click', '.remove-pembahasan', function(){ 
            let idna = $(this).data('id');

            let el = $(this).closest('li');

            // munculin swal menunggu 1500ms
            Swal.fire({
                title: 'Processing...',
                text: 'Please wait a moment.',
                timer: 1500,
                onBeforeOpen: () => {
                    Swal.showLoading()
                },
                willClose: () => {
                    Swal.hideLoading()
                }
            }).then(() => {
                
                // AJAX delete ke backend
                $.ajax({
                    url: _URL_DELETE_PEMBAHASAN,
                    method: "POST",
                    data: {id: idna},
                    dataType: "json",
                    success: function(response) {

                        console.log("Pembahasan deleted successfully:", response);
                        el.remove();     

                    },
                    error: function(xhr, status, error) {
                        console.error("Error deleting pembahasan:", error);
                        // Optionally, you can show an error message here
                    }
                });
                

            });


            

    });

    // delete item dari card sesuai checkbox
    $(document).on('click', '#delete-selected', function(){

        $('#card-mode .selected-card:checked').each(function(){
            let card = $(this).closest(".card-item");
            let idna = card.data("id");

            // AJAX delete ke backend jika id ada
            if(idna){
                $.ajax({
                    url: _URL_DELETE_BAB_PEMBAHASAN,
                    method: "POST",
                    data: {id: idna},
                    dataType: "json",
                    success: function(response) {
                        console.log("Card deleted successfully:", response);
                        
                       card.remove();
                       decrementTotalData();     

                    },
                    error: function(xhr, status, error) {
                        console.error("Error deleting card:", error);
                        // Optionally, you can show an error message here
                    }
                });
            }else{
                // jika belum disimpan di db
                card.remove();
                decrementTotalData();     
            }

        });

    });

    // saat mulai di klik
    $(document).on('click', '.add-pembahasan', function(){

        let card = $(this).closest(".card-item");
        // temporary stored
        _card_used = card;

        let id_babna = card.data("id");
        $('#pembahasan_id_bab').val(id_babna);
        $('#pembahasan_id_user').val($('#id_user').val());
        
        // panggil ajax untuk dptin nomor urut brikutnya
        $.ajax({
            url: _URL_NEXT_NO_URUT_PEMBAHASAN,
            method: 'POST',
            data: {id_bab: id_babna},
            dataType: 'json',
            success: function(response) {
                console.log("Next urut fetched successfully:", response);
                $('#pembahasan_ordering_index').val(response.data);
            },
            error: function(xhr, status, error) {
                console.error("Error fetching next urut:", error);
                $('#pembahasan_ordering_index').val('0');
            }
        });
        

    });
  
    // add pembahasan form works
    $('#formPembahasan').on('submit', function(event) {
        event.preventDefault();
        const formData = $(this).serialize();

        let judul = $(this).find('#judul').val();

        $.ajax({
            url: _URL_ADD_PEMBAHASAN,
            type: 'POST',
            data: formData,
            dataType: 'json',
            success: function(response) {
                // Handle success or error

                console.log(response);

                // terima id nya

                // Optionally close the modal
                $('#modalPembahasan').modal('hide');

                let el = `<li class="list-group-item">
                           ${judul}
                            <button data-id="${response.data}" class="btn btn-warning btn-sm float-end edit-pembahasan"  >
                                <i class="fas fa-edit"></i>
                            </button>
                            <button data-id="${response.data}" class="btn btn-danger btn-sm float-end remove-pembahasan"  >
                                <i class="fas fa-trash-alt"></i>
                            </button>
                          </li>`;
                _card_used.find('.list-group').append(el);
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    });

});
