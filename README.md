## Realtime Chat App

### Teknologi/Library
* Laravel
* Tailwindcss
* Laravel Websockets


### Cara install
Clone repo
```
git clone https://github.com/nuruljaelani/chat-app.git
```
Install library/devedency php
```
composer install
```
Setup .env
```
cp .env.example .env
```
Generate key
```
php artisan key:generate
```
Run migration
```
php artisan migrate --seed
```
Install devedency/library javascript
```
npm install
```

### Cara menjalankan project
```
php artisan serve
```
```
php artisan websockets:serve
```
```
npm run dev
```

### Note
```
email : jay@gmail.com
password : jay12345
```
