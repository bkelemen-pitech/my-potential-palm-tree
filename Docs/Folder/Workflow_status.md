## Workflow status
The application must be able to update the state of a folder by updating the workflow 
status property in the database. To update this field the application calls
`internalAPI/folders/updatestatusworkflow` API.

---
__Method__: POST.  
__URL__: `/api/v1/folders/{folderId}/update-workflow-status`.  
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
