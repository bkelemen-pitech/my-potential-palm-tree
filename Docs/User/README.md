### User
The `administrateur` entity is the user that logs into the BO application.


The list of administrators can be retrieved by calling `/internalAPI/administrators/` endpoint.
This will return the administrator_id, login, role, email, administrator_group_id.
### User roles
A BO user can have different roles:
- administrator - ROLE_1
- supervisor - ROLE_4
- adviser - ROLE_6
