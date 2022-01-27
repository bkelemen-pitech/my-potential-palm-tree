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
`internalAPI/workflowstatushistory?user_dossier_id={folderId}` API.

---
__Method__: GET  
__URL__: `/api/v1/folders/{folderId}/workflow-status-history`  
__Query params__:
- __administrator-id__ (int) - the administrator id, _optional_    

Request example:

```http request
GET {HOST_NAME}/api/v1/folders/1/workflow-status-history?administrator-id=1
Accept: application/json 
Content-Type: application/json

200 OK
{
  "workflowStatusHistory": [
    { 
        "workflowStatus" : 10000,
        "folderId" : 123,
        "createdAt" : "2021-11-18T13:36:17+00:00",
        "updatedAt" : "2021-11-18T13:36:17+00:00",
        "agentId" : 2, 
        "administratorId" : 1
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
