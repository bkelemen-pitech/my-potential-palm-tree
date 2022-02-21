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
  "partner_folder_id":"2",
  "status":1,
  "workflow_status":10300,
  "label":0,
  "subscription":10,
  "persons":[{
    "person_id":1,
    "last_name":"jhon",
    "first_name":"doe",
    "date_of_birth":"1970-01-01T00:00:00+00:00",
    "person_type_id":1,
    "person_uid":"619b8418437b6",
    "person_info":[
      {
        "name_info":"nom",
        "data_info":"john",
        "source":"IMPORT"
      },
      {
        "name_info":"email",
        "data_info":"john.doe@test.com",
        "source":"IMPORT"
      },
      ...
    ],
    "folder_id": 123
  }],
  "agency_id":1088
}

404 NOT FOUND
{
  "statusCode":404,
  "body":null,
  "error":"Folder with id 1 not found",
  "status":"error"
}
```
### Flow
![Folder details](../assets/Folder%20details.png)
