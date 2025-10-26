-- Table : User
-- CREATE
INSERT INTO User (first_name, last_name, pseudo, avatar_file, note, credit, email, password, api_tocken, roles, usage_role, active, created_at) VALUES
    ('Jean', 'Dupont', 'Jeannot', 'user.svg', NULL, 20, 'jeandupont@mail.com', 'motdepassehashé', 'abc123token', '[]', '[]', TRUE, NOW()),
    ('Daniel', 'Dupond', 'DD', 'avatar2.png', NULL, 20, 'jeandupond@mail.com', 'motdepassehashé', 'abc456token', '[]', '[]', TRUE, NOW());
-- READ
SELECT * FROM User WHERE Id_User = 1;
-- UPDATE
UPDATE User
    SET avatar_file = 'avatar1.png', usage_role = '["driver"]', updated_at = NOW()
    WHERE Id_User = 1;
-- DELETE
DELETE FROM User WHERE Id_User = 2;

-- Table : Brand
INSERT INTO Brand (brand) VALUES ('Renault'), ('Susuki'), ('Volvo');
SELECT * FROM Brand WHERE Id_Brand = 2;     -- Affichera la ligne 'Susuki'
UPDATE Brand SET brand = 'Smart' WHERE Id_Brand = 2;   -- Remplacera 'Susuki' par 'Smart'
DELETE FROM Brand WHERE Id_Brand = 3;   -- Supprimera la ligne 'Volvo'

-- Table : Model
INSERT INTO Model (model, Id_Brand) VALUES ('Clio', 1), ('Safrane', 1), ('Forfour', 2);
SELECT m.*, b.brand
    FROM Model m
    JOIN Brand b ON m.Id_Brand = b.Id_Brand     -- Joint la table Brand à la table Model
    WHERE m.Id_Model = 3;   -- Affichera la ligne 'Forfour' avec la marque 'Smart'
UPDATE Model SET model = 'Megane' WHERE Id_Model = 2;
DELETE FROM Model WHERE Id_Model = 2;

-- Table : Color
INSERT INTO Color (color) VALUES ('Bleu'), ('Vert'), ('Noir');
SELECT * FROM Color WHERE Id_Color = 1;
UPDATE Color SET color = 'Gris' WHERE Id_Color = 2;
DELETE FROM Color WHERE Id_Color = 1;

-- Table : Energy
INSERT INTO Energy (energy) VALUES ('Diesel'),('Essence');
SELECT * FROM Energy WHERE Id_Energy = 1;
UPDATE Energy SET energy = 'Électrique' WHERE Id_Energy = 2;
DELETE FROM Energy WHERE Id_Energy = 2;

-- Table : Car
INSERT INTO Car (licence_plate, first_registration, place_nb, main, active, created_at, Id_Model, Id_Energy, Id_Color, Id_User) VALUES 
    ('AB-123-CD', '2005-01-01', 4, TRUE, TRUE, NOW(), 3, 1, 2, 1),
    ('AB-456-CD', '2006-01-01', 2, TRUE, TRUE, NOW(), 1, 1, 2, 1);
SELECT c.*, m.model, b.brand, e.energy, co.color, u.pseudo
    FROM Car c
    JOIN Model m ON c.Id_Model = m.Id_Model
    JOIN Brand b ON m.Id_Brand = b.Id_Brand
    JOIN Energy e ON c.Id_Energy = e.Id_Energy
    JOIN Color co ON c.Id_Color = co.Id_Color
    JOIN User u ON c.Id_User = u.Id_User   -- Joint les tables Model, Brand, Energy et Color à la table Car
    WHERE c.Id_Car = 1;  -- Affichera la voiture avec toutes ses caractéristiques (..., "Forfour", "Smart", "Diesel", "Gris", "Jeannot")
UPDATE Car
    SET active = FALSE, updated_at = NOW()
    WHERE Id_Car = 1;
DELETE FROM Car WHERE Id_Car = 2;
