## Get document by document Uid API
This API retrieves the details about a specific document. Internally it will
call `internalAPI/documents?document-uid={document-uid}`. This API can be used to fetch
the contents of a document by adding the `include_files` query param. The content is
base64 encoded.  
Each type of document can have a specific set of fields that can be retrieved with the
[document fields API](../Document/Document-fields.md).
### Linked documents
If there is a [linked document](./Folder-documents.md#linked-documents) the API need to 
return the content of the linked document also in the `contentVerso` property.

---
__Method__: GET.  
__URL__: `/api/v1/documents/{documentId}`.  
Request example:

```http request
GET {HOST_NAME}/api/v1/documents/{documentUid}

Accept: application/json 
Content-Type: application/json 

200 OK
{
  "documentId":133809,
  "documentUid":"619648c127658",
  "masterDocumentId":0,
  "name":"Statuts entreprise (company articles)",
  "statusVerification":0,
  "statusVerification2":0,
  "status":1,
  "creation":"2021-11-18T13:36:17+00:00",
  "personDocumentId":null,
  "documentTypeId":53,
  "encryption":true,
  "content":"JVBERi0xLjQKJeLjz9MK...",
  "type":"Statuts",
  "contentVerso":"JVBERi0xLjQKJc=",
}

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
  "error":"No document found for documentUid 133815.",
  "status":"error"
}
```
## Delete document
This API will soft delete a document. Internally it will
call `internalAPI/documents/delete/document-id/{documentId}` API.

---
__Method__: DELETE.  
__URL__: `/api/v1/documents/{documentId}`.  
Request example:

```http request
GET {HOST_NAME}/api/v1/documents/{documentUid}

Accept: application/json 
Content-Type: application/json 

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
  "error":"No document found for documentUid 133815.",
  "status":"error"
}
```
