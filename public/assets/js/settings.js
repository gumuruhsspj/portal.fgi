
const _URL_DELETE_PROPIC 	= "/settings/user/delete-propic";
const _URL_UPDATE_PROPIC 	= "/settings/user/update-propic";
const _URL_UPDATE_SETTINGS 	= "/settings/user/update";
const _URL_REINFORCE_SETTINGS 	= "/settings/reinforce";


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
                    		//location.reload();
                    		let us = $('#settingsForm').find('#username').val();
                    		window.location.replace(_URL_REINFORCE_SETTINGS + '?username='+ us);
                    	}

                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                    }
                });


}

function update_propic(filena){

	let default_file = '/assets/img/uploads/propic/' + filena;
	$('#settings-propic-preview').attr('src', default_file);
	$('#sidebar-propic').attr('src', default_file);

	$('#delete-propic').show();

}

function reset_propic(){

	update_propic('empty.png');
	$('#settings-propic').val('');
	$('#delete-propic').hide();
	
}