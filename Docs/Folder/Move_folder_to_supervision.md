## Move a folder to supervision
The application must be able to move a folder into supervision. Internally this API will update the workflow status to
[10310](./Workflow_status.md#workflow-status-codes) and save the reason by calling 
`/internalAPI/supervisionhistory/save`.   

---
__Method__: POST  
__URL__: `/api/v1/folders/{folder_id}/move_to_supervision`  
Request example:

```http request
POST {HOST_NAME}/api/v1/folders/1/move_to_supervision
Accept: application/json 
Content-Type: application/json 

{
  "reason": "Lorem ipsum"
}

201 Created
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
> Obs: reason is mandatory and 500 characters only.
