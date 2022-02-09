## Get folder documents API  
This API retrieves the list of all documents attached on a folder. Internally it will 
call `internalAPI/documents/getdocuments/folder-id/`. 
### Linked documents
If in the response from the internalAPI we receive a document that has set a `master_document_id`
we need to merge this document to the document that has that `document_id` so we should have
only one entry per linked documents.

---
__Method__: GET.  
__URL__: `/api/v1/folders/{folderId}/documents`.  
Request example:

```http request
GET {HOST_NAME}/api/v1/folders/1/documents

Accept: application/json 
Content-Type: application/json 

200 OK
[
  {
    "name": "John",
    "type": 51,
    "status": "pending",
    "uid": "5c3479c5eec12",
    "document_id": 12345,
    "documen_status": 1,
    "person_id": 123
  },
  ...
]

404 NOT FOUND
{
  "statusCode": 404,
  "body": "Resource not found.",
  "error": "Resource not found.",
  "status": "error"
}
```
