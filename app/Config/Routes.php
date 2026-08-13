<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

$routes->get('auth/google', 'Auth::googleLogin');
$routes->get('auth/google/callback', 'Auth::googleCallback');

// antisipasi user blm ada datanya paksa buat daftar ulang
$routes->get('/register', 'Home::register');

$routes->get('/', 'Home::index');
$routes->get('/logout', 'Works::logout');
$routes->get('/homepage', 'Home::display_home');

$routes->get('/manage/program-afiliasi', 'Home::management_program_afiliasi');
$routes->get('/manage/info-afiliasi', 'Home::management_info_afiliasi');
$routes->get('/manage/user', 'Home::management_user');
$routes->get('/manage/group', 'Home::management_group_diskusi');
$routes->get('/manage/perangkat', 'Home::management_perangkat_tautan');
$routes->get('/manage/materi', 'Home::management_materi');
$routes->get('/manage/materi/custom', 'Home::management_materi_custom');
$routes->get('/manage/materi/kategori', 'Home::management_materi_kategori');
$routes->get('/manage/pembayaran', 'Home::management_pembayaran');
$routes->get('/manage/materi/pembahasan', 'Home::management_pembahasan_materi');
$routes->get('/manage/materi/quiz', 'Home::management_quiz_materi');

$routes->post('/manage/pembayaran/update', 'Works::pembayaran_update');

// working now 17-april-2026
$routes->post('/manage/materi/custom/add', 'Works::materi_custom_add');
$routes->post('/manage/materi/custom/delete', 'Works::materi_custom_delete');
$routes->post('/manage/materi/custom/update', 'Works::materi_custom_update');
$routes->post('/manage/materi/custom/edit', 'Works::materi_custom_edit');

$routes->post('/manage/materi/add', 'Works::materi_add');
$routes->post('/manage/materi/delete', 'Works::materi_delete');
$routes->post('/manage/materi/update', 'Works::materi_update');
$routes->post('/manage/materi/edit', 'Works::materi_edit');

$routes->post('/manage/materi/paket/update', 'Works::materi_paket_update');

$routes->post('/manage/materi/icon/add', 'Works::materi_icon_add');
$routes->post('/manage/materi/attachment/add', 'Works::materi_attachment_add');
$routes->post('/manage/materi/kategori/all', 'Works::materi_kategori_all');
$routes->post('/manage/materi/kategori/add', 'Works::materi_kategori_add');
$routes->post('/manage/materi/kategori/delete', 'Works::materi_kategori_delete');

$routes->post('/manage/materi/pembahasan/bab/add', 'Works::pembahasan_bab_add');
$routes->post('/manage/materi/pembahasan/bab/update', 'Works::pembahasan_bab_update');
$routes->post('/manage/materi/pembahasan/bab/delete', 'Works::pembahasan_bab_delete');
$routes->post('/manage/materi/pembahasan/add', 'Works::pembahasan_add');
$routes->post('/manage/materi/pembahasan/no-urut/next', 'Works::pembahasan_next_no_urut');
$routes->post('/manage/materi/pembahasan/delete', 'Works::pembahasan_delete');
$routes->post('/manage/materi/pembahasan/edit', 'Works::pembahasan_edit');
$routes->post('/manage/materi/pembahasan/update', 'Works::pembahasan_update');

$routes->post('/manage/materi/quiz/add', 'Works::materi_quiz_add');
$routes->post('/manage/materi/quiz/update', 'Works::materi_quiz_update');
$routes->post('/manage/materi/quiz/delete', 'Works::materi_quiz_delete');
$routes->post('/manage/materi/quiz/edit', 'Works::materi_quiz_edit');

$routes->post('/manage/materi/comments-rating/all', 'Works::comments_rating_all');
$routes->post('/manage/materi/comments-rating/delete', 'Works::comments_rating_delete');

// display as student
$routes->get('/materi', 'Home::display_single_materi');
$routes->get('/materi/kategori', 'Home::display_materi_kategori');
$routes->get('/materi/start', 'Home::display_start_materi');
$routes->get('/materi/download/(:num)', 'Works::download_materi/$1');


// called by student
$routes->post('/materi/pembahasan/completed', 'Works::pembahasan_completed');
$routes->post('/materi/pembahasan', 'Works::pembahasan_display');
$routes->post('/materi/cancel', 'Works::delete_request_materi');
$routes->post('/materi/checkout', 'Works::checkout_materi');

$routes->post('/comments-rating/add', 'Works::comments_rating_add');
$routes->post('/comments-rating/all', 'Works::comments_rating_all');


// called by general users
$routes->post('/support/send', 'Works::send_support_email');
$routes->post('/register/send', 'Works::register_new_user');

$routes->post('/manage/user/add', 'Works::user_add');
$routes->post('/manage/user/delete', 'Works::user_delete');
$routes->post('/manage/user/update', 'Works::user_update');
$routes->post('/manage/user/edit', 'Works::user_edit');

$routes->post('/manage/daily-notes/add', 'Works::daily_notes_add');
$routes->post('/manage/daily-notes/delete', 'Works::daily_notes_delete');
$routes->post('/manage/daily-notes/update', 'Works::daily_notes_update');
$routes->post('/manage/daily-notes/all', 'Works::daily_notes_all');

$routes->post('/settings/user/update-propic', 'Works::user_update_propic');
$routes->post('/settings/user/update', 'Works::user_update');
$routes->post('/settings/user/delete-propic', 'Works::user_delete_propic');
$routes->get('/settings/reinforce', 'Works::reinforce_user_settings');

$routes->post('/customer-services/update', 'Works::customer_services_update');
$routes->post('/customer-services/reset', 'Works::customer_services_reset');
$routes->post('/customer-services/list', 'Works::customer_services_list');

$routes->get('/manage/history-saldo', 'Home::management_saldo_history');

$routes->post('/manage/group-diskusi/add', 'Works::group_diskusi_add');
$routes->post('/manage/group-diskusi/delete', 'Works::group_diskusi_delete');
$routes->post('/manage/group-diskusi/edit', 'Works::group_diskusi_edit');
$routes->post('/manage/group-diskusi/update', 'Works::group_diskusi_update');

$routes->post('/manage/perangkat-tautan/add', 'Works::perangkat_tautan_add');
$routes->post('/manage/perangkat-tautan/delete', 'Works::perangkat_tautan_delete');
$routes->post('/manage/perangkat-tautan/edit', 'Works::perangkat_tautan_edit');
$routes->post('/manage/perangkat-tautan/update', 'Works::perangkat_tautan_update');
$routes->post('/manage/perangkat-tautan/browse/materi', 'Works::perangkat_tautan_browse_materi');

$routes->post('/manage/info-afiliasi/add', 'Works::info_afiliasi_add');
$routes->post('/manage/info-afiliasi/delete', 'Works::info_afiliasi_delete');
$routes->post('/manage/info-afiliasi/edit', 'Works::info_afiliasi_edit');
$routes->post('/manage/info-afiliasi/update', 'Works::info_afiliasi_update');

$routes->post('/verify-login', 'Works::verify_login');
$routes->get('/lokasi-kami', 'Home::lokasi');
$routes->get('/privacy-policy', 'Home::privacy');

$routes->post('/chart/user-data', 'Works::generate_chart_user_gender');

$routes->get('/all-notification', 'Home::display_all_notification');
$routes->get('/all-messages', 'Home::display_all_message');
$routes->get('/all-user', 'Home::display_all_user');
$routes->get('/all-materi', 'Home::display_all_materi');
$routes->get('/materi-terpilih', 'Home::display_selected_materi');
$routes->get('/program-afiliasi', 'Home::display_program_afiliasi');
$routes->get('/perangkat-tautan', 'Home::display_all_perangkat_tautan');

$routes->group('manage/program-afiliasi', function ($routes) {
    $routes->post('join', 'Works::join_program_afiliasi');
    $routes->post('update', 'Works::program_afiliasi_update');
    $routes->post('edit/(:num)', 'Works::program_afiliasi_edit/$1');
    $routes->post('add', 'Works::program_afiliasi_add');
    $routes->post('delete', 'Works::program_afiliasi_delete');
});


// Group untuk exercise/typing
$routes->group('exercise/typing', function ($routes) {


    $routes->get('/', 'Typing::index');
    // Daftar sub bab dalam suatu bab
    $routes->get('chapter/(:num)', 'Typing::chapter/$1');
    // Halaman latihan (sub_bab)
    $routes->get('lesson/(:num)', 'Typing::lesson/$1');
    // Endpoint simpan hasil latihan (AJAX)
    $routes->post('save-result', 'Typing::saveResult');
    // Halaman high scores
    $routes->get('highscores', 'Typing::highscores');
    // Ubah preferensi bahasa (AJAX)
    $routes->post('set-language', 'Typing::setLanguage');
});

// ini untuk document seal
$routes->get('upload-seal', 'DocumentSealer::index');
$routes->post('upload-seal/process', 'DocumentSealer::process');
$routes->get('result/(:segment)', 'DocumentSealer::result/$1');
$routes->get('result/download/(:segment)', 'DocumentSealer::download/$1');


$routes->get('test', 'Home::test');

// bagian admin Management Media Promosi
// Media Promosi
$routes->group('manage/media-promosi', function ($routes) {
    $routes->get('/', 'Home::management_media_promosi');
    $routes->post('store', 'Works::media_promosi_add');
    $routes->post('update', 'Works::media_promosi_update');
    $routes->post('delete', 'Works::media_promosi_delete');
    $routes->get('edit/(:num)', 'Works::media_promosi_edit/$1');
    $routes->get('preview/(:num)', 'Works::media_promosi_preview/$1');
});

// Management Kategori Media Promosi
$routes->group('manage/media-category', function ($routes) {
    $routes->post('add', 'Works::media_category_add');
    $routes->post('update', 'Works::media_category_update');
    $routes->post('delete', 'Works::media_category_delete');
    $routes->post('edit/(:num)', 'Works::media_category_edit/$1');
    $routes->get('list', 'Works::media_category_list');
});


// bagian promotor - Media Promosi
$routes->group('/afiliasi', function ($routes) {
    $routes->get('text-generator', 'Home::display_affiliate_text_generator');
    $routes->post('text-generator/generate', 'Works::generate_text');
    $routes->get('program-afiliasi', 'Home::display_program_afiliasi');
    $routes->get('media-promosi', 'Home::display_affiliate_media_promosi');
    $routes->get('media-promosi/download/(:num)', 'Works::download_affiliate_media/$1');
});

$routes->group('/saldo', function ($routes) {
    $routes->post('topup', 'Works::saldo_topup');
    $routes->get('rekening-bank', 'Home::display_rekening_bank');
    $routes->post('rekening-bank/submit', 'Works::submit_rekening_bank');
    $routes->get('riwayat-saldo', 'Home::management_saldo_history');
});
