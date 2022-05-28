<p align="center">
  <a href="http://arifszn.github.io/ezfolio" target="_blank">
    <img src="https://arifszn.github.io/ezfolio/img/short-logo.png" alt="Ezfolio" title="Ezfolio" width="80">
  </a>
</p>

<h1 align="center">Ezfolio</h1>
<p align="center">Open Source Portfolio CMS</p>

<p align="center">
    <a href="https://laravel.com/"><img src="https://img.shields.io/badge/laravel-8-blue" alt="laravel 8"></a>
    <a href="https://reactjs.org/"><img src="https://img.shields.io/badge/react-17-blue" alt="react 17"></a>
    <a href="https://ant.design/"><img src="https://img.shields.io/badge/antd-4-blue" alt="antd 4"></a>
    <a href="https://github.com/arifszn/ezfolio/blob/main/LICENSE"><img src="https://img.shields.io/github/license/arifszn/ezfolio"/></a>
</p>

<br/>

<p align="center">
    <a href="https://arifszn.github.io/ezfolio">
        <img src="https://arifszn.github.io/ezfolio/img/assets/preview.gif" alt="Cover"/>
    </a>
    <br/>
    <a href="#arifszn"><img src="https://arifszn.github.io/assets/img/drop-shadow.png" width="60%" alt="Shadow"/></a>
</p>

<span className="keyword">Ezfolio</span> is a professional open source portfolio CMS built using <b>Laravel</b>, <b>React</b> and <b>Ant Design</b>. Choose from awesome templates and control what you like to show your audience. It offers a minimalist admin interface with lots of option for customizations. Get all the features of a portfolio site including visitor tracking, google analytics, maintenance mode, contact form, SEO and many more. 

This project can be used as a guide for learning Laravel with React and making a SPA.

- Made with Laravel, React and Ant Design
- JWT Authentication
- Single Page Application
- Modern and Responsive Design
- Multiple Templates
- Theme Color Customization
- Custom Scripting
- Visitor Tracking
- Location Tracking
- Google Analytics
- Maintenance Mode
- Contact Form
- Search Engine Optimization
- Section Visibility
- And Much More…

> Client: https://github.com/arifszn/ezfolio/tree/main/resources/js/client


## Docs

Checkout the <a href="http://arifszn.github.io/ezfolio">docs</a>. 


## Installation

### With Docker
- Run ```cp .env.example .env```.
- Run the below command to install Composer dependencies:
    ```sh
    docker run --rm \
        -u "$(id -u):$(id -g)" \
        -v $(pwd):/var/www/html \
        -w /var/www/html \
        laravelsail/php81-composer:latest \
        composer install --ignore-platform-reqs
    ```
- Run ```./vendor/bin/sail up -d```.
- Run ```./vendor/bin/sail artisan migrate --seed```. If you face error `Connection refused`, set `DB_HOST=mysql` in .env file.
- Run ```./vendor/bin/sail npm install```.
- Run ```./vendor/bin/sail npm run prod``` or ```./vendor/bin/sail npm run watch```.

`sail` is equivalent of `docker-compose`, read [`laravel/sail`](https://laravel.com/docs/8.x/sail) doc.


### Without Docker

- Run ```cp .env.example .env```
- Run ```composer install```
- Provide db name, username and password in .env
- Run ```php artisan migrate --seed```
- Run ```npm install```
- Run ```npm run prod``` or ```npm run watch```

Admin credentials:

```
Email: admin@admin.com
Password: 12345
```

For more info, visit the <a href="http://arifszn.github.io/ezfolio">docs</a>.


## Screenshots

### Admin Panel
<kbd><img src="https://arifszn.github.io/ezfolio/img/assets/screenshots/login.png" alt="Login"/></kbd>

<kbd><img src="https://arifszn.github.io/ezfolio/img/assets/screenshots/dashboard.png" alt="Dashboard"/></kbd>

<kbd><img src="https://arifszn.github.io/ezfolio/img/assets/screenshots/basic-config.png" alt="Config"/></kbd>

<kbd><img src="https://arifszn.github.io/ezfolio/img/assets/screenshots/theme.png" alt="Theme"/></kbd>

<kbd><img src="https://arifszn.github.io/ezfolio/img/assets/screenshots/visitors.png" alt="Visitors"/></kbd>

<kbd><img src="https://arifszn.github.io/ezfolio/img/assets/screenshots/project.png" alt="Project"/></kbd>

### Front
<kbd><img src="https://arifszn.github.io/ezfolio/img/assets/screenshots/procyon.png" alt="Procyon"/></kbd>

<kbd><img src="https://arifszn.github.io/ezfolio/img/assets/screenshots/rigel.png" alt="Rigel"/></kbd>

<kbd><img src="https://arifszn.github.io/ezfolio/img/assets/screenshots/vega.png" alt="Vega"/></kbd>


## Contributing

Any contributors who want to make this project better can make contributions, which will be greatly appreciated. Check out our <a href="https://github.com/arifszn/ezfolio/blob/main/CONTRIBUTING.md">contribution guide</a> for more info.


## Support

<a href="https://www.buymeacoffee.com/arifszn" target="_blank">
  <img src="https://raw.githubusercontent.com/arifszn/arifszn/main/assets/bmc-button.png" alt="Buy Me A Coffee" style="height: 60px !important;width: 217px !important;" >
</a>


## License

**Ezfolio** is licensed under the [MIT License](https://github.com/arifszn/ezfolio/blob/main/LICENSE).
