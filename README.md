# Silex-path2query

Silex service provider that adds query vars to Request from path.

Installation
------------

    composer require pmaxs/silex-path2query "^1.0" # for silex v1.x
    composer require pmaxs/silex-path2query "^2.0" # for silex v2.x

Usage
-----

Provedier appends parameter to all routes. Than this parameter is parsed and all vars are added to Request query.

Ex.:  
route: /blogs/{year}/  
url: /blogs/2015/order/rating/page/5  
query vars: $request->get('order') - 'rating', $request->get('page') - 5  


```php
$app->register(new \Pmaxs\Silex\Path2Query\Provider\Path2QueryServiceProvider(), []);
```
