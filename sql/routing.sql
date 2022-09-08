USE tdw;

-- Pulisce la tabella prima di caricare il routing
DELETE FROM service WHERE TRUE;

INSERT INTO service (tag, url, script, callback, service_description) VALUES ('Home', '/', 'home.php', 'home', '');
INSERT INTO service (tag, url, script, callback, service_description) VALUES ('Login', '/sign-in', 'auth/access.php', 'signIn', 'Login');
INSERT INTO service (tag, url, script, callback, service_description) VALUES ('Registration', '/sign-up', 'auth/access.php', 'signUp', 'Registration');
INSERT INTO service (tag, url, script, callback, service_description) VALUES ('Logout', '/sign-out', 'auth/access.php', 'signOut', 'Logout');
INSERT INTO service (tag, url, script, callback, service_description) VALUES ('Product', '/product/%', 'product.php', 'product', 'Product Page');
INSERT INTO service (tag, url, script, callback, service_description) VALUES ('Search in Category', '/products%', 'products.php', 'products', 'Product Search Page');
INSERT INTO service (tag, url, script, callback, service_description) VALUES ('Dashboard', '/admin', 'admin/index.php', 'admin', 'Dashboard');
INSERT INTO service (tag, url, script, callback, service_description) VALUES ('Categories', '/categories', 'categories.php', 'categories', 'Categories Page');
INSERT INTO service (tag, url, script, callback, service_description) VALUES ('Category', '/category/%', 'category.php', 'category', 'Category Page');
INSERT INTO service (tag, url, script, callback, service_description) VALUES ('Brand', '/brand/%', 'brand.php', 'brand', 'Brand Page');
INSERT INTO service (tag, url, script, callback, service_description) VALUES ('Contact Us', '/contact', 'contact.php', 'contact', 'Contacts Page');
INSERT INTO service (tag, url, script, callback, service_description) VALUES ('About', '/about', 'about.php', 'about', 'About Page');


INSERT INTO service (tag, url, script, callback, service_description) VALUES ('Profile', '/profile', 'user/profile.php', 'profile', 'Personal Profile Page');

INSERT INTO service (tag, url, script, callback, service_description) VALUES ('Cart', '/cart', 'user/cart.php', 'cart', 'Shopping Cart Page');
INSERT INTO service (tag, url, script, callback, service_description) VALUES ('Add Product To Cart', '/cart/add', 'user/cart.php', 'add', 'Add Product To Cart');
INSERT INTO service (tag, url, script, callback, service_description) VALUES ('Clear Cart', '/cart/clear', 'user/cart.php', 'clear', 'Clear Cart');
INSERT INTO service (tag, url, script, callback, service_description) VALUES ('Edit Product Quantity', '/cart/quantity', 'user/cart.php', 'editQuantity', 'Edit Product Quantity');
INSERT INTO service (tag, url, script, callback, service_description) VALUES ('Remove Product From Cart', '/cart/remove', 'user/cart.php', 'remove', 'Remove Product From Cart');
INSERT INTO service (tag, url, script, callback, service_description) VALUES ('Apply Coupon to Order', '/cart/coupon/apply', 'user/cart.php', 'applyCoupon', 'Apply Coupon To Order');

INSERT INTO service (tag, url, script, callback, service_description) VALUES ('Wishlist', '/wishlist', 'user/wishlist.php', 'wishlist', 'Personal Wishlist Page');

INSERT INTO service (tag, url, script, callback, service_description) VALUES ('Checkout', '/checkout', 'user/checkout.php', 'checkout', 'Checkout Page');

INSERT INTO service (tag, url, script, callback, service_description) VALUES ('Order Completion', '/order', 'user/order.php', 'order', 'Order Completion Page');

INSERT INTO service (tag, url, script, callback, service_description) VALUES ('Add Review', '/reviews/add', 'user/review.php', 'add', 'Review Page (Add Review)');


INSERT INTO service (tag, service_description, url, script, callback) VALUES ('Gestione utenti' , 'Visualizza utenti', '/admin/users', 'admin/users.php', 'users');
INSERT INTO service (tag, service_description, url, script, callback) VALUES ('Gestione gruppi', 'Visualizza gruppi', '/admin/groups', 'admin/groups.php', 'groups');
INSERT INTO service (tag, service_description, url, script, callback) VALUES ('Gestione prodotti', 'Visualizza prodotti', '/admin/products', 'admin/products.php', 'products');
INSERT INTO service (tag, service_description, url, script, callback) VALUES ('Gestione offerte', 'Visualizza offerte', '/admin/offers', 'admin/offers.php', 'offers');
INSERT INTO service (tag, service_description, url, script, callback) VALUES ('Gestione ordini', 'Visualizza ordini', '/admin/orders', 'admin/orders.php', 'orders');
INSERT INTO service (tag, service_description, url, script, callback) VALUES ('Gestione categorie', 'Visualizza singola categoria', '/admin/categories/%', 'admin/categories.php', 'category');

INSERT INTO service ( tag, service_description, url, script, callback) VALUES ( 'Gestione Categorie', 'Categorie', '/admin/categories', 'admin/categories.php', 'categories');

INSERT INTO service (tag, service_description, url, script, callback) VALUES ('Gestione categorie', 'Aggiungi categoria', '/admin/categories/create', 'admin/categories.php', 'create');
INSERT INTO service (tag, service_description, url, script, callback) VALUES ('Gestione prodotto', 'Aggiungi prodotto', '/admin/products/create', 'admin/products.php', 'create');
INSERT INTO service (tag, service_description, url, script, callback) VALUES ('Gestione categorie', 'Modifica categoria', '/admin/categories/%/edit', 'admin/categories.php', 'edit');
INSERT INTO service (tag, service_description, url, script, callback) VALUES ('Gestione categorie', 'Cancella categoria', '/admin/categories/%/delete', 'admin/categories.php', 'delete');


INSERT INTO service (tag, service_description, url, script, callback) VALUES ('Gestione offerte', 'Modifica offerta', '/admin/offers/%/edit', 'admin/offers.php', 'edit');
INSERT INTO service (tag, service_description, url, script, callback) VALUES ('Gestione offerte', 'Cancella offerta', '/admin/offers/%/delete', 'admin/offers.php', 'delete');
INSERT INTO service (tag, service_description, url, script, callback) VALUES ('Gestione offerte', 'Aggiungi offerta', '/admin/offers/create', 'admin/offers.php', 'create');

INSERT INTO service ( tag, service_description, url, script, callback) VALUES ('Gestione brand', 'Visualizza tutti i brand', '/admin/brands', 'admin/brands.php', 'brands');
INSERT INTO service (tag, service_description, url, script, callback) VALUES ('Gestione brand', 'Modifica brand', '/admin/brands/%/edit', 'admin/brands.php', 'edit');
INSERT INTO service (tag, service_description, url, script, callback) VALUES ('Gestione brand', 'Cancella brand', '/admin/brands/%/delete', 'admin/brands.php', 'delete');
INSERT INTO service (tag, service_description, url, script, callback) VALUES ('Gestione brand', 'Aggiungi brand', '/admin/brands/create', 'admin/brands.php', 'create');
INSERT INTO service (tag, service_description, url, script, callback) VALUES ('Gestione brand', 'Visualizza brand', '/admin/brands/%', 'admin/brands.php', 'brand');
INSERT INTO service (tag, service_description, url, script, callback) VALUES ('Gestione coupon', 'Visualizza coupon', '/admin/coupon/%', 'admin/coupon.php', 'coupon');
INSERT INTO service ( tag, service_description, url, script, callback) VALUES ('Gestione coupon', 'Visualizza tutti i coupon', '/admin/coupons', 'admin/coupons.php', 'coupons');
INSERT INTO service (tag, service_description, url, script, callback) VALUES ('Gestione coupon', 'Modifica coupon', '/admin/coupons/%/edit', 'admin/coupons.php', 'edit');
INSERT INTO service (tag, service_description, url, script, callback) VALUES ('Gestione coupon', 'Cancella coupon', '/admin/coupons/%/delete', 'admin/coupons.php', 'delete');
INSERT INTO service (tag, service_description, url, script, callback) VALUES ('Gestione coupon', 'Aggiungi coupon', '/admin/coupons/create', 'admin/coupons.php', 'create');
INSERT INTO service (tag, service_description, url, script, callback) VALUES ('Gestione coupon', 'Visualizza coupon', '/admin/coupons/%', 'admin/coupons.php', 'coupon');
INSERT INTO service (tag, service_description, url, script, callback) VALUES ('Gestione slider', 'Modifica slider', '/admin/slider/%/edit', 'admin/slider.php', 'edit');
INSERT INTO service (tag, service_description, url, script, callback) VALUES ('Gestione slider', 'Cancella slider', '/admin/slider/%/delete', 'admin/slider.php', 'delete');
INSERT INTO service (tag, service_description, url, script, callback) VALUES ('Gestione slider', 'Aggiungi slider', '/admin/slider/create', 'admin/slider.php', 'create');
INSERT INTO service (tag, service_description, url, script, callback) VALUES ('Gestione slider', 'Aggiungi slider', '/admin/slider', 'admin/slider.php', 'slider');

INSERT INTO service (tag, service_description, url, script, callback) VALUES ('Gestione recensioni', 'Visualizza recensioni', '/admin/reviews', 'admin/reviews.php', 'reviews');
