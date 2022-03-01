## OCR
The [Monolith](../Monolith.md) application uses an OCR system to validate and extract data from the uploaded documents.
### Get OCR data for document
This API will retrieve the data extracted from the OCR for a specific document uid and document type id. Internally it 
will call `internalAPI/documents/getocrdata/document-uid/{document_uid}/document-type-id/{document_type_id}`. 

---
__Method__: GET.  
__URL__: `/api/v1/documents/{document_uid}/ocr_data`.  
__Query params__:
- __document_type_id__ (int) - mandatory
  
Request example:

```http request
GET {HOST_NAME}/api/v1/documents/619648c127658/ocr_data?document_type_id=1
Accept: application/json 
Content-Type: application/json 

200 OK
{
  "1": "Lorem ipsum",
  "3": "Lorem ipsum",
  "11": "",
  ...
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
  "error":"No document found for document_uid 619648c127658 with document_type_id 1.",
  "status":"error"
}
```
### Recalculate global status for document
This API will recalculate global status for the document with the specified data. Internally it
will call `internalAPI/documents/recalculateglobalstatus/document-uid/{document_uid}`.

---
__Method__: GET.  
__URL__: `/api/v1/documents/{document_uid}/recalculate_global_status`.  
  
Request example:

```http request
GET {HOST_NAME}/api/v1/documents/619648c127658/recalculate_global_status
Accept: application/json 
Content-Type: application/json 

{
  "date_naissance": "02/02/2022",
  "nom": "John",
  ...
}
200 OK
{
  "status" => 2
  "must_validate" => 1
  "must_validate_messages": 
    [
      "mrz": [
        "0": "Erreur : pas de date expiration"
      ],
      "0": "OK : Le nom inscription a été contrôlé",
      "date_naissance": {[
        "0": "Erreur : ALERTE MINEUR : ddn document = 04/05/2004 > limite 18a (ou max acceptable) = 28/02/2004",
        "1": "Erreur : date naissance document <>  inscription : 04/05/2004 <> 05/05/2020"
      ]},
      "1": "INFO: nom document = nom  SI : Dumortier = Dumortier, Nom document vérifié avec nom inscription (patronimique)",
      "errors": {[
         "0": "Erreur : prenom  document trop long (>15 caractères) : iuliana-alexandra",
         "1": "Erreur : prenom  document <> prenom  inscription : iuliana-alexandra <> test prenom | prenom1",
         ...
      ]}
    ]
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
