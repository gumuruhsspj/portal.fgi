<!-- Modal Manajemen Kategori -->
<div class="modal fade" id="categoryModal" tabindex="-1" aria-labelledby="categoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="categoryModalLabel">Manajemen Kategori</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <!-- Form tambah kategori -->
                <form id="formAddCategory">
                    <div class="row mb-3">
                        <div class="col-8">
                            <input type="text" id="newCategoryName" name="nama" class="form-control" placeholder="Nama kategori baru">
                        </div>
                        <div class="col-4">
                            <button type="submit" id="btnAddCategory" class="btn btn-primary w-100"><i class="fas fa-plus"></i> Tambah</button>
                        </div>
                    </div>
                </form>
                <hr>
                <!-- Daftar kategori -->
                <table class="table table-sm table-bordered" id="tableCategory">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="categoryList">
                        <?php foreach ($kategori_list as $kat): ?>
                            <tr data-id="<?= $kat['id'] ?>">
                                <td><?= $kat['id'] ?></td>
                                <td class="cat-name"><?= esc($kat['nama']) ?></td>
                                <td>
                                    <button class="btn btn-sm btn-warning btn-edit-category" data-id="<?= $kat['id'] ?>"><i class="fas fa-edit"></i></button>
                                    <button class="btn btn-sm btn-danger btn-delete-category" data-id="<?= $kat['id'] ?>"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Edit Kategori (mini) -->
<div class="modal fade" id="editCategoryModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Kategori</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="editCatId">
                <input type="text" id="editCatName" class="form-control" placeholder="Nama kategori">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="btnUpdateCategory">Update</button>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // ========== CATEGORY CRUD ==========

        // ADD
        $('#formAddCategory').submit(function(e) {
            e.preventDefault();
            var nama = $('#newCategoryName').val().trim();
            if (!nama) {
                alert('Nama kategori harus diisi');
                return;
            }
            $.ajax({
                url: '<?= base_url('manage/media-category/add') ?>',
                method: 'POST',
                data: $(this).serialize(),
                dataType: 'json',
                success: function(res) {
                    if (res.status == 'success') {
                        // refresh tabel
                        refreshCategoryTable();
                        refreshCategoryDropdown();
                        // reset form
                        $('#formAddCategory')[0].reset();
                    } else {
                        alert(res.message || 'Gagal menambahkan');
                    }
                }
            });
        });

        // UPDATE (tombol di modal edit)
        $('#btnUpdateCategory').click(function() {
            var id = $('#editCatId').val();
            var nama = $('#editCatName').val().trim();
            if (!nama) {
                alert('Nama kategori harus diisi');
                return;
            }
            $.ajax({
                url: '<?= base_url("manage/media-category/update") ?>',
                method: 'POST',
                data: {
                    id: id,
                    nama: nama
                },
                dataType: 'json',
                success: function(res) {
                    if (res.status == 'success') {
                        //alert('Kategori berhasil diupdate');
                        refreshCategoryTable(); // refresh tabel
                        refreshCategoryDropdown();
                        $('#editCategoryModal').modal('hide'); // tutup modal edit
                    } else {
                        alert(res.message || 'Gagal update kategori');
                    }
                }
            });
        });

        // DELETE dan EDIT menggunakan event delegation (via attachCategoryEvents)
        // Dipanggil pertama kali untuk tombol yang sudah ada
        attachCategoryEvents();
    });

    // ========== FUNGSI GLOBAL ==========
    function attachCategoryEvents() {
        // Event EDIT (class .btn-edit-category)
        $(document).off('click', '.btn-edit-category').on('click', '.btn-edit-category', function() {
            var id = $(this).data('id');
            $.ajax({
                url: '<?= base_url("manage/media-category/edit") ?>/' + id,
                method: 'POST',
                dataType: 'json',
                success: function(res) {
                    if (res.status == 'success') {
                        $('#editCatId').val(res.data.id);
                        $('#editCatName').val(res.data.nama);
                        $('#editCategoryModal').modal('show');
                    } else {
                        alert('Gagal mengambil data kategori');
                    }
                },
                error: function() {
                    alert('Terjadi kesalahan saat mengambil data');
                }
            });
        });

        // Event DELETE (class .btn-delete-category)
        $(document).off('click', '.btn-delete-category').on('click', '.btn-delete-category', function() {
            if (!confirm('Yakin hapus kategori ini?')) return;
            var id = $(this).data('id');
            $.ajax({
                url: '<?= base_url("manage/media-category/delete") ?>',
                method: 'POST',
                data: {
                    id: id
                },
                dataType: 'json',
                success: function(res) {
                    if (res.status == 'success') {
                        // alert('Kategori berhasil dihapus');
                        refreshCategoryTable(); // refresh tabel
                        refreshCategoryDropdown();
                    } else {
                        alert(res.message || 'Gagal hapus kategori');
                    }
                },
                error: function() {
                    alert('Terjadi kesalahan saat menghapus');
                }
            });
        });
    }

    function refreshCategoryDropdown() {
        $.ajax({
            url: '<?= base_url('manage/media-category/list') ?>',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                if (data.status == 'success') {
                    var select = $('#mediaKategori');
                    select.empty();
                    select.append('<option value="">-- Pilih --</option>');
                    $.each(data.data, function(i, cat) {
                        select.append('<option value="' + cat.id + '">' + cat.nama + '</option>');
                    });
                }
            }
        });
    }

    function refreshCategoryTable() {
        $.ajax({
            url: '<?= base_url('manage/media-category/list') ?>',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                if (data.status == 'success') {
                    var rows = '';
                    $.each(data.data, function(i, cat) {
                        rows += '<tr>' +
                            '<td>' + (i + 1) + '</td>' +
                            '<td>' + cat.nama + '</td>' +
                            '<td>' +
                            '<button class="btn btn-sm btn-warning btn-edit-category" data-id="' + cat.id + '"><i class="fas fa-edit"></i></button> ' +
                            '<button class="btn btn-sm btn-danger btn-delete-category" data-id="' + cat.id + '"><i class="fas fa-trash"></i></button>' +
                            '</td>' +
                            '</tr>';
                    });
                    $('#tableCategory tbody').html(rows);
                    // Event delegation sudah terpasang, tidak perlu panggil attach lagi
                }
            }
        });
    }
</script>