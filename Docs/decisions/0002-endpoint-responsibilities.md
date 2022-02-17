# Endpoint responsibilities

* Status: [proposed] <!-- optional -->
* Deciders: [[Bela Kelemen](https://github.com/bkelemen-pitech), [Marius Pop](https://github.com/mariuspop86), [Sergiu Parlea](https://github.com/SergiuParlea), [Florin
  Onica](https://github.com/fonica)]
* Date: [2021-01-18] <!-- optional -->

## Context and Problem Statement

There are some business requirements that span over multiple entities for GET requests. In some cases the business 
logic requires to update one or more entities. 

## Considered Options

* Complex endpoints
* Endpoints that follow REST rules

## Decision Outcome

Chosen option: "[-]", because ...

### Positive Consequences <!-- optional -->

* [e.g., improvement of quality attribute satisfaction, follow-up decisions required, …]
* …

### Negative Consequences <!-- optional -->

* [e.g., compromising quality attribute, follow-up decisions required, …]
* …

## Pros and Cons of the Options <!-- optional -->

### Complex endpoints

* Good, because all the business logic is under one endpoint
* Good, because one single request to the back-end application is needed to achieve the business logic
* Good, because it's easier to recover in case of errors in the middle of the business logic
* Neutral, because it hides the business logic in the back-end application
* Bad, because does not follow the REST rules

### Endpoints that follow REST rules

* Good, because it follows REST rules
* Good, because simpler endpoints
* Neutral, because it hides the business logic in the front-end application
* Bad, because multiple request to the back-end application are needed to achieve the business logic
* Bad, because it's hard to recover in case of errors in the middle of the business logic
