# HeavyD project 

Contains the basic project template that will be used as the base files 
for any HeavyD setup. This is an opiniated basepoint for a light 
project setup. 
It's a personal used project that should be flexible enough to get some 
automation kickstarted. Feel free to steal, borrow or scrap anything in this 
repo for parts. 

# Setting up 
NOTE: Always do this in a separate branch, as mentioned this setup is 
opinionated and can potentially overwrite project files. 
Also, move your settings.php file somewhere safe and make the whole 
filesystem writable if the site was already installed. 

You can get started by adding a .heavyd/composer.json file in any project 
with the following content: 

```
{
  "name": "webct/platform",
  "require": {
    "heavyd/platform": "dev-master"
  },
  "repositories": [
    {
      "type": "vcs",
      "url":  "git@github.com:SurangaPG/heavyd.git"
    }
  ],
  "authors": [
    {
      "name": "Suranga Panagamuwa Gamage",
      "email": "suranga@webct.be"
    }
  ],
  "scripts": {
  },
  "minimum-stability": "dev",
  "prefer-stable": true
}
```
Afterwards run `composer install` in the .heavyd directory. 

Once this is done several files and properties should have appeared 
in the project. If you don't have a build.xml in the project. Add one 
to the root. Otherwise ensure it includes the heavyd one. 

```
<?xml version="1.0" encoding="UTF-8" ?>
<!-- Dummy xml in case it doesn't exist, will just load in the more apptly named 'core' file -->
<project name="HeavyD" default="list">

    <import file=".heavyd/vendor/heavyd/platform/heavyd.project.xml" />

</project>
```

After installing run 

```
cp vendor/heavyd/platform/heavyd.startup.xml ../heavyd.startup.xml
cd .. && ./.heavyd/vendor/bin/phing heavyd-startup:drupal8 -buildfile heavyd.startup.xml
cd .. && rm -rf heavyd.startup.xml
cp vendor/heavyd/platform/heavyd.project.xml ../heavyd.project.xml
```