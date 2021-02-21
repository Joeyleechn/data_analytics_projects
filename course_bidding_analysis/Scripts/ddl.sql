create schema G2T12;
use G2T12;

# create table, school
create table school
(
sch_name varchar(50) not null,
address varchar(100) not null,
url varchar(60) not null,
constraint school_pk primary key(sch_name)
);

# create table, programme
create table programme
(
pid varchar(10) not null,
prog_name varchar(80) not null,
homesch_name varchar(50),
secsch_name varchar(50),
constraint programme_pk primary key(pid),
constraint programme_fk1 foreign key (homesch_name) references school(sch_name),
constraint programme_fk2 foreign key (secsch_name) references school(sch_name)
);

# create table, student
create table student
(
sid varchar(10) not null,
stu_name varchar(80) not null,
email varchar(50) not null,
init_edollars decimal(5,2) not null,
type varchar(2) not null,
pid varchar(10) not null,
constraint student_pk primary key (sid),
constraint student_fk foreign key(pid) references programme(pid)
);


# create table, second_major
create table second_major
(
sid varchar(10) not null,
sch_name varchar(50) not null,
date_dedared date not null,
constraint second_major_pk primary key(sid),
constraint second_major_fk1 foreign key (sid) references student(sid),
constraint second_major_fk2 foreign key (sch_name) references school(sch_name)
);

# create table, double_degree
create table double_degree
(
sid varchar(10) not null,
gpa decimal(3,2) not null,
pid varchar(10) not null,
constraint double_degree_pk primary key(sid),
constraint double_degree_fk1 foreign key (sid) references student(sid),
constraint double_degree_fk2 foreign key (pid) references programme(pid)
);

# create table, round
create table round
(
rid int not null,
start_datetime datetime not null,
end_datetime datetime not null,
constraint round_pk primary key(rid)
);

# create table, course
create table course
(
cid varchar(10) not null,
title varchar(80)  not null,
pid varchar(10) not null,
constraint course_pk primary key(cid),
constraint course_fk1 foreign key (pid) references programme(pid)
);

# create table, programme_courses
create table programme_courses
(
pid varchar(10) not null,
cid varchar(10)  not null,
is_core char(1) not null,
rid int not null,
constraint programme_courses_pk primary key(pid, cid),
constraint programme_courses_fk1 foreign key (pid) references programme(pid),
constraint programme_courses_fk2 foreign key (cid) references course(cid),
constraint programme_courses_fk3 foreign key (rid) references round(rid)
);

# create table, section
create table section
(
cid varchar(10)  not null,
sno varchar(3)  not null,
sch_day varchar(10)  not null,
start_time time not null,
end_time time not null,
capacity int not null,
constraint section_pk primary key(cid, sno),
constraint section_fk1 foreign key (cid) references course(cid)
);

# create table, round_release
create table round_release
(
cid varchar(10)  not null,
sno varchar(3)  not null,
rid int not null,
no_seats int not null,
constraint round_release_pk primary key(cid, sno,rid),
constraint round_release_fk1 foreign key (cid,sno) references section(cid,sno), #note
constraint round_release_fk2 foreign key (rid) references round(rid)
);

# create table, bidding
create table bidding
(
cid varchar(10)  not null,
sno varchar(3)  not null,
rid int not null,
sid varchar(10)  not null,
edollars int not null,
bid_datetime datetime not null,
outcome char(1) not null,
constraint bidding_pk primary key(cid, sno,rid,sid),
constraint bidding_fk1 foreign key (cid, sno, rid) references round_release(cid, sno, rid), #note
constraint bidding_fk2 foreign key (sid) references student(sid)
);
SHOW VARIABLES LIKE "secure_file_priv";
# school O
LOAD DATA INFILE 'D:\\G2T12\\Data\\school.txt' 
INTO TABLE school FIELDS TERMINATED BY '\t' LINES TERMINATED BY '\r\n' IGNORE 1 LINES;

# programme O
LOAD DATA INFILE 'D:\\G2T12\\Data\\programme.txt' 
INTO TABLE programme FIELDS TERMINATED BY '\t' LINES TERMINATED BY '\r\n' IGNORE 1 LINES;

# student O
LOAD DATA INFILE 'D:\\G2T12\\Data\\student.txt' 
INTO TABLE student FIELDS TERMINATED BY '\t' LINES TERMINATED BY '\r\n' IGNORE 1 LINES;

# second_major O
LOAD DATA INFILE 'D:\\G2T12\\Data\\second_major.txt' 
INTO TABLE second_major FIELDS TERMINATED BY '\t' LINES TERMINATED BY '\r\n' IGNORE 1 LINES;

# double_degree O
LOAD DATA INFILE 'D:\\G2T12\\Data\\double_degree.txt' 
INTO TABLE double_degree FIELDS TERMINATED BY '\t' LINES TERMINATED BY '\r\n' IGNORE 1 LINES;

# round O
LOAD DATA INFILE 'D:\\G2T12\\Data\\round.txt' 
INTO TABLE round FIELDS TERMINATED BY '\t' LINES TERMINATED BY '\r\n' IGNORE 1 LINES;

# course O
LOAD DATA INFILE 'D:\\G2T12\\Data\\course.txt' 
INTO TABLE course FIELDS TERMINATED BY '\t' LINES TERMINATED BY '\r\n' IGNORE 1 LINES;

# programme_courses O
LOAD DATA INFILE 'D:\\G2T12\\Data\\programme_courses.txt' 
INTO TABLE programme_courses FIELDS TERMINATED BY '\t' LINES TERMINATED BY '\r\n' IGNORE 1 LINES;

# section O
LOAD DATA INFILE 'D:\\G2T12\\Data\\section.txt' 
INTO TABLE section FIELDS TERMINATED BY '\t' LINES TERMINATED BY '\r\n' IGNORE 1 LINES;

# round_release O
LOAD DATA INFILE 'D:\\G2T12\\Data\\round_release.txt' 
INTO TABLE round_release FIELDS TERMINATED BY '\t' LINES TERMINATED BY '\r\n' IGNORE 1 LINES;

# bidding O
LOAD DATA INFILE 'D:\\G2T12\\Data\\bidding.txt' 
INTO TABLE bidding FIELDS TERMINATED BY '\t' LINES TERMINATED BY '\r\n' IGNORE 1 LINES;

# create table, validtime
create table validtime
(
validtime date not null,
constraint validtime_pk primary key(validtime)
);

# insert value
insert into validtime values
('2018-11-05'),
('2018-11-06'),
('2018-11-07'),
('2018-11-08'),
('2018-11-09'),
('2018-11-10'),
('2018-11-11'),
('2018-11-12'),
('2018-11-13'),
('2018-11-14'),
('2018-11-15'),
('2018-11-16'),
('2018-11-17'),
('2018-11-18'),
('2018-11-19');