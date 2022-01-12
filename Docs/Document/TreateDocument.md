## Treat document  API
This API updates the status of a specific document. Internally it will
call `/internalAPI/documents/treat`. This API is used to validate/invalidate a document
by an agent. The Back-office application will send the `status=2`.

---
__Method__: POST.  
__URL__: `/api/v1/documents/treat`.  
Request example:

```http request
GET {HOST_NAME}/api/v1/documents/treat

Accept: application/json 
Content-Type: application/json 

{
  "documentUid": "1234abc456",
  "statusVerification2": 2
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