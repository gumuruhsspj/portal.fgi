const _URL_ADD_PROGRAM_AFILIASI   = "/manage/program-afiliasi/add";
const _URL_DELETE_PROGRAM_AFILIASI  = "/manage/program-afiliasi/delete";
const _URL_EDIT_PROGRAM_AFILIASI    = "/manage/program-afiliasi/edit";
const _URL_UPDATE_PROGRAM_AFILIASI  = "/manage/program-afiliasi/update";
const _URL_BROWSE_MATERI_PROGRAM_AFILIASI  = "/manage/program-afiliasi/browse/materi";

const _DEBUG = true;

$( document ).ready(function() {

  $('body').on('submit', '#programAfiliasiForm', function(e){

    e.preventDefault();

     var formData = new FormData(this);
    //var data = $(this).serialize();
    let url = $('#programAfiliasiForm').attr('action');

    sendRequest(formData, _URL_ADD_PROGRAM_AFILIASI);
    
  });

  // when user selecting materi
   $('body').on('click', '#browse-materi', function(e){

    $('#programAfiliasiModal').modal('hide');

    e.preventDefault();

    let userna = $(this).attr('data-username');
    let datana = {username: userna};

    sendRequest(datana, _URL_BROWSE_MATERI_PROGRAM_AFILIASI);
    
  });

   //when user done selecting materi
   $('body').on('click', '#button-ok-browse-materi', function(){

      $('#programAfiliasiModal').modal('show');

      let data_selected = get_selected_data('#table-browse-materi');

      render_data('#nama-materi-perangkat', data_selected);

   });


   // when user click remove the name
   $('body').on('click', '.removal', function(){

      $(this).parent().remove();

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

function render_data(idNa, data_json){

    $(idNa).text('');

    for (var i = 0; i < data_json.length; i++) {
      
      let removal = $('<b>');
      removal.attr('class', 'removal');
      removal.text('x');

      let span = $('<span>');
      span.attr('class','nama-materi');
      span.attr('data-id', data_json[i].id);
      span.text(data_json[i].text);

      // we give hidden data too
      let hidden_element = $('<input>');
      hidden_element.attr('type', 'hidden');
      hidden_element.attr('name', 'id_materi[]');
      hidden_element.val(data_json[i].id);

      span.append(hidden_element);
      span.append(removal);
      $(idNa).append(span);


    }

  

}

function clearForm(){

  $('#nama-program').val('');
  $('#deskripsi-program').text('');
  $('#icon-program').val('');

}

  let _total_data_selected = 0;

function processDeleteprogramAfiliasi(numberValues){

  for (var i = 0; i < numberValues.length; i++) {
      
      let n = numberValues[i];
    let data = {id: n, current_post: i};
    sendRequest(data, _URL_DELETE_PROGRAM_AFILIASI);

  }

  

}

function prepareTable(){


  new DataTable('#table-management-program-afiliasi');

  $('body').on('click', '#select-all', function(){

    let stat = $(this).prop('checked');

    $('#table-management-program-afiliasi input[type="checkbox"]').prop('checked', stat);

    
  });

  $('body').on('click', '#delete-selected', function(e){

    e.preventDefault();
    var checkedIds = [];

        $('#table-management-program-afiliasi input[type="checkbox"]:checked').each(function() {
           
            var dataId = $(this).data('id');
            
            checkedIds.push(dataId);
            
        });

        _total_data_selected = checkedIds.length;
        processDeleteprogramAfiliasi(checkedIds);
        

  })

   

  $('body').on('click', '#refresh-data', function(){
    location.reload();
  })

  $('body').on('click', '.delete-single', function(e){

    e.preventDefault();

     _total_data_selected = 1;
     var dataId = $(this).data('id');
         let data = {id: dataId, current_post: 0};

    sendRequest(data, _URL_DELETE_PROGRAM_AFILIASI);  

  });

  $('body').on('click', '.edit-single', function(e){

    e.preventDefault();

     var dataId = $(this).data('id');
     let data = {id: dataId};

    sendRequest(data, _URL_EDIT_PROGRAM_AFILIASI);
     

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

                      if(URLna == _URL_ADD_PROGRAM_AFILIASI || URLna == _URL_UPDATE_PROGRAM_AFILIASI){
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

                      if (URLna == _URL_DELETE_PROGRAM_AFILIASI) {
                        location.reload();
                      } else if(URLna == _URL_EDIT_PROGRAM_AFILIASI){

                        if(dataObtained.status == 'valid'){
                          //alert(JSON.stringify(dataObtained.data));
                          extractprogramAfiliasiData(dataObtained.data);
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



function extractprogramAfiliasiData(dataCome){

  printout('program afiliasi ' , dataCome);

  $('#programAfiliasiModal').modal('show');

  $('#hidden_id-program-afiliasi').val(dataCome.id);
  
  $('#nama-program').val(dataCome.nama);
  $('#deskripsi-program').text(dataCome.deskripsi);
    
  $('#programAfiliasiForm').attr('action', _URL_UPDATE_PROGRAM_AFILIASI);

}

function printout(identifier, data){
  if(_DEBUG){
  console.log(identifier + " " + JSON.stringify(data));
  }
}