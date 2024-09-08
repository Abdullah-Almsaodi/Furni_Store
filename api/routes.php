<?php// routes.php
$router->get('/products', 'ProductController@getAllProducts');
$router->post('/products', 'ProductController@addProduct');


?>