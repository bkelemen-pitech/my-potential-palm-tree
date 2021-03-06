## Workflow status

### Update workflow status
The application must be able to update the state of a folder by updating the workflow 
status property in the database. To update this field the application calls
`internalAPI/folders/updatestatusworkflow` API.

---
__Method__: POST  
__URL__: `/api/v1/folders/{folderId}/update_workflow_status`  
Request example:

```http request
POST {HOST_NAME}/api/v1/folders/1/update_workflow_status
Accept: application/json 
Content-Type: application/json 

{
  "workflow_status": 10301
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
__URL__: `/api/v1/folders/{folderId}/workflow_status_history`  
__Query params__:
- __administrator_id__ (int) - the administrator id, _optional_
- __workflow_status__ (array) - an array of workflow statuses to filter. If a range is needed set the `start` and
`end` keys. If only the `start` key is provided, the API will filter by workflow_status higher or equal with the 
  value provided. If only the `end` key is provided, the API will filter by workflow_status lower or equal with the
  value provided. 

Request example:

```http request
GET {HOST_NAME}/api/v1/folders/1/workflow_status_history?administrator_id=1&workflow_status[start]=10300&workflow_status[end]=10399
Accept: application/json 
Content-Type: application/json

200 OK
{
  "workflow_status_history": [
    { 
      "history_workflow_status_id" : 1,
      "workflow_status" : 10300,
      "folder_id" : 123,
      "created_at" : "2021-11-18T13:36:17+00:00",
      "updated_at" : "2021-11-18T13:36:17+00:00",
      "agent_id" : 2, 
      "administrator_id" : 1,
      "reason": null
    },
    { 
      "history_workflow_status_id" : 2,
      "workflow_status" : 10310,
      "folder_id" : 123,
      "created_at" : "2021-11-18T13:37:17+00:00",
      "updated_at" : "2021-11-18T13:37:17+00:00",
      "agent_id" : 2, 
      "administrator_id" : 1,
      "reason": "Lorem Ipsum..."
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
> Obs: `reason` property is fetched by joining historique_statut_workflow table with historique_supervision table.  

### Workflow status codes
Bellow you can find workflow status relevant to the Back office application. 

| Workflow status code | Label |
| ------------- | ------------- |
| 10300 | To treat |
| 10301 | In treatment - 1. Documents |
| 10302 | In treatment - 2. Cross-checking |
| 10303 | In treatment - 3. Data |
| 10304 | In treatment - 4. Label |
| 10310 | Sent to supervision |
| 10311 | In supervision - 1. Documents |
| 10312 | In supervision - 2. Cross-checking |
| 10313 | In supervision - 3. Data |
| 10314 | In supervision - 4. Label |
| 10350 | Treated folder |
