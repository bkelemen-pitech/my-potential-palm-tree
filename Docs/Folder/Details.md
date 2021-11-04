### Folder detail API
This API retrieves the details of a folder along with all the persons and the information
associated with them. Internally it will call `internalAPI/folders/getfolder/folder-id`
and `internalAPI/folders/persons/folder-id`
__Method__: GET.  
__URL__: `/api/v1/folders/{folderId}`.  
Request example:

```http request
GET {HOST_NAME}/api/v1/folders/1

Accept: application/json 
Content-Type: application/json 

{
    "id":104090,
    "partenaire_dossier_id":"KYCTestPB2B_F_131021011",
    "statut":1,
    "statut_workflow":10401,
    "label":5,
    "abonnement": 20
    "persons":[
        {
            "last_name":null,
            "first_name":null,
            "date_of_birth":null,
            "person_type_id":1,
            "person_uid":"6167c77066952",
            "person_info":[
                {
                    "name_info":"nom",
                    "data_info":"JOHN",
                    "source":"IMPORT"
                },
                {
                    "name_info":"dossier",
                    "data_info":"KYCTestPB2B_F_131021011",
                    "source":"PARCOURS"
                },
                {
                    "name_info":"prenom",
                    "data_info":"DOE",
                    "source":"PARCOURS"
                },
                ...
            ],
            "folder_id":null
        }
    ],
    ...
}
```
