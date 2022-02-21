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
__URL__: `/api/v1/documents/{document_uid}`.  
Request example:

```http request
GET {HOST_NAME}/api/v1/documents/619648c127658
Accept: application/json 
Content-Type: application/json 

200 OK
{
  "document_id":133809,
  "document_uid":"619648c127658",
  "master_documentId":0,
  "name":"Statuts entreprise (company articles)",
  "verification_status":0,
  "verification2_status":0,
  "status":1,
  "creation":"2021-11-18T13:36:17+00:00",
  "person_document_id":null,
  "document_type_id":51,
  "encryption":true,
  "content":"JVBERi0xLjQKJeLjz9MK...",
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
  "size": 81818
  "type":"Kbis",
  "content_verso":"JVBERi0xLjQKJc=...",
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
  "error":"No document found for document_uid 619648c127658.",
  "status":"error"
}
```
> Obs: the `data` property is dynamic, it's a serialized string and the application won't apply any formatting on this

### Document subtype
If the document type id is `1` or `80` the subtype is under `data.type_id` (this is an optional field).  

| Document subtype | Label | Sub document type id |
| ------------- | ------------- | ------------- |
| ID | Carte Nationale d’Identité | 1 |
| P | Passeport | 2 |
| DL | Permis de conduire | 3 |
| RP | Titre de séjour | 4 |
| V | Visa | 5 |
| BL | Permis bateau | 6 |
| E | Carte d'électeur | 7 |
| F | Livret de famille | 8 |

## Delete document
This API will soft delete a document. Internally it will
call `internalAPI/documents/deletebydocumentuid/document_uid/{document_uid}` API.

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
> Obs: the administrator_id is needed for the event SUPPRIME_DOCUMENT.

## Document data log
This API will retrieve the document data logs. Internally it will
call `internalAPI/documents/documentdatalogs` API.

---
__Method__: GET  
__URL__: `/api/v1/documents/document_data_logs`  
__Query params__:
- __document_ids__ (array) - the document ids, at least one id must be provided
- __administrator_id__ (int) - the administrator id, _optional_  

Request example:

```http request
GET {HOST_NAME}/api/v1/documents/document_data_logs?document_ids[]=1&document_ids[]=2&administrator_id=1
Accept: application/json 
Content-Type: application/json 

200 OK
{
  "document_data_logs": [
    { 
      "document_dat_log_id": 1,
      "created_at" : "2021-11-01T06:30:02+00:00",
      "document_id" : 2, 
      "administrator_id" : 1,
      "verification2_status" : 1,
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

## Update document type
This API will update a document type or subtype. Internally it will
call `internalAPI/documents/updatetype/document-uid/{document_uid}` API.

---
__Method__: PATCH.  
__URL__: `/api/v1/documents/{document_uid}`.  
Request example:

```http request
PATCH {HOST_NAME}/api/v1/documents/619648c127658
Accept: application/json 
Content-Type: application/json 

{ 
  "document_type_id": 1,
  "sub_document_type_id": 2
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
  "error":"No document found for document_uid 619648c127658.",
  "status":"error"
}
```
The `sub_document_type_id` is mandatory only for `document_type_id` 1 and 80.
