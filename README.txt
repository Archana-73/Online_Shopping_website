ONLINE SHOPPING WEBSITE - BEGINNER STARTER PROJECT
Stack: HTML, CSS, JavaScript, PHP, MySQL/MariaDB
Run locally with XAMPP.

SETUP
1. Install XAMPP and VS Code.
2. Start Apache and MySQL from the XAMPP Control Panel.
3. Copy the folder "online_shopping_website" into:
   Windows: C:\xampp\htdocs\
4. Open http://localhost/phpmyadmin/
5. Click Import and import database.sql from this project.
   Alternatively, open database.sql, copy its contents into phpMyAdmin > SQL, and run it.
6. Open http://localhost/online_shopping_website/
7. Edit files in VS Code.

FEATURES IN THIS STARTER
- Responsive product listing from MySQL
- Search products by name/category
- Add to cart, update quantity, remove items (session-based cart)
- User registration and login using password_hash/password_verify
- Basic checkout that saves an order and order items
- Simple order confirmation

IMPORTANT
- This is a local learning/demo project, not production-ready.
- Before real deployment, add CSRF protection, stronger validation, rate limiting,
  admin authorization, HTTPS, secure cookie settings, and a real payment provider.
- Checkout here is a demo and does NOT process real payments.
- Product images use remote Unsplash URLs; internet is needed for those images.