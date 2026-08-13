<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Management Media Promosi</title>
    <link rel="stylesheet" href="<?= base_url(); ?>assets/vendor/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/adminlte.min.css">
    <link rel="stylesheet" href="<?= base_url(); ?>assets/vendor/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="<?= base_url(); ?>assets/css/jquery.dataTables.min.css">


    <style>
        /* Untuk elemen draggable di preview */
        #previewContainer .draggable-element {
            border: 2px solid transparent;
            transition: border-color 0.2s, box-shadow 0.2s;
            position: absolute;
            cursor: move;
            user-select: none;
        }

        #previewContainer .draggable-element:hover {
            border-color: #007bff;
            box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.3);
        }

        #previewContainer .draggable-element .resize-handle {
            position: absolute;
            bottom: -6px;
            right: -6px;
            width: 16px;
            height: 16px;
            background: #007bff;
            border: 2px solid white;
            border-radius: 3px;
            cursor: se-resize;
            opacity: 0;
            transition: opacity 0.2s;
            z-index: 10;
        }

        #previewContainer {
            position: relative;
            display: none;
            /* akan di-show oleh JS */
            border: 1px solid #ddd;
            min-height: 300px;
        }

        #previewContainer .draggable-element:hover .resize-handle {
            opacity: 1;
        }

        #waText {
            color: black;
        }
    </style>

</head>

<body class="hold-transition sidebar-mini">
    <div class="wrapper">
        <?php include('nav_menu_upper.php'); ?>
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <?php include('brand_logo.php'); ?>
            <div class="sidebar">
                <?php include('nav_menu_admin.php'); ?>
            </div>
        </aside>

        <div class="content-wrapper">
            <div class="content-header">
                <div class="container-fluid">
                    <div class="row mb-2">
                        <div class="col-sm-6">
                            <h1>Media Promosi</h1>
                        </div>
                        <div class="col-sm-6">
                            <ol class="breadcrumb float-sm-right">
                                <li class="breadcrumb-item"><a href="#">Management</a></li>
                                <li class="breadcrumb-item active">Media Promosi</li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>

            <div class="content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">Daftar Media Promosi</h3>
                                    <div class="card-tools">
                                        <button class="btn btn-sm btn-secondary" data-bs-toggle="modal" data-bs-target="#categoryModal">
                                            <i class="fas fa-tags"></i> Kategori
                                        </button>
                                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#mediaModal">
                                            <i class="fas fa-plus"></i> Tambah
                                        </button>
                                        <button id="refreshData" class="btn btn-sm btn-secondary"><i class="fas fa-sync"></i></button>
                                    </div>
                                </div>
                                <div class="card-body table-responsive p-0">
                                    <table class="table table-striped" id="tableMedia">
                                        <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Nama</th>
                                                <th>Kategori</th>
                                                <th>Gambar</th>
                                                <th>Preview</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php include('footer.php'); ?>
    </div>



    <script src="<?= base_url(); ?>assets/js/jquery371.min.js"></script>
    <script src="<?= base_url(); ?>assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="<?= base_url(); ?>assets/js/adminlte.js"></script>
    <script src="<?= base_url() ?>assets/js/timer.js"></script>
    <script src="<?= base_url() ?>assets/js/jquery.dataTables.min.js"></script>

    <script>
        function loadDataTables() {
            $('#tableMedia').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '<?= base_url('manage/media-promosi') ?>',
                    type: 'GET'
                },
                columns: [{
                        data: null,
                        render: function(data, type, row, meta) {
                            return meta.row + 1;
                        }
                    },
                    {
                        data: 'nama'
                    },
                    {
                        data: 'kategori_nama'
                    },
                    {
                        data: 'image',
                        render: function(data) {
                            return '<img src="<?= base_url() ?>' + data + '" style="height:40px;" loading="lazy">';
                        }
                    },
                    {
                        data: 'id',
                        render: function(data) {
                            return '<a href="<?= base_url('manage/media-promosi/preview/') ?>' + data + '" target="_blank" class="btn btn-sm btn-info"><i class="fas fa-eye"></i></a>';
                        }
                    },
                    {
                        data: 'id',
                        render: function(data) {
                            return `<button class="btn btn-sm btn-warning btn-edit" data-id="${data}"><i class="fas fa-edit"></i></button>
                        <button class="btn btn-sm btn-danger btn-delete" data-id="${data}"><i class="fas fa-trash"></i></button>`;
                        }
                    }
                ]
            });
        }

        $(document).ready(function() {
            loadDataTables();

            // Refresh
            $('#refreshData').click(function() {
                location.reload();
            });

            // Edit: ambil data via AJAX
            $(document).on('click', '.btn-edit', function() {
                var id = $(this).data('id');
                $.ajax({
                    url: '<?= base_url('manage/media-promosi/edit') ?>/' + id,
                    method: 'GET',
                    dataType: 'json',
                    success: function(res) {
                        if (res.status == 'success') {
                            // Panggil fungsi penanganan preview & element draggable dari modal_media_promosi.php
                            window.loadMediaData(res.data);
                        } else {
                            alert('Gagal mengambil data');
                        }
                    }
                });
            });

            // Delete
            $(document).on('click', '.btn-delete', function() {
                if (!confirm('Yakin hapus data ini?')) return;
                var id = $(this).data('id');
                $.ajax({
                    url: '<?= base_url('manage/media-promosi/delete') ?>',
                    method: 'POST',
                    data: {
                        id: id
                    },
                    dataType: 'json',
                    success: function(res) {
                        if (res.status == 'success') {
                            location.reload();
                        } else {
                            alert('Gagal hapus data');
                        }
                    }
                });
            });

            // Saat modal ditutup, reset form
            $('#mediaModal').on('hidden.bs.modal', function() {
                $('#formMedia')[0].reset();
                $('#previewImageOld').hide();
                $('#previewImageOldContainer').hide();
                $('#mediaModalLabel').text('Tambah Media Promosi');
                $('#formMedia').data('mode', 'add');
                $('#mediaId').val('');
                $('#mediaConfig').val('');
            });
        });
    </script>

    <?php include('modal_media_promosi.php'); ?>
    <?php include('modal_media_category.php'); ?>

</body>

</html>