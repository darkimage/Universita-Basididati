-- SET FOREIGN_KEY_CHECKS=0;
-- TRUNCATE TABLE UserRole;

-- MAIN APP DATA
INSERT INTO User VALUES 
(null,'Luca','Faggion','1995-12-04','lucafaggion','$2y$10$4K7U5eQDrsWkdP3B3qYQounKJG/UDAsY/VtAPqbv4aD3nsZoZd0CK'),
(null,'Giacomo','Bianchi','1962-08-01','giacomobianchi','$2y$10$4K7U5eQDrsWkdP3B3qYQounKJG/UDAsY/VtAPqbv4aD3nsZoZd0CK'),
(null,'Marco','Rossi','1972-07-22','marcorossi','$2y$10$4K7U5eQDrsWkdP3B3qYQounKJG/UDAsY/VtAPqbv4aD3nsZoZd0CK'),
(null,'Andrea','Marchi','2000-03-09','andreamarchi','$2y$10$4K7U5eQDrsWkdP3B3qYQounKJG/UDAsY/VtAPqbv4aD3nsZoZd0CK');
INSERT INTO Role VALUES (null,'SUPERADMIN'),(null,'ADMIN'),(null,'USER'),(null,'CREATORE'),(null,'MODERATORE'),(null,'UTENTE');
INSERT INTO UserRole VALUES (null,1,1),(null,2,3),(null,3,3),(null,4,2);