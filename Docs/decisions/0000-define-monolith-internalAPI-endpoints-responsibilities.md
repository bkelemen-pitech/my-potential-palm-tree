# Define monolith internalAPI endpoints responsibilities
* Status: [accepted]
* Deciders: [[Bela Kelemen](https://github.com/bkelemen-pitech), [Marius Pop](https://github.com/mariuspop86), [Sergiu Parlea](https://github.com/SergiuParlea), [Florin
  Onica](https://github.com/fonica)]
* Date: [2022-01-14]

<!-- Technical Story: [description | ticket/issue URL] -->

## Context and Problem Statement

We want to set the responsibilities for the endpoints exposed by the Monolith 
internalAPI.

## Considered Options

* Option 1: each endpoint **should correspond to one entity**
* Option 2: each endpoint **can handle one or more related entities**

## Decision Outcome

Chosen option: "each endpoint should correspond to one entity", because:
- easier to maintain and understand
- abides to the REST principles
- easier to reuse inside the whole platform, also it offers more flexibility 

## Pros and Cons of the Options 

### Each endpoint should correspond to one entity

* Good, because each endpoint has a single responsibility
* Good, because it's easier to maintain and reuse
* Bad, because for related entities the application will have to make multiple 
request to the internalAPI

### Each endpoint can handle one or more related entities

* Good, because one endpoint for related entities
* Bad, because it can't serve multiple purposes
* Bad, because it's hard to reuse
