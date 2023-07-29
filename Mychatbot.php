<?php
/**
 * Plugin Name: My ChatBot
 * Description: AI based chatbot for handling customer support in advance way.
 * Author: WhatsGPT Technology by Cresite


 * Version: 1.0
 */
if (!defined('ABSPATH')) {
    header("Location: /Mychatbot");
    die("");
}



function mychatbot_enqueue_scripts()
{
    // Enqueue CSS file
    global $pagenow;
    wp_enqueue_style('mychatbot-style', plugin_dir_url(__FILE__) . 'css/style.css');


    if ($pagenow == 'my_chatbot') {
        wp_enqueue_style('mychatbot-admin-style', plugin_dir_url(__FILE__) . 'admin/public/admin.css', array(), "1.0", "all");

    }



    wp_enqueue_script('mychatbot-script', plugin_dir_url(__FILE__) . 'js/script.js', array(), '1.0', true);
}
add_action('wp_enqueue_scripts', 'mychatbot_enqueue_scripts');



function enqueue_custom_js()
{
    $path = plugin_dir_url(__FILE__) . 'js/script.js';

    wp_enqueue_script('your-js-handle', $path, array(), '1.0', true);
    $admin_phone_number = get_option('admin_phone_number', '');
    wp_localize_script('your-js-handle', 'adminData', array(
        'adminPhoneNumber' => $admin_phone_number,
    )
    );
}
add_action('wp_enqueue_scripts', 'enqueue_custom_js',1);

function mychatbot_display()
{
    ob_start();
    include plugin_dir_path(__FILE__) . 'public/index.php';
    return ob_get_clean();
}
add_filter('the_content', 'mychatbot_display');


function my_plugin_register_rest_route()
{
    register_rest_route(
        'my-plugin/v1',
        '/chatbot',
        array(
            'methods' => 'POST',
            'callback' => 'my_plugin_chatbot_callback',
            'permission_callback' => '__return_true',
            // No specific permissions required for this endpoint
        )
    );
}
add_action('rest_api_init', 'my_plugin_register_rest_route');

function my_plugin_chatbot_callback($request)
{
    $data = $request->get_json_params(); // Get the JSON data sent from JavaScript

    // Ensure that the user message exists in the data
    if (isset($data['userMessage'])) {
        $user_message = $data['userMessage'];

        // Now you can perform the OpenAI logic here
        // Replace the following example code with your actual OpenAI API call

        $api_key = "{your_api_key}"; // Replace with your OpenAI API key

        // Create the API request
        $request_data = array(
            'model' => 'gpt-3.5-turbo',
            'messages' => array(
                array('role' => 'user', 'content' => $user_message),
            ),
        );

        // Send the API request
        $response = wp_remote_post(
            'https://api.openai.com/v1/chat/completions',
            array(
                'method' => 'POST',
                'headers' => array(
                    'Authorization' => 'Bearer ' . $api_key,
                    'Content-Type' => 'application/json',
                ),
                'body' => json_encode($request_data),
            )
        );

        // Process the API response and send back the chatbot response to the client
        if (!is_wp_error($response) && $response['response']['code'] === 200) {
            $response_data = json_decode($response['body'], true);
            $chatbot_response = $response_data['choices'][0]['message']['content'];
            return rest_ensure_response(array('message' => $chatbot_response));
        } else {
            // Handle API error
            return new WP_Error('api_error', 'Oops! Something went wrong with the OpenAI API. Please try again.', array('status' => 500));
        }
    } else {
        // Handle missing userMessage
        return new WP_Error('invalid_data', 'Invalid data. Please provide a userMessage.', array('status' => 400));
    }
}


function my_page_menu_fun()
{
    include plugin_dir_path(__FILE__) . 'admin/public/index.php';



}
function my_plugin_admin_menu_function()
{
    add_menu_page("my_chatbot", "WhatsGPT", "manage_options", "my_chatbot", "my_page_menu_fun", "dashicons-admin-comments");
}

add_action("admin_menu", "my_plugin_admin_menu_function");


function my_plugin_admin_init()
{
    add_option('chatbot_endpoint', "http://localhost/firstproject/wp-json/my-plugin/v1/chatbot");
}

add_action('admin_init', 'my_plugin_admin_init');

?>