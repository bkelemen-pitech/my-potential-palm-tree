### Folders API
This API retrieves the list of folders. It can be filtered, and it's paginated. 
Internally it will call `internalAPI/folders` and pass along the query params.  
__Method__: GET.  
__URL__: `/api/v1/folders`.  
__Query params__:
- __page__ (int) - current page, default `0`
- __limit__ (int) - the number of entries per page, default `20`
- __filters__ - the filters list, key-value pairs separated by ':' and ','. The same 
  field can be sent more than once. In this case the query will be executed with the
  `IN` operator. Eg: `&filters=statut_workflow:10300,userId:1`
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
GET {HOST_NAME}/api/v1/folders?page=0&limit=10&filters=userId:1&text_search=Doe&text_search_fields=partner_folder_id,first_name&order_by=created_at&order=DESC&view=1

Accept: application/json 
Content-Type: application/json 

200 OK
{
  "folders": [
    {
       "folderId": 1,
       "folder": "TESTFOLDER",
       "firstName": "Jhon",
       "lastName": "Doe", 
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
from [Monolith](../Monolith.md). It can have these values:
- 1 - corresponds to the _to be treated_ tab. This will add `statut_workflow = 10300` filter to the internalAPI request;
- 2 - corresponds to the _in treatment_ tab. This will add `statut_workflow in [10301, 10302, 10303, 10304]` filter to the internalAPI request.
If there is a _userId_ in the `filters` query params, we need to call the `/internalAPI/administrators/assignedfolders/administrator-id/{administrator-id}` 
and filter the [folders list](#folders-api) with the folderIds [assigned](./Details.md#assign-folder-to-user) to the user.
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
### Flow
![Folders list with view = 2 flow](../assets/Folders%20list%20with%20view%202.png)  
