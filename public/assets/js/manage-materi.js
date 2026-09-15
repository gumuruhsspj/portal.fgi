const _URL_ADD_MATERI		= _URL_MAIN_WEBSITE+ "manage/materi/add";
const _URL_DELETE_MATERI 	= _URL_MAIN_WEBSITE+ "manage/materi/delete";
const _URL_EDIT_MATERI 		= _URL_MAIN_WEBSITE+ "manage/materi/edit";
const _URL_UPDATE_MATERI 	= _URL_MAIN_WEBSITE+ "manage/materi/update";
const _URL_UPDATE_PAKET_MATERI 	= _URL_MAIN_WEBSITE+ "manage/materi/paket/update";
const _URL_ALL_KATEGORI_MATERI 	= _URL_MAIN_WEBSITE+ "manage/materi/kategori/all";
const _URL_ADD_KATEGORI_MATERI 	= _URL_MAIN_WEBSITE+ "manage/materi/kategori/add";
const _URL_DELETE_KATEGORI_MATERI 	= _URL_MAIN_WEBSITE+ "manage/materi/kategori/delete";

const _URL_ALL_COMMENTS_RATING_MATERI 	= _URL_MAIN_WEBSITE+ "manage/materi/comments-rating/all";

const _URL_ADD_ATTACHMENT_MATERI 	= _URL_MAIN_WEBSITE+ "manage/materi/attachment/add";
const _URL_ADD_ICON_MATERI 			= _URL_MAIN_WEBSITE+ "manage/materi/icon/add";

const _URL_ALL_DISTINCT_KATEGORI  = _URL_MAIN_WEBSITE + "manage/materi/kategori/distinct";
const _URL_UPDATE_KATEGORI_MATERI = _URL_MAIN_WEBSITE + "manage/materi/kategori/change";


const _DEBUG = true;

let _display_modal = null;

$( document ).ready(function() {
    
// init formatter rupiah
	$('.input-rupiah').each(function () {
		new AutoNumeric(this, {
			digitGroupSeparator: '.',
			decimalCharacter: ',',
			decimalPlaces: 0,           // no desimal
			unformatOnSubmit: false,     // kita handle manual karena pakai FormData
			modifyValueOnWheel: false,   // biar scroll mouse ngga ngubah angka
			emptyInputBehavior: 'zero',
			minimumValue: 0,
			formatOnPageLoad: true
		});
	});

	calculateTax();

	linkPembahasan();
	linkQuiz();
	linkCertificate();

	prepareUploads('#icon-materi', '#icon-error', '#icon-loading', 2);
	prepareUploads('#attachment-materi', '#attachment-error', '#attachment-loading', 2);

	$('body').on('submit', '#materiForm', function(e){

		e.preventDefault();

		$('#owner-materi').removeAttr('disabled');

		var formData = new FormData(this); 
		let url = $(this).attr('action');

		sendRequest(formData, url);
		
		
	});

	$(document).on('submit', '#paketForm', function(e){

		e.preventDefault();

		// ambil nilai MURNI (angka)
		const biayaPokok = AutoNumeric.getNumber(document.getElementById('biayaPokok')) || 0;
		const biayaBelajarSendiri = AutoNumeric.getNumber(document.getElementById('biayaBelajarSendiri')) || 0;
		const biayaKasusCustom = AutoNumeric.getNumber(document.getElementById('biayaKasusCustom')) || 0;

		const formData = new FormData(this);

		// overwrite value supaya yang dikirim ke server angka murni
		formData.set('biaya_pokok', biayaPokok);
		formData.set('biaya_belajar_sendiri', biayaBelajarSendiri);
		formData.set('biaya_kasus_custom', biayaKasusCustom);

		const url = $(this).attr('action');

		sendRequest(formData, url);
		printout('submitting paket form to ' + url, formData);
		
	});

	// when modal comments are shown
	$('body').on('click', '.link-view-comments-rating', function(e){

		e.preventDefault();

		let idna = $(this).attr('data-id');
		let datana = {id: idna};

		sendRequest(datana, _URL_ALL_COMMENTS_RATING_MATERI);
		
	});

	// when modal is shown
	$('#materiModal').on('shown.bs.modal', function () {

		// clear form
		$('#kategori-materi-custom').val('');
        requestDataKategori();
    });

	// ===== Toggle submit button Paket Modal =====
	// Saat modal dibuka
	$('#paketModal').on('shown.bs.modal', function () {
		updatePaketSubmitState();
	});

	// Saat salah satu checkbox paket diubah
	$('body').on('change', 'input[name="paket[]"]', function () {
		updatePaketSubmitState();
	});

	// Saat modal mau ditutup, biar bersih
	$('#paketModal').on('hidden.bs.modal', function () {
		// opsional: reset semua state checkbox kalau memang mau
		// $('input[name="paket[]"]').prop('checked', false);
		updatePaketSubmitState();
	});

	// when user owner is clicked changed
	$('body').on('change', '#owner-materi', function(){

		requestDataKategori();

	}); 

	// add kategori from materi modal popup
	$('body').on('click', '#tambah-kategori', function(e){

		e.preventDefault();

		addNewKategori(true);

	});

	// save the kategori when user pressed enter
	$('#kategori-materi-custom').on('keydown', function(e) {
        if (e.key === 'Enter') {
           
            e.preventDefault();
            
            saveCustomKategori();
        }
    });

	// delete kategori from materi modal popup
	$('body').on('click', '#delete-kategori', function(e){

		e.preventDefault();

		let selna = $('#kategori-materi').val();
		let userna = $('#owner-materi').val();
		let datana = {username : userna, kategori: selna};

		sendRequest(datana, _URL_DELETE_KATEGORI_MATERI);

	})

	$('body').on('click', '#save-kategori', function(e){
        e.preventDefault();
        saveCustomKategori();
    });

    // ★ bind context menu dulu — biar apapun yg terjadi ke DataTable, ini tetap jalan
    initKategoriContextMenu();

    // baru init tabel (dibungkus try/catch di dalamnya)
    prepareTable();

});

function prepareUploads(idFile, idError, idGif, limitFileMB){


	 $(idFile).on('change', function(event) {

	 	$(idGif).show();
        // Get the selected file
        const file = event.target.files[0]; // Get the first file

        // Clear previous messages
        $(idError).text('');
       
        if (file) {
            // Check file size (in bytes)
            const fileSizeInMB = file.size / (1024 * 1024); // Convert to MB

            if (fileSizeInMB > limitFileMB) {
                // If file size is greater than 2 MB
                $(idError).text('Error: File size exceeds 2 MB.');
            } else {
                // If file size is within the limit
                const fileName = file.name;
                const fileSize = fileSizeInMB.toFixed(2); // Size in MB
                //$(idError).text(`Selected file: ${fileName} (${fileSize} MB)`);


                // upload the file
                var formData = new FormData(); 
                
				if (file) {

					let paramName = $(idFile).attr('data-param');
		            formData.append(paramName, file); 

		            if(paramName == 'attachment'){
			            sendRequest(formData, _URL_ADD_ATTACHMENT_MATERI);
		            }else{
		            	sendRequest(formData, _URL_ADD_ICON_MATERI);
		            }

		           // alert(paramName);
		        }

            }
        } else {
            $(idError).text('No file selected.');
        }
    });


}

function saveCustomKategori(){

		let kategoriBaru = $('#kategori-materi-custom').val();
		let usernameNa = $('#owner-materi').val();

		let datana = {kategori: kategoriBaru, username: usernameNa};

		sendRequest(datana, _URL_ADD_KATEGORI_MATERI);

		// clear the form
		$('#kategori-materi-custom').val('');

}

function requestDataKategori(){

		let userna = $('#owner-materi').val();
		let datana = {username : userna};

		//console.log('mo kirim '+ datana);
		sendRequest(datana, _URL_ALL_KATEGORI_MATERI);

}
	
	let _total_data_selected = 0;

function processDeleteMateri(numberValues){

	for (var i = 0; i < numberValues.length; i++) {
    	
    	let n = numberValues[i];
		let data = {id: n, current_post: i};
		sendRequest(data, _URL_DELETE_MATERI);

	}

	

}

// ===================== KATEGORI CONTEXT MENU =====================
let _current_kategori_id    = null;
let _current_kategori_value = '';

function initKategoriContextMenu() {

    // 1. Klik kiri cell kategori → tampilkan popup ganti kategori
    $(document).on('click', 'td.kategori-cell', function(e) {
        if (_USERTYPE !== 'admin' && _USERTYPE !== 'instruktur') return;

        e.preventDefault();
        e.stopPropagation();

        _current_kategori_id    = $(this).attr('data-id');
        _current_kategori_value = $(this).clone().children('i').remove().end().text().trim();

        $('#kategori-edit-subtitle').text('Materi ID #' + _current_kategori_id
            + ' • sebelumnya: ' + _current_kategori_value);

        // reset state input "kategori baru"
        $('#kategori-edit-new').val('').hide();

        let cell  = $(this);
        let popup = $('#kategori-edit-popup');

        $.ajax({
            url: _URL_ALL_DISTINCT_KATEGORI,
            type: 'POST',
            dataType: 'json',
            success: function(res) {
                let opts = '';
                if (res.status === 'valid' && res.data && res.data.length) {
                    res.data.forEach(function(row) {
                        let val = row.kategori || '';
                        opts += '<option value="' + val + '">' + val + '</option>';
                    });
                } else {
                    opts = '<option value="">- belum ada kategori -</option>';
                }

                // ▼▼ TAMBAHAN: option "kategori baru..." di paling bawah ▼▼
                opts += '<option value="__NEW__">➕ Kategori baru...</option>';
                // ▲▲ TAMBAHAN ▲▲

                $('#kategori-edit-select').html(opts).val(_current_kategori_value);

                popup.show();

                let rect = cell[0].getBoundingClientRect();
                let pw   = popup.outerWidth()  || 280;
                let ph   = popup.outerHeight() || 200;

                let left = rect.left;
                if (left + pw > $(window).width() - 10) {
                    left = $(window).width() - pw - 10;
                }

                let top = rect.bottom + 6;
                if (top + ph > $(window).height() - 10) {
                    top = rect.top - ph - 6;
                }

                popup.css({ top: top + 'px', left: left + 'px' });
            },
            error: function() {
                alert('Gagal memuat daftar kategori.');
            }
        });
    });

    // ▼▼ TAMBAHAN: saat dropdown berubah ke "kategori baru..." ▼▼
    $(document).on('change', '#kategori-edit-select', function() {
        if ($(this).val() === '__NEW__') {
            $('#kategori-edit-new').show().focus();
        } else {
            $('#kategori-edit-new').hide().val('');
        }
    });

    // Enter di input baru = trigger tombol Simpan
    $(document).on('keydown', '#kategori-edit-new', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            $('#kategori-edit-save').trigger('click');
        }
    });
    // ▲▲ TAMBAHAN ▲▲

    // 2. Tutup popup kalau klik di luar
    $(document).on('mousedown', function(e) {
        if (!$(e.target).closest('#kategori-edit-popup').length
            && !$(e.target).closest('td.kategori-cell').length) {
            $('#kategori-edit-popup').hide();
        }
    });

    // 3. Sembunyikan saat scroll / resize / esc
    $(window).on('scroll resize', function() {
        $('#kategori-edit-popup').hide();
    });
    $(document).on('keydown', function(e) {
        if (e.key === 'Escape') {
            $('#kategori-edit-popup').hide();
        }
    });

    // 4. Cancel
    $(document).on('click', '#kategori-edit-cancel', function() {
        $('#kategori-edit-popup').hide();
    });

    // 5. Save
    $(document).on('click', '#kategori-edit-save', function() {
        let selected = $('#kategori-edit-select').val();

        // ▼▼ TAMBAHAN: tentukan value kategori final ▼▼
        let newKat;
        if (selected === '__NEW__') {
            newKat = ($('#kategori-edit-new').val() || '').trim();
            if (!newKat) {
                alert('Isi nama kategori baru terlebih dahulu.');
                $('#kategori-edit-new').focus();
                return;
            }
        } else {
            newKat = selected;
            if (!newKat) {
                alert('Pilih kategori terlebih dahulu.');
                return;
            }
            if (newKat === _current_kategori_value) {
                $('#kategori-edit-popup').hide();
                return;
            }
        }
        // ▲▲ TAMBAHAN ▲▲

        let $btn = $(this);
        $btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');

        $.ajax({
            url: _URL_UPDATE_KATEGORI_MATERI,   // manage/materi/kategori/change
            type: 'POST',
            dataType: 'json',
            data: { id: _current_kategori_id, kategori: newKat },
            success: function(res) {
                if (res.status === 'valid') {
                    location.reload();
                } else {
                    alert('Gagal: ' + (res.message || 'Unknown error'));
                    $btn.prop('disabled', false).html('<i class="fas fa-check"></i> Simpan');
                }
            },
            error: function() {
                alert('Terjadi kesalahan jaringan.');
                $btn.prop('disabled', false).html('<i class="fas fa-check"></i> Simpan');
            }
        });
    });
}

function updatePaketSubmitState() {
    let checkedCount = $('input[name="paket[]"]:checked').length;
    let $btn = $('#paketModal #submitUpdate');

    if (checkedCount > 0) {
        $btn.prop('disabled', false).removeClass('disabled');
    } else {
        $btn.prop('disabled', true).addClass('disabled');
    }
}

function prepareTable(){


	   try {
        if (typeof DataTable !== 'undefined') {
            // DataTables v2
            new DataTable('#table-management-materi');
        } else if ($.fn && $.fn.DataTable) {
            // DataTables v1.x (jQuery plugin)
            $('#table-management-materi').DataTable();
        } else {
            console.warn('DataTables library tidak terdeteksi — tabel jalan tanpa DataTable.');
        }
    } catch (err) {
        console.warn('DataTable init gagal:', err);
    }

	$('body').on('click', '#select-all', function(){

		let stat = $(this).prop('checked');

		$('#table-management-materi input[type="checkbox"]').prop('checked', stat);

		
	});

	$('body').on('click', '#delete-selected', function(e){

		e.preventDefault();
		var checkedIds = [];

        $('#table-management-materi input[type="checkbox"]:checked').each(function() {
           
            var dataId = $(this).data('id');
            
            checkedIds.push(dataId);
            
        });

        _total_data_selected = checkedIds.length;
        processDeleteMateri(checkedIds);
        

	})

		$('body').on('click', '.toggle-pass', function(e){

		e.preventDefault();

		let stat = $(this).attr('data-status');
		let datana = $(this).attr('data-value');

		if(stat == 'hide'){
			$(this).attr('data-status', 'shown');
			$(this).text(datana);
		}else{
			$(this).attr('data-status', 'hide');
			$(this).text('show');
		}


	});

	$('body').on('click', '#refresh-data', function(){
		location.reload();
	})

	$('body').on('click', '.delete-single', function(e){

		e.preventDefault();

		 _total_data_selected = 1;
		 var dataId = $(this).data('id');
         let data = {id: dataId, current_post: 0};

		sendRequest(data, _URL_DELETE_MATERI);  

	});

	$('body').on('click', '.edit-single', function(e){

		e.preventDefault();

		 var dataId = $(this).data('id');
		 let data = {id: dataId};

		 _display_modal = "materiModal";
		sendRequest(data, _URL_EDIT_MATERI);
		 

	});

	$('body').on('click', '.paket-single', function(e){

		e.preventDefault();

		 var dataId = $(this).data('id');
		 let data = {id: dataId};

		 _display_modal = "paketModal";	
		sendRequest(data, _URL_EDIT_MATERI);
		 

	});

	

}


function sendRequest(datana, URLna){

	// alert('sending data ' + JSON.stringify(datana) + ' into ' + URLna);

  	if(datana instanceof FormData){

  		sendRequestForm(datana, URLna);

  	}else {

  		sendRequestReguler(datana, URLna);

  	}

}

function sendRequestForm(datana, URLna){


	$.ajax({
                    url: URLna,
                    type: 'POST',
                    data: datana,
                    processData : false,
                    contentType: false,
                    success: function(response) {
                        
                    	let dataObtained = JSON.parse(response);

						if(URLna == _URL_UPDATE_PAKET_MATERI){
							location.reload();
						}else if(URLna == _URL_ADD_MATERI || URLna == _URL_UPDATE_MATERI){
							$('#owner-materi').attr('disabled', 'disabled');
                    		 location.reload();
                    	}else if(URLna == _URL_ADD_ICON_MATERI){
                    		let filena = dataObtained.filename;
                    		let urlna = _URL_MAIN_WEBSITE + 'assets/img/uploads/materi/' + filena;
                    		$('#icon-name').val(filena);
                    		$('#preview-icon-materi').attr('src', urlna);
                    		$('#preview-icon-materi').show();
                    		$('#icon-loading').hide();

							$('#delete-icon-materi').show();
                    	}else if(URLna == _URL_ADD_ATTACHMENT_MATERI){
                    		let filena = dataObtained.filename;
                    		let urlna = _URL_MAIN_WEBSITE + 'assets/attachment/uploads/materi/' + filena;
							$('#attachment-name').val(filena);
							$('#preview-attachment-materi').attr('href', urlna);
							$('#preview-attachment-materi').show();
							$('#attachment-loading').hide();

							$('#delete-attachment').show();
                    	}

                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                    }
                });


}

function sendRequestReguler(datana, URLna){

	//printout('request reg ' + URLna, datana);

	$.ajax({
                    url: URLna,
                    type: 'POST',
                    data: datana,
                    success: function(response) {

                    	let dataObtained = JSON.parse(response);

						if(URLna == _URL_UPDATE_PAKET_MATERI){

							if(dataObtained.status == 'valid'){
								location.reload();
							}

						} else if (URLna == _URL_ALL_COMMENTS_RATING_MATERI){

                    		if(dataObtained.status == 'valid'){
                    			extractCommentsData(dataObtained);
                    		}

                    	}else if(URLna == _URL_ALL_KATEGORI_MATERI){
                    	
                    		if(dataObtained.status == 'valid'){
                    			extractKategoriMateriData(dataObtained);
                    		}else{
                    			defaultKategoriMateri();
                    		}

                    	}else if(URLna == _URL_ADD_KATEGORI_MATERI || URLna == _URL_DELETE_KATEGORI_MATERI){

                    		// doing the refresh once again after added
                    		// into the dropdown
                    		sendRequest(datana, _URL_ALL_KATEGORI_MATERI);

                    	}else if (URLna == _URL_DELETE_MATERI) {
                    		location.reload();
                    	} else if(URLna == _URL_EDIT_MATERI){

                    		if(dataObtained.status == 'valid'){
                    			//alert(JSON.stringify(dataObtained.data));
								if(_display_modal == "materiModal"){
									extractMateriData(dataObtained.data);
								} else if(_display_modal == "paketModal"){
									extractPaketData(dataObtained.data);
								} 
                    			
                    		}

                    	}

                    

                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                    }
                });


}

function extractPaketData(dataCome){
    console.log('dataCome:', dataCome);   // buat debug juga

    $('#paketModal').modal('show');

    // reset manual — TANPA trigger event 'reset' native
    $('input[name="paket[]"]').prop('checked', false);
    $('input[name="rilis_sertifikat"]').prop('checked', false);

    $('#judulMateri').val(dataCome.judul);

    // set AutoNumeric — sekarang aman
    AutoNumeric.set(document.getElementById('biayaPokok'),
                    dataCome.biaya_pokok || 0);
    AutoNumeric.set(document.getElementById('biayaBelajarSendiri'),
                    dataCome.biaya_belajar_sendiri || 0);
    AutoNumeric.set(document.getElementById('biayaKasusCustom'),
                    dataCome.biaya_kasus_custom || 0);

    $('#biayaPokok').trigger('input');   // hitung pajak

    if (dataCome.rilis_sertifikat) {
        $(`input[name="rilis_sertifikat"][value="${dataCome.rilis_sertifikat}"]`)
            .prop('checked', true);
    }
    if (dataCome.paket_belajar_sendiri == 'yes') $('#paket1').prop('checked', true);
    if (dataCome.paket_bimbingan == 'yes')       $('#paket2').prop('checked', true);
    if (dataCome.paket_kasus_custom == 'yes')    $('#paket3').prop('checked', true);

    $('#materiId').val(dataCome.id);
    $('#paketForm').attr('action', _URL_UPDATE_PAKET_MATERI);

	 updatePaketSubmitState();
}

function extractCommentsData(datana){

	// we just grab the data object here
	let data = datana.data;

	let table = $('#table-management-comments-rating').find('tbody');

	// clear everything
	table.html('');

	for(i=0; i<data.length; i++){
		let entry = data[i];

		let tr = $('<tr>');
		let td1 = $('<td>');
		let chk = $('<input>');
		chk.attr('type', 'checkbox');
		chk.attr('class', 'comments-rating-id');
		chk.val(entry.id);

		let td2 = $('<td>');
		td2.text(entry.username);

		let td3 = $('<td>');
		td3.text(entry.comments);

		let td4 = $('<td>');
		td4.text(entry.date_created);

		let link = $('<a>');
		link.attr('href', '#');
		link.attr('data-id', entry.id);
		link.text('Delete');

		let td5 = $('<td>');
		td5.append(link);

		tr.append(td1);
		tr.append(td2);
		tr.append(td3);
		tr.append(td4);
		tr.append(td5);

		table.append(tr);
	}


}

function defaultKategoriMateri(){
	 $('#kategori-materi').empty();
	
	let  el = $('<option>');
    el.text('pilih salah satu');

     $('#kategori-materi').append(el);
     $('#delete-kategori').hide();
}

function extractKategoriMateriData(dataCome){

	//printout('extracting', dataCome);

	// clear first
	
	//console.log('ada ' + dataCome.data.length);

	let koleksi = dataCome.data;

	let kat = '';
    let el = '';

     $('#kategori-materi').empty();

for (const item of koleksi) {
    //console.log(`ID: ${item.id}, Kategori: ${item.kategori}`);

	kat = item.kategori;
	el = $('<option>');
    el.text(kat);
    el.val(kat);

      $('#kategori-materi').append(el);
}

		
		addNewKategori(false);
	
}

function addNewKategori(showMe){

	if(showMe==false){
		$('#kategori-materi').show();
		$('#tambah-kategori').show();
		$('#delete-kategori').show();
		$('#kategori-materi-custom').hide();
		$('#save-kategori').hide();

	}else{

		$('#kategori-materi').hide();
		$('#tambah-kategori').hide();
		$('#delete-kategori').hide();
		$('#kategori-materi-custom').show();
		$('#save-kategori').show();
		

	}

}

function extractMateriData(dataCome){

	//printout('materi' , dataCome);

	$('#materiModal').modal('show');

	$('#hidden_id-materi').val(dataCome.id);
	
	$('#judul-materi').val(dataCome.judul);
	$('#kategori-materi').val(dataCome.kategori);
	$('#deskripsi-materi').text(dataCome.deskripsi);
	$('#owner-materi').val(dataCome.username);
	$('#url-alive-materi').val(dataCome.url_alive || '');
	
	let fileIcon = (dataCome.icon);
	let fileAttachment = (dataCome.attachment);

	//alert(fileAttachment);

	let urlIcon = _URL_MAIN_WEBSITE + "assets/img/uploads/materi/" + fileIcon;
	let urlAttachment = _URL_MAIN_WEBSITE + "assets/attachment/uploads/materi/" + fileAttachment;

	$('#preview-icon-materi').attr('src', urlIcon);
	$('#preview-attachment-materi').attr('href', urlAttachment);

	if(urlIcon!=null){
		$('#preview-icon-materi').show();
		$('#icon-name').val(fileIcon);
	}else{
		$('#preview-icon-materi').hide();
	}

	if(urlAttachment != null){
		$('#preview-attachment-materi').show();
		$('#attachment-name').val(fileAttachment);
	}else{
		$('#preview-attachment-materi').hide();
	}

	$('#materiForm').attr('action', _URL_UPDATE_MATERI);

}

function printout(identifier, data){
	if(_DEBUG){
	console.log(identifier + " " + JSON.stringify(data));
	}
}

function calculateTax(){
    $(document).on('input', '#biayaPokok', function(){
        let biayaPokok = AutoNumeric.getNumber(this) || 0;
        let pajak = biayaPokok * 0.1;
        let total = biayaPokok + pajak;

        $('#nilaiPajak').text(formatRupiah(pajak));

        // kalau mau tampil total juga:
        // $('#nilaiTotal').text(formatRupiah(total));
    });
}

// helper kecil buat nampilin angka di display (bukan input)
function formatRupiah(angka){
    return 'Rp ' + new Intl.NumberFormat('id-ID').format(Math.round(angka));
}

function linkCertificate(){

	$('body').on('click', '.certificate-single', function(e){

		e.preventDefault();

		let idna = $(this).attr('data-id');
		
		location.href = _URL_MAIN_WEBSITE+'manage/certificate?materi_id=' + idna;
		
	});

}

function linkPembahasan(){

	  $('.pembahasan-single').on('click', function(e) {

		e.preventDefault();

			let idna = $(this).attr('data-id');
		
                Swal.fire({
                    title: 'Pilih Jenis Pembahasan',
                    text: 'Silakan pilih tipe pembahasan yang Anda inginkan',
                    icon: 'question',
                    showDenyButton: true,
                    showCancelButton: false,
                    confirmButtonText: 'Umum',
                    denyButtonText: 'Custom',
                    confirmButtonColor: '#3085d6',
                    denyButtonColor: '#d33',
                    background: '#fff',
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        bukaPembahasanUmum(idna);
                    } else if (result.isDenied) {
                        bukaPembahasanCustom(idna);
                    }
                });
            });

}

function bukaPembahasanUmum(idna){

		location.href = _URL_MAIN_WEBSITE+ 'manage/materi/pembahasan?materi_id=' + idna;
		
}

function bukaPembahasanCustom(idna){

		location.href = _URL_MAIN_WEBSITE+ 'manage/materi/custom?materi_id=' + idna;
		
}


function linkQuiz(){

	$('body').on('click', '.quiz-single', function(e){

		e.preventDefault();

		let idna = $(this).attr('data-id');
		
		location.href = _URL_MAIN_WEBSITE+'manage/materi/quiz?materi_id=' + idna;
		
	});

}