# Simple CRM

A simple CRM system that will collect customer data and build their profiles.

## Features
Here is a list of the plugin features.

### General features
* Collect customer data via the frontend form
* Form can be added anywhere via shortcode `[et-simple-crm-form]`
* You can use multiple forms per page
* This form, when submitted, will save the submission as a private post as part of a custom post type called “Customer.”
* These posts can then be viewed, managed, tagged and categorized by the admin in the WordPress Dashboard.
* You can use two form styles: No styles to fit any theme design and styled form with the plugin default style.
* Form is optimized for the mobile usage

### Dev features
* Override form labels/titles/attributes via shortcode attributes
* Grunt commands to build and deploy the plugin (create the plugin .zip file for the production)
* Client side and server side check for the required fields values
* Check if the submitted email already exists in the DB, so we don't have doubled records for the same user 
* Automatically fetch the user current time by the user IP and use it as a form submitted time
* Form ajax submit
* Customer records are viewable by the Administrator role only
* Edit Customer fields in the admin using the WP custom fields
* Plugin is translation ready. Already prepared .pot file
* If `WP_DEBUG_LOG` is enabled, then all errors will be logged to the file: `wp-content/uploads/et-simple-crm/log.log`
* Custom post type is added to record all form sumissions
* Custom taxonomies for tags and categories are added only for this post type. You can use them to tag and categorize the
post type records.

### Form fields
* Name (required)
* Phone number (required)
* Email address (required)
* Desired budget (required)
* Message

### Override form labels/titles/attributes via shortcode attributes
You can override multiple form functionalities by adding the attributes into the shortcode. That will apply only to the 
form that this shortcode render, not all forms.

#### Example of the shortcode with no attributes which uses the default values
```text
[et-simple-crm-form]
```

#### Example of the shortcode with all available attributes
```text
[et-simple-crm-form name="Name value" phone="0123456789" email="email@email.com" budget="10.000 usd" label_name="Name label override" message="Message text override" label_phone="Phone label override" label_email="Email label override" label_budget="Budget label override" label_message="Message label override" message_rows="20" message_cols="10" label_button="Button text override"  maxlength_name="20" maxlength_phone="20" maxlength_email="20" maxlength_budget="20" maxlength_message="20"  styled_form="1"]Message real value[/et-simple-crm-form]
```

#### Available attributes for overrides
Just add any of these attributes to the shortcode and pass the value you want to use instead of the default one:
* `name` - Override name value field
* `phone` - Override phone value field
* `email` - Override email value field
* `budget` - Override budget value field (Numbers only)
* `message` - Override textarea value. You can also use the shortcode `$content` value between shortcodes opening and cosing tags
* `label_name` - Override name field label 
* `label_phone` - Override phone field label 
* `label_email` - Override email field label 
* `label_budget` - Override budget field label 
* `label_message` - Override message field label 
* `message_rows` - Set textarea `rows` attribute. May not be visible because of the element css rules
* `message_cols` - Set textarea `cols` attribute. May not be visible because of the element css rules
* `label_button` - Override submit button text
* `maxlength_name` - Set maximum number of characters for the name field
* `maxlength_phone` - Set maximum number of characters for the phone field 
* `maxlength_email` - Set maximum number of characters for the email field
* `maxlength_budget` - Set maximum number of characters for the budget field
* `maxlength_message` - Set maximum number of characters for the message field
* `styled_form` - Set value here to 1 or 0. 1 to use the plugin form styles. 0 To leave form unstyled, so it can fit to the theme styles.


## Installation and dev instructions
You will need to have `composer` installed on your system. Also, in order to build and test plugin, 
you'll need to install its dev dependencies. We will assume [node]( https://nodejs.org/ ) (`$ npm install`) is 
installed and `npm` is added to system path.
 
#### Install composer dependencies in the plugin root folder
```shell
composer install
```
 
#### Install Grunt CLI as global
Install this globally, and you'll have access to the `grunt` command anywhere on your system.
You can also use the local `grunt` installation
```shell
npm install -g grunt-cli
```

#### Install npm in the plugin root folder
This command installs a package, and any packages that it depends on in the local `node_modules` folder. 
The package has a [package-lock.json](https://docs.npmjs.com/files/package-lock.json) and the installation of 
dependencies will be driven by that.
```shell
npm install
```

#### Install Grunt in the plugin root folder
If you haven't used [Grunt](http://gruntjs.com/) before, be sure to check out the [Getting Started](http://gruntjs.com/getting-started) 
guide, as it explains how to create a [Gruntfile](http://gruntjs.com/sample-gruntfile) as well as install and use 
Grunt plugins. Once you're familiar with that process, you may install this plugin with this command:
```shell
npm install grunt
```

## Grunt most used tasks
Below is an overview of the available Grunt tasks that'll be useful in development.

### Build the plugin .zip for the installation via WP plugins screen
Use those two commands to deploy and build the plugin .zip file the installation via WP plugins screen.
The .zip file will be located in the plugin root folder.
```shell
grunt deploy
grunt build
```

### Start development watch process
Run this command to start the watch process. When you change any JS or SCSS file, it will automatically recompile the needed files.
```shell
grunt watch
```

### Compile production ready scripts
Run this command to minify, compile, optimize and make all resources covered by `grunt` production ready 
```shell
grunt watch
```

## Plugin version change
To update plugin version, please modify these files:
* `package.json`
* `et-simple-crm.php` Here you have version defined in the file WP header (PHP comment)

## Using the filters to modify some values in the `config`
You have a few dynamic crated filters in `src/Helpers/Config.php` to override some config values.

## Licence

The PSM Fields library is licensed under the GPL v2 or later.

>This program is free software; you can redistribute it and/or modify it under the terms of the GNU General Public License, version 2, as published by the Free Software Foundation.

>This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.

>You should have received a copy of the GNU General Public License along with this program; if not, write to the Free Software Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA 02110-1301 USA

## Changelog

 * 0.0.1 - 25.07.2020.
    * initial version
    
## TODO
* Write unit tests
