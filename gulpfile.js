var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
    mix.sass('app.scss')
        .styles(
           [
               'bootstrap/bootstrap.min.css',
               'bootstrap/bootstrap-theme.min.css'
           ],
           'public/css/bootstrap.min.css'
        )
        .styles(
            [
                'login/login.css'
            ],
            'public/css/login.css'
        )
});

elixir(function(mix) {
    mix.scripts(
            [
                'bootstrap/bootstrap.min.js'
            ],
            'public/js/bootstrap.min.js'
        )
        .scripts(
            [
                'login.js'
            ],
            'public/js/login.js'
        )
        .scripts(
            [
                'jquery.js'
            ],
            'public/js/jquery.js'
        )
});