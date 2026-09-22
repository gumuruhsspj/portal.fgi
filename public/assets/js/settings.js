const _URL_DELETE_PROPIC 	= _URL_MAIN_WEBSITE +"settings/user/delete-propic";
const _URL_UPDATE_PROPIC 	= _URL_MAIN_WEBSITE + "settings/user/update-propic";
const _URL_UPDATE_SETTINGS 	= _URL_MAIN_WEBSITE + "settings/user/update";
const _URL_REINFORCE_SETTINGS 	= _URL_MAIN_WEBSITE + "settings/reinforce";

// selector input text yang boleh autopost (kecuali readonly / file / hidden)
const _SETTINGS_AUTOPOST_SELECTOR = '#settingsForm input[type="text"]:not([readonly]), #settingsForm input[type="password"], #settingsForm input[type="email"], #settingsForm input[type="number"]';


$( document ).ready(function() {
    
	$('body').on('click', '#delete-propic', function(e){

		let idna = $('#hidden-id').val();
		let data = {id: idna};
		sendRequestSettings(data, _URL_DELETE_PROPIC);

	});

	$('body').on('submit', '#settingsForm', function(e){ 

		e.preventDefault();

		let data = $(this).serialize();

		sendRequestSettings(data, _URL_UPDATE_SETTINGS);

	});

	$('body').on('change', '#settings-propic', function(){

		var formData = new FormData();
		let anId = $('#hidden-id').val();

		if ($(this).prop('files').length > 0) {

					var file = $(this).prop('files')[0];
                    formData.append("propic", file);
                    formData.append("id", anId);

                    sendRequestSettings(formData, _URL_UPDATE_PROPIC);
		}

	});

	// ============================================
	// AUTO POST SAAT BLUR (fokus pindah dari input text)
	// ============================================

	// Simpan nilai awal saat input di-focus
	$('body').on('focus', _SETTINGS_AUTOPOST_SELECTOR, function(){

		$(this).data('prev-val', $(this).val());

	});

	// Saat blur, cek apakah nilainya berubah. Kalau iya -> autopost
	$('body').on('blur', _SETTINGS_AUTOPOST_SELECTOR, function(){

		let prevVal = $(this).data('prev-val');
		let currVal = $(this).val();

		// kalau sebelumnya belum pernah di-focus (undefined), skip
		if (typeof prevVal === 'undefined') return;

		// kalau nilainya sama, gak usah kirim
		if (prevVal === currVal) return;

		// trigger submit form (nanti di-handle sama handler submit di atas)
		$('#settingsForm').trigger('submit');

	});

});

function sendRequestSettings(datana, URLna){

	console.log('sending data ' + JSON.stringify(datana) + ' into ' + URLna);

  	if(datana instanceof FormData){

  		sendRequestFormSettings(datana, URLna);

  	}else {

  		sendRequestRegulerSettings(datana, URLna);

  	}

}

function sendRequestFormSettings(datana, URLna){


	$.ajax({
                    url: URLna,
                    type: 'POST',
                    data: datana,
                    processData : false,
                    contentType: false,
                    success: function(response) {
                        
                    	let dataObtained = JSON.parse(response);

                    	 if(URLna == _URL_UPDATE_PROPIC){
                    		if(dataObtained.status == 'valid'){
                    			update_propic(dataObtained.filename);
                    		}
                    	}

                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                    }
                });


}

function sendRequestRegulerSettings(datana, URLna){

	$.ajax({
                    url: URLna,
                    type: 'POST',
                    data: datana,
                    success: function(response) {
                        
                    	let dataObtained = JSON.parse(response);

                    	if(URLna == _URL_DELETE_PROPIC){
                    		reset_propic();
                    	}else if(URLna == _URL_UPDATE_SETTINGS){
                    		// TANPA REDIRECT — cukup update UI di tempat
                    		if(dataObtained.status === 'valid' || dataObtained.status === 'success'){
                    			update_settings_ui(datana);
                    			show_settings_feedback('Perubahan tersimpan', 'success');
								let us = $('#settingsForm').find('#username').val();
								window.location.href = _URL_REINFORCE_SETTINGS + "?username=" + us;
                    		}else{
                    			show_settings_feedback('Gagal menyimpan perubahan', 'error');
                    		}
                    	}

                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                        if(URLna == _URL_UPDATE_SETTINGS){
                        	show_settings_feedback('Gagal menyimpan perubahan', 'error');
                        }
                    }
                });


}

// ============================================
// Update UI di tempat setelah autosave
// ============================================
function update_settings_ui(serializedData){
	// serializedData bisa berupa string querystring: "nama_lengkap=...&email=..."
	let map = {};
	if(typeof serializedData === 'string'){
		serializedData.split('&').forEach(function(pair){
			let kv = pair.split('=');
			if(kv[0]) map[decodeURIComponent(kv[0])] = decodeURIComponent((kv[1]||'').replace(/\+/g,' '));
		});
	}

	// Update nama_lengkap di sidebar kalau ada
	if(map.nama_lengkap){
		$('#sidebar-nama-lengkap, .sidebar-nama-lengkap, .user-nama-lengkap').text(map.nama_lengkap);
	}
	if(map.email){
		$('#sidebar-email, .sidebar-email').text(map.email);
	}
}

// ============================================
// Feedback kecil di pojok (toast-like)
// ============================================
function show_settings_feedback(message, type){
	let $el = $('#settings-feedback-toast');
	if($el.length === 0){
		$el = $('<div id="settings-feedback-toast"></div>').appendTo('body');
		$el.css({
			position: 'fixed',
			right: '20px',
			bottom: '20px',
			padding: '10px 18px',
			'border-radius': '10px',
			'font-size': '0.85rem',
			'font-weight': '600',
			color: '#fff',
			'z-index': 99999,
			opacity: 0,
			transition: 'opacity .25s ease, transform .25s ease',
			transform: 'translateY(10px)'
		});
	}
	$el.stop(true, true)
	   .css('background', type === 'success' ? '#38a169' : '#e53e3e')
	   .text(message)
	   .animate({opacity: 1}, 150)
	   .css('transform', 'translateY(0)')
	   .delay(1500)
	   .animate({opacity: 0}, 250);
}

function update_propic(filena){

	let default_file = _URL_MAIN_WEBSITE +'assets/img/uploads/propic/' + filena;
	$('#settings-propic-preview').attr('src', default_file);
	$('#sidebar-propic').attr('src', default_file);

	$('#delete-propic').show();

}

function reset_propic(){

	update_propic('empty.png');
	$('#settings-propic').val('');
	$('#delete-propic').hide();
	
}