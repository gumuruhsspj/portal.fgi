const _URL_ADD_PERANGKAT_TAUTAN   = "/manage/perangkat-tautan/add";
const _URL_DELETE_PERANGKAT_TAUTAN  = "/manage/perangkat-tautan/delete";
const _URL_EDIT_PERANGKAT_TAUTAN    = "/manage/perangkat-tautan/edit";
const _URL_UPDATE_PERANGKAT_TAUTAN  = "/manage/perangkat-tautan/update";
const _URL_BROWSE_MATERI_PERANGKAT_TAUTAN  = "/manage/perangkat-tautan/browse/materi";

const _DEBUG = true;

$( document ).ready(function() {

  $('body').on('submit', '#perangkatTautanForm', function(e){

    e.preventDefault();

     //var formData = new FormData(this);
    var data = $(this).serialize();
    let url = $('#perangkatTautanForm').attr('action');

    sendRequest(data, _URL_ADD_PERANGKAT_TAUTAN);
    
  });

  // when user selecting materi
   $('body').on('click', '#browse-materi', function(e){

    $('#perangkatTautanModal').modal('hide');

    e.preventDefault();

    let userna = $(this).attr('data-username');
    let datana = {username: userna};

    sendRequest(datana, _URL_BROWSE_MATERI_PERANGKAT_TAUTAN);
    
  });

   //when user done selecting materi
   $('body').on('click', '#button-ok-browse-materi', function(){

      $('#perangkatTautanModal').modal('show');

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

  $('#nama-perangkat').val('');
  $('#deskripsi-perangkat').text('');
  $('#url-perangkat').val('');
  $('#materi-perangkat').val('');
  $('#id-materi-perangkat').val();
  $('#nama-materi-perangkat').val();

}

  let _total_data_selected = 0;

function processDeletePerangkatTautan(numberValues){

  for (var i = 0; i < numberValues.length; i++) {
      
      let n = numberValues[i];
    let data = {id: n, current_post: i};
    sendRequest(data, _URL_DELETE_PERANGKAT_TAUTAN);

  }

  

}

function prepareTable(){


  new DataTable('#table-management-perangkat-tautan');

  $('body').on('click', '#select-all', function(){

    let stat = $(this).prop('checked');

    $('#table-management-perangkat-tautan input[type="checkbox"]').prop('checked', stat);

    
  });

  $('body').on('click', '#delete-selected', function(e){

    e.preventDefault();
    var checkedIds = [];

        $('#table-management-perangkat-tautan input[type="checkbox"]:checked').each(function() {
           
            var dataId = $(this).data('id');
            
            checkedIds.push(dataId);
            
        });

        _total_data_selected = checkedIds.length;
        processDeletePerangkatTautan(checkedIds);
        

  })

   

  $('body').on('click', '#refresh-data', function(){
    location.reload();
  })

  $('body').on('click', '.delete-single', function(e){

    e.preventDefault();

     _total_data_selected = 1;
     var dataId = $(this).data('id');
         let data = {id: dataId, current_post: 0};

    sendRequest(data, _URL_DELETE_PERANGKAT_TAUTAN);  

  });

  $('body').on('click', '.edit-single', function(e){

    e.preventDefault();

     var dataId = $(this).data('id');
     let data = {id: dataId};

    sendRequest(data, _URL_EDIT_PERANGKAT_TAUTAN);
     

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

                      if(URLna == _URL_ADD_PERANGKAT_TAUTAN || URLna == _URL_UPDATE_PERANGKAT_TAUTAN){
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

                      if (URLna == _URL_DELETE_PERANGKAT_TAUTAN) {
                        location.reload();
                      } else if(URLna == _URL_EDIT_PERANGKAT_TAUTAN){

                        if(dataObtained.status == 'valid'){
                          //alert(JSON.stringify(dataObtained.data));
                          extractPerangkatTautanData(dataObtained.data);
                        }

                      } else if(URLna == _URL_BROWSE_MATERI_PERANGKAT_TAUTAN){
                          extractMateriData(dataObtained.data);
                      } else {
                         window.location.reload();
                      }

                    

                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                    }
                });


}

function extractMateriData(dataCome){

  $('#table-browse-materi tbody').html('');

  let i=0;

  for(i=0; i<dataCome.length; i++){

  let datana = dataCome[i];
  let tr = $('<tr>');

  let td0 = $('<td>');
  let chk = $('<input>');
  chk.attr('type','checkbox');
  chk.attr('class', 'select-single');
  chk.attr('data-id', datana.id);
  chk.attr('data-judul', datana.judul);

  td0.append(chk);

  let td1 = $('<td>');
  td1.text(datana.judul);

  let td2 = $('<td>');
  td2.text(datana.kategori);

  let td3 = $('<td>');
  let ah3 = $('<a>');
  ah3.attr('href', '#');
  ah3.attr('class', 'display-deskripsi');
  ah3.text('Display');

  td3.append(ah3);

  let urlna = "/assets/attachment/uploads/materi/" + datana.attachment;

  let td4 = $('<td>');
  let ah4 = $('<a>');
  ah4.attr('href', urlna);
  ah4.attr('class', 'display-attachment');
  ah4.text('Preview');
  
  td4.append(ah4);

  let td5 = $('<td>');
  td5.text(datana.username);

  let td6 = $('<td>');
  td6.text(datana.date_created);

  tr.append(td0);
  tr.append(td1);
  tr.append(td2);
  tr.append(td3);
  tr.append(td4);
  tr.append(td5);
  tr.append(td6);

  $('#table-browse-materi tbody').append(tr);

  }

}

function extractPerangkatTautanData(dataCome){

  printout('perangkat tautan' , dataCome);

  $('#perangkatTautanModal').modal('show');

  $('#hidden_id-perangkat-tautan').val(dataCome.id);
  
  $('#nama-perangkat').val(dataCome.nama);
  $('#url-perangkat').val(dataCome.url);
  $('#deskripsi-perangkat').text(dataCome.deskripsi);
  $('#nama-materi-perangkat').val(dataCome.nama_materi);
  $('#id-materi-perangkat').val(dataCome.id_materi);
  
  $('#perangkatTautanForm').attr('action', _URL_UPDATE_PERANGKAT_TAUTAN);

}

function printout(identifier, data){
  if(_DEBUG){
  console.log(identifier + " " + JSON.stringify(data));
  }
}