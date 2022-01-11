The image was generated on [https://www.websequencediagrams.com](https://www.websequencediagrams.com)
with this content

```
title BO Logout flow

FE BO->BE BO: /api/v1/users/logout
BE BO->BE BO: Remove JWT from Redis
BE BO->FE BO: 204 No Content

```
