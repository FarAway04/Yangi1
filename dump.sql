CREATE TABLE IF NOT EXISTS admins (
    user_id BIGINT PRIMARY KEY
);

CREATE TABLE IF NOT EXISTS superusers (
    user_id BIGINT PRIMARY KEY
);

CREATE TABLE IF NOT EXISTS channels (
    username TEXT PRIMARY KEY
);

CREATE TABLE IF NOT EXISTS movies (
    id SERIAL PRIMARY KEY,
    file_id TEXT,
    description TEXT
);
