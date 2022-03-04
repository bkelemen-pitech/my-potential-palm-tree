The image was generated on [https://www.websequencediagrams.com](https://www.websequencediagrams.com)
with this content

```
title BO - Folder details

FE BO->+BE BO: Get folder by Id
BE BO->+Monolith: InternalAPI get folder by id
alt Error - folder not found
    Monolith->BE BO: 404
    BE BO->FE BO: 404 - Not found
else Folder found
    Monolith->-BE BO: 200
    BE BO->BE BO: Check permissions
    alt Error - forbidden
        BE BO->FE BO: 403 - Forbidden
    else OK
        BE BO->-FE BO: 200 - Folder
        opt IF workflowStatus = 10300/10310
            FE BO->+BE BO: Assign folder to logged user
            BE BO->+Monolith: InternalAPI assign folder to user
            alt Error
                Monolith->BE BO: 404
                BE BO->FE BO: 404 - Failed to assign folder to user
            else Success
                Monolith->-BE BO: 204
                BE BO->-FE BO: 204
                FE BO->FE BO: Compute new workflowStatus
                FE BO->+BE BO: Update folder workflowStatus
                BE BO->+Monolith: internalAPI update folder workflowStatus
                Monolith->-BE BO: 204
                BE BO->-FE BO: 204
            end
        end
    end
end
```
