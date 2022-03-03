### Agencies API
This API retrieves the list of agencies. It can be filtered, and sorted.
Internally it will call `internalAPI/agencies` and pass along the query params.  
__Method__: GET.  
__URL__: `/api/v1/agencies`.  
__Query params__:
- __filters__ - described in the [filter section](#filters)
- __order_by__ - (array) the order by column(optional)
- __order__ - the order `ASC`|`DESC`(optional)

Request example:
```http request
GET {HOST_NAME}/api/v1/agencies?filters['administrator_group_id']=1&order_by[]=name&order=ASC

Accept: application/json 
Content-Type: application/json 

200 OK
{
  "agencies": [
    {
      "agency_id": 1,
      "name": "Lorem Ipsum"
    },
    ...
  ]
}
```
#### Filters
The filters query params is an array that can accept fields from `agence`, `administrateur_affectation`, `user_dossier` tables[^1], for 
example `workflow_status, administrator_group_id, .etc...`.  
Eg: `filters['workflow_status'][]=10301&filters['administrator_group_id'][]=123`  
The same field can be passed multiple times. In this case the query will be executed with the `IN` operator.
Eg: `filters['workflow_status'][]=10301&filters['workflow_status'][]=10302`.  
In order to achieve a between filter the keys `start` and `end` must be provided.  
Eg: `filters['workflow_status']['start']=10301&filters['workflow_status']['end']=10310`.  
The `start` and `end` keys can be used separately for a `higher or equal then` or `lower or equal then` filtering.  
Eg: `filters['workflow_status']['end']=10301` will return the folders list with `workflow_status` <= 10301.


[^1]: The joins in the query should be made depending on the filters. So for example the join for 
`administrateur_affectation` is made only if `administrator_group_id` filter exists, for  `user_dossier` table is made 
only if `workflow_status` filter exists, etc...
