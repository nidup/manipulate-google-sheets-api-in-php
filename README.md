# Manipulate Google Sheets API in PHP 🐘

Code examples for the tutorial [How to Use Google Sheets API in PHP?](https://www.nidup.io/blog/manipulate-google-sheets-in-php-with-api)

The code is packaged as a simple Symfony application with cli commands.

This series also proposes other PHP data integration tutorials:
- [How to Read and Write CSV Files in PHP?](https://www.nidup.io/blog/manipulate-csv-files-in-php)
- [How to Read and Write Excel Files in PHP?](https://www.nidup.io/blog/manipulate-excel-files-in-php)
- [How to Read, Decode, Encode, and Write JSON Files in PHP?](https://www.nidup.io/blog/manipulate-json-files-in-php)

## Installation 📦

Download the source code:

```
git clone git@github.com:nidup/manipulate-google-sheets-api-in-php.git
cd manipulate-google-sheets-api-in-php
```

Then the installation comes into 2 flavors, directly on your host or using docker.

Once installed the commands are the same, some docker shortcuts are provided in `.docker/bin`.

### Install directly on your system (option A) 💻

Install the PHP dependencies:

```
composer install
```

### Install with docker & docker-compose (option B) 🐋

Build the docker image and install the PHP dependencies:

```
docker-compose up -d
.docker/bin/composer install
```

## Use the console commands 🚀

Use `bin/console` or `.docker/bin/console` to launch a command.

List the commands:
```
bin/console --env=prod
[...]
nidup
  nidup:google-sheets:manipulate-google-sheets  Manipulate Google Sheets
[...]
```

The example of data is in data/movies-100.csv, it can be imported in a new google sheet.

Then the google sheet id can be used as the argument of the following command.

Launch a command:
```
bin/console nidup:google-sheets:manipulate-google-sheets myGoogleSheetId --env=prod
```

This command contains various examples that can be commented out to read, write, filter, etc.

We use the prod environment here to have the most efficient execution.
