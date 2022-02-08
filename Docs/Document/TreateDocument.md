## Treat document  API
This API updates the status of a specific document. Internally it will
call `/internalAPI/documents/treat`. This API is used to validate/invalidate a document
by an agent. The Back-office application will send the `status=2`.

---
__Method__: POST.  
__URL__: `/api/v1/documents/{document_uid}/treat`.  
Request example:

```http request
POST {HOST_NAME}/api/v1/documents/1234abc456/treat
Accept: application/json 
Content-Type: application/json

{
  "agency_id": 1,
  "verification2_status": 2
  "folder_id": 2
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
```
> Obs: the agency_id, folder_id and administrator_id is needed for the event DOC_TRAITE.
