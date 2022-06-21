# MyFood

Myfood je online receptár, ktorý použivateľom ponúka všetky moderné funkcie. Použivatelia vytváraju, upravuju a vylepšuju svoje recepty. Recepty môžte dalej komentovať, hodnotiť alebo si ich uložiť do svojho receptára, ktorý môže byt viditelný iba pre vás , vašich followerov alebo uplne verejný .Receptár je tiež možné zdielať s ostatnými použivateľmi, ktorí následne budú mať možnosť vylepšovat a spravovať receptár spolu s vami. Použivatelia si môžu navzájom chatovat.   

## Inštalácia

### Nastavíme .env : 
1. Nastavíme pripojenie na databázu.
2. Nastavíme mailového klienta (ja som použil mailtrap)
3. Nastavíme websocket (ja som použil pusher)


### Aplikácia beži na php 8.1 a  vyžaduje package manager  [composer](https://getcomposer.org/) a [NPM](https://www.npmjs.com/).

```bash
composer install 
npm install
npm run dev
```

### Migrácia databázy 
```bash
php artisan migrate
```

databázu je možné pripraviť dvoma spôsobmi:

1. použijeme knižnicu Faker (automaticky vyplní obsah aplikacie)
```bash
php artisan DB:seed
```

2. Naimportujeme subor database.sql do projektu ručne. (čistá databaza bez obsahu)

### Administrácia

Prvému použivateľovi je potrebné pridať administrátorské práva manuálne. (role_id nastaviť na hodnotu 1 )

### Storage

vytvoríme link na storage
```bash
php artisan storage:link
```

## Dodatočné informácie

Aplikacia slúži iba na prezentačné učely a nie je určená na produkciu. 
