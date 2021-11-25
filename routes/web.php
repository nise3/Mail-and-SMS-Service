<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

/** @var Router $router */

use App\Helpers\Classes\CustomRouter;
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

});

/** Mail Sender */
$router->post('sendmail', function () {
    try {
        $to = [
            "tasmidur.softbdltd@gmail.com",
            "tasmidur.softbdltd@gmail.com",
            "rahulbgc21@gmail.com"
        ];
        $from = "tasmidurrahman@gmail.com";
        $subject = "Testing Mail - " . time();
        $details = [
            "from" => $from,
            "message" => "Nise Mailing Service"
        ];
        $messageBody = view("mail.default", compact('details'));
        $mail = new App\Services\MailService($from, $to, $subject, $messageBody);
        $mail->sendMail();
    } catch (Exception $exception) {
        dd($exception->getMessage());
    }
});

