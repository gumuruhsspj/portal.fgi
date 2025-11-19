const _URL_ADD_DAILY_NOTES	= "/manage/daily-notes/add";
const _URL_DELETE_DAILY_NOTES = "/manage/daily-notes/delete";
const _URL_EDIT_DAILY_NOTES = "/manage/daily-notes/edit";
const _URL_UPDATE_DAILY_NOTES = "/manage/daily-notes/update";
const _URL_ALL_DAILY_NOTES = "/manage/daily-notes/all";

$( document ).ready(function() {
    
	$('body').on('click', '#btn-add-new-note', function(e){

        // we give empty id, pesan
		addDailyNotes("","");

	});

    
    enterDailyNotes();
    editDailyNotes();
    deleteDailyNotes();
    chooseDay();

});

function chooseDay(){

    $('body').on('click', '#calendar', function(){

        // call the query after 1500 sec
        setTimeout(function(){

            // clear first
            $('#todo-list-ul').html('');

            let tgl = $('#date-chosen').attr('data-date');
            let datana = {date_created: tgl};
            sendRequestDailyNotes(datana, _URL_ALL_DAILY_NOTES);

        }, 900);

    });
    
}

function deleteDailyNotes(){


    $('body').on('click', '.delete-todoCheck', function(e){

        let up = $(this).parent();

        let idna = $(this).attr('data-id');

        let datana = {id: idna};

        sendRequestDailyNotes(datana, _URL_DELETE_DAILY_NOTES);



    });

}

function editDailyNotes(){


    $('body').on('click', '.edit-todoCheck', function(e){

        let up = $(this).parent().parent();

        let text = $(up).find('label').text();
        $(up).find('label').hide();

        $(up).find('.text-todoCheck').show();
        $(up).find('.text-todoCheck').val(text);




    });

}

function enterDailyNotes(){

    $('body').on('keypress', '.text-todoCheck', function(e){

        if(e.which === 13){
            e.preventDefault();

            let text = $(this).val();
            let up = $(this).parent();
            $(up).find('label').text(text);
            $(up).find('label').show();

            $(this).hide();

        
        let status  = $(up).find('.todoCheck').is(':checked');
        let pesan   = $(this).val();
        let idna    = $(this).attr('data-id');
        
        // the username is placed inside the title of href
        let usna = $('#username-link').attr('title');

        if(idna != '' || idna.length>0){
            let datana = {id: idna, notes: pesan, username : usna};
            sendRequestDailyNotes(datana, _URL_UPDATE_DAILY_NOTES);
        }else {
            let datana = {notes: pesan, username : usna};
            sendRequestDailyNotes(datana, _URL_ADD_DAILY_NOTES);
        }

        }


    });

}



function addDailyNotes(id, notes){

    let display = 'style="display:inline-block;"';
    
    // make it hidden
    if(id!=''){
        display = "";
    }

    var liElement = `
        <li>
           
            <div class="icheck-primary d-inline ml-2">
                <input type="checkbox" data-id="${id}" value="done" id="" class="todoCheck">
                <label class="label" data-id="${id}" for="todoCheck">${notes}</label>
                <input type="text" class="isian text-todoCheck" data-id="${id}" ${display} />
            </div>
            <span class="text"></span>
            
            <div class="tools">
                <i class="edit-todoCheck fas fa-edit"></i>
                <i data-id="" class="delete-todoCheck fas fa-trash"></i>
            </div>
        </li>
    `;

    // Append the <li> element to the <ul>
    $('#todo-list-ul').append(liElement);



}

function sendRequestDailyNotes(datana, URLna){

console.log('kirim ke ' + URLna + ' data ' + JSON.stringify(datana));

	$.ajax({
                    url: URLna,
                    type: 'POST',
                    data: datana,
                    success: function(response) {
                        
                    	let dataObtained = JSON.parse(response);

                    	 if(URLna == _URL_ADD_DAILY_NOTES || 
                    	 	URLna == _URL_DELETE_DAILY_NOTES || 
                    	 	URLna == _URL_UPDATE_DAILY_NOTES){

                    		if(dataObtained.status == 'valid'){
                    			window.location.reload();
                    		}

                    	} else if(URLna == _URL_ALL_DAILY_NOTES){

                            if(dataObtained.status == 'valid'){
                                
                                for (const item of dataObtained.data) {
                                    let idna = item.id;
                                    let notesna = item.notes;

                                    addDailyNotes(idna, notesna);
                                }

                            }

                            //alert(',,');

                        }

                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                    }
                });


}
