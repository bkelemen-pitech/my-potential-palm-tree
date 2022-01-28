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
__URL__: `/api/v1/documents/{documentUId}`.  
Request example:

```http request
GET {HOST_NAME}/api/v1/documents/619648c127658
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
  "error":"No document found for documentUid 619648c127658.",
  "status":"error"
}
```
## Delete document
This API will soft delete a document. Internally it will
call `internalAPI/documents/deletebydocumentuid/document-uid/{document-uid}` API.

---
__Method__: DELETE.  
__URL__: `/api/v1/documents/{document_uid}`.  
Request example:

```http request
DELETE {HOST_NAME}/api/v1/documents/619648c127658
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
  "error":"No document found for document_uid 619648c127658.",
  "status":"error"
}
```

## Document data log
This API will retrieve the document data logs. Internally it will
call `internalAPI/documents/documentdatalogs` API.

---
__Method__: GET  
__URL__: `/api/v1/documents/document-data-logs`  
__Query params__:
- __documentIds__ (array) - the document Ids, at least one id must be provided
- __administratorId__ (int) - the administrator id, _optional_  

Request example:

```http request
GET {HOST_NAME}/api/v1/documents/document-data-logs?documentIds[]=1&documentIds[]=2&administratorId=1
Accept: application/json 
Content-Type: application/json 

200 OK
{
  "documentDataLogs": [
    { 
      "createdAt" : "2052-01-14 09:42:38.000000",
      "documentId" : 2, 
      "administratorId" : 1,
      "verification2Status" : 1,
      "data": {
        "agence_document_type": "1_T00022",
        "date_delivrance": "01/02/2009",
        "nom": "JOHN",
        "prenom": "Doe",
        "date_naissance": "01/01/2021",
        "lieu_naissance": "CITY",
        "mrz1": "XXXXX",
        "mrz2": "XXXXX",
        "mrz3": NULL,
        "nationalite": "FRA",
        "pays_emetteur": "FRA",
        "autorite_emettrice": "PREFECTURE DE POLICE - PARIS",
        "type_id": "ID",
        "sexe": "M",
        "numero": "090999999999",
        "verso": true,
        "controle_couleur": 1,
        "expirationdate": "01/01/2031",
      }
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
> Obs: the `data` property is dynamic, it's a serialized string and the application won't apply any formatting on this
