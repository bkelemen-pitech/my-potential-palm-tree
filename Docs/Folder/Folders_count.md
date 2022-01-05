### Folders Count API
This API retrieves the folders count for each view.
Internally it will call `internalAPI/folders/count`.  
__Method__: GET.  
__URL__: `/api/v1/folders/count`.

Request example:
```http request
GET {HOST_NAME}/api/v1/folders/count

Accept: application/json 
Content-Type: application/json 

200 OK
{
  "folders": [
    {
       "view": 1,
       "total": 10 
    },
    {
       "view": 2,
       "total": 0 
    },
    ...
  ]
}
```
