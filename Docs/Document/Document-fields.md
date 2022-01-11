### Document fields API
This API retrieves the fields of a specific document type and person type. 
Internally it will call `internalAPI/documents/fields`. 
> Note: When calling the internalAPI, BO should also pass along the 
> agency_id in the request. The agency_id can be retrieved from the [JWT token](../Authentification/Authentication.md#decoding-the-jwt)

__Method__: GET.  
__URL__: `/api/v1/documents/fields`.  
__Query params__:
- __document_type_id__ (int) 
- __person_type_id__ (int)   

Request example:

```http request
GET {HOST_NAME}/api/v1/document/fields?document_type_id=1&person_type_id=2
Accept: application/json 
Content-Type: application/json 

200 OK
{
  "fields":{[
    { 
      "db_field_name" : "nom",
      "label" : "Nom",
      "mandatory' : 1,
      "order" : 1,
      "format" : "text",
      "helper_method" : "getCommonNom",
      "ocr_field" : 3,
      "validator_method" : "validateFormData",
    },
    ...
   ]}
}

400 Bad REQUEST
{
  "statusCode":400,
  "body":null,
  "error":"Invalid document type id or person typ id",
  "status":"error"
}

404 NOT FOUND
{
  "statusCode":404,
  "body":null,
  "error":"Resourse not found for document type id or person type id",
  "status":"error"
}
```
#### Internal API response
The response from internalAPI needs to be mapped according to this table

| InteralAPI  | Bundle | Obs |
| ------------- | ------------- | ------------- |
| nom_info  | db_field_name  |  |
| libelle  | label  |  |
| obligatoir  | mandatory  | Possible values 1 for mandatory, 2 for optional |
| order  | order  | The order in which the fields need to be displayed |
| format  | format  | The values from the internalAPI should be translated to english. Eg: "Texte" -> "text"  |
| valeur_helper  | helper_method  |  |
| champs_ocr  | ocr_field  |  |
| validateur  | validator_method  |  |
