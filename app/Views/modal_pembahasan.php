
<div class="modal fade" id="modalPembahasan" tabindex="-1" aria-labelledby="modalPembahasanLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalPembahasanLabel">Tambah Pembahasan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="formPembahasan">
                    
                    <input type="hidden" id="pembahasan_id" name="id" >
                    <input type="hidden" id="materi_id" name="id_materi" >
                    <input type="hidden" id="custom_id" name="id_materi_custom" value="<?=$id_materi_custom ?? ''?>" >
                    <input type="hidden" id="pembahasan_id_bab" name="id_bab" >
                    <input type="hidden" id="pembahasan_id_user" name="id_user" >

                    <div class="col-md-2">
                        <label for="pembahasan_ordering_index" class="form-label">No.Urut</label>
                        <input type="number" class="form-control"  id="pembahasan_ordering_index" name="ordering_index" required>
                    </div>

                    <div class="mb-3">
                        <label for="judul" class="form-label">Judul</label>
                        <input type="text" class="form-control" placeholder="tuliskan judulnya" id="judul" name="judul" required>
                    </div>
                    <div class="mb-3">
                        <label for="deskripsi" class="form-label">Deskripsi</label>
                        <input id="deskripsi" name="deskripsi" type="hidden">
                        <trix-editor input="deskripsi" placeholder="Tulis deskripsi di sini..."></trix-editor>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>
    </div>
</div>
