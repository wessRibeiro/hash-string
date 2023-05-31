### informações
- é necessário Docker para montar o ambiente

- Crie o Arquivo .env
```sh
cp .env.example .env
```
- garanta as configs de banco:
```sh
  DB_CONNECTION=mysql
  DB_HOST=db
  DB_PORT=3306
  DB_DATABASE=laravel
  DB_USERNAME=root
  DB_PASSWORD=root
```

- Suba os serviços (subirá o banco, um adminer client e o backend)
```sh
bash up.sh
```

- instale as dependencias do laravel
```sh
docker exec hash-string-app-1 composer install
```

- gere a chave
```sh
docker exec hash-string-app-1 php artisan key:generate
docker exec hash-string-app-1 php artisan migrate
```

### para executar o comando:
````sh
docker exec hash-string-app-1 php artisan avato:test Ávato --requests=20
````

### rotas:

- para listar de forma paginada 
http://localhost/api/hash

- para listar de forma paginada e com filtro por attempts 
http://localhost/api/hash?attempts=500

- para listar de forma paginada e com filtro por attempts e com pagina  
http://localhost/api/hash?page=1&attempts=500

- para receber uma hash (agnóstico)
  http://localhost/api/hash/{string}

### Acessar o admin de banco de dados
[http://localhost:8080](http://localhost:8080)


