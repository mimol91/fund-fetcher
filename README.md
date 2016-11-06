Fund fetcher
====

#### Description
Backend application allows to fetch found data from `http://www.notowania.openlife.pl`.

Frontend application display found list in a table, and show historical values.

#### Usage
* Save `id` and `name` of found that you want to track under `app/Resources/funds.json`
* Run `app:fund:fetch-list` to fetch funds list and save into db.
* Run `app:fund:fetch` to fetch recent fund data.
* Run `app:fund:list` to display results. (Also available over HTTP).

#### Installation
* `cd .docker && docker-compose up` to create container.
* `composer install` to download backend dependencies.
* `npm install` to download and build frontend.


#### Demo
![alt text](https://github.com/mimol91/fund-fetcher/blob/master/demo/demo.png "Demo")
