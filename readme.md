![PLJ Logo](http://www.unglobalpulse.org/sites/default/files/Pulse_Lab_Jakarta_logo.png "PLJ Logo")


## Introduction


An essential element of public policy research is to pay attention to citizens’ feedback, both active and passive, for instance, citizens’ complaints to governments through official channels and on social media. To do this in a computational manner, researchers need a set of keywords, or ‘taxonomy’, by topic or government priorities for example. But given the rich linguistic and cultural diversity in Indonesia, this poses some difficulties in that many languages and dialects are used in different provinces and islands. On social media, such variations - including jargon - make building a list of keywords more challenging as words, context and, by extension, meaning change from region to region. Translator Gator is a people-powered language game which creates a dictionary of translations to support academic research and social projects in Indonesia. It aims to translate a set of English keywords into Indonesian, as well as other local languages such as Bahasa Jawa, Sunda, Minang, Bugis, and even includes slang. The translated keywords will be applicable to many projects, especially those related to digital text analysis.


## Features


There are four components of the game:
* ___Translate___ keywords into other languages.
* Provide ___Alternative___ translations of words.
* ___Evaluate___ other players' translations.
* ___Classify___ a given translations to some categories.


This project is fully customisable from the keywords, translation languages, active game components, gamification points, etc.


## Installation


This project use [Lumen](https://lumen.laravel.com/) for RESTful API and [AngularJS](https://angularjs.org/) for it’s front-end framework. Currently, we only support [MongoDB](https://www.mongodb.com/) because we have unrequited love with aggregate.


Before installing this project, you need to ensure that [Composer](https://getcomposer.org/doc/00-intro.md), [MongoDB](https://www.mongodb.com/download-center), [PHP>=5.6.4](http://php.net/manual/en/install.php) and some PHP extension (php-mongodb php-mbstring php-xml php-zip) installed on your machine.


In the root of your clone project run ```[sudo] composer install``` to install latest lumen dependencies that we use. Then you can set the environment file for this project by copy the example file via ```[sudo] cp .env.example .env```


The .env file will be like this.
```
APP_ENV={local vs production, if local, it won’t send mail because it gave error when accessed from angular $http}
APP_DEBUG={boolean, currently not used in this project}
APP_KEY={[32 character, random string](https://lumen.laravel.com/docs/5.2/encryption)}
APP_BASEURL={your url, eg : http://translator-gator.unglobalpulse.net/}


DB_CONNECTION={mongodb, we currently only support mongoDB for this project}
DB_HOST={self-explanatory}
DB_PORT={self-explanatory}
DB_DATABASE={self-explanatory}
DB_USERNAME={self-explanatory}
DB_PASSWORD={self-explanatory}


MAIL_DRIVER={Supported: "smtp", "mail", "sendmail", "mailgun", "mandrill", "ses", "log", only mailgun tested}
MAIL_HOST={self-explanatory}
MAIL_PORT={self-explanatory}
MAIL_USERNAME={self-explanatory}
MAIL_PASSWORD={self-explanatory}
MAIL_ENCRYPTION={self-explanatory}
MAIL_NAME={self-explanatory}
MAIL_ADDRESS={self-explanatory}
MAIL_SUBJECT={self-explanatory}


FB_APP_ID={ID for facebook aplication, needed for fb share}
```


For mail credential, please check __/config/services.php__ file and add the parameter to .env with your credential. Please direct the root folder for this project to __/public/__  folder at your web server and ensure that rewrite module is working. All front-end library are accessed via cdn, so you don’t have to install anything.


## Seeding


You must initialize translator-gator database with [Laravel Database Seeder](https://laravel.com/docs/master/seeding). The files are located at __/database/seeds folder__. There are four files that you can change:
* __CategoryCollectionSeeder.php__, to initialize categories and category_items collection
* __ConfigurationCollectionSeeder.php__, to initialize configuration collection, if you’re not sure with the value, leave them be.
* __LanguageCollectionSeeder.php__, change array $list to initialize available translation languages.
* __UserCollectionSeeder.php__, to initialize admin account for translator-gator.


Run ```[sudo] php artisan db:seed``` to seed your database. You can upload the origin words via words management on admin panel. The uploaded file must be a csv and each sentence must be written on separate line. And don’t forget to change __/storage/__ folder’s  permission to 777 or the ownership to www-data if there’s an error when uploading file.


## License
