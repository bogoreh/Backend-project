-- Minimal version without users table
CREATE DATABASE IF NOT EXISTS defect_tracking;
USE defect_tracking;

CREATE TABLE defects (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    status ENUM('Open', 'In Progress', 'Resolved', 'Closed') DEFAULT 'Open',
    priority ENUM('Low', 'Medium', 'High', 'Critical') DEFAULT 'Medium',
    assigned_to VARCHAR(100),
    created_by VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert sample defects
INSERT INTO defects (title, description, status, priority, assigned_to, created_by) VALUES 
('Login page not loading', 'Users are unable to access the login page.', 'Open', 'Critical', 'John Developer', 'Sarah Tester'),
('Button color incorrect', 'Submit button shows wrong color on mobile.', 'In Progress', 'Medium', 'John Developer', 'Mike Manager'),
('Database connection slow', 'Query performance needs optimization.', 'Open', 'High', 'Jane Developer', 'Admin User');