# Endpoint responsibilities

* Status: [accepted] <!-- optional -->
* Deciders: [[Bela Kelemen](https://github.com/bkelemen-pitech), [Marius Pop](https://github.com/mariuspop86), [Sergiu Parlea](https://github.com/SergiuParlea), [Florin
  Onica](https://github.com/fonica)]
* Date: [2021-02-18] <!-- optional -->

## Context and Problem Statement

There are some business requirements that requires the update of certain entities when fetching a resource.  
For example: for Back Office application when opening a folder for the first time, this folder must be assigned to the 
user and the status of the folder must be updated.

## Considered Options

* Not idempotent endpoints
* Idempotent endpoints

## Decision Outcome

Chosen option: "[Idempotent endpoints]", because fetching a resource should just to that. It does not hide 
implementation details. It will be easier to reuse if needed. Easier to maintain.

### Positive Consequences <!-- optional -->

* simpler endpoints
* maintainability
* implementation details are not obscured

### Negative Consequences <!-- optional -->

*  multiple request to the back-end application are needed to achieve the business logic, resulting in slower 
   application
* it's hard to recover in case of errors in the middle of the business logic

## Pros and Cons of the Options <!-- optional -->

### Not idempotent endpoints

* Good, because all the business logic is under one endpoint
* Good, because one single request to the back-end application is needed to achieve the business logic
* Good, because it's easier to recover in case of errors in the middle of the business logic
* Neutral, because it hides the business logic in the back-end application
* Bad, because it's not an idempotent endpoint(the resource can change when requested multiple times)

### Idempotent endpoints

* Good, because it follows REST rules for idempotency
* Good, because simpler endpoints
* Neutral, because it hides the business logic in the front-end application
* Bad, because multiple request to the back-end application are needed to achieve the business logic
* Bad, because it's hard to recover in case of errors in the middle of the business logic
