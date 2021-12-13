### Folder detail API
This API retrieves the details of a folder along with all the persons and the information
associated with them. Internally it will call `internalAPI/folders/getfolder/folder-id`
and `internalAPI/folders/persons/folder-id`.
> Note: when the folder is opened for the first time
> the workflowStatus property will be updated from 10300 to 10301 by calling `/internalAPI/folders/updatestatusworkflow`

__Method__: GET.  
__URL__: `/api/v1/folders/{folderId}`.  
Request example:

```http request
GET {HOST_NAME}/api/v1/folders/1

Accept: application/json 
Content-Type: application/json 

200 OK
{
  "id":1,
  "partnerFolderId":"2",
  "status":1,
  "workflowStatus":10300,
  "label":0,
  "subscription":10,
  "persons":[{
    "personId":1,
    "lastName":"jhon",
    "firstName":"doe",
    "dateOfBirth":"1970-01-01T00:00:00+00:00",
    "personTypeId":1,
    "personUid":"619b8418437b6",
    "personInfo":[
      {
        "nameInfo":"nom",
        "dataInfo":"john",
        "source":"IMPORT"
      },
      {
        "nameInfo":"email",
        "dataInfo":"john.doe@test.com",
        "source":"IMPORT"
      },
      ...
    ],
    "folderId":null
  }],
  "agencyId":1088
}

404 NOT FOUND
{
  "statusCode":404,
  "body":null,
  "error":"Folder with id 1072383444 not found",
  "status":"error"
}
```
#### Assign folder to user
This API will also assign the folder to the active user by calling the 
`/internalAPI/folders/assign` in [Monolith](../Monolith.md). The active user id can be retrieved
by decoding the [JWT token](../Authentification/Authentication.md#Decoding the JWT)

### Flow
![Folder details](../assets/Folder%20details.png)
