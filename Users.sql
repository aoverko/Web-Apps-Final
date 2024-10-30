CREATE TABLE Users (
    user_id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    first_name VARCHAR(50),
    last_name VARCHAR(50)
);

INSERT INTO Users (username, password_hash, first_name, last_name) VALUES
('jdoe', 'hash123abc', 'John', 'Doe'),
('asmith', 'hash456def', 'Alice', 'Smith'),
('mjones', 'hash789ghi', 'Michael', 'Jones'),
('rwhite', 'hash101jkl', 'Rachel', 'White'),
('bwilliams', 'hash102mno', 'Brian', 'Williams'),
('ljohnson', 'hash103pqr', 'Linda', 'Johnson'),
('tsmith', 'hash104stu', 'Tom', 'Smith'),
('cgreen', 'hash105vwx', 'Chris', 'Green'),
('kbrown', 'hash106yz1', 'Karen', 'Brown'),
('dlee', 'hash107234', 'David', 'Lee');
