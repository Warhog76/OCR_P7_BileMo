[![Codacy Badge](https://app.codacy.com/project/badge/Grade/52aade2b249147239e815b70cbe9354c)](https://www.codacy.com/gh/Warhog76/OCR_P7_Bilemo/dashboard?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=Warhog76/OCR_P7_Bilemo&amp;utm_campaign=Badge_Grade)

# OCR_P7_Bilemo

## Background
BileMo is a company offering a wide selection of high-end cell phones.

You are in charge of the development of BileMo's cell phone showcase. BileMo's business model is not to sell its products directly on the website,
but to provide all the platforms that wish to access the catalog via an API (Application Programming Interface). It is therefore exclusively a B2B 
(business to business) sale.

## Description of the need
The first customer has finally signed a partnership contract with BileMo! It's a real rush to meet the needs of this first customer, which will allow
us to set up all the APIs and to test them immediately.

After a dense meeting with the customer, a certain amount of information was identified. It must be possible to :

consult the list of BileMo products;
consult the details of a BileMo product;
consult the list of registered users linked to a customer on the website;
consult the details of a registered user linked to a customer;
add a new user linked to a customer;
delete a user added by a client.

Only referenced clients can access the APIs. API clients must be authenticated via OAuth or JWT.

### Requested
For this project, you need :

- PHP 8.1
- Symfony 6
- ApiPlatform 3.0

### Installation
Manually download the content of the Github repository to a location on your file system.\
You can also use git.\
In Git, go to the chosen location and execute the following command:
```
git clone https://github.com/Warhog76/OCR_P7_BileMo.git .
```

Open a command console and join the application root directory.\
Install dependencies by running the following command:
```
composer install
```

### Database generation

Change the database connection values for correct ones in the .env file.\
Like the following example with a snowtricks named database to create:
```
DATABASE_URL="mysql://root:@127.0.0.1:3306/bilemo?serverVersion=5.7&charset=utf8mb4"
```

In a new console placed in the root directory of the application;\
Launch the creation of the database:
```
php bin/console doctrine:database:create
```

Then, build the database structure using the following command:
```
php bin/console doctrine:migrations:migrate
```

Finally, load the initial dataset into the database with example users.\
if you want to load the initial dataset and generic users, use this command:
```
php bin/console doctrine:fixtures:load
```

## Start project

Launch the Apache/Php runtime environment by using Symfony via the following command:
```
php bin/console server:run
```
Leave this console open.\
Then consult the URL <http://127.0.0.1:8000> from your browser.

## Made with
* [Symfony](https://symfony.com/) - Framework PHP
* [ApiPlatform](https://api-platform.com/) - API framework
* [PhpStorm](https://www.jetbrains.com/fr-fr/phpstorm/) - IDE

## Writer
* **Nicolas** _alias_ [Warhog76](https://github.com/warhog76)
