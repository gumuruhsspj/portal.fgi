$(document).ready(function () {

    // Auto submit ketika dropdown filter berubah
    $('#filter-materi').on('change', function () {
        let val = $(this).val();
        let url = _URL_MAIN_WEBSITE + 'manage/quiz-submission';
        if (val) url += '?materi_id=' + val;
        window.location.href = url;
    });

    $('#refresh-data').on('click', function (e) {
        e.preventDefault();
        location.reload();
    });

    // Init DataTable (kalau ada baris > 0)
    if ($('#table-quiz-submission tbody tr').length > 0 &&
        !$('#table-quiz-submission tbody td[colspan]').length) {
        $('#table-quiz-submission').DataTable({
            order: [[7, 'desc']], // urut dari tanggal submitted
            pageLength: 25,
        });
    }
});