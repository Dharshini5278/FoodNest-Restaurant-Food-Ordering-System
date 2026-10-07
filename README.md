# FoodNest - Restaurant Food Ordering System

## XAMPP setup
1. Copy this folder to `C:\xampp\htdocs\FoodNest`.
2. Open XAMPP Control Panel and start **Apache** and **MySQL**.
3. Open `http://localhost/phpmyadmin`, create/import the database by opening `database/foodnest.sql` in the Import tab.
4. Visit `http://localhost/FoodNest/`.

Demo customer: `customer@foodnest.com` / `password`
Demo admin: `admin@foodnest.com` / `password`

## Restaurant discovery
The homepage uses OpenStreetMap Nominatim for address suggestions and the Overpass API for nearby restaurant, fast-food, and cafe discovery. Coordinates are posted only for the current nearby search and are stored in the PHP session for the current delivery flow. No Google Cloud billing or API key is required.

If this database was imported before the restaurant upgrade, run the new restaurant table and column statements from `database/foodnest.sql`, or import the schema into a fresh `foodnest_db` database. The admin restaurant tools are available at `admin/restaurants.php` after admin login.

External OSM restaurants have no guaranteed ratings, photos, menus, or prices. FoodNest only displays those fields when OSM or the local FoodNest database actually provides them. Numeric FoodNest restaurant IDs use the existing MySQL menus and cart flow; unmatched OSM restaurants are view-only and show `Menu information is currently unavailable.`

The project uses PDO prepared statements, PHP sessions, password hashing, AJAX JSON endpoints, and server-side order totals. Food image URLs are included as sample data; replace them with local files in `assets/images` for a fully offline visual demo.
