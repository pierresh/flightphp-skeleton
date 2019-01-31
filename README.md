# flightphp-skeleton for pure REST API

This project aims to provide a skeleton for REST API returning to client only JSON based on FlightPhP.

## Getting started
1. Create the folder where the API will be located
2. Install FlightPHP, ideally through Composer
3. Put this skeleton in that folder (beside the folder vendor)


Then, the logics about authorization/user rights/database connection should be defined in the file index.php (lines 18 to 49). In this skeleton, 3 variables should be created
1. $DB is the database instance 
2. $o_user is the object refering to the user connected
3. $user_right is an array refering to the various user right of the user connected. The index of array is the API / Page number used to distinguish user rights per route

In order to be easily scalable, the methods should be saved in files named and placed according to the route. Per example, let's consider the following routes:

| Routes | Path the file |
|---|---|
| GET [path_to_api]/module/items | module/items_get.php |
| GET [path_to_api]/module/items/1 | module/items_get.php |
| POST [path_to_api]/module/items | module/items_post.php |
| POST [path_to_api]/module/items/1/details | module/items_details_post.php |
| PUT [path_to_api]/module/items/1 | module/items_put.php |
| DELETE [path_to_api]/module/items/1 | module/items_delete.php |
| DELETE [path_to_api]/module/items/1/details/2 | module/items_details_delete.php |
