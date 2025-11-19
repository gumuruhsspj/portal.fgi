const _URL_ADD_INFO_AFILIASI   = "/manage/info-afiliasi/add";
const _URL_DELETE_INFO_AFILIASI  = "/manage/info-afiliasi/delete";
const _URL_EDIT_INFO_AFILIASI    = "/manage/info-afiliasi/edit";
const _URL_UPDATE_INFO_AFILIASI  = "/manage/info-afiliasi/update";

const _DEBUG = true;

$( document ).ready(function() {

  $('body').on('submit', '#infoAfiliasiForm', function(e){

    e.preventDefault();

     //var formData = new FormData(this);
    var data = $(this).serialize();
    let url = $('#infoAfiliasiForm').attr('action');

    sendRequest(data, url);
    
  });

 
  prepareTable();

});

function get_selected_data(idNa, format_target){

  let end_result = [];
  
     $(idNa + ' input[type="checkbox"]:checked').each(function() {
        // Get the data-id attribute value
        var idna = $(this).attr('data-id');
        var judul = $(this).attr('data-judul');

        let datana = {text: judul, id:idna};

       
          end_result.push(datana);
       

    });

     console.log('we got ' + JSON.stringify(end_result));
     return end_result;

}



function clearForm(){

  $('#judul').val('');
  $('#berita').text('');
  $('#status').val('');

}

  let _total_data_selected = 0;

function processDeleteinfoAfiliasi(numberValues){

  for (var i = 0; i < numberValues.length; i++) {
      
      let n = numberValues[i];
    let data = {id: n, current_post: i};
    sendRequest(data, _URL_DELETE_INFO_AFILIASI);

  }

  

}

function prepareTable(){


  new DataTable('#table-management-info-afiliasi');

  $('body').on('click', '#select-all', function(){

    let stat = $(this).prop('checked');

    $('#table-management-info-afiliasi input[type="checkbox"]').prop('checked', stat);

    
  });

  $('body').on('click', '#delete-selected', function(e){

    e.preventDefault();
    var checkedIds = [];

        $('#table-management-info-afiliasi input[type="checkbox"]:checked').each(function() {
           
            var dataId = $(this).data('id');
            
            checkedIds.push(dataId);
            
        });

        _total_data_selected = checkedIds.length;
        processDeleteinfoAfiliasi(checkedIds);
        

  })

   

  $('body').on('click', '#refresh-data', function(){
    location.reload();
  })

  $('body').on('click', '.delete-single', function(e){

    e.preventDefault();

     _total_data_selected = 1;
     var dataId = $(this).data('id');
         let data = {id: dataId, current_post: 0};

    sendRequest(data, _URL_DELETE_INFO_AFILIASI);  

  });

  $('body').on('click', '.edit-single', function(e){

    e.preventDefault();

     var dataId = $(this).data('id');
     let data = {id: dataId};

    sendRequest(data, _URL_EDIT_INFO_AFILIASI);
     

  });

  

}


function sendRequest(datana, URLna){

   //alert('sending data ' + JSON.stringify(datana) + ' into ' + URLna);

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

                      if(URLna == _URL_ADD_INFO_AFILIASI || URLna == _URL_UPDATE_INFO_AFILIASI){
                        location.reload();
                      }

                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                    }
                });


}

function sendRequestReguler(datana, URLna){

  printout('request reg ' + URLna, datana);

  $.ajax({
                    url: URLna,
                    type: 'POST',
                    data: datana,
                    success: function(response) {

                      let dataObtained = JSON.parse(response);

                      if (URLna == _URL_DELETE_INFO_AFILIASI) {
                        location.reload();
                      } else if(URLna == _URL_EDIT_INFO_AFILIASI){

                        if(dataObtained.status == 'valid'){
                          //alert(JSON.stringify(dataObtained.data));
                          extractinfoAfiliasiData(dataObtained.data);
                        }

                      } else {
                         window.location.reload();
                      }

                    

                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                    }
                });


}



function extractinfoAfiliasiData(dataCome){

  printout('info afiliasi ' , dataCome);

  $('#infoAfiliasiModal').modal('show');

  $('#hidden_id-info-afiliasi').val(dataCome.id);
  
  $('#berita').text(dataCome.berita);
  $('#judul').val(dataCome.judul);
  $('#status').val(dataCome.status);
    
  $('#infoAfiliasiForm').attr('action', _URL_UPDATE_INFO_AFILIASI);

}

function printout(identifier, data){
  if(_DEBUG){
  console.log(identifier + " " + JSON.stringify(data));
  }
}