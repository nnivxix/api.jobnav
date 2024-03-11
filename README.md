# API Jobnav

![jobnav banner](./designs/Jobnav_id.jpg)

Jobnav.id is a cutting-edge job search application designed to help individuals navigate their way through the competitive job market. Whether you're a recent graduate looking for your first job or a seasoned professional seeking a career change, jobnav.id provides the tools and resources you need to streamline your job search process.

## Features

1. Authentication (Sanctum) 🔑.
2. Unit test 🧪.
3. Searchable content 🔍.

## Steps Instalation

### Requirments

1.  PHP >= 8.0 
2.  Node.js >= 16
  
### Steps

1. Run composer to install all depedencies
```bash
composer install
```

2. copy env file

```bash
cp .env.example .env
```

3. Run migrations and seeder

```bash
php artisan migrate
```
```bash
php artisan db:seed
```

4. Link the storage

```bash
php artisan storage:link
```

5. Update all images 
  
Update all images using dummy image from unsplash, and before update the images.

⚠ Attention ⚠

Please get the [Access Key](https://unsplash.com/oauth/applications/new) from API unsplash. Then update the key `UNSPLASH_ACCESS_KEY` on `env` file.

   ```bash
   php artisan replace-images:company
   ```
 ```bash
   php artisan replace-image:users
   ```

```bash
php artisan replace-logos:experience
```

6. Done
   
And your setup has been done. Happy Coding.

## Links

[Jobnav Front-end](https://github.com/nnivxix/jobnav)