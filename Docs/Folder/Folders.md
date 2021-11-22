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
  `IN` operator. Eg: `&filters=status_verification2:10400,status_verification2:10402,status=1`
- __text_search__ (string) - filter criteria. Eg `&text_search=Doe`.
- __text_search_fields__ -  a list of fields on which the `text_search` is applied. 
  The items in the list are separated by `,`. This works only in conjunction 
  with `text_search`. In this case the query will be executed with the `OR` operator.
  Eg `&text_search_fields=partner_folder_id,first_name`.
- __order_by__ - the order by column
- __order__ - the order `ASC`|`DESC`

Request example:
```http request
GET {HOST_NAME}/api/v1/folders?page=0&limit=10&filters=status_verification2:10400,status_verification2:10402,status=1&text_search=Doe&text_search_fields=partner_folder_id,first_name&order_by=created_at&order=DESC

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
    "total": 3
  }
}
```

