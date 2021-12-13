### Get folder documents API
This API retrieves the list of all documents attached on a folder. Internally it will 
call `internalAPI/documents/getdocuments/folder-id/`.  
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
    "documentId": 12345,
    "documenStatus": 1,
    "personId": 123
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
### Get document by document Uid API
This API retrieves the details about a specific document. Internally it will
call `internalAPI/documents?document-uid={document-uid}`. This API can be used to fetch
the contests of a document by adding the `include_files` query param. The content is
base64 encoded.  
---
__Method__: GET.  
__URL__: `/api/v1/documents/{documentId}`.  
Request example:

```http request
GET {HOST_NAME}/api/v1/documents/{documentId}

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
  "customerAnomaly":null,
  "partnerVerificationStatus":null,
  "content":"JVBERi0xLjQKJeLjz9MK...",
  "signatureInfos":null,
  "orderDocument":22,
  "type":"Statuts",
  "mandatory":null
}

404 NOT FOUND
{
  "statusCode":404,
  "body":null,
  "error":"No document found for documentUid 133815.",
  "status":"error"
}
```
### Treat document  API
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

404 NOT FOUND
{
  "statusCode":404,
  "body":null,
  "error":"Resourse not found",
  "status":"error"
}
```
