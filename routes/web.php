<?php
/** @var Router $router */

use App\Helpers\Classes\CustomRouter;
use App\Helpers\Classes\FileHandler;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Laravel\Lumen\Routing\Router;

$customRouter = function (string $as = '') use ($router) {
    $custom = new CustomRouter($router);
    return $custom->as($as);
};

$router->get('/', function () use ($router) {
    return $router->app->version();
});

$router->group(['prefix' => 'api/v1', 'as' => 'api.v1'], function () use ($router, $customRouter) {

    $router->get('/', ['as' => 'api-info', 'uses' => 'ApiInfoController@apiInfo']);

    /** Mail Sender */
    $router->post('send-mail', ["as" => "mail.send-mail", "uses" => "MailSendController@mailSend"]);

    /** Sms Sender */
    $router->post('send-sms', ["as" => "sms.send-sms", "uses" => "SmsController@smsSend"]);

});




