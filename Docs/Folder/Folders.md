### Folders API
This API retrieves the list of folders. It can be filtered, and it's paginated. 
Internally it will call `internalAPI/folders` and pass along the query params.  
__Method__: GET.  
__URL__: `/api/v1/folders`.  
__Query params__:
- __page__ (int) - current page, default `0`
- __limit__ (int) - the number of entries per page, default `20`
- __filters__ - described in the [filter section](#filters)
- __text_search__ (string) - filter criteria. Eg `&text_search=Doe`.
- __text_search_fields__ -  a list of fields on which the `text_search` is applied. 
  The items in the list are separated by `,`. This works only in conjunction 
  with `text_search`. In this case the query will be executed with the `OR` operator.
  Eg `&text_search_fields=partner_folder_id,first_name`.
- __order_by__ - the order by column
- __order__ - the order `ASC`|`DESC`
- __view__ - the folders list [view](#views) 
- __view_criteria__ - the [view criteria](#view-criteria)

Request example:
```http request
GET {HOST_NAME}/api/v1/folders?page=0&limit=10&filters[user_id]=1&text_search=Doe&text_search_fields=partner_folder_id,first_name&order_by=created_at&order=DESC&view=1

Accept: application/json 
Content-Type: application/json 

200 OK
{
  "folders": [
    {
       "folder_id": 1,
       "folder": "TESTFOLDER",
       "first_name": "Jhon",
       "last_name": "Doe", 
       "date_of_birth": "1970-01-01T00:00:00+00:00"
       "subscription": 10
    },
    ...
  ],
  "meta": {
    "total": 3,
    "view_criteria": 1
  }
}
```
> Note: if in the filters query param we find `userId` we must remove it before sending it to the internalAPi 
#### Views
This parameter will set a specific set of filters on the request to the internalAPI 
from [Monolith](../Monolith.md). There are views that are specific to a user [role](../User/README.md#users-role)(eg.
view = 3). It can have these values:
- 1 - corresponds to the _to be treated_ tab. This will add `workflow_status = 10300` filter to the internalAPI request;
- 2 - corresponds to the _in treatment_ tab. This will add `workflow_status in [10301, 10302, 10303, 10304]` filter 
  to the internalAPI request.  
- 3 - corresponds to the _to be treated_ tab for **supervisor** user role. This will add `workflow_status = 10310` filter to the internalAPI request;
> Note: do not send this parameter to the internalAPI
#### View criteria
`view_criteria` can have two values:
- 1 - **all folders** - filter the list based on the given filters;
- 2 - **only my folders** - must be used in conjunction with `userId` send in the `filters` query param, if it's not present return an empty list; 
The user id must match the `userId` in the [JWT token](../Authentification/Authentication.md#decoding-the-jwt)

In order to determine the default view criteria for the active user the `view_criteria` query param should be
left empty and in the `filters` query param the _userId_ must be sent. In this case the 
API should get the assigned folders of the user, here we have the following cases:
- **if the user has assigned folders**: return the list of folder assigned to the user and also the view_criteria=2 in the meta property
- **if the user does not have assigned folders**: return the list of folders and the view_criteria=1 in the meta property
> Note: do not send this parameter to the internalAPI

#### Filters
The filters query params is an array that can accept folder fields for example `workflow_status, user_id, .etc...`.  
Eg: `filters['workflow_status']=10301&filters['user_id']=123`  
The same field can be passed multiple times. In this case the query will be executed with the `IN` operator. 
Eg: `filters['workflow_status'][]=10301&filters['workflow_status'][]=10302`.  
In order to achieve a between filter the keys `start` and `end` must be provided.  
Eg: `filters['workflow_status']['start']=10301&filters['workflow_status']['end']=10310`.  
The `start` and `end` keys can be used separately for a `higher or equal then` or `lower or equal then` filtering.  
Eg: `filters['workflow_status']['end']=10301` will return the folders list with `workflow_status` <= 10301.

### Flow
![Folders list with view = 2 flow](../assets/Folders%20list%20with%20view%202.png)  
