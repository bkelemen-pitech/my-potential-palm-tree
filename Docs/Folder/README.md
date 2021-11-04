## Folder Actions
The Back-office application must give an agent the possibility to manage folders. Here 
are described the different actions an agent can perform.

### List
Each agent can see the list of folders. The folder list is organised by statuses. Here are
the different statues:
- _treated folders_ - this list can be retrieved by calling the [folders](./Folders.md) 
  API with `workflow_status` 10400 and 1040 as filters

### Details
When an agent selects a folder from the list he is redirected to the details page,
where he is able to see the information about that folder. The information is retrieved
by calling the [folder details](Details.md) API.
