### Document fields API
This API retrieves the fields of a specific document type and person type. 
Internally it will call `internalAPI/documents/fields`. 

__Method__: GET.  
__URL__: `/api/v1/documents/fields`.  
__Query params__:
- __document_type_id__ (int) 
- __person_type_id__ (int)   
- __agency_id__ (int)

Request example:

```http request
GET {HOST_NAME}/api/v1/documents/fields?document_type_id=1&person_type_id=2&agency_id=1
Accept: application/json 
Content-Type: application/json 

200 OK
{
  "fields":{[
    { 
      "db_field_name": "nom",
      "label": "Nom",
      "mandatory": 1,
      "order": 1,
      "format": 1,
      "ocr_field": 1,
      "values" : null,
      "validator_method": "validateFormData",
    },
    { 
      "db_field_name": "nom",
      "label": "Nom",
      "mandatory": 1,
      "order": 1,
      "format": 2,
      "ocr_field": 1,
      "values" : { 
        "key1": "value1",
        "key2": "value2",
        ...
      },
      "validator_method": "validateFormData",
    }
    ...
   ]}
}

400 Bad REQUEST
{
  "statusCode":400,
  "body":null,
  "error":"Invalid document type id or person type id",
  "status":"error"
}
```
#### Internal API response
The response from internalAPI needs to be mapped according to this table

| InternalAPI  | Bundle | Obs |
| ------------- | ------------- | ------------- |
| nom_info  | db_field_name  | Database field name |
| libelle  | label  |  |
| obligatoir  | mandatory  | Possible values: 1 for mandatory, 2 for optional |
| ordre  | order  | The order in which the fields need to be displayed |
| format  | format  | Input type |
| champs_ocr  | ocr_field  |  |
| values  | values  | The values from dropdowns or radio boxes |
| validateur  | validator_method  |  |
