The image was generated on [https://www.websequencediagrams.com](https://www.websequencediagrams.com)
with this content

```
title BO - Folder details

FE BO->+BE BO: /api/v1/folders/{folderId}
BE BO->+Monolith: internalAPI get folder by id
alt Folder not found
    Monolith->BE BO: 404 - folder
    BE BO->FE BO: 404 - not found
else Folder found
    Monolith->-BE BO: 200 - folder
    opt IF workflowStatus = 10300
        BE BO->+Monolith: internalAPI assign folder to user
        Monolith->-BE BO: 204
    end
BE BO->-FE BO: 200 - folder
end
```
