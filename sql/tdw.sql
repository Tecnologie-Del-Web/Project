-- Elimino il database se già esistente
DROP DATABASE IF EXISTS tdw;

-- Creo il database e lo seleziono come default
CREATE DATABASE tdw;
USE tdw;

CREATE TABLE `user`
(
    user_id       INTEGER UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    email_address VARCHAR(25) UNIQUE NOT NULL,
    `name`        VARCHAR(50)        NOT NULL,
    surname       VARCHAR(50)        NOT NULL,
    phone_number  VARCHAR(15)        NOT NULL,
    username      VARCHAR(30) UNIQUE NULL,
    `password`    VARCHAR(50)        NOT NULL
);

CREATE TABLE payment_method
(
    payment_id   INTEGER UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    payment_code VARCHAR(10) UNIQUE NOT NULL,
    `type`       VARCHAR(20)        NOT NULL,
    credentials  VARCHAR(100)       NOT NULL,
    validity     DATETIME           NOT NULL,
    user_id      INTEGER UNSIGNED   NOT NULL,
    FOREIGN KEY (user_id)
        REFERENCES `user` (user_id)
        ON DELETE NO ACTION ON UPDATE CASCADE
);

CREATE TABLE coupon
(
    coupon_id       INTEGER UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    coupon_code     VARCHAR(20) UNIQUE NOT NULL,
    percentage      DECIMAL(5, 2)      NOT NULL,
    start_date      DATETIME           NOT NULL,
    expiration_date DATETIME           NOT NULL,
    `description`   TEXT               NULL,
    CHECK (percentage BETWEEN 1.00 AND 100.00),
    CHECK ( start_date < coupon.expiration_date )
);

CREATE TABLE `order`
(
    order_id        INTEGER UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    order_code      VARCHAR(20) UNIQUE                       NOT NULL,
    updated_at      DATETIME                                 NOT NULL,
    total           FLOAT                                    NOT NULL,
    progress_status ENUM ('placed', 'processing', 'shipped') NOT NULL,
    user_id         INTEGER UNSIGNED                         NOT NULL,
    payment_id      INTEGER UNSIGNED                         NOT NULL,
    coupon_id       INTEGER UNSIGNED                         NULL,
    FOREIGN KEY (user_id)
        REFERENCES `user` (user_id)
        ON DELETE NO ACTION ON UPDATE CASCADE,
    FOREIGN KEY (payment_id)
        REFERENCES payment_method (payment_id)
        ON DELETE NO ACTION ON UPDATE CASCADE,
    FOREIGN KEY (coupon_id)
        REFERENCES coupon (coupon_id)
        ON DELETE NO ACTION ON UPDATE CASCADE
);

CREATE TABLE brand
(
    brand_id      INTEGER UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    brand_name    VARCHAR(255) UNIQUE NOT NULL,
    brand_image   VARCHAR(50)         NOT NULL,
    website       VARCHAR(50)         NOT NULL,
    phone_number  VARCHAR(15)         NOT NULL,
    email_address VARCHAR(25)         NOT NULL,
    address       VARCHAR(50)         NOT NULL
);

CREATE TABLE category
(
    category_id          INTEGER UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    category_name        VARCHAR(20) UNIQUE NOT NULL,
    category_image       VARCHAR(255)       NOT NULL,
    category_description TEXT               NOT NULL
);

CREATE TABLE product
(
    product_id          INTEGER UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    product_name        VARCHAR(50)      NOT NULL,
    price               FLOAT            NOT NULL,
    quantity_available  SMALLINT         NOT NULL,
    product_description TEXT             NOT NULL,
    sku                 VARCHAR(20)      NOT NULL,
    brand_id            INTEGER UNSIGNED NOT NULL,
    category_id         INTEGER UNSIGNED NOT NULL,
    FOREIGN KEY (brand_id)
        REFERENCES brand (brand_id)
        ON DELETE NO ACTION ON UPDATE CASCADE,
    FOREIGN KEY (category_id)
        REFERENCES category (category_id)
        ON DELETE NO ACTION ON UPDATE CASCADE,
    UNIQUE (product_name, brand_id)
);

CREATE TABLE product_image
(
    image_id   INTEGER UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    file_name  VARCHAR(255)     NOT NULL,
    `type`     VARCHAR(20)      NOT NULL,
    product_id INTEGER UNSIGNED NOT NULL,
    FOREIGN KEY (product_id)
        REFERENCES product (product_id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    UNIQUE (file_name, product_id)
);

CREATE TABLE offer
(
    offer_id      INTEGER UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `description` TEXT             NULL,
    percentage    DECIMAL(5, 2)    NOT NULL,
    start_date    DATETIME         NOT NULL,
    end_date      DATETIME         NOT NULL,
    product_id    INTEGER UNSIGNED NOT NULL,
    CHECK (percentage BETWEEN 1.00 AND 100.00),
    CHECK (start_date < offer.end_date),
    FOREIGN KEY (product_id)
        REFERENCES product (product_id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    UNIQUE (start_date, product_id)
);

CREATE TABLE product_review
(
    review_id  INTEGER UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `text`     TEXT             NOT NULL,
    rating     TINYINT          NOT NULL,
    `date`     DATETIME         NOT NULL,
    user_id    INTEGER UNSIGNED NOT NULL,
    product_id INTEGER UNSIGNED NOT NULL,
    FOREIGN KEY (user_id)
        REFERENCES `user` (user_id)
        ON DELETE NO ACTION ON UPDATE CASCADE,
    FOREIGN KEY (product_id)
        REFERENCES product (product_id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    CHECK (rating BETWEEN 1 AND 5),
    UNIQUE (user_id, product_id)
);

CREATE TABLE shipment_address
(
    address_id  INTEGER UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    city        VARCHAR(30)      NOT NULL,
    address     VARCHAR(50)      NOT NULL,
    province    VARCHAR(30)      NOT NULL,
    country     VARCHAR(30)      NOT NULL,
    postal_code VARCHAR(10)      NOT NULL,
    user_id     INTEGER UNSIGNED NOT NULL,
    FOREIGN KEY (user_id)
        REFERENCES `user` (user_id)
        ON DELETE NO ACTION ON UPDATE CASCADE,
    UNIQUE (city, address, country)
);

CREATE TABLE customization
(
    customization_id INTEGER UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    personal_image   BLOB         NULL,
    phone_number     VARCHAR(15)  NOT NULL,
    email_address    VARCHAR(25)  NOT NULL,
    about_info       TEXT         NULL,
    personal_address VARCHAR(50)  NOT NULL,
    logo             VARCHAR(255) NOT NULL,
    site_name        VARCHAR(255) NOT NULL
);

CREATE TABLE `group`
(
    group_id          INTEGER UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    group_name        VARCHAR(20) NOT NULL,
    group_description TEXT        NOT NULL
);

CREATE TABLE user_has_group
(
    user_id  INTEGER UNSIGNED,
    group_id INTEGER UNSIGNED,
    PRIMARY KEY (user_id, group_id),
    FOREIGN KEY (user_id)
        REFERENCES `user` (user_id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (group_id)
        REFERENCES `group` (group_id)
        ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE TABLE service
(
    service_id          INTEGER UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    tag                 VARCHAR(100)        NOT NULL,
    url                 VARCHAR(255) UNIQUE NOT NULL,
    script              VARCHAR(255)        NOT NULL,
    callback            VARCHAR(255)        NOT NULL,
    service_description TEXT                NULL
);

CREATE TABLE service_has_group (
    service_id INTEGER UNSIGNED,
    group_id INTEGER UNSIGNED,
    PRIMARY KEY (service_id , group_id),
    FOREIGN KEY (service_id)
        REFERENCES service (service_id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (group_id)
        REFERENCES `group` (group_id)
        ON DELETE CASCADE ON UPDATE CASCADE
);

-- contains
CREATE TABLE order_product
(
    order_id   INTEGER UNSIGNED,
    product_id INTEGER UNSIGNED,
    quantity   SMALLINT NOT NULL,
    price      FLOAT    NOT NULL,
    PRIMARY KEY (order_id, product_id),
    FOREIGN KEY (order_id)
        REFERENCES `order` (order_id)
        ON DELETE NO ACTION ON UPDATE CASCADE,
    FOREIGN KEY (product_id)
        REFERENCES product (product_id)
        ON DELETE NO ACTION ON UPDATE CASCADE
);

-- adds to wishlist
CREATE TABLE user_product_wishlist
(
    user_id    INTEGER UNSIGNED,
    product_id INTEGER UNSIGNED,
    `date`     DATETIME NOT NULL,
    PRIMARY KEY (user_id, product_id),
    FOREIGN KEY (user_id)
        REFERENCES user (user_id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (product_id)
        REFERENCES product (product_id)
        ON DELETE NO ACTION ON UPDATE CASCADE
);

-- adds to cart
CREATE TABLE user_product_cart
(
    user_id    INTEGER UNSIGNED,
    product_id INTEGER UNSIGNED,
    `date`     DATETIME NOT NULL,
    quantity   SMALLINT NOT NULL,
    subtotal   FLOAT    NOT NULL,
    PRIMARY KEY (user_id, product_id),
    FOREIGN KEY (user_id)
        REFERENCES `user` (user_id)
        ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (product_id)
        REFERENCES product (product_id)
        ON DELETE NO ACTION ON UPDATE CASCADE
);

CREATE TABLE slider
(
    slider_id        INTEGER UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    background_image VARCHAR(255),
    front_image      VARCHAR(255),
    subtitle         VARCHAR(255),
    title            VARCHAR(255),
    top_title        VARCHAR(255),
    redirect_url     VARCHAR(255)
)