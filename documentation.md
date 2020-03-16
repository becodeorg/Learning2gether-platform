# Documentation

Current Symfony version: `Symfony 4.3.11`

to check:

```
bin/console --version
```

## How to connect to server

Find the PEM file in 1Password and store it on your local hard drive. Chmod it 400, then :

```
ssh -i ./path/to/learning2gether-platform.pem ubuntu@platform.learning2gether.org
```

Once on the server, switch to the www-data user

```
sudo -u www-data bash
```

(use CTRL+D to exit and switch back to the ubuntu user)

## Deployment

ssh into the server, cd into /var/www/l2g-prod and pull as www-data

```
sudo -u www-data git pull
```

### command to upgrade normal users to partners:

```bash
sudo ./bin/console l2g:upgrade-partner $emailadres
```

### Clear cache

```
./bin/console cache:clear --env=prod
```

### manually fix image url resolve

```
./bin/console liip:imagine:cache:resolve da305b9a0fee3eb7bdc5bfc9b64e88ff.jpeg --filter=learningModuleImage
```

## Local development environement

I'm using MAMP PRO but apparently Symfony has built-in tools.

Create a "l2g" mysql database with a user and password.

Put the credentials in the `.env` file.

Then

```
php bin/console doctrine:migrations:migrate
```

Then import the dependencies via composer.

```
COMPOSER_MEMORY_LIMIT=-1 composer install
```

### CSS updates

```
cd /into/project/root/folder
sass --watch sass/app.scss:public/assets/css/app.css
```

### JS updates

Plain old javascript, to be included in `base.html.twig` or directly in `/public/assets/js/script.js`

### Symfony tools (untried)

see: https://symfony.com/doc/current/setup/symfony_server.html

1. [Install Symfony binary](https://symfony.com/download)
2. Install certificates `symfony server:ca:install`
3. Run local server : `symfony server:start`

## Routes

```bash
./bin/console debug:router
```

````
 ----------------------------- ---------- -------- ------ -------------------------------------------------------------
  Name                          Method     Scheme   Host   Path
 ----------------------------- ---------- -------- ------ -------------------------------------------------------------
  api_module                    ANY        ANY      ANY    /api/module/{module}
  category                      ANY        ANY      ANY    /portal/forum/{category}
  create_chapter                ANY        ANY      ANY    /partner/create/module/{module}/chapter/{chapter}
  create_module                 ANY        ANY      ANY    /partner/create/module
  create_page                   ANY        ANY      ANY    /partner/create/module/{module}/chapter/{chapter}/page
  dashboard_chapter             ANY        ANY      ANY    /partner/dashboard/chapter/{chapter}
  dashboard                     ANY        ANY      ANY    /partner/dashboard
  dashboard_module              ANY        ANY      ANY    /partner/dashboard/module/{module}
  dashboard_question            ANY        ANY      ANY    /partner/dashboard/chapter/{chapter}/question/{question}
  delete_chapter                ANY        ANY      ANY    /partner/delete/chapter/{chapter}
  delete_module                 ANY        ANY      ANY    /partner/delete/module/{module}
  delete_page                   ANY        ANY      ANY    /partner/delete/{module}/{chapter}/{page}
  edit_chapter                  ANY        ANY      ANY    /partner/edit/module/{module}/chapter/{chapter}
  edit_module                   ANY        ANY      ANY    /partner/edit/module/{module}
  edit_module_translation       ANY        ANY      ANY    /partner/edit/translation/module/{module}
  edit_page                     ANY        ANY      ANY    /partner/edit/module/{module}/chapter/{chapter}/page/{page}
  faq                           ANY        ANY      ANY    /faq
  forum                         ANY        ANY      ANY    /portal/forum
  searchbar                     ANY        ANY      ANY    /portal/forum/searchbar
  homepage                      ANY        ANY      ANY    /{_locale}/
  homepage_default              ANY        ANY      ANY    /
  language_switcher             ANY        ANY      ANY    /languageswitcher
  modal                         ANY        ANY      ANY    /modal
  module                        ANY        ANY      ANY    /portal/module/{module}
  module_view_page              ANY        ANY      ANY    /portal/module/view-page/{chapterPage}/
  partner                       ANY        ANY      ANY    /partner
  password_reset                ANY        ANY      ANY    /password-reset
  password_new                  ANY        ANY      ANY    /password-new
  portal                        ANY        ANY      ANY    /portal
  profile                       ANY        ANY      ANY    /profile
  publish_module                ANY        ANY      ANY    /partner/publish/module/{module}
  question                      ANY        ANY      ANY    /portal/forum/{category}/{chapter}/{question}
  upvote                        ANY        ANY      ANY    /forum/{category}/{chapter}/{question}/upvote
  post                          ANY        ANY      ANY    /forum/{category}/{chapter}/{question}/post
  post_delete                   DELETE     ANY      ANY    /deletePost/{id}
  question_delete               DELETE     ANY      ANY    /deleteQuestion/{id}
  quiz_answer_index             GET        ANY      ANY    /partner/quiz/answer
  quiz_answer_new               GET|POST   ANY      ANY    /partner/quiz/answer/new/{id}
  quiz_answer_show              GET        ANY      ANY    /partner/quiz/answer/{id}
  quiz_answer_edit              GET|POST   ANY      ANY    /partner/quiz/answer/{id}/edit
  quiz_answer_delete            DELETE     ANY      ANY    /partner/quiz/answer/{id}
  quiz_show                     GET        ANY      ANY    /partner/quiz/{id}
  quiz_show_user                ANY        ANY      ANY    /portal/quiz/{quiz}
  quiz_send                     ANY        ANY      ANY    /portal/quiz/{quiz}/send
  quiz_question_index           GET        ANY      ANY    /partner/quiz/question/
  quiz_question_new             GET|POST   ANY      ANY    /partner/quiz/question/new/{id}
  quiz_question_show            GET        ANY      ANY    /partner/quiz/question/{id}
  quiz_question_edit            GET|POST   ANY      ANY    /partner/quiz/question/{id}/edit
  quiz_question_delete          DELETE     ANY      ANY    /partner/quiz/question/{id}
  app_register                  ANY        ANY      ANY    /{_locale}/register
  app_login                     ANY        ANY      ANY    /{_locale}/login
  app_logout                    ANY        ANY      ANY    /{_locale}/logout
  topic                         ANY        ANY      ANY    /portal/forum/{category}/{chapter}
  addQuestion                   ANY        ANY      ANY    /forum/{category}/{chapter}/addQuestion
  upload_content                ANY        ANY      ANY    /partner/upload/content
  fos_js_routing_js             GET        ANY      ANY    /js/routing.{_format}
  liip_imagine_filter_runtime   GET        ANY      ANY    /media/cache/resolve/{filter}/rc/{hash}/{path}
  liip_imagine_filter           GET        ANY      ANY    /media/cache/resolve/{filter}/{path}
 ----------------------------- ---------- -------- ------ -------------------------------------------------------------

 ```
````
