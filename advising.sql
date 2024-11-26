
-- Table for all users (students, advisors, admins, faculty)
CREATE TABLE Users (
    UserID INT AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(50),
    Email VARCHAR(100),
    Phone VARCHAR(15),
    Password VARCHAR(50),
    Role ENUM('Student', 'Advisor', 'Admin', 'Faculty'),
    AdditionalInfo TEXT
);

-- Table for courses
CREATE TABLE Courses (
    CourseID INT AUTO_INCREMENT PRIMARY KEY,
    CourseCode VARCHAR(10) UNIQUE,
    CourseTitle VARCHAR(100),
    CreditHours INT,
    Department VARCHAR(50),
    Semester VARCHAR(20),
    AcademicYear INT
);

-- Table for course registrations
CREATE TABLE Registrations (
    RegistrationID INT AUTO_INCREMENT PRIMARY KEY,
    StudentID INT,
    CourseID INT,
    RegistrationStatus ENUM('Registered', 'Dropped', 'Completed'),
    RegistrationDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    BatchYear INT,
    FOREIGN KEY (StudentID) REFERENCES Users(UserID),
    FOREIGN KEY (CourseID) REFERENCES Courses(CourseID)
);

-- Table for dropped courses
CREATE TABLE DroppedCourses (
    DropID INT AUTO_INCREMENT PRIMARY KEY,
    StudentID INT,
    CourseID INT,
    DropReason TEXT,
    DropDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    BatchYear INT,
    FOREIGN KEY (StudentID) REFERENCES Users(UserID),
    FOREIGN KEY (CourseID) REFERENCES Courses(CourseID)
);

-- Table for advising relationships
CREATE TABLE Advising (
    AdvisorID INT,
    StudentID INT,
    FOREIGN KEY (AdvisorID) REFERENCES Users(UserID),
    FOREIGN KEY (StudentID) REFERENCES Users(UserID),
    PRIMARY KEY (AdvisorID, StudentID)
);

-- Table for course offerings
CREATE TABLE CourseOfferings (
    OfferingID INT AUTO_INCREMENT PRIMARY KEY,
    CourseID INT,
    Semester VARCHAR(20),
    AcademicYear INT,
    FacultyID INT,
    FOREIGN KEY (CourseID) REFERENCES Courses(CourseID),
    FOREIGN KEY (FacultyID) REFERENCES Users(UserID)
);

-- Table for course approvals (if applicable)
CREATE TABLE CourseApprovals (
    ApprovalID INT AUTO_INCREMENT PRIMARY KEY,
    StudentID INT,
    CourseID INT,
    ApprovedBy INT,
    ApprovalDate TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (StudentID) REFERENCES Users(UserID),
    FOREIGN KEY (CourseID) REFERENCES Courses(CourseID),
    FOREIGN KEY (ApprovedBy) REFERENCES Users(UserID)
);

-- Table for student results
CREATE TABLE StudentResults (
    ResultID INT AUTO_INCREMENT PRIMARY KEY,
    StudentID INT,
    CourseID INT,
    Grade VARCHAR(2),
    Semester VARCHAR(20),
    AcademicYear INT,
    FOREIGN KEY (StudentID) REFERENCES Users(UserID),
    FOREIGN KEY (CourseID) REFERENCES Courses(CourseID)
);
