INSERT IGNORE INTO users (username, email, password, role)
VALUES (
    'Rutaganda Eric',
    'rutagandaeric22@gmail.com',
    '$2y$10$K9lMJT/6ON0TsmPQBaUskeN1l/FfuUa7jcZgffd/Sn9ZdFOgZc43a',
    'admin'
);

INSERT INTO players (full_name, position, jersey_no, dob, phone, status)
SELECT 'Demo Goalkeeper', 'Goalkeeper', 1, '2000-04-12', NULL, 'active'
WHERE NOT EXISTS (SELECT 1 FROM players WHERE full_name = 'Demo Goalkeeper');

INSERT INTO players (full_name, position, jersey_no, dob, phone, status)
SELECT 'Demo Midfielder', 'Midfielder', 8, '2002-09-05', NULL, 'active'
WHERE NOT EXISTS (SELECT 1 FROM players WHERE full_name = 'Demo Midfielder');

INSERT INTO matches (opponent, match_date, venue, competition, our_score, opponent_score, status)
SELECT 'Demo United', '2027-01-15 15:00:00', 'Club Stadium', 'Friendly', NULL, NULL, 'scheduled'
WHERE NOT EXISTS (SELECT 1 FROM matches WHERE opponent = 'Demo United' AND match_date = '2027-01-15 15:00:00');

INSERT INTO trainings (title, training_date, location, coach, notes)
SELECT 'Demo Fitness Session', '2027-01-12 09:00:00', 'Training Ground', 'Demo Coach', 'Seed data for demonstration.'
WHERE NOT EXISTS (SELECT 1 FROM trainings WHERE title = 'Demo Fitness Session' AND training_date = '2027-01-12 09:00:00');
