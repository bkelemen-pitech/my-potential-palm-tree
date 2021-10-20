### Monolith
The Monolith or Beprems is a zend application that handles the KYC platform.
### Communication
The communication between Back Office symfony app and Monolith is done backend to 
backend through HTTP requests. All Monolith API endpoints will be secured. In order 
to communicate securely a set of headers must be sent on all requests.
These headers are:
- `X-APP-ID`: it is an identifier for the requesting application
- `X-API-KEY`: it is a secret hash generated for the requesting application  

Example:
```http request
POST {MONOLITH_HOST_NAME}/internalApi/login
Accept: application/json 
X-APP-ID: bo_traitement
X-API_KEY: a66290xxxxxxxxxxxxxxxxxxxxxxxxxx

...
```
