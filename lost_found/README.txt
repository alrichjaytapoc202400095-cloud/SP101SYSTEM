LOST AND FOUND MANAGEMENT SYSTEM - PHP + MYSQL

FEATURES
- User registration and login
- Passwords stored with password_hash()
- Logout
- Report lost or found items
- Require a photo for every report
- On supported mobile browsers, file input may open the camera; otherwise choose a photo
- Search items by name or location
- Display item pictures
- User can update/delete only their own reports
- Dashboard counts

SETUP
1. Install XAMPP.
2. Start Apache and MySQL.
3. Copy the lost_found folder to C:\\xampp\\htdocs\\
4. Open http://localhost/phpmyadmin
5. Select Import and import database.sql.
   IMPORTANT: If upgrading an earlier version, back up any data first. The new schema is different.
6. Make sure the uploads folder exists. It is included in the ZIP.
7. Open http://localhost/lost_found/
8. Register a new account, then log in.
9. Click Report Item and attach a photo.

IMAGE UPLOAD
- JPG, PNG, GIF, WEBP accepted
- Maximum 5 MB
- Photo is required
- Keep uploads folder writable by PHP

NOTE
This is a basic school project. Before public deployment, add CSRF protection, rate limits, stricter server configuration, and review privacy/security requirements. Do not upload sensitive personal information in item photos.
