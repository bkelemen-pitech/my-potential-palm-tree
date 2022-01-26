# History API implementation

* Status: [accepted] <!-- optional -->
* Deciders: [[Bela Kelemen](https://github.com/bkelemen-pitech), [Marius Pop](https://github.com/mariuspop86), [Sergiu Parlea](https://github.com/SergiuParlea), [Florin 
  Onica](https://github.com/fonica)]
* Date: [2021-01-26] <!-- optional -->

<!-- Technical Story: [description | ticket/issue URL]  optional -->

## Context and Problem Statement

The history API data is composed of multiple sources and the logic to merge the data can be done either in the 
bundle or the application.  
For BO application the history data is composed of these entities:
- personne
- document
- document_data_log
- historique_statut_workflow  

For Core Front Pro application the history data is composed of these entities:
- personne
- personne_info
- agent_dossier
- historique_statut_workflow

>For the moment for Core front pro there is an internalAPI endpoint that merge the data 
`/folders/gethistory/folder-id/{folderId}` that needs to be refactored by splitting it into endpoints for each entity.

## Decision Drivers <!-- optional -->

* maintainability

## Considered Options

* Option 1: data should be merged by the bundle 
* Option 2: data should be merged by the application

## Decision Outcome

Chosen option: "data should be merged by the application", because it keeps the logic inside the application and not in 
the bundle making it unaware of the application using it. It is easier to maintain/update/refactor.
Extracting this type of reusable business logic into a separate bundle (not the internalAPI one) can be reconsidered in 
the future if needed.

## Pros and Cons of the Options <!-- optional -->

### Data should be merged by the bundle

The bundle should implement a service that will handle the merging of the data from all sources. Based on a 
parameter it should return the correct data for each use case or application(Back office, Middle office)

* Good, because easier implementation in the applications(just calling the service with the correct parameter)
* Good, because all the code for handling the history data is in one place 
* Bad, because it hides the implementation from the application
* Bad, because it's harder to maintain or update without introducing regressions
* Bad, because it changes the scope of the bundle(the initial scope was that it should be an interface for the 
  data source in this case the monolith and the applications that communicate with it)
* Bad, because it increases the logic with the extra parameter

### Data should be merged by the application

The application should be responsible for the merging of history data, by calling each endpoint separately through 
the bundle.

* Good, because each application is responsible for retrieving the correct data
* Good, because does not change the bundle's scope
* Good, because it prevents the applications' logic from creeping into the bundle, making the bundle independent of any application
* Bad, because some code can/will be duplicated across applications
