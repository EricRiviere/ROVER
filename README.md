# Mars Rover Mission

## Task:
You're part of a team that explores Mars by sending remotely controlled vehicles to the surface of the planet. Develop a software that translates the commands sent from Earth to instructions that are understood by the rover.

## Requirements:
- You are given the initial starting point (x, y) of a rover and the direction (N, S, E, W) it is facing.
- The rover receives a collection of commands (e.g. FFFRRFFFRL).
- The rover can move forward (F).
- The rover can move left/right (L/R).
- Suppose we are on a really weird planet that is square-shaped (e.g. 200x200).
- Implement obstacle detection before each move to a new square. If a given sequence of commands encounters an obstacle, the rover moves up to the last possible point, aborts the sequence, and reports the obstacle.

## Take into account:
- Rovers are expensive, so make sure the software works as expected.

## Getting Started:

To run the project, clone the repository:

```bash
git clone https://github.com/EricRiviere/ROVER.git ROVER-MISSION
```

Change to the project directory:

```bash
cd ROVER-MISSION
```

Install Composer dependencies:

```bash
composer install
```

Create the `.env` file from `.env.example`:

```bash
cp .env.example .env
```

In this file, uncomment the following section and enter the database name and password:

```ini
DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=dbname
# DB_USERNAME=root
# DB_PASSWORD=password
```

Run the migrations:

```bash
php artisan migrate
```

Create application key:

```bash
php artisan key:generate
```
Start the local server:

```bash
php artisan serve
```

## Accessing the Dashboard:

Once the server is running, access the dashboard at:

[http://localhost:8000/dashboard](http://localhost:8000/dashboard)

In the dashboard, you can view maps, rovers, missions, and exploration maps.

## Starting a Mission:

To start a mission, you need to create at least one rover by entering its name, create a map by entering the map name, and click on "Go to create mission."

This will take you to the mission control panel where you can start a mission by:

1. Selecting 1 rover, 1 map, landing coordinates (maps have a maximum size of 200x200), and a direction (N, S, E, W).
2. Clicking on "Start Mission."

If everything works correctly (you might need to input the coordinates more than once if there's an obstacle at the landing point), you will be redirected to the rover control.

In the rover control, you will see the rover's current position and direction.

## Command Line Interface:

You can enter a command line in the format `"FFRFFFLFF"`, where:
- Each "F" moves the rover one square forward.
- Each "R" turns the rover 90 degrees to the right.
- Each "L" turns the rover 90 degrees to the left.

Click on "Send Commands" to update the rover's position and orientation. You will also see the "Exploration Data" section, which displays the exploration map of your current mission.

## Obstacle Detection:

If a command moves the rover to the map's edge or encounters an obstacle, the command is interrupted, and the rover stays at the last possible position.

- All explored cells without obstacles will be shown in green.
- Cells with obstacles will be shown in red.

## Finishing a Mission:

When you want to finish the mission, click on "Finish Mission." This will mark the mission as completed, and you can either create a new mission or go back to the dashboard to review mission data, exploration maps, etc.

## Obstacle Details:

Maps are created with 50 obstacles placed in completely random positions. If you want to test the obstacle detection functionality, you can view the selected map (which shows all the obstacles) for the mission in the dashboard and use the command line to navigate to a specific obstacle.

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[WebReinvent](https://webreinvent.com/)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Jump24](https://jump24.co.uk)**
- **[Redberry](https://redberry.international/laravel/)**
- **[Active Logic](https://activelogic.com)**
- **[byte5](https://byte5.de)**
- **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
