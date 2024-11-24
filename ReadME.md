# Rathna Events Management System

This project is a web-based event management system developed using HTML, PHP, and MySQL. It provides a platform for Rathna Events to manage events, venues, clients, staff, bookings, and payments efficiently.  The system offers separate interfaces for clients and administrators, with role-based access control for different admin functionalities.

## What's the purpose behind this project?

This project was intend to be my Project for EEX3417 subject (The Open University of Sri Lanka).
Created & Designed by K.M.D.G.P.M.B. SENAVIRATHNA

## Features

**Client-Side:**

*   Browse and search events.
*   View event details (including photos, venue information, available seats).
*   Book events with a simulated payment gateway.
*   View booking history and upcoming bookings.
*   Manage their profile (edit personal information, change password, update profile picture).

**Admin-Side (Role-Based Access Control):**

*   **Manager:** Manage clients, staff, events, venues, bookings, payments, and generate reports.
*   **Event Organizer:** Manage events, bookings, and payments for assigned events.
*   **System Administrator:** Manage clients, staff, events, and perform system-level tasks like database backups.

## Technologies Used

*   **Front-end:** HTML, CSS, JavaScript
*   **Back-end:** PHP
*   **Database:** MySQL

## Installation

1.  Clone the repository: `git clone https://github.com/prasad-kmd/ems`
2.  Set up a MySQL database and import the provided SQL schema (e.g. from your `db_ems.sql` file).
3.  Configure database credentials in `db_config.php`.
4.  Place the project files in your web server's root directory (e.g., `htdocs` for XAMPP, `www` for WAMP).
5.  Access the application through your web browser.

## Usage

**Client-Side:**

*   Access the home page to browse events.
*   Sign up or log in to book events and manage your profile.

**Admin-Side:**

*   Access the admin login page.
*   Use your assigned credentials to access the admin dashboard.
*   Manage events, clients, staff, bookings, payments, and system settings based on your role.

## Database Structure

The database consists of the following tables:

*   `client`: Stores client information.
*   `staff`: Stores staff member information.
*   `event`: Stores event details.
*   `venue`: Stores venue information.
*   `booking`: Stores booking records.
*   `payment`: Stores payment details.
*   `reviews`: Stores event reviews (optional - if implemented) | TODO.

Refer to the project documentation or the SQL schema file for details on table structure and relationships.

## File Structure

```
📦 EMS
├─ .gitignore
├─ about.html
├─ admin_dashboard.php
├─ admin_login.php
├─ assets
│  ├─ audio
│  │  └─ file.audio
│  ├─ css
│  │  ├─ auth.css
│  │  ├─ auth_admin.css
│  │  ├─ config.css
│  │  ├─ dashboard.css
│  │  ├─ file.css
│  │  ├─ fomantic.css
│  │  ├─ fonts
│  │  │  ├─ brand-icons.eot
│  │  │  ├─ brand-icons.svg
│  │  │  ├─ brand-icons.ttf
│  │  │  ├─ brand-icons.woff
│  │  │  ├─ brand-icons.woff2
│  │  │  ├─ icons.eot
│  │  │  ├─ icons.otf
│  │  │  ├─ icons.svg
│  │  │  ├─ icons.ttf
│  │  │  ├─ icons.woff
│  │  │  ├─ icons.woff2
│  │  │  ├─ outline-icons.eot
│  │  │  ├─ outline-icons.svg
│  │  │  ├─ outline-icons.ttf
│  │  │  ├─ outline-icons.woff
│  │  │  └─ outline-icons.woff2
│  │  ├─ index.css
│  │  ├─ libs.css
│  │  ├─ loader.css
│  │  ├─ old
│  │  │  └─ auth.css
│  │  ├─ payment_gateway.css
│  │  ├─ responsive.css
│  │  ├─ semantic.css
│  │  ├─ stg.css
│  │  └─ style_index.css
│  ├─ favicon
│  │  ├─ admin_icon.ico
│  │  ├─ client_icon.ico
│  │  ├─ file.favicon
│  │  └─ index_favicon.ico
│  ├─ font
│  │  ├─ AaarghNormal.eot
│  │  ├─ AaarghNormal.svg
│  │  ├─ AaarghNormal.ttf
│  │  ├─ AaarghNormal.woff
│  │  ├─ AaarghNormal.woff2
│  │  ├─ Abel-Regular.eot
│  │  ├─ Abel-Regular.svg
│  │  ├─ Abel-Regular.ttf
│  │  ├─ Abel-Regular.woff
│  │  ├─ Abel-Regular.woff2
│  │  ├─ AbhayaLibre-Medium.eot
│  │  ├─ AbhayaLibre-Medium.svg
│  │  ├─ AbhayaLibre-Medium.ttf
│  │  ├─ AbhayaLibre-Medium.woff
│  │  ├─ AbhayaLibre-Medium.woff2
│  │  ├─ Aquatico-Regular.eot
│  │  ├─ Aquatico-Regular.svg
│  │  ├─ Aquatico-Regular.ttf
│  │  ├─ Aquatico-Regular.woff
│  │  ├─ Aquatico-Regular.woff2
│  │  ├─ CaviarDreams.eot
│  │  ├─ CaviarDreams.svg
│  │  ├─ CaviarDreams.ttf
│  │  ├─ CaviarDreams.woff
│  │  ├─ CaviarDreams.woff2
│  │  ├─ Computerfont.eot
│  │  ├─ Computerfont.svg
│  │  ├─ Computerfont.ttf
│  │  ├─ Computerfont.woff
│  │  ├─ Computerfont.woff2
│  │  ├─ Cubic.eot
│  │  ├─ Cubic.svg
│  │  ├─ Cubic.ttf
│  │  ├─ Cubic.woff
│  │  ├─ Cubic.woff2
│  │  ├─ DimitriSwank.eot
│  │  ├─ DimitriSwank.svg
│  │  ├─ DimitriSwank.ttf
│  │  ├─ DimitriSwank.woff
│  │  ├─ DimitriSwank.woff2
│  │  ├─ ElMessiri-Bold.eot
│  │  ├─ ElMessiri-Bold.svg
│  │  ├─ ElMessiri-Bold.ttf
│  │  ├─ ElMessiri-Bold.woff
│  │  ├─ ElMessiri-Bold.woff2
│  │  ├─ ElMessiri-Medium.eot
│  │  ├─ ElMessiri-Medium.svg
│  │  ├─ ElMessiri-Medium.ttf
│  │  ├─ ElMessiri-Medium.woff
│  │  ├─ ElMessiri-Medium.woff2
│  │  ├─ ElMessiri-Regular.eot
│  │  ├─ ElMessiri-Regular.svg
│  │  ├─ ElMessiri-Regular.ttf
│  │  ├─ ElMessiri-Regular.woff
│  │  ├─ ElMessiri-Regular.woff2
│  │  ├─ ElMessiri-SemiBold.eot
│  │  ├─ ElMessiri-SemiBold.svg
│  │  ├─ ElMessiri-SemiBold.ttf
│  │  ├─ ElMessiri-SemiBold.woff
│  │  ├─ ElMessiri-SemiBold.woff2
│  │  ├─ GoogleSans-Regular.eot
│  │  ├─ GoogleSans-Regular.svg
│  │  ├─ GoogleSans-Regular.ttf
│  │  ├─ GoogleSans-Regular.woff
│  │  ├─ GoogleSans-Regular.woff2
│  │  ├─ GoogleSeptember2015.eot
│  │  ├─ GoogleSeptember2015.svg
│  │  ├─ GoogleSeptember2015.ttf
│  │  ├─ GoogleSeptember2015.woff
│  │  ├─ GoogleSeptember2015.woff2
│  │  ├─ GreyscaleBasic.eot
│  │  ├─ GreyscaleBasic.svg
│  │  ├─ GreyscaleBasic.ttf
│  │  ├─ GreyscaleBasic.woff
│  │  ├─ GreyscaleBasic.woff2
│  │  ├─ JetBrainsMono-Bold.eot
│  │  ├─ JetBrainsMono-Bold.svg
│  │  ├─ JetBrainsMono-Bold.ttf
│  │  ├─ JetBrainsMono-Bold.woff
│  │  ├─ JetBrainsMono-Bold.woff2
│  │  ├─ JetBrainsMono-BoldItalic.eot
│  │  ├─ JetBrainsMono-BoldItalic.svg
│  │  ├─ JetBrainsMono-BoldItalic.ttf
│  │  ├─ JetBrainsMono-BoldItalic.woff
│  │  ├─ JetBrainsMono-BoldItalic.woff2
│  │  ├─ JetBrainsMono-ExtraBold.eot
│  │  ├─ JetBrainsMono-ExtraBold.svg
│  │  ├─ JetBrainsMono-ExtraBold.ttf
│  │  ├─ JetBrainsMono-ExtraBold.woff
│  │  ├─ JetBrainsMono-ExtraBold.woff2
│  │  ├─ JetBrainsMono-ExtraBoldItalic.eot
│  │  ├─ JetBrainsMono-ExtraBoldItalic.svg
│  │  ├─ JetBrainsMono-ExtraBoldItalic.ttf
│  │  ├─ JetBrainsMono-ExtraBoldItalic.woff
│  │  ├─ JetBrainsMono-ExtraBoldItalic.woff2
│  │  ├─ JetBrainsMono-Italic.eot
│  │  ├─ JetBrainsMono-Italic.svg
│  │  ├─ JetBrainsMono-Italic.ttf
│  │  ├─ JetBrainsMono-Italic.woff
│  │  ├─ JetBrainsMono-Italic.woff2
│  │  ├─ JetBrainsMono-Medium.eot
│  │  ├─ JetBrainsMono-Medium.svg
│  │  ├─ JetBrainsMono-Medium.ttf
│  │  ├─ JetBrainsMono-Medium.woff
│  │  ├─ JetBrainsMono-Medium.woff2
│  │  ├─ JetBrainsMono-MediumItalic.eot
│  │  ├─ JetBrainsMono-MediumItalic.svg
│  │  ├─ JetBrainsMono-MediumItalic.ttf
│  │  ├─ JetBrainsMono-MediumItalic.woff
│  │  ├─ JetBrainsMono-MediumItalic.woff2
│  │  ├─ JetBrainsMono-Regular.eot
│  │  ├─ JetBrainsMono-Regular.svg
│  │  ├─ JetBrainsMono-Regular.ttf
│  │  ├─ JetBrainsMono-Regular.woff
│  │  ├─ JetBrainsMono-Regular.woff2
│  │  ├─ JuliusSansOne-Regular.eot
│  │  ├─ JuliusSansOne-Regular.svg
│  │  ├─ JuliusSansOne-Regular.ttf
│  │  ├─ JuliusSansOne-Regular.woff
│  │  ├─ JuliusSansOne-Regular.woff2
│  │  ├─ NasalizationRg-Regular.eot
│  │  ├─ NasalizationRg-Regular.svg
│  │  ├─ NasalizationRg-Regular.ttf
│  │  ├─ NasalizationRg-Regular.woff
│  │  ├─ NasalizationRg-Regular.woff2
│  │  ├─ Neuropol-Regular.eot
│  │  ├─ Neuropol-Regular.svg
│  │  ├─ Neuropol-Regular.ttf
│  │  ├─ Neuropol-Regular.woff
│  │  ├─ Neuropol-Regular.woff2
│  │  ├─ Neuropol-Regular_1.eot
│  │  ├─ Neuropol-Regular_1.svg
│  │  ├─ Neuropol-Regular_1.ttf
│  │  ├─ Neuropol-Regular_1.woff
│  │  ├─ Neuropol-Regular_1.woff2
│  │  ├─ NewCicle-Fina.eot
│  │  ├─ NewCicle-Fina.svg
│  │  ├─ NewCicle-Fina.ttf
│  │  ├─ NewCicle-Fina.woff
│  │  ├─ NewCicle-Fina.woff2
│  │  ├─ NewCicle-FinaItalic.eot
│  │  ├─ NewCicle-FinaItalic.svg
│  │  ├─ NewCicle-FinaItalic.ttf
│  │  ├─ NewCicle-FinaItalic.woff
│  │  ├─ NewCicle-FinaItalic.woff2
│  │  ├─ NewCicle-Gordita.eot
│  │  ├─ NewCicle-Gordita.svg
│  │  ├─ NewCicle-Gordita.ttf
│  │  ├─ NewCicle-Gordita.woff
│  │  ├─ NewCicle-Gordita.woff2
│  │  ├─ NewCicle-GorditaItalic.eot
│  │  ├─ NewCicle-GorditaItalic.svg
│  │  ├─ NewCicle-GorditaItalic.ttf
│  │  ├─ NewCicle-GorditaItalic.woff
│  │  ├─ NewCicle-GorditaItalic.woff2
│  │  ├─ NewCicle-Semi.eot
│  │  ├─ NewCicle-Semi.svg
│  │  ├─ NewCicle-Semi.ttf
│  │  ├─ NewCicle-Semi.woff
│  │  ├─ NewCicle-Semi.woff2
│  │  ├─ NewCicle-SemiItalic.eot
│  │  ├─ NewCicle-SemiItalic.svg
│  │  ├─ NewCicle-SemiItalic.ttf
│  │  ├─ NewCicle-SemiItalic.woff
│  │  ├─ NewCicle-SemiItalic.woff2
│  │  ├─ Norwester-Regular.eot
│  │  ├─ Norwester-Regular.svg
│  │  ├─ Norwester-Regular.ttf
│  │  ├─ Norwester-Regular.woff
│  │  ├─ Norwester-Regular.woff2
│  │  ├─ NotoSerifSinhala-Regular.eot
│  │  ├─ NotoSerifSinhala-Regular.svg
│  │  ├─ NotoSerifSinhala-Regular.ttf
│  │  ├─ NotoSerifSinhala-Regular.woff
│  │  ├─ NotoSerifSinhala-Regular.woff2
│  │  ├─ Orbit-Regular.eot
│  │  ├─ Orbit-Regular.svg
│  │  ├─ Orbit-Regular.ttf
│  │  ├─ Orbit-Regular.woff
│  │  ├─ Orbit-Regular.woff2
│  │  ├─ Philosopher-Bold.eot
│  │  ├─ Philosopher-Bold.svg
│  │  ├─ Philosopher-Bold.ttf
│  │  ├─ Philosopher-Bold.woff
│  │  ├─ Philosopher-Bold.woff2
│  │  ├─ Philosopher-BoldItalic.eot
│  │  ├─ Philosopher-BoldItalic.svg
│  │  ├─ Philosopher-BoldItalic.ttf
│  │  ├─ Philosopher-BoldItalic.woff
│  │  ├─ Philosopher-BoldItalic.woff2
│  │  ├─ Philosopher-Italic.eot
│  │  ├─ Philosopher-Italic.svg
│  │  ├─ Philosopher-Italic.ttf
│  │  ├─ Philosopher-Italic.woff
│  │  ├─ Philosopher-Italic.woff2
│  │  ├─ Philosopher.eot
│  │  ├─ Philosopher.svg
│  │  ├─ Philosopher.ttf
│  │  ├─ Philosopher.woff
│  │  ├─ Philosopher.woff2
│  │  ├─ Quicksand-Light.eot
│  │  ├─ Quicksand-Light.svg
│  │  ├─ Quicksand-Light.ttf
│  │  ├─ Quicksand-Light.woff
│  │  ├─ Quicksand-Light.woff2
│  │  ├─ Sansumi-Bold.eot
│  │  ├─ Sansumi-Bold.svg
│  │  ├─ Sansumi-Bold.ttf
│  │  ├─ Sansumi-Bold.woff
│  │  ├─ Sansumi-Bold.woff2
│  │  ├─ Sansumi-ExtraBold.eot
│  │  ├─ Sansumi-ExtraBold.svg
│  │  ├─ Sansumi-ExtraBold.ttf
│  │  ├─ Sansumi-ExtraBold.woff
│  │  ├─ Sansumi-ExtraBold.woff2
│  │  ├─ Syncopate-Bold.eot
│  │  ├─ Syncopate-Bold.svg
│  │  ├─ Syncopate-Bold.ttf
│  │  ├─ Syncopate-Bold.woff
│  │  ├─ Syncopate-Bold.woff2
│  │  ├─ Syncopate-Regular.eot
│  │  ├─ Syncopate-Regular.svg
│  │  ├─ Syncopate-Regular.ttf
│  │  ├─ Syncopate-Regular.woff
│  │  ├─ Syncopate-Regular.woff2
│  │  ├─ UN-Arundathee.eot
│  │  ├─ UN-Arundathee.svg
│  │  ├─ UN-Arundathee.ttf
│  │  ├─ UN-Arundathee.woff
│  │  ├─ UN-Arundathee.woff2
│  │  ├─ WalkwaySemiBold.eot
│  │  ├─ WalkwaySemiBold.svg
│  │  ├─ WalkwaySemiBold.ttf
│  │  ├─ WalkwaySemiBold.woff
│  │  ├─ WalkwaySemiBold.woff2
│  │  ├─ Yaldevi-Regular.eot
│  │  ├─ Yaldevi-Regular.svg
│  │  ├─ Yaldevi-Regular.ttf
│  │  ├─ Yaldevi-Regular.woff
│  │  ├─ Yaldevi-Regular.woff2
│  │  ├─ demo.html
│  │  ├─ fonts.css
│  │  └─ stylesheet.css
│  ├─ html
│  │  └─ old
│  │     └─ index.html
│  ├─ images
│  │  ├─ default_admin.png
│  │  ├─ default_client.png
│  │  ├─ file.img
│  │  ├─ index_hero1.webp
│  │  ├─ index_hero2.webp
│  │  ├─ index_hero3.webp
│  │  ├─ index_hero4.webp
│  │  ├─ index_hero5.webp
│  │  ├─ index_hero6.webp
│  │  ├─ logo.webp
│  │  ├─ null.png
│  │  └─ svg
│  │     ├─ admin.svg
│  │     ├─ dashboard_layout.svg
│  │     └─ user.svg
│  ├─ index.html
│  ├─ js
│  │  ├─ auth.js
│  │  ├─ cal
│  │  │  └─ index.global.js
│  │  ├─ classes.js
│  │  ├─ file.js
│  │  ├─ fomantic.min.js
│  │  ├─ imask.min.js
│  │  ├─ index-title.js
│  │  ├─ jquery-3.7.1.min.js
│  │  ├─ jquery.min.js
│  │  ├─ lib
│  │  │  ├─ jquery.min.js
│  │  │  └─ libs.js
│  │  ├─ main.js
│  │  ├─ payment_gateway.js
│  │  ├─ semantic.js
│  │  └─ st-core.js
│  ├─ md
│  │  ├─ Add_Admin.md
│  │  └─ sql_scheme.md
│  ├─ php
│  │  ├─ add_admin.php
│  │  ├─ gpt-4o
│  │  │  ├─ admin_auth.php
│  │  │  ├─ admin_dashboard.php
│  │  │  ├─ admin_login.php
│  │  │  ├─ auth.php
│  │  │  ├─ db_connection.php
│  │  │  ├─ logout.php
│  │  │  ├─ signin.php
│  │  │  └─ signup.php
│  │  ├─ nvidia
│  │  │  ├─ auth.php
│  │  │  └─ db_connection.php
│  │  ├─ old
│  │  │  ├─ admin_login.php
│  │  │  └─ auth.php
│  │  ├─ qwen.coder
│  │  │  ├─ admin_auth.php
│  │  │  ├─ admin_dashboard.php
│  │  │  ├─ auth.php
│  │  │  ├─ book_event.php
│  │  │  ├─ client_dashboard.php
│  │  │  ├─ manage_clients.php
│  │  │  ├─ manage_events.php
│  │  │  ├─ manage_staff.php
│  │  │  └─ update_account.php
│  │  ├─ qwen
│  │  │  ├─ add_client_process.php
│  │  │  ├─ add_event_process.php
│  │  │  ├─ add_staff_process.php
│  │  │  ├─ admin_dashboard.php
│  │  │  ├─ admin_login.php
│  │  │  ├─ admin_login_process.php
│  │  │  ├─ admin_logout.php
│  │  │  ├─ client_dashboard.php
│  │  │  ├─ db_connect.php
│  │  │  ├─ default_profile_picture.webp
│  │  │  ├─ delete_client.php
│  │  │  ├─ delete_event.php
│  │  │  ├─ delete_staff.php
│  │  │  ├─ edit_client.php
│  │  │  ├─ edit_client_process.php
│  │  │  ├─ edit_event.php
│  │  │  ├─ edit_event_process.php
│  │  │  ├─ edit_staff.php
│  │  │  ├─ edit_staff_process.php
│  │  │  ├─ index.php
│  │  │  ├─ login.php
│  │  │  ├─ login_process.php
│  │  │  ├─ logout.php
│  │  │  ├─ manage_clients.php
│  │  │  ├─ manage_events.php
│  │  │  ├─ manage_staff.php
│  │  │  └─ signup_process.php
│  │  ├─ qwen2
│  │  │  ├─ auth.html
│  │  │  ├─ client_dashboard.php
│  │  │  ├─ db.php
│  │  │  ├─ login.php
│  │  │  ├─ logout.php
│  │  │  ├─ register.php
│  │  │  └─ styles.css
│  │  ├─ unused
│  │  │  ├─ client_communication.php
│  │  │  ├─ message_organizer.php
│  │  │  ├─ process_message_to_organizer.php
│  │  │  ├─ send_message.php
│  │  │  ├─ send_message_process.php
│  │  │  └─ send_message_to_organizer.php
│  │  └─ v0.dev
│  │     ├─ add_event.php
│  │     ├─ admin_dashboard.php
│  │     ├─ admin_login.php
│  │     ├─ admin_login_handler.php
│  │     ├─ auth.php
│  │     ├─ db_connection.php
│  │     ├─ logout.php
│  │     └─ manage_events.php
│  ├─ sql
│  │  └─ base.sql
│  └─ video
│     └─ file.vid
├─ auth.html
├─ backup_database.php
├─ bookings
│  ├─ booking_confirmation.php
│  ├─ booking_history.php
│  ├─ delete_booking.php
│  ├─ edit_booking.php
│  ├─ index.html
│  ├─ manage_bookings.php
│  ├─ process_booking.php
│  ├─ process_edit_booking.php
│  ├─ upcoming_bookings.php
│  └─ view_booking.php
├─ client_dashboard.php
├─ clients
│  ├─ delete_client.php
│  ├─ edit_client.php
│  ├─ index.html
│  ├─ manage_clients.php
│  └─ process_edit_client.php
├─ contacts.html
├─ content
│  ├─ admin_dashboard.php
│  ├─ admin_login.html
│  ├─ auth.php
│  └─ index.html
├─ db_config.php
├─ default_profile.png
├─ edit_client_profile.php
├─ edit_profile.php
├─ events
│  ├─ add_event.php
│  ├─ book_event.php
│  ├─ browse_events.php
│  ├─ delete_event.php
│  ├─ edit_event.php
│  ├─ index.html
│  ├─ manage_event.php
│  ├─ process_add_event.php
│  └─ process_edit_event.php
├─ index.html
├─ login.php
├─ logout.php
├─ payments
│  ├─ delete_payment.php
│  ├─ index.html
│  ├─ manage_payments.php
│  ├─ payment_gateway.php
│  ├─ process_payment.php
│  ├─ process_payment_gateway.php
│  └─ view_payment.php
├─ process_edit_profile.php
├─ register.php
├─ staff
│  ├─ delete_staff.php
│  ├─ edit_staff.php
│  ├─ index.html
│  ├─ manage_staff.php
│  └─ process_edit_staff.php
├─ temp.html
├─ uploads
│  ├─ 673ad795d4c17.webp
│  ├─ 673ada8b5bd97.webp
│  ├─ 673adae7b6a2a.webp
│  ├─ 673ae22dd9003.webp
│  ├─ 673af3c75566f.webp
│  ├─ 673af8d65fa94.webp
│  ├─ 673afc09f2284.webp
│  ├─ 673afcfce4491.webp
│  ├─ 673afecbb046e.webp
│  ├─ 673aff2923787.webp
│  ├─ 673aff9b04794.webp
│  └─ index.html
└─ venues
   ├─ add_venue.php
   ├─ delete_venue.php
   ├─ edit_venue.php
   ├─ manage_venues.php
   ├─ process_add_venue.php
   └─ process_edit_venue.php
```
©generated by [Project Tree Generator](https://woochanleee.github.io/project-tree-generator)

*Last Updated @ 2024.11.25 - 02:41:41AM


## Contributing

Contributions are welcome!  Please open an issue or submit a pull request.

## License

This project is licensed under the [MIT License](LICENSE).

## Future Enhancements

*   Integrate with a real payment gateway.
*   Implement automated email notifications.
*   Add advanced search and filtering options.
*   Implement client review functionality.
*   Integrate with social media platforms.