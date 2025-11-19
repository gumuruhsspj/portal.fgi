const _URL_ADD_MATERI		= "/manage/materi/add";
const _URL_DELETE_MATERI 	= "/manage/materi/delete";
const _URL_EDIT_MATERI 		= "/manage/materi/edit";
const _URL_UPDATE_MATERI 	= "/manage/materi/update";
const _URL_UPDATE_PAKET_MATERI 	= "/manage/materi/paket/update";
const _URL_ALL_KATEGORI_MATERI 	= "/manage/materi/kategori/all";
const _URL_ADD_KATEGORI_MATERI 	= "/manage/materi/kategori/add";
const _URL_DELETE_KATEGORI_MATERI 	= "/manage/materi/kategori/delete";

const _URL_ALL_COMMENTS_RATING_MATERI 	= "/manage/materi/comments-rating/all";

const _URL_ADD_ATTACHMENT_MATERI 	= "/manage/materi/attachment/add";
const _URL_ADD_ICON_MATERI 			= "/manage/materi/icon/add";


const _DEBUG = true;

let _display_modal = null;

$( document ).ready(function() {
    
	calculateTax();

	linkPembahasan();

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

		var formData = new FormData(this); 
		let url = $(this).attr('action');

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

function prepareTable(){


	new DataTable('#table-management-materi');

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
                    		let urlna = '/assets/img/uploads/materi/' + filena;
                    		$('#icon-name').val(filena);
                    		$('#preview-icon-materi').attr('src', urlna);
                    		$('#preview-icon-materi').show();
                    		$('#icon-loading').hide();

							$('#delete-icon-materi').show();
                    	}else if(URLna == _URL_ADD_ATTACHMENT_MATERI){
                    		let filena = dataObtained.filename;
                    		let urlna = '/assets/attachment/uploads/materi/' + filena;
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
    // 1. Tampilkan Modal
	//printout('extract paket', dataCome);
 
	// a. Reset semua field (Penting untuk mode Edit)
    $('#paketForm')[0].reset();

    $('#paketModal').modal('show');
    
    // 2. Atur URL Form Action (Opsional, karena kita pakai AJAX)
    // Walaupun menggunakan AJAX, menetapkan ID record membantu jika Anda perlu mengirim ID tersebut.
    // Jika Anda ingin mengirim ID, ubah AJAX menjadi: url: '/update/' + dataCome.materi_id,
    // Jika tidak, gunakan hidden input di form.
    // $('#paketForm').attr('action', _URL_UPDATE_MATERI + dataCome.materi_id);
    
    // --- 3. Isi Nilai Form ---
	$('#judulMateri').val(dataCome.judul);
   
    
    // b. Isi Biaya Pokok
    $('#biayaPokok').val(dataCome.biaya_pokok).trigger('input'); 
    // .trigger('input') penting agar perhitungan pajak (yang terikat pada event 'input') terpicu.
    
    // c. Isi Rilis Sertifikat (Radio Button)
    $(`input[name="rilis_sertifikat"][value="${dataCome.rilis_sertifikat}"]`).prop('checked', true);
    
    // d. Isi Model Paket (Checkboxes)
    // Uncheck semua dulu (sudah dilakukan oleh reset(), tapi ini jaga-jaga)
    $('input[name="paket[]"]').prop('checked', false); 

    // Ceklis paket yang aktif
   if(dataCome.paket_belajar_sendiri == 'yes'){
	$('#paket1').prop('checked', true);
   }else{
	$('#paket1').prop('checked', false);
   }

   if(dataCome.paket_belajar_sendiri == 'yes'){
	$('#paket1').prop('checked', true);
   }else{
	$('#paket1').prop('checked', false);
   }

   if(dataCome.paket_bimbingan == 'yes'){
	$('#paket2').prop('checked', true);
   }else{
	$('#paket2').prop('checked', false);
   }

   if(dataCome.paket_kasus_custom == 'yes'){
	$('#paket3').prop('checked', true);
   }else{
	$('#paket3').prop('checked', false);
   }

    // 4. (Opsional) Tampilkan ID Record di Modal (untuk debugging/informasi)
    $('#materiId').val(dataCome.id);

	// set URL ke update
	$('#paketForm').attr('action', _URL_UPDATE_PAKET_MATERI);
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
	
	let fileIcon = (dataCome.icon);
	let fileAttachment = (dataCome.attachment);

	//alert(fileAttachment);

	let urlIcon = "/assets/img/uploads/materi/" + fileIcon;
	let urlAttachment = "/assets/attachment/uploads/materi/" + fileAttachment;

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
		let biayaPokok = parseFloat($(this).val()) || 0;
		let pajak = biayaPokok * 0.1; // 10% tax
		let total = biayaPokok + pajak;

		$('#nilaiPajak').text(pajak.toFixed(2));

		//alert('a');
	});
}

function linkPembahasan(){

	$('body').on('click', '.pembahasan-single', function(e){

		e.preventDefault();

		let idna = $(this).attr('data-id');
		
		location.href = '/manage/materi/pembahasan?materi_id=' + idna;
		
	});

}