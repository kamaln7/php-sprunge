<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Simply tell Laravel the HTTP verbs and URIs it should respond to. It is a
| breeze to setup your application using Laravel's RESTful routing and it
| is perfectly suited for building large applications and simple APIs.
|
| Let's respond to a simple GET request to http://example.com/hello:
|
|		Route::get('hello', function()
|		{
|			return 'Hello World!';
|		});
|
| You can even respond to more than one URI:
|
|		Route::post(array('hello', 'world'), function()
|		{
|			return 'Hello World!';
|		});
|
| It's easy to allow URI wildcards using (:num) or (:any):
|
|		Route::put('hello/(:any)', function($name)
|		{
|			return "Welcome, $name.";
|		});
|
*/

Route::get('/', function()
{
	return "
<style> a { text-decoration: none } </style>
<pre>
sprunge(1)                          SPRUNGE                          sprunge(1)

NAME
    sprunge: command line pastebin.

SYNOPSIS
    &lt;command&gt; | curl -F 'sprunge=&lt;-' " . URL::full() . "
    use <a href='data:text/html,<form action=\"" . URL::full() . "\" method=\"POST\"><textarea name=\"sprunge\" cols=\"80\" rows=\"24\"></textarea><br><button type=\"submit\">sprunge</button></form>'>this form</a> to paste from a browser

DESCRIPTION
    -

EXAMPLES
    ~$ cat bin/ching | curl -F 'sprunge=&lt;-' " . URL::full() . "
       " . URL::to_route('sprunge', ['d41d8cd98f00b204e9800998ecf8427e']) . "
    ~$ firefox " . URL::to_route('sprunge', ['d41d8cd98f00b204e9800998ecf8427e']) . "

SEE ALSO
    http://github.com/KamalN7/php-sprunge

</pre>
    ";
});

Route::get('/(:any)', ['as' => 'sprunge', function($sprunge) {
    $content = Sprunge::where_hash($sprunge)->first();

    if ($content) {
        return 'Not found.';
    } else {
        return $content->content;
    }
}]);

Route::post('/', function() {
    $sprunge = Input::get('sprunge');
   if (!empty($sprunge)) {
       $hash = md5(uniqid());

       $sprunge = new Sprunge;
       $sprunge->hash = $hash;
       $sprunge->content($sprunge);

       if ($sprunge->save()) {
           return URL::to_route('sprunge', [$hash]);
       } else {
           return 'Error.';
       }
   } else {
       return Redirect::to('/');
   }
});

/*
|--------------------------------------------------------------------------
| Application 404 & 500 Error Handlers
|--------------------------------------------------------------------------
|
| To centralize and simplify 404 handling, Laravel uses an awesome event
| system to retrieve the response. Feel free to modify this function to
| your tastes and the needs of your application.
|
| Similarly, we use an event to handle the display of 500 level errors
| within the application. These errors are fired when there is an
| uncaught exception thrown in the application.
|
*/

Event::listen('404', function()
{
	return Response::error('404');
});

Event::listen('500', function()
{
	return Response::error('500');
});

/*
|--------------------------------------------------------------------------
| Route Filters
|--------------------------------------------------------------------------
|
| Filters provide a convenient method for attaching functionality to your
| routes. The built-in before and after filters are called before and
| after every request to your application, and you may even create
| other filters that can be attached to individual routes.
|
| Let's walk through an example...
|
| First, define a filter:
|
|		Route::filter('filter', function()
|		{
|			return 'Filtered!';
|		});
|
| Next, attach the filter to a route:
|
|		Router::register('GET /', array('before' => 'filter', function()
|		{
|			return 'Hello World!';
|		}));
|
*/

Route::filter('before', function()
{
	// Do stuff before every request to your application...
});

Route::filter('after', function($response)
{
	// Do stuff after every request to your application...
});

Route::filter('csrf', function()
{
	if (Request::forged()) return Response::error('500');
});

Route::filter('auth', function()
{
	if (Auth::guest()) return Redirect::to('login');
});