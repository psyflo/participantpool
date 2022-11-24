# ParticipantPool

ParticipantPool is a self-hosted web-based tool to invite and manage participants for research studies (e.g., user experience, psychology, market research, customer experience).


This software was developed with XX and XX. 


```
![Screenshot of the sign-up window](/readme/screenshot_participant.jpg?raw=true "Sign-up")
```

```
![Screenshot of the admin interface](/readme/screenshot_participant.jpg?raw=true "Admin interface")
```

## Steps to setup application with XAMPP

1. Clone the repository or download a zip file
2. Install XAMPP (https://www.apachefriends.org/index.html)
4. Install composer (https://getcomposer.org/) and node.js (https://nodejs.org/en/)
5. Install php 8.1+ on your system or use the php version provided with XAMPP 
6. Move the contents of the repository `participantpool` in the subfolder `htdocs` of XAMPP (e.g., `/Applications/XAMPP/htdocs/participantpool`)
7. Open a Terminal and navigate to this folder
8. Run composer and npm
```
$ composer install
$ npm install
```
8. Make a copy of the environment file
```
cp .env.example .env
nano .env
```
9. Set the correct APP_URL in `.env` (e.g.,`APP_URL=http://localhost/participantpool/public`)
10. Change database settings in `.env`
```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=participantpool # make sure you create this database
DB_USERNAME=participantpool # or use root on a local system
DB_PASSWORD=participantpool # use the password you defined in mysql
```
 11.  Set correct URL in webpack (e.g., `/participantpool/public/`)
```
nano webpack.admin.mix.js
nano webpack.mix.js
```
 12. Make sure XAMPP has access to the `storage` folder 
```
chmod -R 777 storage  
```
13. Generate php artisan key
 ```
/Applications/XAMPP/bin/php-8.1.6 artisan key:generate
 ```
 13. Set up database (with example)
```
/Applications/XAMPP/bin/php-8.1.6 artisan migrate:fresh --seed
```
14. Run vue
```
npm run vue
```
15. Compile development version
 ```
npm run dev
```
16. Run Apache Web Server and the MySQL Database in your XAMPP manager
17. Navigate to your participantpool instance:
* `http://localhost/participantpool/public/`
* Log in with default admin user
	* User: XXXXXX
	* Password: 12345678