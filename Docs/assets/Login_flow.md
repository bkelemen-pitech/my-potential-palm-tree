The image was generated on [https://www.websequencediagrams.com](https://www.websequencediagrams.com)
with this content

```
title BO Login flow

FE BO->BE BO: /api/v1/login
BE BO->+Monolith: /internalApi/login
Monolith->Monolith: authenticate
alt Authorized
    Monolith->BE BO: User
    BE BO->BE BO: check IP
    alt IP is in the whitelist 
        BE BO->BE BO: generate JWT
        BE BO->FE BO: JWT
    else IP not in the whitelist
        BE BO->FE BO: 401 Unauthorized
    end
else Invalid user or password
    Monolith->-BE BO: 401 Unauthorized
    BE BO->FE BO: 401 Unauthorized
end
```
