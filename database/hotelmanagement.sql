-- Create the database
CREATE DATABASE IF NOT EXISTS hotelmanagement;

-- Use the newly created database
USE hotelmanagement;

-- Create the Guests table
CREATE TABLE IF NOT EXISTS Guest (
    GuestID INT PRIMARY KEY AUTO_INCREMENT,
    Guestname VARCHAR(255)  NOT NULL,
    Email VARCHAR(255)  NOT NULL,
    ContactInfo VARCHAR(255),
    Nationality VARCHAR(100),
    Gender VARCHAR(50)
);

-- Create the Hotels table
CREATE TABLE IF NOT EXISTS Hotel (
    HotelID INT PRIMARY KEY AUTO_INCREMENT,
    Name VARCHAR(255) NOT NULL,
    ContactInfo VARCHAR(255),
    TotalRooms INT
);

-- Create the Rooms table
CREATE TABLE IF NOT EXISTS Room (
    RoomNo INT PRIMARY KEY AUTO_INCREMENT,
    HotelID INT NOT NULL,
    Category VARCHAR(50),
    Rent DECIMAL(10, 2),
    Status VARCHAR(50),
    FOREIGN KEY (HotelID) REFERENCES Hotel(HotelID) ON DELETE CASCADE
);

-- Create the Reservations table
CREATE TABLE IF NOT EXISTS Reservation (
    ReservationID INT PRIMARY KEY AUTO_INCREMENT,
    GuestID INT NOT NULL,
    RoomNo INT NOT NULL,
    CheckInDate DATE NOT NULL,
    CheckOutDate DATE NOT NULL,
    FOREIGN KEY (GuestID) REFERENCES Guest(GuestID) ON DELETE CASCADE,
    FOREIGN KEY (RoomNo) REFERENCES Room(RoomNo) ON DELETE CASCADE
);

-- Create the Departments table
CREATE TABLE IF NOT EXISTS Department (
    DepartmentID INT PRIMARY KEY AUTO_INCREMENT,
    HotelID INT NOT NULL,
    Name VARCHAR(255) NOT NULL,
    DepartmentHead VARCHAR(255),
    DepartmentRole VARCHAR(255),
    ContactInfo VARCHAR(255),
    StaffCount INT,
    FOREIGN KEY (HotelID) REFERENCES Hotel(HotelID) ON DELETE CASCADE
);

-- Create the Staff table
CREATE TABLE IF NOT EXISTS Staff (
    StaffID INT PRIMARY KEY AUTO_INCREMENT,
    DepartmentID INT NOT NULL,
    Name VARCHAR(255) NOT NULL,
    Gender VARCHAR(50),
    ContactInfo VARCHAR(255),
    Salary DECIMAL(10, 2),
    FOREIGN KEY (DepartmentID) REFERENCES Department(DepartmentID) ON DELETE CASCADE
);

-- Create the Admin table
CREATE TABLE IF NOT EXISTS Admin (
    AdminID INT PRIMARY KEY AUTO_INCREMENT,
    Username VARCHAR(255) UNIQUE NOT NULL,
    Email VARCHAR(255) UNIQUE NOT NULL,
    Password VARCHAR(255) NOT NULL
);

-- Create the User table
CREATE TABLE IF NOT EXISTS User (
    UserID INT PRIMARY KEY AUTO_INCREMENT,
    Username VARCHAR(255) UNIQUE NOT NULL,
    Email VARCHAR(255) UNIQUE NOT NULL,
    Password VARCHAR(255) NOT NULL,
    ContactInfo VARCHAR(255),
    Gender VARCHAR(50)
);


-- Insert sample data into Admin table
INSERT INTO Admin (Username, Email, Password)
VALUES 
    ('admin1', 'admin1@example.com', 'adminpassword'),
    ('admin2', 'admin2@example.com', 'adminpassword123');


