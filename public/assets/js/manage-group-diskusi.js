const _URL_ADD_GROUP_DISKUSI   = "/manage/group-diskusi/add";
const _URL_DELETE_GROUP_DISKUSI  = "/manage/group-diskusi/delete";
const _URL_EDIT_GROUP_DISKUSI    = "/manage/group-diskusi/edit";
const _URL_UPDATE_GROUP_DISKUSI  = "/manage/group-diskusi/update";


const _DEBUG = true;



$( document ).ready(function() {

  $('body').on('submit', '#groupDiskusiForm', function(e){

    e.preventDefault();

     var formData = new FormData(this);
    let url = $('#groupDiskusiForm').attr('action');

    sendRequest(formData, url);
    
  });


  // save the kategori when user pressed enter
  $('#nama-group, #url-group').on('keydown', function(e) {
        if (e.key === 'Enter') {
           
            e.preventDefault();
            
            saveForm();
        }
    });

 

  prepareTable();

});

function clearForm(){

  $('#nama-group').val('');
  $('#jenis-group').val('');
  $('#url-group').val('');
  $('#admin-group').val('');

}

function saveForm(){

  

}


  let _total_data_selected = 0;

function processDeleteGroupDiskusi(numberValues){

  for (var i = 0; i < numberValues.length; i++) {
      
      let n = numberValues[i];
    let data = {id: n, current_post: i};
    sendRequest(data, _URL_DELETE_GROUP_DISKUSI);

  }

  

}

function prepareTable(){


  new DataTable('#table-management-group-diskusi');

  $('body').on('click', '#select-all', function(){

    let stat = $(this).prop('checked');

    $('#table-management-group-diskusi input[type="checkbox"]').prop('checked', stat);

    
  });

  $('body').on('click', '#delete-selected', function(e){

    e.preventDefault();
    var checkedIds = [];

        $('#table-management-group-diskusi input[type="checkbox"]:checked').each(function() {
           
            var dataId = $(this).data('id');
            
            checkedIds.push(dataId);
            
        });

        _total_data_selected = checkedIds.length;
        processDeleteGroupDiskusi(checkedIds);
        

  })

   

  $('body').on('click', '#refresh-data', function(){
    location.reload();
  })

  $('body').on('click', '.delete-single', function(e){

    e.preventDefault();

     _total_data_selected = 1;
     var dataId = $(this).data('id');
         let data = {id: dataId, current_post: 0};

    sendRequest(data, _URL_DELETE_GROUP_DISKUSI);  

  });

  $('body').on('click', '.edit-single', function(e){

    e.preventDefault();

     var dataId = $(this).data('id');
     let data = {id: dataId};

    sendRequest(data, _URL_EDIT_GROUP_DISKUSI);
     

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

                      if(URLna == _URL_ADD_GROUP_DISKUSI || URLna == _URL_UPDATE_GROUP_DISKUSI){
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

                      if (URLna == _URL_DELETE_GROUP_DISKUSI) {
                        location.reload();
                      } else if(URLna == _URL_EDIT_GROUP_DISKUSI){

                        if(dataObtained.status == 'valid'){
                          //alert(JSON.stringify(dataObtained.data));
                          extractGroupDiskusiData(dataObtained.data);
                        }

                      }

                    

                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                    }
                });


}



function extractGroupDiskusiData(dataCome){

  printout('group diskusi' , dataCome);

  $('#groupDiskusiModal').modal('show');

  $('#hidden_id-group-diskusi').val(dataCome.id);
  
  $('#nama-group').val(dataCome.nama);
  $('#url-group').val(dataCome.url);
  $('#admin-group').val(dataCome.username);
  $('#jenis-group').val(dataCome.jenis);
  
  $('#groupDiskusiForm').attr('action', _URL_UPDATE_GROUP_DISKUSI);

}

function printout(identifier, data){
  if(_DEBUG){
  console.log(identifier + " " + JSON.stringify(data));
  }
}