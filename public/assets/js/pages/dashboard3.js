/* global Chart:false */

var mode = 'index';
var intersect = true;
 var ticksStyle = {
    fontColor: '#495057',
    fontStyle: 'bold'
  };

function printOut(title, data) {

  console.log(title + " : " + JSON.stringify(data));

}

  function asRupiah(number, includeDecimal = false) {
    if (typeof number !== 'number' || isNaN(number)) {
        // Mengembalikan nilai default jika input tidak valid
        return 'Rp 0';
    }

    const formatter = new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        // Jika includeDecimal false, maka desimal akan dihilangkan (misal: 1.000.000)
        minimumFractionDigits: includeDecimal ? 2 : 0, 
        maximumFractionDigits: includeDecimal ? 2 : 0,
    });

    return formatter.format(number);
}

$(function () {
  'use strict'

  // ensure this is for admin call only 
  // while generating the chartjs
  let usertype = $('#usertype').val();

  if(usertype == 'admin'){
    get_data_display();
  }else {

  let opt_cal = {
    prevButton:"<<",
    nextButton:">>",
    showTodayButton:false,
  };

    if($('#calendar').length>0){

    $('#calendar').calendar(opt_cal);
    calendarClicked();

    }
  }


});

function calendarClicked(){

  $('#calendar').calendar({
  onClickDate: function (dateString) {
   // alert(date);

    // Tanggal awal
//const dateString = 'Wed Feb 19 2025 00:00:00 GMT+0700 (Indochina Time)';
const date = new Date(dateString);

// Mendapatkan tahun, bulan, dan hari
const year = date.getFullYear();
const month_text = date.toLocaleString('default', { month: 'short' }); 
const month = String(date.getMonth() + 1).padStart(2, '0'); // Bulan dimulai dari 0
const day = String(date.getDate()).padStart(2, '0');

// Mendapatkan jam, menit, dan detik
const hours = String(new Date().getHours()).padStart(2, '0');
const minutes = String(new Date().getMinutes()).padStart(2, '0');
const seconds = String(new Date().getSeconds()).padStart(2, '0');

// Menggabungkan menjadi format yang diinginkan
const date_for_db = `${year}-${month}-${day}`;

const today_text = `${day}/${month_text}/${year}`;
$('#date-chosen').text(today_text);
$('#date-chosen').attr('data-date', date_for_db);

//console.log(date_for_db);

  }
});

}

function render_chart2(){

  var $visitorsChart = $('#visitors-chart')
  // eslint-disable-next-line no-unused-vars
  var visitorsChart = new Chart($visitorsChart, {
    data: {
      labels: _months_name,
      datasets: [{
        type: 'line',
        data: _counter_mixed,
        backgroundColor: 'transparent',
        borderColor: '#007bff',
        pointBorderColor: '#007bff',
        pointBackgroundColor: '#007bff',
        fill: false
        // pointHoverBackgroundColor: '#007bff',
        // pointHoverBorderColor    : '#007bff'
      }]
    },
    options: {
      maintainAspectRatio: false,
      tooltips: {
        mode: mode,
        intersect: intersect
      },
      hover: {
        mode: mode,
        intersect: intersect
      },
      legend: {
        display: false
      },
      scales: {
        yAxes: [{
          // display: false,
          gridLines: {
            display: true,
            lineWidth: '4px',
            color: 'rgba(0, 0, 0, .2)',
            zeroLineColor: 'transparent'
          },
          ticks: $.extend({
            min: 0,
            max: 20,
            beginAtZero: true,
            suggestedMax: 200
          }, ticksStyle)
        }],
        xAxes: [{
          display: true,
          gridLines: {
            display: false
          },
          ticks: ticksStyle
        }]
      }
    }
  });


}

function render_chart(){

  var $salesChart = $('#sales-chart')
  // eslint-disable-next-line no-unused-vars
  var salesChart = new Chart($salesChart, {
    type: 'bar',
    data: {
      labels: _months_name,
      datasets: [
        {
          backgroundColor: '#007bff',
          borderColor: '#007bff',
          data: _counter_male
        },
        {
          backgroundColor: '#ced4da',
          borderColor: '#ced4da',
          data: _counter_female
        }
      ]
    },
    options: {
      maintainAspectRatio: false,
      tooltips: {
        mode: mode,
        intersect: intersect
      },
      hover: {
        mode: mode,
        intersect: intersect
      },
      legend: {
        display: false
      },
      scales: {
        yAxes: [{
          // display: false,
          gridLines: {
            display: true,
            lineWidth: '4px',
            color: 'rgba(0, 0, 0, .2)',
            zeroLineColor: 'transparent'
          },
          ticks: $.extend({
            beginAtZero: true,
            min: 0,
            max: 20,
            // Include a dollar sign in the ticks
            callback: function (value) {
              if (value >= 1000) {
                value /= 1000
                value += 'k'
              }

              return value
            }
          }, ticksStyle)
        }],
        xAxes: [{
          display: true,
          gridLines: {
            display: false
          },
          ticks: ticksStyle
        }]
      }
    }
  });


}


let _months_name      = [];
let _counter_male     = [];
let _counter_female   = [];
let _counter_mixed    = [];

function get_data_display(){

    let by_male = { 'gender': 'male' };
    let by_female = { 'gender': 'female' };

    let url = "/chart/user-data";

    // Call both AJAX requests and wait for them to complete
    Promise.all([
        get_data_chart('', url),
        get_data_chart(by_male, url),
        get_data_chart(by_female, url)
    ]).then(() => {
        // This code will run after both AJAX calls have completed
        console.log('Both data requests completed.');
        console.log('Months:', _months_name);
        console.log('Male Counter:', _counter_male);
        console.log('Female Counter:', _counter_female);
        console.log('Mixed Counter:', _counter_mixed);


        render_chart();
        render_chart2();

    }).catch(error => {
        console.error('Error in one of the requests:', error);
    });

}


function get_data_chart(datana, URLna){

return new Promise((resolve, reject) => {
                $.ajax({
                    url: URLna,
                    type: 'POST',
                    data: datana,
                    success: function(response) {
                        
                    let dataObtained = JSON.parse(response);

                    var data = JSON.parse(response);

                    // Initialize arrays for months and values
                    var months = [];
                    var values = [];

                    // Loop through the data and populate the arrays
                    data.forEach(function(item) {
                        months.push(item.bulan);
                        values.push(item.jumlah);
                    });
                     

                     if(months.length>0){
                        _months_name = months;
                        console.log('didapat nama bulan yaitu : ' + JSON.stringify(_months_name));
                     }

                     if(datana.gender == 'female'){
                        _counter_female = values;
                        console.log('didapat counter female yaitu : ' + JSON.stringify(_counter_female));
                     }else if(datana.gender == 'male'){
                          _counter_male = values;
                          console.log('didapat counter male yaitu : ' + JSON.stringify(_counter_male));
                     }else {
                        _counter_mixed = values;
                        console.log('didapat counter mixed yaitu : ' + JSON.stringify(_counter_mixed));
                     }

                     resolve();

                    },
                    error: function(xhr, status, error) {
                        console.error('Error:', error);
                    }
                });
              });

}

