# How to install project Todo-List
<hr>

Step 1. clone code 
```sh
    $ git clone https://gitlab.com/nam_kopy/todo-list.git
```
Step 2. install composer 
```sh
    $ composer install
``` 
Step 3. rename `.env.example` to `.env`

Step 4. copy in to `.env`
```sh
    FIREBASE_CREDENTIALS="todolist-481f2-firebase-adminsdk-qrfqw-de7aff476f.json"
    FIREBASE_DATABASE_URL="https://todolist-481f2-default-rtdb.firebaseio.com/"
``` 
Step 5. generate key
```sh
    $ php artisan key:generate
``` 
Step 6. run the code 
```sh
    $ php artisan serve
``` 
