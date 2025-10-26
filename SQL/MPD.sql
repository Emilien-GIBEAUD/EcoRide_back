CREATE DATABASE dossier_projet
    DEFAULT CHARACTER SET = 'utf8mb4';

USE dossier_projet;

CREATE TABLE User(
    Id_User INT AUTO_INCREMENT,
    first_name VARCHAR(255)  NOT NULL,
    last_name VARCHAR(255)  NOT NULL,
    pseudo VARCHAR(255)  NOT NULL,
    avatar_file VARCHAR(255) ,
    note DECIMAL(3,1)  ,
    credit INT NOT NULL,
    email VARCHAR(255)  NOT NULL,
    password VARCHAR(255)  NOT NULL,
    api_tocken VARCHAR(255)  NOT NULL,
    roles VARCHAR(255)  NOT NULL,
    usage_role VARCHAR(255)  NOT NULL,
    active BOOLEAN NOT NULL,
    created_at DATETIME NOT NULL,
    updated_at DATETIME,
    PRIMARY KEY(Id_User),
    UNIQUE(pseudo),
    UNIQUE(avatar_file),
    UNIQUE(email),
    UNIQUE(api_tocken)
);
CREATE TABLE Brand(
    Id_Brand INT AUTO_INCREMENT,
    brand VARCHAR(255)  NOT NULL,
    PRIMARY KEY(Id_Brand),
    UNIQUE(brand)
);
CREATE TABLE Model(
    Id_Model INT AUTO_INCREMENT,
    model VARCHAR(255)  NOT NULL,
    Id_Brand INT NOT NULL,
    PRIMARY KEY(Id_Model),
    UNIQUE(model),
    FOREIGN KEY(Id_Brand) REFERENCES Brand(Id_Brand)
);
CREATE TABLE Color(
    Id_Color INT AUTO_INCREMENT,
    color VARCHAR(255)  NOT NULL,
    PRIMARY KEY(Id_Color),
    UNIQUE(color)
);
CREATE TABLE Preference(
    Id_Preference INT AUTO_INCREMENT,
    smoker BOOLEAN NOT NULL,
    animal BOOLEAN NOT NULL,
    other_preference VARCHAR(255) ,
    Id_User INT NOT NULL,
    PRIMARY KEY(Id_Preference),
    UNIQUE(Id_User),
    FOREIGN KEY(Id_User) REFERENCES User(Id_User)
);
CREATE TABLE Energy(
    Id_Energy INT AUTO_INCREMENT,
    energy VARCHAR(255)  NOT NULL,
    PRIMARY KEY(Id_Energy),
    UNIQUE(energy)
);
CREATE TABLE Car(
    Id_Car INT AUTO_INCREMENT,
    licence_plate VARCHAR(255)  NOT NULL,
    first_registration DATETIME NOT NULL,
    place_nb SMALLINT NOT NULL,
    main BOOLEAN NOT NULL,
    active BOOLEAN NOT NULL,
    created_at DATETIME NOT NULL,
    updated_at DATETIME,
    Id_Model INT NOT NULL,
    Id_Energy INT NOT NULL,
    Id_Color INT NOT NULL,
    Id_User INT NOT NULL,
    PRIMARY KEY(Id_Car),
    FOREIGN KEY(Id_Model) REFERENCES Model(Id_Model),
    FOREIGN KEY(Id_Energy) REFERENCES Energy(Id_Energy),
    FOREIGN KEY(Id_Color) REFERENCES Color(Id_Color),
    FOREIGN KEY(Id_User) REFERENCES User(Id_User)
);
CREATE TABLE Travel(
    Id_Travel INT AUTO_INCREMENT,
    eco BOOLEAN NOT NULL,
    travel_place SMALLINT NOT NULL,
    available_place SMALLINT NOT NULL,
    price INT NOT NULL,
    status VARCHAR(255)  NOT NULL,
    dep_date_time DATETIME NOT NULL,
    dep_address VARCHAR(255)  NOT NULL,
    dep_geo_x DECIMAL(9,6)   NOT NULL,
    dep_geo_y DECIMAL(9,6)   NOT NULL,
    arr_date_time DATETIME NOT NULL,
    arr_address VARCHAR(255)  NOT NULL,
    arr_geo_x DECIMAL(9,6)   NOT NULL,
    arr_geo_y DECIMAL(9,6)   NOT NULL,
    created_at DATETIME NOT NULL,
    updated_at DATETIME,
    Id_Car INT NOT NULL,
    PRIMARY KEY(Id_Travel),
    FOREIGN KEY(Id_Car) REFERENCES Car(Id_Car)
);
CREATE INDEX idx_travel_status_date ON travel (status, dep_date_time);
CREATE TABLE Travel_user(
    Id_Travel INT,
    Id_User INT,
    travel_role VARCHAR(50)  NOT NULL,
    PRIMARY KEY(Id_Travel, Id_User),
    FOREIGN KEY(Id_Travel) REFERENCES Travel(Id_Travel),
    FOREIGN KEY(Id_User) REFERENCES User(Id_User)
);

