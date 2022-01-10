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

$router->get('/', function () use ($router) {
    return $router->app->version();
});


$router->group(['prefix' => 'api'], function () use ($router) {
    $router->post('/login', 'AuthController@login');
    $router->post('/register', 'AuthController@register');

    $router->group(['middleware' => 'admin', 'prefix' => 'admin'], function () use ($router) {
        $router->post('/logout', 'AuthController@logout');
        $router->get('/courses', 'CourseController@index');
        $router->delete('/courses/course/{id}', 'CourseController@destroy');
        $router->post('/instructors', 'AdminController@registerInstructor');
    });

    $router->group(['middleware' => 'student'], function () use ($router) {
        $router->get('/courses', 'CourseController@index');
        $router->post('/courses/course/{id}', 'EnrollmentController@enroll');
        $router->post('/logout', 'AuthController@logout');
        $router->get('/profile', 'UserDetailsController@userProfile');
        $router->put('/profile-update/{id}', 'UserDetailsController@updateProfile');
        $router->get('/user-details', 'UserDetailsController@userDetails');
        $router->post('/user-details', 'UserDetailsController@store');
        $router->put('/user-details-update/{id}', 'UserDetailsController@update');
        $router->get('/my-course/{id}', 'CourseController@myCourse');
        $router->get('/my-course/thread/{id}', 'ThreadController@thread');
        $router->post('/my-course/thread/{id}/comment', 'CommentController@store');
        $router->delete('/my-course/thread/comment/{id}', 'CommentController@destroy');
        $router->get('/my-course/{id}', 'CourseController@myCourse');


    });

    $router->group(['middleware' => 'instructor', 'prefix' => 'instructor'], function () use ($router) {
        $router->post('/logout', 'AuthController@logout');
        $router->get('/profile', 'UserDetailsController@userProfile');
        $router->put('/profile-update', 'UserDetailsController@updateProfile');
        $router->get('/user-details', 'UserDetailsController@userDetails');
        $router->post('/user-details', 'UserDetailsController@store');
        $router->put('/user-details-update/{id}', 'UserDetailsController@update');
        $router->get('/my-courses', 'CourseController@myCourses');
        $router->post('/my-courses', 'CourseController@store');
        $router->get('/my-courses/my-course/{id}', 'CourseController@myCourse');
//        $router->get('/my-courses/my-course/{id}', 'ThreadController@myThreads');
        $router->post('/my-courses/my-course/{id}/add-thread', 'ThreadController@store');
        $router->get('/my-course/my-thread/{id}', 'ThreadController@thread');
        $router->delete('/my-thread/comment/{id}', 'CommentController@destroy');
        $router->delete('/my-course/my-thread/{id}', 'ThreadController@destroy');

    });
});