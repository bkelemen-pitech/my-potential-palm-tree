### Folders API
This API retrieves the list of folders. It can be filtered, and it's paginated. 
Internally it will call `internalAPI/folders` and pass along the query params.  
__Method__: GET.  
__URL__: `/api/v1/folders`.  
__Query params__:
- __offset__ (int) - current page, default `0`
- __limit__ (int) - the number of entries per page, default `20`
- __filters__ - the filters list, key-value pairs separated by ':' and ','. The same 
  field can be sent more than once. In this case the query will be executed with the
  `IN` operator. Eg: `&filters=workflow_status:10400,workflow_status:10402,statut=1`
- __text_search__ (string) - filter criteria. Eg `&text_search=Doe`.
- __text_search_fields__ -  a list of fields on which the `text_search` is applied. 
  The items in the list are separated by `,`. This works only in conjunction 
  with `text_search`. In this case the query will be executed with the `OR` operator.
  Eg `&text_search_fields=nom,prenom`.
- __order_by__ - the order by column
- __order__ - the order `ASC`|`DESC`

Request example:
```http request
GET {HOST_NAME}/api/v1/folders?offset=0&limit=10&filters=workflow_status:10400,workflow_status:10402&text_search=Doe&text_search_fields=nom,prenom&order_by=creation&order=DESC

Accept: application/json 
Content-Type: application/json 

{
  "folders": [
    {
       "folderId": 1,
       "folder": "TESTFOLDER",
       "name": "Jhon",
       "surname": "Doe", 
    },
    ...
  ],
  "meta": {
    "total": 3
  }
}
```

