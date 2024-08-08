
# Project Overview 
What is LEMP?

LEMP is a powerful and popular open-source web application stack.

 It stands for:   

**Linux**: The operating system foundation, providing the environment for the other components.  &#x0A;  

**Nginx** (pronounced "engine-x"): A high-performance web server known for its speed and efficiency in handling concurrent connections. &#x0A;

**MariaDB**: A community-developed fork of MySQL, offering a robust and reliable relational database management system. &#x0A;   

**PHP**: A widely-used server-side scripting language for dynamic web content generation. &#x0A;    
How Does LEMP Work?

Client Request: A user's web browser sends a request for a webpage (e.g., by typing a URL).

Nginx Handles Request: Nginx receives the request and determines how to handle it. If it's a static file (like an image or CSS), Nginx serves it directly. If it's a dynamic request, it passes it 
to PHP.   

PHP Processes Request: PHP processes the request, often interacting with MariaDB to fetch or store data. PHP then generates the HTML output for the webpage.   

Nginx Delivers Response: Nginx sends the HTML response back to the 
user's browser, which renders the webpage.

Why Choose LEMP?

Performance: Nginx is renowned for its speed and ability to handle high traffic loads.   

Flexibility: Each component can be easily customized and configured to suit your specific needs.   

Cost-Effective: All LEMP components are free and open-source.   

Scalability: The stack can be scaled horizontally or vertically to accommodate growth.

Security: With proper configuration, LEMP offers a secure environment for web applications.   

Community Support: There's a vast online community of LEMP users and developers to help you with any issues or questions.

**Use Cases:**

LEMP is a versatile stack suitable for a wide range of web applications, including:

-Content Management Systems (WordPress, Drupal)

-E-commerce platforms (Magento, WooCommerce)

-Custom web applications

-API servers
## Steps


**Install Required Dependencies:**

```
sudo apt update

```

```
sudo apt install nginx mariadb-server mariadb-client php8.1 php8.1-fpm php8.1-mysql php-common php8.1-cli php8.1-common php8.1-opcache php8.1-readline php8.1-mbstring php8.1-xml php8.1-gd php8.1-curl -y

```


**Start and enable required services**

```
sudo systemctl start nginx mariadb php8.1-fpm

```
```
sudo systemctl enable nginx mariadb php8.1-fpm
```

**Configure Nginx**

- Download all files in SRC Directory into your target machine

- Create a Directory with a name of your choice where Nginx usually Serve its files. 

```
mkdir -p /var/www/[your-website-name]/html

```
- Copy all downloaded code into the directory you just created.

- Configure Required ownership Permission on that Directory including its files.

```
sudo chown -R www-data:$USER /var/www/[your-website-name]/html/

```

```
sudo chmod 755 -R /var/www/[your-website-name]/html/

```
- Configure The server block of Nginx

```
sudo nano /etc/nginx/sites-available/[your-website-name]

```

**Add the following Code**

```
server {
    listen 80;
    server_name [your-machine-ip or localhost];


    root /var/www/demo_website/html; # Replace with the actual path to your website files
    index register.html register.php  login.html  login.php  style.css  welcome.php logout.php;
    location / {
        try_files $uri $uri/ /index.php$is_args$args;
    }

    location ~ \.php$ {
    include snippets/fastcgi-php.conf;
    fastcgi_pass unix:/var/run/php/php8.1-fpm.sock;
    fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
    include fastcgi_params;
     }

}

```

- Redirect the default index page on nginx to your website frontend

```
sudo unlink /etc/nginx/sites-enabled/default

```

```
sudo ln -s /etc/nginx/sites-available/[your-server-block-file-name] /etc/nginx/sites-enabled/

```

- Check nginx configurations and reload the services

```
sudo nginx -t 

```

``` 
sudo systemctl reload nginx

```


**Configure Database and deploy the required tables 

- ensure the mariadb service is properly installed and working

```
sudo systemctl status mariadb 

```

- install mariadb database service and configure the root password

```
sudo mysql_secure_installation

```
accept all defaults by hitting and when prompt, set the root password.

- relogin with root user into your mariadb server

```
sudo mysql -u root -p 

```

- Create a database

```
CREATE DATABASE [db-name];

```

- Create DB user with password

```
CREATE USER [choose-user-name]@localhost IDENTIFIED BY '[chooose a password]';

```

- Grant permissions for that user as admin of all database server

```
GRANT ALL PRIVILEGES ON *.* TO '[user-name]'@[localhost] IDENTIFIED BY '[password-of-user]';
```

- Grant permssions on DB itself for the user

```
GRANT ALL PRIVILEGES ON [db-name].* TO [username]@localhost;

```

- Refresh your DB server in order to permissions to take effect:

```
FLUSH PRIVILEGES;

```

```
EXIT

```

- relogin with the user you just created

```
sudo mysql -u [user-name] -p 

```
- Create table based on the schema in your website

```

CREATE TABLE IF NOT EXISTS users (
    username VARCHAR(50),
    password VARCHAR(100),
    category VARCHAR(50)
);

```

- exit the db server

``` 
exit 

```

**Configure your website to read from and write to database**

- Change the following lines in **login.php**

```
  // Database connection parameters
    $servername = "localhost"; // Change this if your database is hosted elsewhere
    $db_username = "replace-it-with-username-db"; // Your MariaDB username
    $db_password = "replace-it-with-password-db"; // Your MariaDB password
    $database = "replace-it-with-db-name"; // Your MariaDB database name

```

- Change the following lines in **register.php**

```
// MariaDB database connection settings
$servername = "localhost"; // Change this to the IP address of your MariaDB server
$username = "replace-it-with-username-db"; // Change this to your MariaDB username
$password = "replace-it-with-password-db"; // Change this to your MariaDB password
$database = "replace-it-with-db-name"; // Change this to your MariaDB database name

```

- Change the following lines in **welcome.php**

```
$host = 'localhost'; // Assuming MariaDB is running on the same machine
$dbname = "replace-it-with-password-db"; // Your MariaDB database name
$user = "replace-it-with-username-db"; // Your MariaDB username
$password = "replace-it-with-password-db"; // Your MariaDB password

```


- Restart all services and log into your website

```
sudo systemctl restart mariadb 

```
```
sudo systemctl reload nginx php8.1-fpm 
```

- Type into your browser 

``` 
http://machine-ip

```

** Now you are logged in **

Thank you