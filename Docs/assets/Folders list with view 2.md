The image was generated on [https://www.websequencediagrams.com](https://www.websequencediagrams.com)
with this content

```
title BO - Folders list with view 2

FE BO->BE BO: /api/v1/folders
opt view_criteria empty
    BE BO->Monolith: internalAPI assigned folders
    Monolith->BE BO: assigned folders list
    alt assigned folders list
        BE BO->+Monolith: internalAPI folder lists filtered by assigned to user
        Monolith->-BE BO: folders list
        BE BO->FE BO: folders list + meta: view_criteria: 2
    else empty assigned folders list
        BE BO->+Monolith: internalAPI folder lists 
        Monolith->-BE BO: folders list
        BE BO->FE BO: folders list + meta: view_criteria: 1
    end
end
opt view_criteria 1
    alt userId set 
        BE BO->Monolith: internalAPI assigned folders by userId
        Monolith->BE BO: assigned folders list
        BE BO->+Monolith: internalAPI folder lists filtered by assigned to userId
        Monolith->-BE BO: folders list
        BE BO->FE BO: folders list
    else userId not set
        BE BO->+Monolith: internalAPI folder lists 
        Monolith->-BE BO: folders list
        BE BO->FE BO: folders list
    end
end
opt view_criteria 2
    alt userId set 
        BE BO->+Monolith: internalAPI assigned folders by userId
        Monolith->-BE BO: assigned folders list
        BE BO->+Monolith: internalAPI folder lists filtered by assigned to userId
        Monolith->-BE BO: folders list
        BE BO->FE BO: folders list
    else userId not set
        BE BO->FE BO: empty folders list
    end
end
```