## Merge document  API
This API merges the content of the documents sent in the {documentIds} parameter. Internally it will
call `/internalAPI/documents/mergedocument`. This API is used to create a new document that will have the content
of all the documents to be merged (documents with ids {documentIds}). The new document status will be "UNTREATED"
(status=1) and verification status "NOT VERIFIED" (statutVerification2 = 0).

---
__Method__: POST.
__URL__: `/api/v1/folders/{folderId}/document/merge`.
Request example:

```http request
GET {HOST_NAME}/api/v1/folders/1/documents/merge
Accept: application/json
Content-Type: application/json

{
  "personUid": "617ff03bb7c55",
  "filename": "Fusion_25",
  "documentTypeId": 50,
  "documentIds": [37515,37516]
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
