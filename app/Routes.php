<?php

/**
 * Home Routing
 */

$routes->group('', function ($routes) {
    $routes->get('help', 'Home::help');
    $routes->post('privacy', 'Home::privacy');
    $routes->get('terms', 'Home::terms'); 
}); 


/**
 * Dashboard Routing
 */

$routes->add('dashboard', 'Dashboard::index');



/**
 * API Routing
 */

$routes->group('api', function ($routes) {

    $routes->get('', 'API::index');
    $routes->get('questions', 'API::get_questions');

    $routes->get('question', 'API::get_question');
    $routes->post('question', 'API::update_question');
    $routes->delete('question', 'API::delete_question');

    $routes->get('profile', 'API::get_profile');
    $routes->post('profile', 'API::update_profile');  

    $routes->get('messages', 'API::get_messages');  
    $routes->post('message', 'API::create_message');  
    $routes->delete('message', 'API::delete_message');  
    
    $routes->post('option', 'API::update_options');  
    $routes->get('users', 'API::get_users');   

    $routes->post('auth', 'API::auth_user');   

    $routes->add('(:any)', 'API::index');
    
});

/**
 * Send message route 
 */
$routes->add('(:any)', 'Home::send_message/$1');
