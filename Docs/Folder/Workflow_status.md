## Workflow status

### Update workflow status
The application must be able to update the state of a folder by updating the workflow 
status property in the database. To update this field the application calls
`internalAPI/folders/updatestatusworkflow` API.

---
__Method__: POST  
__URL__: `/api/v1/folders/{folderId}/update-workflow-status`  
Request example:

```http request
POST {HOST_NAME}/api/v1/folders/1/update-workflow-status
Accept: application/json 
Content-Type: application/json 

{
  "workflowStatus": 10301
}

204 NO CONTENT
{}

400 BAD REQUEST
{
  "statusCode":400,
  "body":null,
  "error":"Bad request",
  "status":"error"
}

404 NOT FOUND
{
  "statusCode":404,
  "body":null,
  "error":"No folder found for folderId 1.",
  "status":"error"
}
```

### Workflow status history
This API will retrieve the workflow status history. Internally it will call
`internalAPI/workflowstatushistory/folder-id/{folderId}` API.

---
__Method__: GET  
__URL__: `/api/v1/folders/{folderId}/workflow-status-history`  
__Query params__:
- __administrator_id__ (int) - the administrator id, _optional_
- __workflow_status__ (array) - an array of workflow statuses to filter. If a range is needed set the `start` and
`end` keys. If only the `start` key is provided, the API will filter by workflow_status higher or equal with the 
  value provided. If only the `end` key is provided, the API will filter by workflow_status lower or equal with the
  value provided. 

Request example:

```http request
GET {HOST_NAME}/api/v1/folders/1/workflow-status-history?administratorId=1&workflow_status[start]=10300&workflow_status[end]=10399
Accept: application/json 
Content-Type: application/json

200 OK
{
  "workflow_status_history": [
    { 
        "workflow_status" : 10300,
        "folder_id" : 123,
        "created_at" : "2022-01-14 09:42:38.000000",
        "updated_at" : "2022-01-14 09:43:38.000000",
        "agent_id" : 2, 
        "administrator_id" : 1
    },
    ...
  ]
}

400 BAD REQUEST
{
  "statusCode":400,
  "body":null,
  "error":"Bad request",
  "status":"error"
}
```
