<?php

function string_to_url($string) {
    // Convert to lowercase
    $string = strtolower($string);
    
    // Replace spaces with hyphens
    $string = str_replace(' ', '-', $string);
    
    // Remove special characters
    $string = preg_replace('/[^a-z0-9-]/', '', $string);
    
    // Remove multiple hyphens
    $string = preg_replace('/-+/', '-', $string);
    
    // Trim hyphens from the beginning and end
    $string = trim($string, '-');
    
    return $string;
}

function generateRandomMixedString(int $length = 7): string
    {
        // Karakter yang diizinkan: Angka (0-9) dan Huruf Kapital (A-Z)
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';

        // Looping sebanyak panjang yang diminta
        for ($i = 0; $i < $length; $i++) {
            // Pilih karakter acak dari string $characters
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        
        return $randomString;
    }

function as_rupiah($amount){

    return "Rp " . number_format($amount, 0, ',', '.');

}

function convert_to_lowercase_property($object) {
    // Get the properties of the object as an associative array
    $properties = get_object_vars($object);
    
    // Create a new stdClass object to hold the lowercase properties
    $lowercaseObject = new stdClass();
    
    // Iterate through the properties
    foreach ($properties as $key => $value) {
        // Convert the property name to lowercase and assign the value
        $lowercaseObject->{strtolower($key)} = $value;
    }
    
    return $lowercaseObject;
}


function get_data_as_key($data, $key){

    $end_data = array();

    foreach($data as $d){
       $end_data [] = $d->$key;
    }

    return $end_data;

}

function get_data_as_key_value($data, $key, $valueNeeded){

    $end_data = array();

    foreach($data as $d){
        if($d->$key == $valueNeeded){
            $end_data [] = $d;
       }
    }

    return $end_data;

}

function get_data_as_achor($dataTitle, $dataURL){

    $data = "<a href='" . $dataURL . "'> " . $dataTitle . "</a>";
    return $data;

}

function get_text_data_from_array($data){


    $datana = implode(", ", $data);
    return $datana;

}

function get_portion_text($text, $total_char){

    $max = strlen($text)-1;

    if($total_char > $max){
        $total_char = $max;
    }

    return substr($text, 0, $total_char);

}

function calculate_time_elapsed($timestampFormat){
     
        //$timestamp = '2025-01-06 15:33:44';

// Create DateTime objects
$dateTime = new DateTime($timestampFormat);
$currentDateTime = new DateTime();

// Calculate the difference
$interval = $currentDateTime->diff($dateTime);

// Get the difference in various units
$seconds = $interval->s;
$minutes = $interval->i;
$hours = $interval->h;
$days = $interval->d;
$months = $interval->m;
$years = $interval->y;

// Determine the output
if ($years > 0) {
    $text = $years . '  tahun lalu';
} elseif ($months > 0) {
    $text = $months . '  bulan lalu';
} elseif ($days > 0) {
    $text = $days . '  hari lalu';
} elseif ($hours > 0) {
    $text = $hours . '  jam lalu';
} elseif ($minutes > 0) {
    $text = $minutes . '  menit lalu';
} else {
    $text = $seconds . '  detik lalu';
}

        return $text;
}
