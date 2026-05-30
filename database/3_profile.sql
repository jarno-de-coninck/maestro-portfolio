CREATE TABLE profiles (
    id INTEGER PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(255) NOT NULL,
    birthdate DATE,
    bio TEXT NOT NULL,
    characteristics TEXT,
    skills TEXT,
    linkedin_url VARCHAR(255),
    github_url VARCHAR(255)
);

INSERT INTO profiles (name, bio, birthdate, characteristics, skills, linkedin_url, github_url) VALUES (
    'Jarno de Coninck',
    'I am {age} years old, and I was born in Knokke. One of my strengths is that I always keep my code neat and organized. I am also very creative. I am currently studying software development in college to improve my programming skills. My hobbies include making games and going to the gym, and one of my main ambitions is to become a professional software developer.',
    '2005-01-17',
    'Analytical,Precise,Creative,Teamplayer',
    'Java,Luau,HTML/CSS,SQL,Python,PHP,JavaScript,TypeScript',
    'https://www.linkedin.com/in/jarno-de-coninck-7a1b63227/',
    'https://github.com/jarno-de-coninck/'
);