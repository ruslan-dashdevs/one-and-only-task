# ONE AND ONLY TASK
- You will use the MVC framework that we use: Symfony
- You will deliver solution in form of a Github repository.
- Create 2 models/classes - `Circle` and `Triangle`
- Implement 2 methods:
  - Calculate surface
  - Calculate diameter
- Create routes:
  - `[GET] /triangle/{a}/{b}/{c}`
  - `[GET] /circle/{radius}`
  > Routes must return JSON with serialized objects and calculated surfaces and diameters. For example:

  ```json
  {
    "type": "circle",
    "radius": 2.0,
    "surface": 12.56,
    "circumference": 12.56
  }
  ```
  or
  ```json
  {
    "type": "triangle",
    "a": 3.0,
    "b": 4.0,
    "c": 5.0,
    "surface": 6.0,
    "circumference": 12.0
  }
  ```
- Create service/or similar structure for the given framework (for example `app.geometry_calculator`)
- Implement method for **sum of areas** for two given objects
- Implement method for **sum of diameters** for two given objects
- Please return us your solution in 24 hours.

## Install
Steps for the developer to setup the project initially:
```shell
make install
```

### Start
Steps for the developer to start this project locally:
```shell
docker-compose up
```

### Lint
Steps for the developer to run code quality tools on the project after some code changes:
```shell
make lint
```

### Test
Steps for the developer to run tests locally to make sure that everything is works properly:
```shell
make test
```
