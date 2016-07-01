<?php

Flight::route('GET /', ['App\controllers\ViewsController', 'form']);
Flight::route('POST /', ['App\controllers\ViewsController', 'battle']);
Flight::route('/scores', ['App\controllers\ViewsController', 'scores']);

Flight::start();
