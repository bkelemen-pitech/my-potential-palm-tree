### Assign folder 
This API will assign a folder to the logged-in user by calling the
`/internalAPI/folders/assign` in [Monolith](../Monolith.md). The logged-in user id can be retrieved
by decoding the [JWT token](../Authentification/Authentication.md#decoding-the-jwt).

__Method__: POST.  
__URL__: `/api/v1/folders/{folder_id}/assign`.  
Request example:

```http request
GET {HOST_NAME}/api/v1/folders/1/assign

Accept: application/json 
Content-Type: application/json 

204 NO CONTENT
{}

404 NOT FOUND
{
  "statusCode":404,
  "body":null,
  "error":"Folder with id 1 not found",
  "status":"error"
}
```
From a technical point of view when a folder is assigned to a user the following actions are executed:
- the `user_dossier.treatment_status` is updated to `11`; 
- if there is an entry in `administrateur_affectation` for the specified folder id(user_dossier_id column), the 
  entry is updated, else a new entry is created;
- the`user_dossier.statut_workflow` is updated to [10301](Workflow_status.md#workflow-status-codes) and a log entry is 
  added in `historique_statut_workflow`;
- the events set at the agency level are triggered;  

From a functional point of view when a folder is assigned to a user, the folder is listed in the
 [_to be treated_ list (view=2)](Folders.md#views)
