
const _URL_LIST_CUSTOMER_SERVICES 		= "/customer-services/list";
const _URL_UPDATE_CUSTOMER_SERVICES 	= "/customer-services/update";
const _URL_RESET_CUSTOMER_SERVICES 		= "/customer-services/reset";

$( document ).ready(function() {
    
	 $('#customerServicesForm').on('shown.bs.modal', function () {
           
	 		sendRequestCustomerServices(null, _URL_LIST_CUSTOMER_SERVICES);

        });

	$('body').on('submit', '#customerServicesForm', function(e){ 

		e.preventDefault();

		let data = $(this).serialize();

		sendRequestCustomerServices(data, _URL_UPDATE_CUSTOMER_SERVICES);

	});

	$('body').on('click', '#btn-reset-customer-services', function(e){ 

		e.preventDefault();

		let data = $(this).serialize();

		sendRequestCustomerServices(data, _URL_RESET_CUSTOMER_SERVICES);

	});

	$('body').on('click', '.customer-opt', function(){ 


		let v = $(this).text();

		let order = $(this).attr('data-id');

		$('#customer-services-dropdown').text('Profile : ' + v);
		
		if(order==1){
			$('#cs-01-form').show();
			$('#cs-02-form').hide();
		}else{
			$('#cs-01-form').hide();
			$('#cs-02-form').show();
		}

	});

	

	

});

function sendRequestCustomerServices(datana, URLna){

	console.log('sending data ' + JSON.stringify(datana) + ' into ' + URLna);

  	if(datana instanceof FormData){

  		sendRequestFormCustomerServices(datana, URLna);

  	}else {

  		sendRequestRegulerCustomerServices(datana, URLna);

  	}

}

function sendRequestFormCustomerServices(datana, URLna){


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
                        console.error('Error Form:', error);
                    }
                });


}

function sendRequestRegulerCustomerServices(datana, URLna){


	$.ajax({
                    url: URLna,
                    type: 'POST',
                    data: datana,
                    success: function(response) {
                        
                    	let dataObtained = JSON.parse(response);

                    	if(URLna == _URL_LIST_CUSTOMER_SERVICES){
                    		if(dataObtained.status=='valid')
                    		extractDataForm(dataObtained.data);

                    	}else if(URLna == _URL_UPDATE_CUSTOMER_SERVICES){
                    		location.reload();
                    	}

                    },
                    error: function(xhr, status, error) {
                        console.error('Error Reguler:', error);
                    }
                });


}

function extractDataForm(datana){

	let wa1 =	datana[0].whatsapp;
	let status1 =	datana[0].status;
	let nama1 =	datana[0].nama;
	let stat1 =	datana[0].status;

	let wa2 =	datana[1].whatsapp;
	let status2 =	datana[1].status;
	let nama2 =	datana[1].nama;
	let stat2 =	datana[1].status;

	$('#nama1').val(nama1);
	$('#status1').val(status1);
	$('#whatsapp1').val(wa1);
	$('#status1').val(stat1);

	$('#nama2').val(nama2);
	$('#status2').val(status2);
	$('#whatsapp2').val(wa2);
	$('#status2').val(stat2);

}

function reset_propic(){

	update_propic('empty.png');
	$('#settings-propic').val('');
	$('#delete-propic').hide();
	
}