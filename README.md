- garanta as configs de banco:
`
  DB_CONNECTION=mysql
  DB_HOST=db
  DB_PORT=3306
  DB_DATABASE=laravel
  DB_USERNAME=root
  DB_PASSWORD=root
`

- Crie o Arquivo .env
```sh
cp .env.example .env
```

- Suba os serviços
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

###testes
`docker exec hash-string-app-1 php artisan test`


- Acessar o backend
[http://localhost:80](http://localhost:80)

- Acessar o admin de banco de dados
[http://localhost:8080](http://localhost:8080)


