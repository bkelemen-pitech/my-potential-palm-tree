### Assign folder 
This API will assign a folder to the logged-in user by calling the
`/internalAPI/folders/assign` in [Monolith](../Monolith.md). The logged-in user id can be retrieved
by decoding the [JWT token](../Authentification/Authentication.md#decoding-the-jwt).

__Method__: POST.  
__URL__: `/api/v1/folders/{folderId}/assign`.  
Request example:

```http request
GET {HOST_NAME}/api/v1/folders/1/assign

Accept: application/json 
Content-Type: application/json 

204 OK
{}

404 NOT FOUND
{
  "statusCode":404,
  "body":null,
  "error":"Folder with id 1 not found",
  "status":"error"
}
```
