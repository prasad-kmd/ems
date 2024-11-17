CREATE DATABASE db_ems;
USE db_ems;

-- Create the client table
CREATE TABLE client (
    client_id INT AUTO_INCREMENT PRIMARY KEY,
    client_name VARCHAR(100) NOT NULL,
    client_email VARCHAR(100) NOT NULL UNIQUE,
    client_password VARCHAR(255) NOT NULL,
    client_photo VARCHAR(255),
    client_phone VARCHAR(20) NOT NULL,
    client_address TEXT,
    client_created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    client_updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=INNODB;

-- Create the staff table
CREATE TABLE staff (
    staff_id INT AUTO_INCREMENT PRIMARY KEY,
    staff_name VARCHAR(100) NOT NULL,
    staff_email VARCHAR(100) NOT NULL UNIQUE,
    staff_password VARCHAR(255) NOT NULL,
    staff_photo VARCHAR(255),
    staff_phone VARCHAR(20) NOT NULL,
    staff_role ENUM('Manager', 'Event Organizer', 'System Administrator') NOT NULL DEFAULT 'Event Organizer',
    staff_created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    staff_updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=INNODB;

-- Create the venue table
CREATE TABLE venue (
    venue_id INT AUTO_INCREMENT PRIMARY KEY,
    venue_name VARCHAR(100) NOT NULL,
    venue_description TEXT,
    venue_photo VARCHAR(255),
    venue_address TEXT NOT NULL,
    venue_capacity INT,
    venue_email VARCHAR(100) NOT NULL,
    venue_phone VARCHAR(20) NOT NULL,
    venue_created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    venue_updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=INNODB;

-- Create the Event Table
CREATE TABLE event (
    event_id INT AUTO_INCREMENT PRIMARY KEY,
    event_title VARCHAR(100) NOT NULL,
    event_description TEXT,
    event_photo VARCHAR(255),
    event_price DECIMAL(10, 2) NOT NULL,
    event_type ENUM('Conferences','Seminars','Sports Events','Weddings','Birthday Parties','Webinars','Training Sessions / Workshops','Product Launches','Trade Shows / Exhibitions','Non-profit / Fundraising Events','Art and Cultural Events','Festivals','Fairs / Carnivals','VIP Events') NOT NULL DEFAULT 'VIP Events',
    event_date DATE NOT NULL,
    event_start_time TIME NOT NULL,
    event_end_time TIME NOT NULL,
    venue_id INT NOT NULL,
    event_capacity INT,
    event_remain_capacity INT,
    -- event_review TEXT,
    event_status ENUM('Upcoming','Cancelled', 'Ongoing', 'Completed') DEFAULT 'Upcoming',
    event_created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    event_updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    staff_id INT,
    FOREIGN KEY (venue_id) REFERENCES venue(venue_id) ON DELETE CASCADE,
    FOREIGN KEY (staff_id) REFERENCES staff(staff_id) ON DELETE SET NULL
) ENGINE=INNODB;

-- Create the Booking Table
CREATE TABLE booking (
    booking_id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT NOT NULL,
    event_id INT NOT NULL,
    booking_date DATE NOT NULL,
    number_guests INT NOT NULL,
    booking_status ENUM('pending', 'confirmed', 'cancelled') NOT NULL DEFAULT 'pending',
    booking_created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    booking_updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    staff_id INT,
    FOREIGN KEY (client_id) REFERENCES client(client_id) ON DELETE CASCADE,
    FOREIGN KEY (event_id) REFERENCES event(event_id) ON DELETE CASCADE,
    FOREIGN KEY (staff_id) REFERENCES staff(staff_id) ON DELETE SET NULL
) ENGINE=INNODB;

-- Create the Payment Table
CREATE TABLE payment (
    payment_id INT AUTO_INCREMENT PRIMARY KEY,
    booking_id INT NOT NULL,
    payment_method ENUM('Credit Card', 'Debit Card', 'Bank Transfer', 'Crypto Currency') NOT NULL DEFAULT 'Credit Card',
    payment_amount DECIMAL(10, 2) NOT NULL,
    payment_date DATE NOT NULL,
    payment_time TIME NOT NULL,
    payment_status ENUM('pending', 'success', 'failed') NOT NULL DEFAULT 'pending',
    discount DECIMAL(10, 2) DEFAULT 0.00,
    -- card_number VARCHAR(16) NOT NULL,
    -- card_expiry DATE NOT NULL,
    payment_transaction_id VARCHAR(100) NOT NULL,
    payment_created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    payment_updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    staff_id INT,
    FOREIGN KEY (booking_id) REFERENCES booking(booking_id) ON DELETE CASCADE,
    FOREIGN KEY (staff_id) REFERENCES staff(staff_id) ON DELETE SET NULL
) ENGINE=INNODB;