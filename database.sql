DROP TABLE IF EXISTS Fines;
CREATE TABLE Fines (
  fine_ID int(11) NOT NULL,
  fine_Amount int(11) NOT NULL,
  fine_Points int(11) NOT NULL,
  incident_ID int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO Fines (fine_ID, fine_Amount, fine_Points, incident_ID) VALUES
(1, 2000, 6, 3),
(2, 50, 0, 2),
(3, 500, 3, 4);

DROP TABLE IF EXISTS Incident;
CREATE TABLE Incident (
  incident_ID int(11) NOT NULL,
  vehicle_ID int(11) DEFAULT NULL,
  people_ID int(11) DEFAULT NULL,
  incident_Date date NOT NULL,
  incident_Report varchar(500) NOT NULL,
  offence_ID int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO Incident (incident_ID, vehicle_ID, people_ID, incident_Date, incident_Report, offence_ID) VALUES
(1, 15, 4, '2017-12-01', '40mph in a 30 limit', 1),
(2, 20, 8, '2017-11-01', 'Double parked', 4),
(3, 13, 4, '2017-09-17', '110mph on motorway', 1),
(4, 14, 2, '2017-08-22', 'Failure to stop at a red light - travelling 25mph', 8),
(5, 13, 4, '2017-10-17', 'Not wearing a seatbelt on the M1', 3);

DROP TABLE IF EXISTS Offence;
CREATE TABLE Offence (
  offence_ID int(11) NOT NULL,
  offence_description varchar(50) NOT NULL,
  offence_maxFine int(11) NOT NULL,
  offence_maxPoints int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO Offence (offence_ID, offence_description, offence_maxFine, offence_maxPoints) VALUES
(1, 'Speeding', 1000, 3),
(2, 'Speeding on a motorway', 2500, 6),
(3, 'Seat belt offence', 500, 0),
(4, 'Illegal parking', 500, 0),
(5, 'Drink driving', 10000, 11),
(6, 'Driving without a licence', 10000, 0),
(7, 'Driving without a licence', 10000, 0),
(8, 'Traffic light offences', 1000, 3),
(9, 'Cycling on pavement', 500, 0),
(10, 'Failure to have control of vehicle', 1000, 3),
(11, 'Dangerous driving', 1000, 11),
(12, 'Careless driving', 5000, 6),
(13, 'Dangerous cycling', 2500, 0);

DROP TABLE IF EXISTS Ownership;
CREATE TABLE Ownership (
  people_ID int(11) NOT NULL,
  vehicle_ID int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO Ownership (people_ID, vehicle_ID) VALUES
(3, 12),
(8, 20),
(4, 15),
(4, 13),
(1, 16),
(2, 14),
(5, 17),
(6, 18),
(7, 21);

DROP TABLE IF EXISTS People;
CREATE TABLE People (
  people_ID int(11) NOT NULL,
  people_name varchar(50) NOT NULL,
  people_address varchar(50) DEFAULT NULL,
  people_licence varchar(16) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO People (people_ID, people_name, people_address, people_licence) VALUES
(1, 'James Smith', '23 Barnsdale Road, Leicester', 'SMITH92LDOFJJ829'),
(2, 'Jennifer Allen', '46 Bramcote Drive, Nottingham', 'ALLEN88K23KLR9B3'),
(3, 'John Myers', '323 Derby Road, Nottingham', 'MYERS99JDW8REWL3'),
(4, 'James Smith', '26 Devonshire Avenue, Nottingham', 'SMITHR004JFS20TR'),
(5, 'Terry Brown', '7 Clarke Rd, Nottingham', 'BROWND3PJJ39DLFG'),
(6, 'Mary Adams', '38 Thurman St, Nottingham', 'ADAMSH9O3JRHH107'),
(7, 'Neil Becker', '6 Fairfax Close, Nottingham', 'BECKE88UPR840F9R'),
(8, 'Angela Smith', '30 Avenue Road, Grantham', 'SMITH222LE9FJ5DS');

DROP TABLE IF EXISTS Vehicle;
CREATE TABLE Vehicle (
  vehicle_ID int(11) NOT NULL,
  vehicle_type varchar(20) NOT NULL,
  vehicle_colour varchar(20) NOT NULL,
  vehicle_licence varchar(7) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO Vehicle (vehicle_ID, vehicle_type, vehicle_colour, vehicle_licence) VALUES
(12, 'Ford Fiesta', 'Blue', 'LB15AJL'),
(13, 'Ferrari 458', 'Red', 'MY64PRE'),
(14, 'Vauxhall Astra', 'Silver', 'FD65WPQ'),
(15, 'Honda Civic', 'Green', 'FJ17AUG'),
(16, 'Toyota Prius', 'Silver', 'FP16KKE'),
(17, 'Ford Mondeo', 'Black', 'FP66KLM'),
(18, 'Ford Focus', 'White', 'DJ14SLE'),
(20, 'Nissan Pulsar', 'Red', 'NY64KWD'),
(21, 'Renault Scenic', 'Silver', 'BC16OEA');

DROP TABLE IF EXISTS User_Access;
CREATE TABLE User_Access (
  user_ID int(11) NOT NULL,
  username varchar(20) NOT NULL,
  role varchar(20) NOT NULL,
  password varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

INSERT INTO User_Access (user_ID, username, role, password) VALUES
(1, 'Regan', 'officer', 'plod123'),
(2, 'Carter', 'officer', 'fuzz42'),
(3, 'haskins', 'admin', 'copper99');

ALTER TABLE Fines
  ADD PRIMARY KEY (Fine_ID),
  ADD KEY incident_ID (incident_ID);

ALTER TABLE Incident
  ADD PRIMARY KEY (incident_ID),
  ADD KEY fk_incident_vehicle (vehicle_ID),
  ADD KEY fk_incident_people (people_ID),
  ADD KEY fk_incident_offence (offence_ID);

ALTER TABLE Offence
  ADD PRIMARY KEY (offence_ID);

ALTER TABLE Ownership
  ADD KEY fk_people (people_ID),
  ADD KEY fk_vehicle (vehicle_ID);

ALTER TABLE People
  ADD PRIMARY KEY (people_ID),
  ADD UNIQUE KEY (people_licence);

ALTER TABLE User_Access
  ADD PRIMARY KEY (user_ID),
  ADD UNIQUE KEY (username);

ALTER TABLE Vehicle
  ADD PRIMARY KEY (vehicle_ID);


ALTER TABLE Fines
  MODIFY fine_ID int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;
ALTER TABLE Incident
  MODIFY incident_ID int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
ALTER TABLE Offence
  MODIFY offence_ID int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
ALTER TABLE People
  MODIFY people_ID int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;
ALTER TABLE Vehicle
  MODIFY vehicle_ID int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

ALTER TABLE Fines
  ADD CONSTRAINT fk_fines FOREIGN KEY (incident_ID) REFERENCES Incident (incident_ID);

ALTER TABLE Incident
  ADD CONSTRAINT fk_incident_offence FOREIGN KEY (offence_ID) REFERENCES Offence (offence_ID),
  ADD CONSTRAINT fk_incident_people FOREIGN KEY (people_ID) REFERENCES People (people_ID),
  ADD CONSTRAINT fk_incident_vehicle FOREIGN KEY (vehicle_ID) REFERENCES Vehicle (vehicle_ID);

ALTER TABLE Ownership
  ADD CONSTRAINT fk_person FOREIGN KEY (people_ID) REFERENCES People (people_ID),
  ADD CONSTRAINT fk_vehicle FOREIGN KEY (vehicle_ID) REFERENCES Vehicle (vehicle_ID);

