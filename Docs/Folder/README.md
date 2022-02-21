## Folder Actions
The Back-office application must give to the user the possibility to manage folders. Here 
are described the different actions a user can perform.

### List
Each agent can see the list of folders. The [folders list](./Folders.md) is organised by [views](./Folders.md#views).
The list is ordered by creation date descending. It should be limited to 10, 20, 50 
or 100 rows per page. The list can be filtered by the name of the folder(partner_folder_id)
and the name of the main person(first_name). In order to show the total number
of folders for each view we use the [folders count](./Folders_count.md).

### Details
When an agent selects a folder from the list he is redirected to the details page,
where he is able to see the information about that folder. The information is retrieved
by calling the [folder details](Details.md) API. For details regarding the documents
attached to a specific folders check documentation [here](../Document/Folder-documents.md). 

### Misc actions
There are other API endpoints that are needed by the frontend application. Here is a list:
- [Update workflow status](./Workflow_status.md)  
- [Move folder to supervision](./Move_folder_to_supervision.md)
- [Assign folder to administrator](./Assign_folder.md)

