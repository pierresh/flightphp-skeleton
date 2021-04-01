# flightphp-skeleton for pure REST API

This project aims to provide a skeleton to build scalable RESTful APIs based on [FlightPhP](https://github.com/mikecao/flight) returning to the client only JSON objects.

## Getting started
1. Create the folder where the API will be located
2. Install **FlightPHP**, ideally through Composer
3. Put this skeleton in that folder (the file `index.php` and the folder `module` should put beside the folder `vendor`)

Then, logics about authorization/user rights/database connection should be defined in the file `index.php` (from line 18 to 49). In this skeleton, 3 variables would be created:
1. **$DB** is the database instance
2. **$o_user** is the object referring to the user connected
3. **$user_right** is an array referring to the various user right of the connected user. The index of array is the API / Page number used to distinguish user rights per route

In order to be easily scalable, the methods should be saved in files named and placed according to the route. Per example, let's consider the following route:

<pre><b>GET</b> [path_to_api]/<em>module</em>/items
│
└──> ./<em>module</em>/items_<b>get</b>.php
</pre>

The same logic can be applied to the other routes / methods, per example:

| Routes | File paths |
|---|---|
| GET [path_to_api]/module/items | ./module/items_get.php |
| GET [path_to_api]/module/items/1 | ./module/items_get.php |
| POST [path_to_api]/module/items | ./module/items_post.php |
| POST [path_to_api]/module/items/1/details | ./module/items_details_post.php |
| PUT [path_to_api]/module/items/1 | ./module/items_put.php |
| PATCH [path_to_api]/module/items/1 | ./module/items_patch.php |
| PATCH [path_to_api]/module/items/1,3,5 | ./module/items_patch.php |
| DELETE [path_to_api]/module/items/1 | ./module/items_delete.php |
| DELETE [path_to_api]/module/items/1,3,5 | ./module/items_delete.php |
| DELETE [path_to_api]/module/items/1/details/2 | ./module/items_details_delete.php |
| DELETE [path_to_api]/module/items/1/details/2,3,4 | ./module/items_details_delete.php |

The folder `module` should be duplicated and renamed according to your routes, as well as the files inside.

## Going further
Once the first APIs have been set up, it is possible to extend its capabilities by installing additional packages. I recommend the following 2:
1. Add email sending with PHPMailer (I recommend [this approach](https://github.com/mikecao/flight/issues/386#issuecomment-494993998))
2. Add [Monolog](https://github.com/Seldaek/monolog) for logging

### :heart: Like it? :heart:

:star: Star it! :star:
