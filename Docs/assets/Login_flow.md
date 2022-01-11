The image was generated on [https://www.websequencediagrams.com](https://www.websequencediagrams.com)
with this content

```
title BO Login flow

FE BO->BE BO: /api/v1/users/login
BE BO->+Monolith: /internalApi/user/login
Monolith->Monolith: Authenticate
alt Authorized
    Monolith->BE BO: User
    BE BO->BE BO: Generate JWT
    BE BO->BE BO: Store JWT in Redis
    BE BO->FE BO: JWT
else Invalid user or password
    Monolith->-BE BO: 401 Unauthorized
    BE BO->FE BO: 401 Unauthorized
end
```
