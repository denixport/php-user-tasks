
Sample PHP REST app for managing user tasks using:

* Slim 4 Framework
* Basic CQRS (without event sourcing)
* TDD based on user story along with unit tests

Main user story 
>"As a user, I want to have an ability to see a list of tasks for my day"

is described in test case 

`tests/Functional/Application/Actions/Tasks/User/ListUserTasksAction.php`

Project structure includes
```
app/            - application configuration and bootstrap
    /routes.php - implemented endpoints  
src/Application - application actions, handlers
src/Domain      - domain model
    /Common     - common value objects
    /Tasks      - task context
    /Users      - user context
```        
To run the app

`> git clone https://github.com/denixport/php-user-tasks.git`

`> cd php-user-tasks`

`> composer install`

`> composer run test`

`> composer run serve`

To run in container
`[TBD]`