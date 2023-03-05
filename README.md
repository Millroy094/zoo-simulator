## Introduction

A simple Zoo simulator which contains 3 different types of animal: monkey, giraffe and elephant. The zoo should open with 5 of each type of animal. Each animal has a health value held as a percentage (100% is completely healthy). Every animal starts at 100% health.

The application acts as a simulator, with time passing at the rate of 1 hour with each iteration. Every hour that passes, a random value between 0 and 20 is to be generated for each animal. This value is passed to the appropriate animal, whose health is then reduced by that percentage of their current health.

The user is able to feed the animals in the zoo. When this happens, the zoo generates three random values between 10 and 25; one for each type of animal. The health of the respective animals is to be increased by the specified percentage of their current health. Health is capped at 100%.

When an Elephant has a health below 70% it cannot walk. If its health does not return above 70% once the subsequent hour has elapsed, it is pronounced dead.

When a Monkey has a health below 30%, or a Giraffe below 50%, it is pronounced dead straight away.

The user interface shows the current status of each Animal and contains three buttons, one to provoke an hour of time to pass, another to feed the zoo and lastly to reset the zoo. The UI update reflects each change in state, and the current time at the zoo.

## Database design

The application uses a MySql database with only one table called animal, comprising of the following columns (I've not mentioned all but those relevant):

1. type -> type of animal.
2. name -> name of animal.
3. current_health -> current health of animal.
4. previous_health -> previous health of animal.
5. status -> status of animal.
6. zoo_creation_time -> the time the zoo was created.
7. life_span -> how many hr(s) animal has lived.

## Backend design

The backend uses Php under the hood of laravel MVC framwork, the application however uses only the Model & Controller part of the application. We have Animal Model links to the animal table.

It has only one controller i.e. ZooController with 4 main public methods:

1. createZoo function that creates the zoo and the animals.
2. incrementHourAtZoo function that increments a hour at the zoo there by also updating animal health i.e. decreasing it.
3. feedZoo function feeds all the animals at the zoo i.e increase their health.
4. destroyZoo deletes the whole zoo and all the animals with it.

There are two main parts that form a part of main logic found in the ecosystem folder of this simulator:

1. ZooTimeManager: This is a util class to persist and handle the time at the zoo during the application lifecycle.
2. Species: These are set of classes that represent the animals, each animal has its own class but inherites and also overrides some main functionality from their base Animal Class.

Note: All the routes to the controller are found in api routes.

## Frontend design

The frontend design is very straight forward, it is designed in React.js, there is a local state for zoo time and the animals. There are 3 buttons one to increment the time at the zoo, another to feed the animals, and another to reset the zoo.

The aniamls are shown on an animal health card and are sorted based on status i.e. as soon as an animal is dead it will be moved to the end of the health card list, if a elephant can't walk it will be moved after all alive animals but before dead animals. Each status also has its own color i.e. green for alive, orange for can't walk, and red for dead.

## Installation

### Prerequisite

1. MySql database of the name zoo, with user of root and no password (This can be modified in the database configs).
2. Php composer.
3. Npm or yarn (I've used yarn).

### Instructions

1. Initially clone the git repo or download the source code.
2. Run `composer install` from the console, this will load all the laravel dependencies.
3. Run `php artisan migrate` to create `animal` schema.
4. Run `php artisan serve` to let laravel listen on port `8000` and that's the backend ready to serve api calls.
5. To prepare the frontend open the `zoo-simulator-front-end` and run `yarn` this will install the frontend dependencies.
6. Finally run `yarn dev` which will start the frontend to start zoo simulator GUI.

## Extras

The code also has some unit tests that are run on the four api calls:

1. api/create
2. api/destroy
3. api/increment-hour
4. api/feed

These test can be run using php artisan test.
