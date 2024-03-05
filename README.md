**Завдання:**
Написати CRUD API для сутності працівника компанії використовуючи Symfony останньої версії.

Cутність має містити:
- Ім'я
- Прізвище
- Електронну пошту
- Дату зарахування в компанію
- Поточну сумму заробітної плати
- Дата створення сутності
- Дата оновлення сутності

При створенні/редагуванні має проводитись валідація даних:
- Ім'я, прізвище, пошта, дата зарахування та розмір заробітної плати є обов'язковими полями
- Дата не має бути у минулому
- Розмір заробітної плати має бути не менше 100

**Вимоги до результату:**
Результат має бути представлений у вигляді коду, викладеного у репозиторій.
Readme має містити базовий опис про систему і як її запустити.
Запуск оточення має бути реалізований через docker-compose.
Система має містити 4 ендпоінт, які відповідають за створення/редагування/читання та видалення відповідно.
Кожен ендпойнт має бути описаний swagger документації (раджу NelmioApiDoc bundle).
Кожен ендпойнт має бути покритий функціональними тестами.


## How to Run (Docker / [Symfony CLI](https://symfony.com/download) required)
```bash
git clone git@github.com:kefzce/level99.git && cd level99
```
```bash
composer install
```
```bash
docker compose up -d --build 
```
```bash
./bin/console doctrine:migrations:migrate
```
```bash
symfony serve
```
## Routes
```bash
zsh ❯ ./bin/console debug:router
 --------------------- -------- -------- ------ --------------------
Name                  Method   Scheme   Host   Path
 --------------------- -------- -------- ------ --------------------
app.swagger           GET      ANY      ANY    /api/doc.json
app_employee_list     GET      ANY      ANY    /api/employee
app_employee_get      GET      ANY      ANY    /api/employee/{id}
app_employee_create   POST     ANY      ANY    /api/employee
app_employee_update   PUT      ANY      ANY    /api/employee/{id}
app_employee_delete   DELETE   ANY      ANY    /api/employee/{id}
app.swagger_ui        GET      ANY      ANY    /api/doc
 --------------------- -------- -------- ------ --------------------
```


## Time Report

[2h45m]

## Api doc to try-out
https://127.0.0.1:8000/api/doc