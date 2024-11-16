To design an event management system using HTML, PHP, and MySQL, you need to create a database schema that includes the tables and relationships you've outlined. Below is the SQL code to create the tables along with their primary keys, foreign keys, and relevant data types.

### SQL Schema

```sql
-- Create the database
CREATE DATABASE event_management_system;
USE event_management_system;

-- Create the client table
CREATE TABLE client (
    client_id INT AUTO_INCREMENT PRIMARY KEY,
    client_name VARCHAR(100) NOT NULL,
    client_email VARCHAR(100) NOT NULL UNIQUE,
    client_password VARCHAR(255) NOT NULL,
    client_photo VARCHAR(255),
    client_phone VARCHAR(20),
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
    staff_phone VARCHAR(20),
    staff_role VARCHAR(50) NOT NULL,
    staff_created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    staff_updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=INNODB;

-- Create the venue table
CREATE TABLE venue (
    venue_id INT AUTO_INCREMENT PRIMARY KEY,
    venue_name VARCHAR(100) NOT NULL,
    venue_description TEXT,
    venue_photo VARCHAR(255),
    venue_address TEXT,
    venue_capacity INT,
    venue_email VARCHAR(100),
    venue_phone VARCHAR(20),
    venue_created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    venue_updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=INNODB;

-- Create the Event Table
CREATE TABLE event (
    event_id INT AUTO_INCREMENT PRIMARY KEY,
    event_title VARCHAR(100) NOT NULL,
    event_description TEXT,
    event_photo VARCHAR(255),
    event_price DECIMAL(10, 2),
    event_type VARCHAR(50),
    event_date DATE,
    event_start_time TIME,
    event_endtime TIME,
    venue_id INT,
    event_capacity INT,
    event_status VARCHAR(50),
    event_created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    event_updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (venue_id) REFERENCES venue(venue_id) ON DELETE CASCADE
) ENGINE=INNODB;

-- Create the Booking Table
CREATE TABLE booking (
    booking_id INT AUTO_INCREMENT PRIMARY KEY,
    client_id INT,
    event_id INT,
    booking_date DATE,
    booking_status VARCHAR(50),
    booking_created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    booking_updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES client(client_id) ON DELETE CASCADE,
    FOREIGN KEY (event_id) REFERENCES event(event_id) ON DELETE CASCADE
) ENGINE=INNODB;

-- Create the Payment Table
CREATE TABLE payment (
    payment_id INT AUTO_INCREMENT PRIMARY KEY,
    booking_id INT,
    payment_method VARCHAR(50),
    payment_amount DECIMAL(10, 2),
    payment_date DATE,
    payment_time TIME,
    payment_status VARCHAR(50),
    payment_transaction_id VARCHAR(100),
    payment_created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    payment_updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (booking_id) REFERENCES booking(booking_id) ON DELETE CASCADE
) ENGINE=INNODB;
```

### Explanation of Tables and Keys

1. **Client Table**
   - **client_id**: INT (Primary Key, Auto Increment)
   - **client_name**: VARCHAR(100)
   - **client_email**: VARCHAR(100) (Unique)
   - **client_password**: VARCHAR(255)
   - **client_phone**: VARCHAR(20)
   - **client_address**: TEXT
   - **client_createdAt**: TIMESTAMP (Default Current Timestamp)
   - **client_updated_at**: TIMESTAMP (Default Current Timestamp, On Update Current Timestamp)

2. **Staff Table**
   - **staff_id**: INT (Primary Key, Auto Increment)
   - **staff_name**: VARCHAR(100)
   - **staff_email**: VARCHAR(100) (Unique)
   - **staffpassword**: VARCHAR(255)
   - **staff_phone**: VARCHAR(20)
   - **staff_role**: VARCHAR(50)
   - **staff_created_at**: TIMESTAMP (Default Current Timestamp)
   - **staff_updated_at**: TIMESTAMP (Default Current Timestamp, On Update Current Timestamp)

3. **Venue Table**
   - **venue_id**: INT (Primary Key, Auto Increment)
   - **venue_name**: VARCHAR(100)
   - **venue_description**: TEXT
   - **venue_photo**: VARCHAR(255)
   - **venue_address**: TEXT
   - **venue_capacity**: INT
   - **venue_email**: VARCHAR(100)
   - **venuePhone**: VARCHAR(20)
   - **venue_created_at**: TIMESTAMP (Default Current Timestamp)
   - **venue_updated_at**: TIMESTAMP (Default Current Timestamp, On Update Current Timestamp)

4. **Event Table**
   - **event_id**: INT (Primary Key, Auto Increment)
   - **event_title**: VARCHAR(100)
   - **event_description**: TEXT
   - **event_photo**: VARCHAR(255)
   - **event_price**: DECIMAL(10, 2)
   - **event_type**: VARCHAR(50)
   - **event_date**: DATE
   - **event_start_time**: TIME
   - **event_endtime**: TIME
   - **venue_id**: INT (Foreign Key, References venue(venue_id), On Delete Cascade)
   - **event_capacity**: INT
   - **event_status**: VARCHAR(50)
   - **event_created_at**: TIMESTAMP (Default Current Timestamp)
   - **event_updated_at**: TIMESTAMP (Default Current Timestamp, On Update Current Timestamp)

5. **Booking Table**
   - **booking_id**: INT (Primary Key, Auto Increment)
   - **client_id**: INT (Foreign Key, References client(clientId), On Delete Cascade)
   - **event_id**: INT (Foreign Key, References event(eventId), On Delete Cascade)
   - **booking_date**: DATE
   - **booking_status**: VARCHAR(50)
   - **booking_created_at**: TIMESTAMP (Default Current Timestamp)
   - **booking_updated_at**: TIMESTAMP (Default Current Timestamp, On Update Current Timestamp)

6. **Payment Table**
   - **payment_id**: INT (Primary Key, Auto Increment)
   - **booking_id**: INT (Foreign Key, References booking(bookingId), On Delete Cascade)
   - **payment_method**: VARCHAR(50)
   - **payment_amount**: DECIMAL(10, 2)
   - **payment_date**: DATE
   - **payment_time**: TIME
   - **payment_status**: VARCHAR(50)
   - **payment_transaction_id**: VARCHAR(100)
   - **payment_created_at**: TIMESTAMP (Default Current Timestamp)
   - **payment_updated_at**: TIMESTAMP (Default Current Timestamp, On Update Current Timestamp)

### Additional Notes
- **Foreign Keys**: Foreign keys are used to maintain referential integrity between tables. They ensure that the data in one table corresponds to the data in another table.
- **On Delete Cascade**: This ensures that when a record in the parent table is deleted, the corresponding records in the child table are also deleted to maintain data consistency.
- **Auto Increment**: This ensures that the primary key is automatically incremented for each new record.
- **Unique**: Ensures that the specified column contains unique values.
- **Default Current Timestamp**: Sets the default value to the current timestamp.
- **On Update Current Timestamp**: Updates the timestamp to the current timestamp whenever the record is updated.

This schema should provide a robust foundation for your event management system, ensuring that all necessary relationships and constraints are in place to maintain data integrity and efficiency.