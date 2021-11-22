## Folder Actions
The Back-office application must give an agent the possibility to manage folders. Here 
are described the different actions an agent can perform.

### List
Each agent can see the list of folders. The folder list is organised by statuses. Here are
the different statues:
- _treated folders_ - this list can be retrieved by calling the [folders](./Folders.md) 
  API with `status_verification2` 10300   

The list is ordered by creation date descending. It should be limited to 10, 20, 50 
or 100 rows per page. The list can be filtered by the name of the folder(partner_folder_id)
and the name of the main person(first_name)

### Details
When an agent selects a folder from the list he is redirected to the details page,
where he is able to see the information about that folder. The information is retrieved
by calling the [folder details](Details.md) API. The documents attached to a specific
folders is retrieved by calling the [folder documents](Documents.md).
