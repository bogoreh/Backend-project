CREATE DATABASE ticket_system;
USE ticket_system;

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE events (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT,
    date DATE NOT NULL,
    time TIME NOT NULL,
    venue VARCHAR(100) NOT NULL,
    total_tickets INT NOT NULL,
    available_tickets INT NOT NULL,
    price DECIMAL(10,2) NOT NULL
);

CREATE TABLE tickets (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    event_id INT NOT NULL,
    quantity INT NOT NULL,
    total_price DECIMAL(10,2) NOT NULL,
    booking_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id),
    FOREIGN KEY (event_id) REFERENCES events(id)
);

-- Insert sample events
INSERT INTO events (name, description, date, time, venue, total_tickets, available_tickets, price) VALUES
('Rock Concert 2024', 'Amazing rock band performance', '2024-02-15', '19:00:00', 'City Arena', 1000, 1000, 75.00),
('Tech Conference', 'Latest technology trends', '2024-02-20', '09:00:00', 'Convention Center', 500, 500, 150.00),
('Comedy Show', 'Stand-up comedy night', '2024-02-25', '20:00:00', 'Comedy Club', 200, 200, 45.00);