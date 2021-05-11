# api-platform
1. Add jwt token stateless for connecting to any api
2. Foundry bundle for generate fixtures
3. User with role admin can see all the users but simple user juste see him with UserExtension
4. Test Functional for users api
5. Management rules for products:
    * ROLE_ADMIN get all products with owner property
    * ROLE_PROVIDER get his products without owner property
    * ROLE_USER can get all products without owner property
6. Management rules for user:
   * Everybody can create user without roles (ROLE_USER default)
   * Only Admin can update roles (ROLE_PROVIDER or ROLE_ADMIN, ROLE_ADMIN)
7. BDD model:
    * category
    * order
    * product
    * user
![Image of Fintechtocat](https://octodex.github.com/images/Fintechtocat.png)
   

