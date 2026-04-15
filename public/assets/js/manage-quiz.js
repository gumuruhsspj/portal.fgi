const _URL_DELETE_MATERI_QUIZ = '/manage/materi/quiz/delete';
const _URL_ADD_MATERI_QUIZ = '/manage/materi/quiz/delete';
const _URL_UPDATE_MATERI_QUIZ = '/manage/materi/quiz/update';

$(document).ready(function(){

    $(document).on('click', '.delete-card', function() {
        $(this).closest('.card-item').remove();
    });

    muncullinJenisSoal();
    addSoalBaru();
    deleteSelectedSoal();

});

function deleteSelectedSoal(){

    $('#delete-selected').on('click', function() {
        $('.is-selected:checked').each(function() {

            let idna = $(this).closest('.card-item').data('id');
            let datana = { id_quiz_bab: idna };
            
            // post delete quiz ke server
            if(idna){
                
                $.ajax({
                    url: _URL_DELETE_QUIZ_BAB, 
                    type: 'POST',
                    data: datana,
                    success: function(response) {
                        
                        // pakai swall info 1500 ms
                        Swal.fire({
                            icon: 'success',
                            title: 'Success',
                            text: 'Quiz berhasil dihapus!',
                            timer: 1500,
                            showConfirmButton: false
                        });
                        
                    },
                    error: function(xhr) {
                        console.log("Error:", xhr.responseText);
                    }
                });
                

            $(this).closest('.card-item').remove();

            }

        });
    });
}

function generateId() {
    return 'tmp-' + Math.floor(Math.random() * 100000);
}

function addSoalBaru(){

    $('#add-card').on('click', function() {
        let id = generateId(); // id unik sementara
        let cardHtml = `
        <div class="col-md-6 mb-4 card-item" data-id-materi="${id}" data-id="">
            <div class="card h-100">
                <div class="card-body" style="margin-left: 15px;">
                    <!-- Pertanyaan -->
                    <div class="mb-2" >
                        <input type="checkbox" class="form-check-input mb-2 is-selected" >
                        <label>Pertanyaan:</label>
                        <textarea class="form-control mb-2 pertanyaan" placeholder="tulis Pertanyaan disini"></textarea>
                    </div>
                    
    
                    <!-- Jenis Soal -->
                    <select class="form-select mb-2 jenis-soal">
                        <option value="essay">Essay</option>
                        <option value="pg2">PG 2 opsi</option>
                        <option value="pg4">PG 4 opsi</option>
                    </select>
    
                    <!-- Opsi PG -->
                    <div class="pg-opsi mb-2" style="display:none;">
                        <input type="text" class="form-control mb-1 opsi-a" placeholder="Opsi A">
                        <input type="text" class="form-control mb-1 opsi-b" placeholder="Opsi B">
                        <input type="text" class="form-control mb-1 opsi-c" placeholder="Opsi C" style="display:none;">
                        <input type="text" class="form-control mb-1 opsi-d" placeholder="Opsi D" style="display:none;">
                    </div>
    
                    <!-- Keterangan -->
                    <textarea class="form-control mb-2 keterangan" placeholder="Keterangan"></textarea>
    
                    <!-- Jawaban Final -->
                    <div class="mb-2 jawaban-akhir-part d-none" >
                        <label>Jawaban Final:</label>
                        <select class="form-select mb-2 jawaban-akhir">
                        </select>
                    </div>
                     
    
                    <!-- Buttons -->
                    <button class="btn btn-sm btn-danger delete-card">Delete</button>
                    <button class="btn btn-sm btn-success float-end save-card">Save</button>
                </div>
            </div>
        </div>`;
    
        // Append ke container
        $('#card-mode').append(cardHtml).show(); // otomatis show kalau sebelumnya display:none
    });

}

function muncullinJenisSoal(){

    $(document).on('change', '.jenis-soal', function() {
        let jenis = $(this).val();
        let pgOpsi = $(this).closest('.card-body').find('.pg-opsi');
        let jawaban = $(this).closest('.card-body').find('.jawaban-akhir');
    
        let jawabanContainer = $(this).closest('.card-body').find('.jawaban-akhir-part');

        if(jenis === 'essay') {
            pgOpsi.hide();
            pgOpsi.find('input').val('');
            jawaban.hide().val('');
            jawabanContainer.addClass('d-none');
        } else if(jenis === 'pg2') {
            pgOpsi.show();
            pgOpsi.find('.opsi-a, .opsi-b').show();
            pgOpsi.find('.opsi-c, .opsi-d').hide().val('');
            let data = `
                <option value="A">Opsi A</option>
                <option value="B">Opsi B</option>
            `;
            jawaban.text('');
            jawaban.append(data);
            jawaban.show();
            jawabanContainer.removeClass('d-none');
            
        } else if(jenis === 'pg4') {
            pgOpsi.show();
            pgOpsi.find('input').show();

            let data = `
                <option value="A">Opsi A</option>
                <option value="B">Opsi B</option>
                <option value="C">Opsi C</option>
                <option value="D">Opsi D</option>
            `;
            jawaban.text('');
            jawaban.append(data);
            jawaban.show();
            jawabanContainer.removeClass('d-none');
        }
    });

}