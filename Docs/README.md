## Back-office for KYC
### Introduction
This project is the backend part of the back-office of KYC platform. It will 
work as a middleware between Monolith and the front-end application.
### Architecture
Here is a simple overview of the components involved in the Back-office context:
- a frontend application made with React used as the user interface layer. _TODO: add link to doc_
- a symfony application used as an [API layer](./API-layer.md), this will act as a
  middleware between the frontend application and Monolith.
- a zend application - [Monolith(Beprems)](./Monolith.md) - used for saving/fetching data to/from the
  database and to process and store documents. It has other functionalities
  that are not relevant for this context
- a database layer  

This documentation will focus on the symfony component.
### Contents
- [API layer](API-layer.md)
