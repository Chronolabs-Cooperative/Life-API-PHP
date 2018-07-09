## Chronolabs Cooperative presents

# Life Streams + Media REST API Services

## Version: 2.0.11 (pre-alpha)

### Author: Dr. Simon Antony Roberts <simon@snails.email>

#### Demo: http://life.snails.email

This is an API Service for conducting search or retriving URL/URI's for Streaming service around the internet, you can use it to find one of our life media sources anytime, and remember to use it wisely. It will allow you to search for streams as well as provision a list of current stations.

It will output a *.pls for stations individually.

# Apache Mod Rewrite (SEO Friendly URLS)

The follow lines go in your API_ROOT_PATH/.htaccess

    php_value memory_limit 16M
    php_value upload_max_filesize 1M
    php_value post_max_size 1M
    php_value error_reporting 0
    php_value display_errors 0
    
    RewriteEngine On
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^v2/(radio|audio|sound)/(.*?)/(.*?)/(json|xml|serial|raw).api index.php?source=$1&mode=$2&basis=$3&output=$4 [L,NC,QSA]
    RewriteRule ^v2/(radio|audio|sound)/(.*?)/(json|xml|serial|raw).api index.php?source=$1&mode=$2&basis=&output=$3 [L,NC,QSA]
    
## Licensing

 * This is released under General Public License 3 - GPL3 - Only!

# Installation

Copy the contents of this archive/repository to the run time environment, configue apache2, ngix or iis to resolve the path of this repository and run the HTML Installer.
