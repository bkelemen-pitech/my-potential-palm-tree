### Agencies API
This API retrieves the list of agencies. It can be filtered, and sorted.
Internally it will call `internalAPI/agencies` and pass along the query params.  
__Method__: GET.  
__URL__: `/api/v1/agencies`.  
__Query params__:
- __filters__ - described in the [filter section](#filters)
- __order_by__ - (array) the order by column(optional)
- __order__ - the order `ASC`|`DESC`(optional)
- __view__ - described in the [view section](#views)

Request example:
```http request
GET {HOST_NAME}/api/v1/agencies?filters['administrator_group_id']=1&order_by[]=name&order=ASC&view=1

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
The filters query params is an array that can accept fields from `administrateur_affectation` and `user_dossier` tables
[^1], for 
example `administrator_group_id`.  
Eg: `filters['administrator_group_id'][]=123`  
The same field can be passed multiple times. In this case the query will be executed with the `IN` operator.
Eg: `filters['administrator_group_id'][]=1&filters['administrator_group_id'][]=2`.  
In order to achieve a between filter the keys `start` and `end` must be provided.  
Eg: `filters['workflow_status']['start']=10301&filters['workflow_status']['end']=10310`.  
The `start` and `end` keys can be used separately for a `higher or equal then` or `lower or equal then` filtering.  
Eg: `filters['workflow_status']['end']=10301` will return the folders list with `workflow_status` <= 10301.

#### Views
This parameter will set a specific set of filters on the request to the internalAPI to [Monolith](../Monolith.md). It 
can have these values:
- 0 - corresponds to the _to search_ tab. This will not add any filters;
- 1 - corresponds to the _to be treated_ tab. This will add `workflow_status = 10300` filter to the internalAPI request;
- 2 - corresponds to the _in treatment_ tab. This will add `workflow_status in [10301, 10302, 10303, 10304]` filter to the internalAPI request.
- 3 - corresponds to the _to be treated_ tab for **supervisor** user role. This will add `workflow_status = 10310` filter to the internalAPI request;
> Note: do not send this parameter to the internalAPI

[^1]: On [Monolith](../Monolith.md) the joins in the query are made depending on the filters. So for example the join for 
`administrateur_affectation` is made only if `administrator_group_id` filter exists, for  `user_dossier` table is made 
only if `workflow_status` filter exists, etc...
