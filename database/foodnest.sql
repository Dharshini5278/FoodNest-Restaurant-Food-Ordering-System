CREATE DATABASE IF NOT EXISTS foodnest_db;
USE foodnest_db;

CREATE TABLE users (id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(100) NOT NULL, email VARCHAR(120) UNIQUE NOT NULL, password VARCHAR(255) NOT NULL, phone VARCHAR(20) NOT NULL, address TEXT NOT NULL, role ENUM('customer','admin') DEFAULT 'customer', created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP);
CREATE TABLE categories (id INT AUTO_INCREMENT PRIMARY KEY, category_name VARCHAR(80) UNIQUE NOT NULL);
CREATE TABLE foods (id INT AUTO_INCREMENT PRIMARY KEY, category_id INT NOT NULL, food_name VARCHAR(120) NOT NULL, description VARCHAR(255) NOT NULL, price DECIMAL(10,2) NOT NULL, image VARCHAR(255) NOT NULL, availability TINYINT(1) DEFAULT 1, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, FOREIGN KEY (category_id) REFERENCES categories(id) ON DELETE RESTRICT);
CREATE TABLE restaurants (id INT AUTO_INCREMENT PRIMARY KEY, name VARCHAR(120) NOT NULL, description VARCHAR(255) NOT NULL, locality VARCHAR(100) NOT NULL, address VARCHAR(255) NOT NULL, latitude DECIMAL(10,7) NULL, longitude DECIMAL(10,7) NULL, phone VARCHAR(30) NULL, image VARCHAR(255) NULL, cuisine VARCHAR(255) NOT NULL, rating DECIMAL(2,1) NULL, opening_time TIME NULL, closing_time TIME NULL, is_active TINYINT(1) DEFAULT 1, created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP);
CREATE TABLE restaurant_foods (id INT AUTO_INCREMENT PRIMARY KEY, restaurant_id INT NOT NULL, food_id INT NOT NULL, UNIQUE KEY unique_restaurant_food (restaurant_id, food_id), FOREIGN KEY (restaurant_id) REFERENCES restaurants(id) ON DELETE CASCADE, FOREIGN KEY (food_id) REFERENCES foods(id) ON DELETE CASCADE);
CREATE TABLE orders (id INT AUTO_INCREMENT PRIMARY KEY, user_id INT NOT NULL, restaurant_id INT NULL, total_amount DECIMAL(10,2) NOT NULL, delivery_address TEXT NOT NULL, payment_method VARCHAR(30) NOT NULL, order_status ENUM('Pending','Preparing','Out for Delivery','Delivered','Cancelled') DEFAULT 'Pending', order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP, FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE, FOREIGN KEY (restaurant_id) REFERENCES restaurants(id) ON DELETE SET NULL);
CREATE TABLE order_items (id INT AUTO_INCREMENT PRIMARY KEY, order_id INT NOT NULL, food_id INT NOT NULL, quantity INT NOT NULL, price DECIMAL(10,2) NOT NULL, FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE, FOREIGN KEY (food_id) REFERENCES foods(id) ON DELETE RESTRICT);

INSERT INTO categories (category_name) VALUES ('Starters'),('Main Course'),('Pizza'),('Burgers'),('Biriyani'),('Desserts'),('Beverages');
INSERT INTO users (name,email,password,phone,address,role) VALUES ('FoodNest Admin','admin@foodnest.com', '$2y$10$dcwpPj4//C7//iBZh3FKne/D2gWoNvZAw01WrHnWY5h46J2fDdNKG', '9876543210','FoodNest Kitchen','admin'), ('Demo Customer','customer@foodnest.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC7Zk9iE6R6Q1yQ0Wl7u', '9876543211','12 College Road','customer');
INSERT INTO foods (category_id,food_name,description,price,image) VALUES
(3,'Farmhouse Pizza','Loaded with vegetables and mozzarella.',299,'assets/images/food-pizza.jpg'),
(4,'Classic Veg Burger','Crispy patty, fresh lettuce and house sauce.',159,'assets/images/food-burger.jpg'),
(5,'Chicken Biriyani','Aromatic basmati rice with tender chicken.',249,'assets/images/food-biryani.jpg'),
(2,'Creamy Alfredo Pasta','Pasta tossed in a smooth garlic cream sauce.',219,'https://images.unsplash.com/photo-1473093295043-cdd812d0e601?auto=format&fit=crop&w=700&q=80'),
(1,'Paneer Tikka','Char-grilled paneer with peppers and onions.',189,'https://images.unsplash.com/photo-1567188040759-fb8a883dc6d8?auto=format&fit=crop&w=700&q=80'),
(6,'Chocolate Brownie','Warm chocolate brownie for a sweet finish.',99,'assets/images/food-dessert.jpg'),
(7,'Fresh Lime Soda','Cold, refreshing lime soda.',79,'https://images.unsplash.com/photo-1513558161293-cdaf765ed2fd?auto=format&fit=crop&w=700&q=80');
INSERT INTO foods (category_id,food_name,description,price,image) VALUES
(5,'Mutton Biryani','Slow-cooked rice layered with tender mutton and aromatic spices.',329,'assets/images/food-biryani.jpg'),
(1,'Chicken 65','Crisp, spiced chicken bites served hot.',179,'assets/images/food-burger.jpg'),
(2,'Butter Naan','Soft tandoor-baked naan finished with butter.',69,'assets/images/food-biryani.jpg'),
(3,'Margherita Pizza','Tomato, basil and mozzarella on a crisp base.',249,'assets/images/food-pizza.jpg'),
(4,'French Fries','Golden fries with a light seasoning.',109,'assets/images/food-burger.jpg'),
(6,'Gulab Jamun','Warm syrup-soaked milk dumplings.',89,'assets/images/food-dessert.jpg'),
(7,'Mango Lassi','Chilled mango yoghurt drink.',99,'assets/images/food-biryani.jpg');
INSERT INTO foods (category_id,food_name,description,price,image) VALUES
(2,'Masala Dosa','Crisp dosa with spiced potato filling and chutney.',119,'assets/images/food-biryani.jpg'),
(1,'Chicken Kebab','Tender marinated chicken grilled with herbs.',199,'assets/images/food-burger.jpg'),
(3,'Paneer Tikka Pizza','Mozzarella, paneer tikka and roasted peppers.',279,'assets/images/food-pizza.jpg'),
(1,'Gobi 65','Crisp cauliflower tossed in South Indian spices.',149,'assets/images/food-burger.jpg'),
(2,'Idli Sambar','Steamed idlis with warm sambar and chutney.',89,'assets/images/food-biryani.jpg'),
(2,'Veg Noodles','Wok-tossed noodles with seasonal vegetables.',159,'assets/images/food-burger.jpg'),
(7,'Filter Coffee','Strong South Indian filter coffee with milk.',59,'assets/images/food-biryani.jpg'),
(6,'Caramel Custard','Silky baked custard with caramel topping.',119,'assets/images/food-dessert.jpg');
INSERT INTO foods (category_id,food_name,description,price,image) VALUES (4,'Chicken Burger','Grilled chicken patty with lettuce and house sauce.',189,'assets/images/foods/chicken-burger.jpg');
UPDATE foods SET image='assets/images/foods/farmhouse-pizza.jpg' WHERE food_name='Farmhouse Pizza';
UPDATE foods SET image='assets/images/foods/classic-burger.jpg' WHERE food_name='Classic Veg Burger';
UPDATE foods SET food_name='Chicken Biryani',image='assets/images/foods/chicken-biriyani.jpg' WHERE food_name='Chicken Biriyani';
UPDATE foods SET image='assets/images/foods/alfredo-pasta.jpg' WHERE food_name='Creamy Alfredo Pasta';
UPDATE foods SET image='assets/images/foods/paneer-tikka.jpg' WHERE food_name='Paneer Tikka';
UPDATE foods SET image='assets/images/foods/chocolate-brownie.jpg' WHERE food_name='Chocolate Brownie';
UPDATE foods SET image='assets/images/foods/fresh-lime-soda.jpg' WHERE food_name='Fresh Lime Soda';
UPDATE foods SET image='assets/images/foods/mutton-biriyani.jpg' WHERE food_name='Mutton Biryani';
UPDATE foods SET image='assets/images/foods/chicken-65.jpg' WHERE food_name='Chicken 65';
UPDATE foods SET image='assets/images/foods/butter-naan.jpg' WHERE food_name='Butter Naan';
UPDATE foods SET image='assets/images/foods/margherita-pizza.jpg' WHERE food_name='Margherita Pizza';
UPDATE foods SET image='assets/images/foods/french-fries.jpg' WHERE food_name='French Fries';
UPDATE foods SET image='assets/images/foods/gulab-jamun.jpg' WHERE food_name='Gulab Jamun';
UPDATE foods SET image='assets/images/foods/mango-lassi.jpg' WHERE food_name='Mango Lassi';
UPDATE foods SET image='assets/images/foods/masala-dosa.jpg' WHERE food_name='Masala Dosa';
UPDATE foods SET image='assets/images/foods/chicken-kebab.jpg' WHERE food_name='Chicken Kebab';
UPDATE foods SET image='assets/images/foods/paneer-tikka.jpg' WHERE food_name='Paneer Tikka Pizza';
UPDATE foods SET image='assets/images/foods/gobi-65.jpg' WHERE food_name='Gobi 65';
UPDATE foods SET image='assets/images/foods/idli-sambar.jpg' WHERE food_name='Idli Sambar';
UPDATE foods SET image='assets/images/foods/veg-noodles.jpg' WHERE food_name='Veg Noodles';
UPDATE foods SET image='assets/images/foods/filter-coffee.jpg' WHERE food_name='Filter Coffee';
UPDATE foods SET image='assets/images/foods/caramel-custard.jpg' WHERE food_name='Caramel Custard';
INSERT INTO restaurants (id,name,description,locality,address,latitude,longitude,phone,image,cuisine,rating,opening_time,closing_time,is_active) VALUES
(7,'FoodNest Kitchen','FoodNest demo restaurant for local menu testing.','Sivakasi','12 Market Road, Sivakasi',9.4508300,77.7976686,'9876543210','assets/images/restaurants/foodnest-kitchen.jpg','Indian, South Indian, Biryani',4.5,'10:00:00','22:30:00',1),
(8,'Spice Garden','FoodNest demo restaurant for local menu testing.','Sivakasi','24 Main Street, Sivakasi',9.4542000,77.7951000,'9876543211','assets/images/restaurants/spice-garden.jpg','Indian, South Indian',4.3,'10:00:00','22:00:00',1),
(9,'Urban Bites','FoodNest demo restaurant for local menu testing.','Sivakasi','8 Temple Road, Sivakasi',9.4475000,77.8002000,'9876543212','assets/images/restaurants/urban-bites.jpg','Burgers, Fast Food, Chinese',4.4,'11:00:00','23:00:00',1),
(10,'Royal Biryani House','FoodNest demo restaurant for local menu testing.','Sivakasi','32 Temple Road, Sivakasi',9.4521000,77.7982000,'9876543213','assets/images/restaurants/royal-biryani-house.jpg','Biryani, Indian, Chicken',4.6,'11:00:00','23:00:00',1),
(11,'Pizza Palace','FoodNest demo restaurant for local menu testing.','Sivakasi','18 Main Road, Sivakasi',9.4484000,77.7995000,'9876543214','assets/images/restaurants/pizza-palace.jpg','Pizza, Burgers, Fast Food',4.4,'11:00:00','23:00:00',1),
(12,'South Street Cafe','FoodNest demo restaurant for local menu testing.','Sivakasi','5 Railway Road, Sivakasi',9.4515000,77.7948000,'9876543215','assets/images/restaurants/south-street-cafe.jpg','South Indian, Cafe, Beverages',4.2,'07:00:00','22:00:00',1);
INSERT INTO restaurant_foods (restaurant_id,food_id) SELECT r.id,f.id FROM restaurants r JOIN foods f ON ((r.name='FoodNest Kitchen' AND f.food_name IN ('Paneer Tikka','Chicken 65','Chicken Kebab','Butter Naan','Chicken Biryani','Mutton Biryani','Gulab Jamun','Mango Lassi')) OR (r.name='Spice Garden' AND f.food_name IN ('Paneer Tikka','Gobi 65','Masala Dosa','Idli Sambar','Veg Noodles','Butter Naan','Mango Lassi','Filter Coffee')) OR (r.name='Urban Bites' AND f.food_name IN ('Classic Veg Burger','Chicken Burger','French Fries','Chicken 65','Veg Noodles','Fresh Lime Soda','Chocolate Brownie')) OR (r.name='Royal Biryani House' AND f.food_name IN ('Chicken Biryani','Mutton Biryani','Chicken Kebab','Chicken 65','Butter Naan','Gulab Jamun','Mango Lassi','Fresh Lime Soda')) OR (r.name='Pizza Palace' AND f.food_name IN ('Margherita Pizza','Farmhouse Pizza','Paneer Tikka Pizza','Classic Veg Burger','French Fries','Chocolate Brownie','Fresh Lime Soda')) OR (r.name='South Street Cafe' AND f.food_name IN ('Masala Dosa','Idli Sambar','Veg Noodles','Gobi 65','Butter Naan','Gulab Jamun','Filter Coffee','Mango Lassi')));
INSERT INTO restaurants (id,name,description,locality,address,latitude,longitude,phone,image,cuisine,rating,opening_time,closing_time) VALUES
(7,'FoodNest Kitchen','FoodNest demo restaurant for local menu testing.','Sivakasi','12 Market Road, Sivakasi',9.4508300,77.7976686,'9876543210','assets/images/restaurant-1.jpg','Indian, South Indian, Biryani',4.5,'10:00:00','22:30:00'),
(8,'Spice Garden','FoodNest demo restaurant for local menu testing.','Sivakasi','24 Main Street, Sivakasi',9.4542000,77.7951000,'9876543211','assets/images/restaurant-2.jpg','Indian, South Indian',4.3,'10:00:00','22:00:00'),
(9,'Pizza & Burger House','FoodNest demo restaurant for local menu testing.','Sivakasi','8 Temple Road, Sivakasi',9.4475000,77.8002000,'9876543212','assets/images/restaurant-3.jpg','Pizza, Burgers, Fast Food',4.4,'11:00:00','23:00:00');
INSERT INTO restaurant_foods (restaurant_id,food_id) VALUES (7,3),(7,8),(7,9),(7,10),(7,13),(7,14),(7,16),(7,19),(7,21),(8,5),(8,15),(8,18),(8,19),(8,20),(8,21),(8,22),(9,1),(9,2),(9,4),(9,11),(9,12),(9,17);
