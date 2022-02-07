The image was generated on [https://www.websequencediagrams.com](https://www.websequencediagrams.com)
with this content

```
title BO - Folders list with view 2

FE BO->BE BO: /api/v1/folders
opt view_criteria empty
    BE BO->Monolith: internalAPI folders with logged administrateur_id
    Monolith->BE BO: assigned folders list
    alt assigned folders list
        BE BO->FE BO: folders list + meta: view_criteria: 2
    else empty assigned folders list
        BE BO->+Monolith: internalAPI folders without administrateur_id 
        Monolith->-BE BO: folders list
        BE BO->+Monolith: internalAPI assigned administrators
        Monolith->-BE BO: folders with assigned addministrators
        BE BO->FE BO: folders list + meta: view_criteria: 1
    end
end
opt view_criteria 1
    alt userId set 
        BE BO->Monolith: internalAPI folders with administrateur_id
        Monolith->BE BO: assigned folders list
        BE BO->FE BO: folders list + meta: view_criteria: 1
    else userId not set
        BE BO->+Monolith: internalAPI folder without administrateur_id
        Monolith->-BE BO: folders list
        BE BO->+Monolith: internalAPI assigned administrators
        Monolith->-BE BO: folders with assigned addministrators
        BE BO->FE BO: folders list + meta: view_criteria: 1
    end
end
opt view_criteria 2
    BE BO->+Monolith: internalAPI folders with logged administrateur_id
    Monolith->-BE BO: assigned folders lis
    BE BO->FE BO: folders list + meta: view_criteria: 2
end
```
