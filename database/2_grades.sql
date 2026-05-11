CREATE TABLE grades (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    course_name VARCHAR(255) NOT NULL,
    test_name VARCHAR(255) NOT NULL,
    ec DECIMAL(3,1) NOT NULL,
    quarter VARCHAR(50) NOT NULL,
    grade DECIMAL(3,1) DEFAULT 0.0
);

INSERT INTO grades (course_name, test_name, ec, quarter, grade) VALUES
('Program & Career Orientation', 'Portfolio website (Presentation)', 2.5, 'Quarter 1', 0),
('Computer Science Basics', 'Written knowledge test', 5.0, 'Quarter 1', 0),
('Programming Basics', 'Written knowledge test', 5.0, 'Quarter 1', 0),
('IT Personality', 'Portfolio', 2.5, 'Quarter 2 - 4', 0),
('Object Oriented Programming', 'Presentation (group)', 5.0, 'Quarter 2', 0),
('Object Oriented Programming', 'Written knowledge test', 5.0, 'Quarter 2', 0),
('Framework Project 1', 'Written knowledge test', 4.0, 'Quarter 3', 0),
('Framework Project 1', 'Database exam', 1.0, 'Quarter 3', 0),
('Framework Project 1', 'Group presentation', 2.5, 'Quarter 3', 0),
('Framework Project 1', 'Individual requirements', 2.5, 'Quarter 3', 0),
('Business IT Consultancy Basics', 'Assignment', 2.5, 'Quarter 3 - 4', 0),
('Framework Project 2', 'Final delivery', 2.5, 'Quarter 4', 0),
('Framework Project 2', 'Report assessment', 2.5, 'Quarter 4', 0),
('Framework Project 2', 'IT Dev Portfolio', 5.0, 'Quarter 4', 0),
('Personal Professional Development Exploration', 'Criterion - referenced assessment', 12.5, 'Quarter 1 - 4', 0);