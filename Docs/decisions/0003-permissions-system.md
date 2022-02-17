# Permissions system

* Status: [accepted] <!-- optional -->
* Deciders: [[Bela Kelemen](https://github.com/bkelemen-pitech), [Marius Pop](https://github.com/mariuspop86), [Sergiu Parlea](https://github.com/SergiuParlea), [Florin
  Onica](https://github.com/fonica)]
* Date: [2021-02-01] <!-- optional -->

Technical Story: [https://jira.pitechplus.com/browse/KC-1908](https://jira.pitechplus.com/browse/KC-1908) <!-- optional -->

## Context and Problem Statement

For Back Office and Middle office applications a system is needed that can handle complex permissions rules.  
At the 
moment for Middle office permissions checks are done on the FE side and security consists in hiding 
buttons/sections/pages from specific users/roles.

## Considered Options

* Symfony's [roles](https://symfony.com/doc/current/security.html#roles) +
 [voters](https://symfony.com/doc/current/security/voters.html) system
* ACL list/matrix in json
* [Casbin](https://casbin.org/)

## Decision Outcome

Chosen option: "[Symfony's roles + voters system]", because it's the default way to implement permissions, and it 
allows for a complex permissions' system, while keeping the business logic intact.  
Implementation details:
* each entity is handled by one voter
* if one or more voters fails access is denied

## Pros and Cons of the Options <!-- optional -->

### Symfony's roles + voters system

* Good, because it's the default way to implement permissions in a symfony app
* Good, because it allows for complex permissions' system with the help of voters
* Good, because it separates the permissions from the business logic

### ACL list/matrix in json

* Good, because can be reused both on front end and back end
* Bad, because the permission's logic is mixed with the business logic 
* Bad, because it can make the code hard to read and understand
* Bad, because it's difficult to maintain

### [Casbin](https://casbin.org/)

* Good, because cross-language and cross-platform, the same system can be used both on front end and back end
* Good, because it's a hybrid access control models
* Good, because has a flexible policy storage
* Bad, because it's very complex
