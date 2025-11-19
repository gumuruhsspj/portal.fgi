const _URL_ADD_USER	= "/manage/user/add";
const _URL_DELETE_USER = "/manage/user/delete";
const _URL_EDIT_USER = "/manage/user/edit";
const _URL_UPDATE_USER = "/manage/user/update";

$( document ).ready(function() {
    
	$('body').on('submit', '#userForm', function(e){

		e.preventDefault();

		// check hidden_id_user to determine add or update
		let id_user = $('#hidden_id_user').val();

		var formData = new FormData(this); 
		let url = '';

		if(id_user != ''){
			url = _URL_UPDATE_USER;
		}else{
			url = _URL_ADD_USER;
		}

		sendRequest(formData, url);
		

	});

	prepareTable();

});

	
	let _total_data_selected = 0;

function processDeleteUser(numberValues){

	for (var i = 0; i < numberValues.length; i++) {
    	
    	let n = numberValues[i];
		let data = {id: n, current_post: i};
		sendRequest(data, _URL_DELETE_USER);

	}

	

}

function prepareTable(){


	new DataTable('#table-management-user');

	$('body').on('click', '#select-all', function(){

		let stat = $(this).prop('checked');

		$('#table-management-user input[type="checkbox"]').prop('checked', stat);

		
	});

	$('body').on('click', '#delete-selected', function(e){

		e.preventDefault();
		var checkedIds = [];

        $('#table-management-user input[type="checkbox"]:checked').each(function() {
           
            var dataId = $(this).data('id');
            
            checkedIds.push(dataId);
            
        });

        _total_data_selected = checkedIds.length;
        processDeleteUser(checkedIds);
        

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

		sendRequest(data, _URL_DELETE_USER);  

	});

	$('body').on('click', '.edit-single', function(e){

		e.preventDefault();

		 var dataId = $(this).data('id');
		 let data = {id: dataId};

		sendRequest(data, _URL_EDIT_USER);
		 

	});

	// return back the lock of username entries
	  $('#userModal').on('hidden.bs.modal', function () {
           $('#username_user').attr('readonly', false);
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

						console.log(dataObtained);

                    	if(URLna == _URL_ADD_USER || URLna == _URL_UPDATE_USER){
                    		location.reload();
                    	}

                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                    }
                });


}

function sendRequestReguler(datana, URLna){

	$.ajax({
                    url: URLna,
                    type: 'POST',
                    data: datana,
                    success: function(response) {
                        
                    	let dataObtained = JSON.parse(response);

                    	if(URLna == _URL_DELETE_USER){
                    		
                    		if(_total_data_selected == datana.current_post+1){
                    			location.reload();
                    		}

                    	}else if(URLna == _URL_EDIT_USER){

                    		if(dataObtained.status == 'valid'){
                    			//alert(JSON.stringify(dataObtained.data));
                    			extractUserData(dataObtained.data);
                    		}

                    	}

                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                    }
                });


}

function extractUserData(dataCome){

	console.log(JSON.stringify(dataCome));

	$('#userModal').modal('show');

	$('#hidden_id_user').val(dataCome.id);
	$('#nama_lengkap_user').val(dataCome.nama_lengkap);
	$('#username_user').val(dataCome.username);
	$('#email_user').val(dataCome.email);
	$('#pass_user').val(dataCome.pass);
	$('#whatsapp_user').val(dataCome.whatsapp);
	$('#usertype_user').val(dataCome.usertype);

	$('#username_user').attr('readonly', true);
	$('#userForm').attr('action', _URL_UPDATE_USER);

}