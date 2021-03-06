# Aeon

[![Minimum PHP Version](https://img.shields.io/badge/php-%3E%3D%207.4-8892BF.svg)](https://php.net/)

Time Management Framework for PHP

> The word aeon /ˈiːɒn/, also spelled eon (in American English), originally meant "life", "vital force" or "being", 
> "generation" or "a period of time", though it tended to be translated as "age" in the sense of "ages", "forever", 
> "timeless" or "for eternity".

[Source: Wikipedia](https://en.wikipedia.org/wiki/Aeon) 

## Website

Documentation of Aeon PHP project. 

### Development 

```bash
composer install
npm install
node_modules/.bin/gulp
symfony server:start
```

[http://127.0.0.1:8000/](http://127.0.0.1:8000/)

### Download latest version of the library

```bash
bin/console aeon:library:download calendar 1.x
```

### Generate static content

```bash
bin/console static-content-generator:copy:assets --env=prod
bin/console static-content-generator:generate:routes --env=prod --parallel=8
```

### Generate static content for single library

```bash
bin/console static-content-generator:generate:routes --env=prod --parallel=8 --filter-route-prefix=docs_calendar
```

### Push new changes to github pages

```bash
git subtree push --prefix=output upstream gh-pages
```