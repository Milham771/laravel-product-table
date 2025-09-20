<?php

return array (
  'cors' => 
  array (
    'allowed_origins' => 
    array (
      0 => 'http://localhost:3000',
      1 => 'http://localhost:8080',
      2 => 'http://127.0.0.1:3000',
      3 => 'http://127.0.0.1:8080',
      4 => 'http://localhost:3001',
    ),
    'allowed_methods' => 
    array (
      0 => 'GET',
      1 => 'POST',
      2 => 'PUT',
      3 => 'PATCH',
      4 => 'DELETE',
      5 => 'OPTIONS',
    ),
    'allowed_headers' => 
    array (
      0 => 'Content-Type',
      1 => 'Authorization',
      2 => 'X-Requested-With',
      3 => 'Accept',
      4 => 'Origin',
      5 => 'X-CSRF-TOKEN',
    ),
    'exposed_headers' => 
    array (
    ),
    'max_age' => 0,
    'supports_credentials' => false,
  ),
);
