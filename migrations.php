<?php 

// Include config file
include 'config.php';

/* Attempt to connect to MySQL database */
$link = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD);

// Check connection
if($link === false){
    die("ERROR: Could not connect. " . $link->connect_error);
}

// Create database bookrental
$sql = "CREATE DATABASE IF NOT EXISTS bookrental";
if (mysqli_query($link, $sql)) {
  echo "Database created successfully";
} else {
  echo "Error creating database: " . mysqli_error($link);
}

echo"<br />";

// Use database
$sql = "USE bookrental";
if (mysqli_query($link, $sql)) {
    echo "Database changed successfully";
} else {
    echo "Error using database: " . mysqli_error($link);
}

echo"<br />";

// Create tables

// Customer
$sql = "CREATE TABLE IF NOT EXISTS customer (
	user_id INT NOT NULL AUTO_INCREMENT,
    email VARCHAR(40) NOT NULL,
    first_name VARCHAR(255) NOT NULL,
    last_name VARCHAR(255) NOT NULL,
    country VARCHAR(255),
    state VARCHAR(255),
    city VARCHAR(255),
    password VARCHAR(255) NOT NULL,
    PRIMARY KEY (user_id),
    UNIQUE (email)
);";
if (mysqli_query($link, $sql)) {
    echo "Customer user table created successfully";
} else {
    echo "Error creating customer table: " . mysqli_error($link);
}

echo"<br />";

// Phone number
$sql = "CREATE TABLE IF NOT EXISTS phone_no (
    phone_no VARCHAR(15),
    user_id INT NOT NULL,
    PRIMARY KEY (phone_no),
    FOREIGN KEY (user_id) REFERENCES customer(user_id)
    ON DELETE CASCADE
);";
if (mysqli_query($link, $sql)) {
    echo "Phone number table created successfully";
} else {
    echo "Error creating phone number table: " . mysqli_error($link);
}

echo"<br />";

// Book
$sql = "CREATE TABLE IF NOT EXISTS book (
	book_id INT NOT NULL AUTO_INCREMENT,
    user_id INT,
    title VARCHAR(255) NOT NULL,
    cover_image varchar(255) COLLATE utf8_unicode_ci,
    info VARCHAR(500),
    author VARCHAR(255),
    lang VARCHAR(255),
    no_of_pages INT DEFAULT 0,
    PRIMARY KEY (book_id)
);";
if (mysqli_query($link, $sql)) {
    echo "Book table created successfully";
} else {
    echo "Error creating book table: " . mysqli_error($link);
}

echo"<br />";

// Cart Items
$sql = "CREATE TABLE IF NOT EXISTS cart_item (
    cart_item_id INT ,
    is_ordered BOOLEAN DEFAULT FALSE,
    order_id INT NOT NULL,
    user_id INT NOT NULL,
    PRIMARY KEY (cart_item_id),
    FOREIGN KEY (user_id) REFERENCES customer(user_id)
    ON DELETE CASCADE
);";
if (mysqli_query($link, $sql)) {
    echo "Cart item table created successfully";
} else {
    echo "Error creating cart item table: " . mysqli_error($link);
}

echo"<br />";

// Book for sale
$sql = "CREATE TABLE IF NOT EXISTS book_for_sale(
    price INT NOT NULL,
    discount_price INT,
    cart_item_id INT,
    book_id INT NOT NULL,
    FOREIGN KEY (cart_item_id) REFERENCES cart_item(cart_item_id),
    FOREIGN KEY (book_id) REFERENCES book(book_id)
    ON DELETE CASCADE
);";
if (mysqli_query($link, $sql)) {
    echo "Book for sale table created successfully";
} else {
    echo "Error creating book for sale table: " . mysqli_error($link);
}

echo"<br />";

// Book for rent
$sql = "CREATE TABLE IF NOT EXISTS book_for_rent (
    monthly_rate INT NOT NULL,
    rating INT DEFAULT 0,
    is_available BOOLEAN DEFAULT TRUE,
    book_id INT NOT NULL,
    FOREIGN KEY (book_id) REFERENCES book(book_id)
    ON DELETE CASCADE
);";
if (mysqli_query($link, $sql)) {
    echo "Book for rent table created successfully";
} else {
    echo "Error creating book for rent table: " . mysqli_error($link);
}

echo"<br />";

// Queue
$sql = "CREATE TABLE IF NOT EXISTS queue (
    book_id INT NOT NULL,
    user_id INT NOT NULL,
    status ENUM ('Waiting','Pending', 'Cancelled', 'Currently renting', 'Returned'),
    date_of_request DATETIME DEFAULT NOW(),
    date_granted DATETIME,
    FOREIGN KEY (user_id) REFERENCES customer(user_id),
    FOREIGN KEY (book_id) REFERENCES book(book_id)
    ON DELETE CASCADE
);";
if (mysqli_query($link, $sql)) {
    echo "Queue table created successfully";
} else {
    echo "Error creating queue table: " . mysqli_error($link);
}

echo"<br />";

// Is Rented
$sql = "CREATE TABLE IF NOT EXISTS is_rented (
    book_rented_id INT NOT NULL,
    cart_item_id INT NOT NULL,
    no_of_months INT DEFAULT 1,
    date_rented DATETIME DEFAULT NOW(),
    FOREIGN KEY (cart_item_id) REFERENCES cart_item(cart_item_id),
    FOREIGN KEY (book_rented_id) REFERENCES book(book_id)
    ON DELETE CASCADE
);";
if (mysqli_query($link, $sql)) {
    echo "Is rented table created successfully";
} else {
    echo "Error creating is rented table: " . mysqli_error($link);
}

echo"<br />";

// Is sold
$sql = "CREATE TABLE IF NOT EXISTS is_rented (
    book_sold_id INT NOT NULL,
    cart_item_id INT NOT NULL,
    FOREIGN KEY (cart_item_id) REFERENCES cart_item(cart_item_id),
    FOREIGN KEY (book_sold_id) REFERENCES book(book_id)
    ON DELETE CASCADE
);";
if (mysqli_query($link, $sql)) {
    echo "Is sold table created successfully";
} else {
    echo "Error creating is rented table: " . mysqli_error($link);
}

echo"<br />";

// Order items
$sql = "CREATE TABLE IF NOT EXISTS order_item (
    order_id INT ,
    user_id INT NOT NULL,
    street VARCHAR(255) NOT NULL,
    zipcode VARCHAR(6) NOT NULL,
    state VARCHAR(255) NOT NULL,
    PRIMARY KEY (order_id),
    FOREIGN KEY (user_id) REFERENCES customer(user_id)
    ON DELETE CASCADE
);";
if (mysqli_query($link, $sql)) {
    echo "Order table created successfully";
} else {
    echo "Error creating order items table: " . mysqli_error($link);
}

echo"<br />";

// Constraint
$sql = "ALTER TABLE cart_item
ADD CONSTRAINT FOREIGN KEY (order_id) REFERENCES order_item(order_id);";
if (mysqli_query($link, $sql)) {
    echo "Constraint added successfully";
} else {
    echo "Error adding constraint: " . mysqli_error($link);
}

echo"<br />";

// Payment
$sql = "CREATE TABLE IF NOT EXISTS payment(
    payment_date DATETIME  DEFAULT NOW(),
    payment_amount FLOAT,
    mode_of_payment ENUM ('Net Banking','Cash on Delivery','Credit Card'),
    order_id INT,
    FOREIGN KEY (order_id) REFERENCES order_item(order_id)
);";
if (mysqli_query($link, $sql)) {
    echo "Payment table created successfully";
} else {
    echo "Error creating payment table: " . mysqli_error($link);
}

?>