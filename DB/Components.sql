-- -----------------------------------------------------
-- Data for table `uebungsplattform`.`Component`
-- -----------------------------------------------------
START TRANSACTION;
USE `uebungsplattform`;
INSERT INTO `uebungsplattform`.`Component` (`CO_id`, `CO_name`, `CO_address`, `CO_option`) VALUES (1, 'FSControl', 'localhost/uebungsplattform/FS/FSControl', '');
INSERT INTO `uebungsplattform`.`Component` (`CO_id`, `CO_name`, `CO_address`, `CO_option`) VALUES (2, 'FSFile', 'localhost/uebungsplattform/FS/FSFile', '');
INSERT INTO `uebungsplattform`.`Component` (`CO_id`, `CO_name`, `CO_address`, `CO_option`) VALUES (3, 'FSZip', 'localhost/uebungsplattform/FS/FSZip', '');
INSERT INTO `uebungsplattform`.`Component` (`CO_id`, `CO_name`, `CO_address`, `CO_option`) VALUES (4, 'DBControl', 'localhost/uebungsplattform/DB/DBControl', '');
INSERT INTO `uebungsplattform`.`Component` (`CO_id`, `CO_name`, `CO_address`, `CO_option`) VALUES (5, 'FSBinder', 'localhost/uebungsplattform/FS/FSBinder', '');
INSERT INTO `uebungsplattform`.`Component` (`CO_id`, `CO_name`, `CO_address`, `CO_option`) VALUES (6, 'DBCourse', 'localhost/uebungsplattform/DB/DBCourse', '');
INSERT INTO `uebungsplattform`.`Component` (`CO_id`, `CO_name`, `CO_address`, `CO_option`) VALUES (7, 'DBUser', 'localhost/uebungsplattform/DB/DBUser', '');
INSERT INTO `uebungsplattform`.`Component` (`CO_id`, `CO_name`, `CO_address`, `CO_option`) VALUES (8, 'DBAttachment', 'localhost/uebungsplattform/DB/DBAttachment', '');
INSERT INTO `uebungsplattform`.`Component` (`CO_id`, `CO_name`, `CO_address`, `CO_option`) VALUES (9, 'DBExercise', 'localhost/uebungsplattform/DB/DBExercise', '');
INSERT INTO `uebungsplattform`.`Component` (`CO_id`, `CO_name`, `CO_address`, `CO_option`) VALUES (10, 'DBExerciseSheet', 'localhost/uebungsplattform/DB/DBExerciseSheet', '');
INSERT INTO `uebungsplattform`.`Component` (`CO_id`, `CO_name`, `CO_address`, `CO_option`) VALUES (11, 'DBExerciseType', 'localhost/uebungsplattform/DB/DBExerciseType', '');
INSERT INTO `uebungsplattform`.`Component` (`CO_id`, `CO_name`, `CO_address`, `CO_option`) VALUES (12, 'DBFile', 'localhost/uebungsplattform/DB/DBFile', '');
INSERT INTO `uebungsplattform`.`Component` (`CO_id`, `CO_name`, `CO_address`, `CO_option`) VALUES (13, 'DBQuery', 'localhost/uebungsplattform/DB/DBQuery', '');
INSERT INTO `uebungsplattform`.`Component` (`CO_id`, `CO_name`, `CO_address`, `CO_option`) VALUES (14, 'LController', 'localhost/uebungsplattform/logic/Controller', '');
INSERT INTO `uebungsplattform`.`Component` (`CO_id`, `CO_name`, `CO_address`, `CO_option`) VALUES (15, 'LCourse', 'localhost/uebungsplattform/logic/Course', '');
INSERT INTO `uebungsplattform`.`Component` (`CO_id`, `CO_name`, `CO_address`, `CO_option`) VALUES (16, 'LGroup', 'localhost/uebungsplattform/logic/Group', '');
INSERT INTO `uebungsplattform`.`Component` (`CO_id`, `CO_name`, `CO_address`, `CO_option`) VALUES (17, 'LUser', 'localhost/uebungsplattform/logic/User', '');
INSERT INTO `uebungsplattform`.`Component` (`CO_id`, `CO_name`, `CO_address`, `CO_option`) VALUES (18, 'DBCourseStatus', 'localhost/uebungsplattform/DB/DBCourseStatus', '');
INSERT INTO `uebungsplattform`.`Component` (`CO_id`, `CO_name`, `CO_address`, `CO_option`) VALUES (19, 'FSPdf', 'localhost/uebungsplattform/FS/FSPdf', '');
INSERT INTO `uebungsplattform`.`Component` (`CO_id`, `CO_name`, `CO_address`, `CO_option`) VALUES (20, 'DBExternalId', 'localhost/uebungsplattform/DB/DBExternalId', '');
INSERT INTO `uebungsplattform`.`Component` (`CO_id`, `CO_name`, `CO_address`, `CO_option`) VALUES (21, 'DBApprovalCondition', 'localhost/uebungsplattform/DB/DBApprovalCondition', '');
INSERT INTO `uebungsplattform`.`Component` (`CO_id`, `CO_name`, `CO_address`, `CO_option`) VALUES (22, 'DBGroup', 'localhost/uebungsplattform/DB/DBGroup', '');
INSERT INTO `uebungsplattform`.`Component` (`CO_id`, `CO_name`, `CO_address`, `CO_option`) VALUES (23, 'DBInvitation', 'localhost/uebungsplattform/DB/DBInvitation', '');
INSERT INTO `uebungsplattform`.`Component` (`CO_id`, `CO_name`, `CO_address`, `CO_option`) VALUES (24, 'DBMarking', 'localhost/uebungsplattform/DB/DBMarking', '');
INSERT INTO `uebungsplattform`.`Component` (`CO_id`, `CO_name`, `CO_address`, `CO_option`) VALUES (25, 'DBSession', 'localhost/uebungsplattform/DB/DBSession', '');
INSERT INTO `uebungsplattform`.`Component` (`CO_id`, `CO_name`, `CO_address`, `CO_option`) VALUES (26, 'DBSubmission', 'localhost/uebungsplattform/DB/DBSubmission', '');
INSERT INTO `uebungsplattform`.`Component` (`CO_id`, `CO_name`, `CO_address`, `CO_option`) VALUES (27, 'DBSelectedSubmission', 'localhost/uebungsplattform/DB/DBSelectedSubmission', '');
INSERT INTO `uebungsplattform`.`Component` (`CO_id`, `CO_name`, `CO_address`, `CO_option`) VALUES (28, 'LTutor', 'localhost/uebungsplattform/logic/Tutor', '');
INSERT INTO `uebungsplattform`.`Component` (`CO_id`, `CO_name`, `CO_address`, `CO_option`) VALUES (29, 'LSubmission', 'localhost/uebungsplattform/logic/Submission', '');
INSERT INTO `uebungsplattform`.`Component` (`CO_id`, `CO_name`, `CO_address`, `CO_option`) VALUES (30, 'LMarking', 'localhost/uebungsplattform/logic/Marking', '');
INSERT INTO `uebungsplattform`.`Component` (`CO_id`, `CO_name`, `CO_address`, `CO_option`) VALUES (31, 'LExerciseSheet', 'localhost/uebungsplattform/logic/ExerciseSheet', '');
INSERT INTO `uebungsplattform`.`Component` (`CO_id`, `CO_name`, `CO_address`, `CO_option`) VALUES (32, 'LExercise', 'localhost/uebungsplattform/logic/Exercise', '');
INSERT INTO `uebungsplattform`.`Component` (`CO_id`, `CO_name`, `CO_address`, `CO_option`) VALUES (33, 'LCondition', 'localhost/uebungsplattform/logic/Condition', '');
INSERT INTO `uebungsplattform`.`Component` (`CO_id`, `CO_name`, `CO_address`, `CO_option`) VALUES (34, 'LAttachment', 'localhost/uebungsplattform/logic/Attachment', '');
INSERT INTO `uebungsplattform`.`Component` (`CO_id`, `CO_name`, `CO_address`, `CO_option`) VALUES (35, 'LExerciseType', 'localhost/uebungsplattform/logic/ExerciseType', '');
INSERT INTO `uebungsplattform`.`Component` (`CO_id`, `CO_name`, `CO_address`, `CO_option`) VALUES (37, 'LGetSite', 'localhost/uebungsplattform/logic/GetSite', '');
INSERT INTO `uebungsplattform`.`Component` (`CO_id`, `CO_name`, `CO_address`, `CO_option`) VALUES (38, 'DBExerciseFileType', 'localhost/uebungsplattform/DB/DBExerciseFileType', '');
COMMIT;


-- -----------------------------------------------------
-- Data for table `uebungsplattform`.`ComponentLinkage`
-- -----------------------------------------------------
START TRANSACTION;
USE `uebungsplattform`;
INSERT INTO `uebungsplattform`.`ComponentLinkage` (`CL_id`, `CO_id_owner`, `CL_name`, `CL_relevanz`, `CO_id_target`) VALUES (1, 3, 'getFile', '', 1);
INSERT INTO `uebungsplattform`.`ComponentLinkage` (`CL_id`, `CO_id_owner`, `CL_name`, `CL_relevanz`, `CO_id_target`) VALUES (2, 1, 'out', '', 2);
INSERT INTO `uebungsplattform`.`ComponentLinkage` (`CL_id`, `CO_id_owner`, `CL_name`, `CL_relevanz`, `CO_id_target`) VALUES (3, 1, 'out', '', 3);
INSERT INTO `uebungsplattform`.`ComponentLinkage` (`CL_id`, `CO_id_owner`, `CL_name`, `CL_relevanz`, `CO_id_target`) VALUES (4, 3, 'out', '0-f', 5);
INSERT INTO `uebungsplattform`.`ComponentLinkage` (`CL_id`, `CO_id_owner`, `CL_name`, `CL_relevanz`, `CO_id_target`) VALUES (5, 2, 'out', '0-f', 5);
INSERT INTO `uebungsplattform`.`ComponentLinkage` (`CL_id`, `CO_id_owner`, `CL_name`, `CL_relevanz`, `CO_id_target`) VALUES (6, 6, 'out', '', 13);
INSERT INTO `uebungsplattform`.`ComponentLinkage` (`CL_id`, `CO_id_owner`, `CL_name`, `CL_relevanz`, `CO_id_target`) VALUES (7, 7, 'out', '', 13);
INSERT INTO `uebungsplattform`.`ComponentLinkage` (`CL_id`, `CO_id_owner`, `CL_name`, `CL_relevanz`, `CO_id_target`) VALUES (8, 8, 'out', '', 13);
INSERT INTO `uebungsplattform`.`ComponentLinkage` (`CL_id`, `CO_id_owner`, `CL_name`, `CL_relevanz`, `CO_id_target`) VALUES (9, 9, 'out', '', 13);
INSERT INTO `uebungsplattform`.`ComponentLinkage` (`CL_id`, `CO_id_owner`, `CL_name`, `CL_relevanz`, `CO_id_target`) VALUES (10, 10, 'out', '', 13);
INSERT INTO `uebungsplattform`.`ComponentLinkage` (`CL_id`, `CO_id_owner`, `CL_name`, `CL_relevanz`, `CO_id_target`) VALUES (11, 11, 'out', '', 13);
INSERT INTO `uebungsplattform`.`ComponentLinkage` (`CL_id`, `CO_id_owner`, `CL_name`, `CL_relevanz`, `CO_id_target`) VALUES (12, 12, 'out', '', 13);
INSERT INTO `uebungsplattform`.`ComponentLinkage` (`CL_id`, `CO_id_owner`, `CL_name`, `CL_relevanz`, `CO_id_target`) VALUES (13, 4, 'out', '', 6);
INSERT INTO `uebungsplattform`.`ComponentLinkage` (`CL_id`, `CO_id_owner`, `CL_name`, `CL_relevanz`, `CO_id_target`) VALUES (14, 4, 'out', '', 7);
INSERT INTO `uebungsplattform`.`ComponentLinkage` (`CL_id`, `CO_id_owner`, `CL_name`, `CL_relevanz`, `CO_id_target`) VALUES (15, 4, 'out', '', 8);
INSERT INTO `uebungsplattform`.`ComponentLinkage` (`CL_id`, `CO_id_owner`, `CL_name`, `CL_relevanz`, `CO_id_target`) VALUES (16, 4, 'out', '', 9);
INSERT INTO `uebungsplattform`.`ComponentLinkage` (`CL_id`, `CO_id_owner`, `CL_name`, `CL_relevanz`, `CO_id_target`) VALUES (17, 4, 'out', '', 10);
INSERT INTO `uebungsplattform`.`ComponentLinkage` (`CL_id`, `CO_id_owner`, `CL_name`, `CL_relevanz`, `CO_id_target`) VALUES (18, 4, 'out', '', 11);
INSERT INTO `uebungsplattform`.`ComponentLinkage` (`CL_id`, `CO_id_owner`, `CL_name`, `CL_relevanz`, `CO_id_target`) VALUES (19, 4, 'out', '', 12);
INSERT INTO `uebungsplattform`.`ComponentLinkage` (`CL_id`, `CO_id_owner`, `CL_name`, `CL_relevanz`, `CO_id_target`) VALUES (21, 14, 'out', '', 15);
INSERT INTO `uebungsplattform`.`ComponentLinkage` (`CL_id`, `CO_id_owner`, `CL_name`, `CL_relevanz`, `CO_id_target`) VALUES (22, 14, 'out', '', 16);
INSERT INTO `uebungsplattform`.`ComponentLinkage` (`CL_id`, `CO_id_owner`, `CL_name`, `CL_relevanz`, `CO_id_target`) VALUES (23, 14, 'out', '', 17);
INSERT INTO `uebungsplattform`.`ComponentLinkage` (`CL_id`, `CO_id_owner`, `CL_name`, `CL_relevanz`, `CO_id_target`) VALUES (24, 14, 'database', '', 4);
INSERT INTO `uebungsplattform`.`ComponentLinkage` (`CL_id`, `CO_id_owner`, `CL_name`, `CL_relevanz`, `CO_id_target`) VALUES (25, 14, 'filesystem', '', 1);
INSERT INTO `uebungsplattform`.`ComponentLinkage` (`CL_id`, `CO_id_owner`, `CL_name`, `CL_relevanz`, `CO_id_target`) VALUES (29, 19, 'out', '0-f', 5);
INSERT INTO `uebungsplattform`.`ComponentLinkage` (`CL_id`, `CO_id_owner`, `CL_name`, `CL_relevanz`, `CO_id_target`) VALUES (30, 1, 'out', '', 19);
INSERT INTO `uebungsplattform`.`ComponentLinkage` (`CL_id`, `CO_id_owner`, `CL_name`, `CL_relevanz`, `CO_id_target`) VALUES (31, 4, 'out', '', 25);
INSERT INTO `uebungsplattform`.`ComponentLinkage` (`CL_id`, `CO_id_owner`, `CL_name`, `CL_relevanz`, `CO_id_target`) VALUES (32, 4, 'out', '', 20);
INSERT INTO `uebungsplattform`.`ComponentLinkage` (`CL_id`, `CO_id_owner`, `CL_name`, `CL_relevanz`, `CO_id_target`) VALUES (33, 25, 'out', '', 13);
INSERT INTO `uebungsplattform`.`ComponentLinkage` (`CL_id`, `CO_id_owner`, `CL_name`, `CL_relevanz`, `CO_id_target`) VALUES (40, 20, 'out', '', 13);
INSERT INTO `uebungsplattform`.`ComponentLinkage` (`CL_id`, `CO_id_owner`, `CL_name`, `CL_relevanz`, `CO_id_target`) VALUES (34, 4, 'out', '', 21);
INSERT INTO `uebungsplattform`.`ComponentLinkage` (`CL_id`, `CO_id_owner`, `CL_name`, `CL_relevanz`, `CO_id_target`) VALUES (35, 4, 'out', '', 22);
INSERT INTO `uebungsplattform`.`ComponentLinkage` (`CL_id`, `CO_id_owner`, `CL_name`, `CL_relevanz`, `CO_id_target`) VALUES (37, 21, 'out', '', 13);
INSERT INTO `uebungsplattform`.`ComponentLinkage` (`CL_id`, `CO_id_owner`, `CL_name`, `CL_relevanz`, `CO_id_target`) VALUES (38, 22, 'out', '', 13);
INSERT INTO `uebungsplattform`.`ComponentLinkage` (`CL_id`, `CO_id_owner`, `CL_name`, `CL_relevanz`, `CO_id_target`) VALUES (39, 23, 'out', '', 13);
INSERT INTO `uebungsplattform`.`ComponentLinkage` (`CL_id`, `CO_id_owner`, `CL_name`, `CL_relevanz`, `CO_id_target`) VALUES (41, 4, 'out', '', 24);
INSERT INTO `uebungsplattform`.`ComponentLinkage` (`CL_id`, `CO_id_owner`, `CL_name`, `CL_relevanz`, `CO_id_target`) VALUES (42, 24, 'out', '', 13);
INSERT INTO `uebungsplattform`.`ComponentLinkage` (`CL_id`, `CO_id_owner`, `CL_name`, `CL_relevanz`, `CO_id_target`) VALUES (43, 4, 'out', '', 18);
INSERT INTO `uebungsplattform`.`ComponentLinkage` (`CL_id`, `CO_id_owner`, `CL_name`, `CL_relevanz`, `CO_id_target`) VALUES (44, 18, 'out', '', 13);
INSERT INTO `uebungsplattform`.`ComponentLinkage` (`CL_id`, `CO_id_owner`, `CL_name`, `CL_relevanz`, `CO_id_target`) VALUES (45, 4, 'out', '', 27);
INSERT INTO `uebungsplattform`.`ComponentLinkage` (`CL_id`, `CO_id_owner`, `CL_name`, `CL_relevanz`, `CO_id_target`) VALUES (46, 27, 'out', '', 13);
INSERT INTO `uebungsplattform`.`ComponentLinkage` (`CL_id`, `CO_id_owner`, `CL_name`, `CL_relevanz`, `CO_id_target`) VALUES (47, 4, 'out', '', 26);
INSERT INTO `uebungsplattform`.`ComponentLinkage` (`CL_id`, `CO_id_owner`, `CL_name`, `CL_relevanz`, `CO_id_target`) VALUES (48, 26, 'out', '', 13);
INSERT INTO `uebungsplattform`.`ComponentLinkage` (`CL_id`, `CO_id_owner`, `CL_name`, `CL_relevanz`, `CO_id_target`) VALUES (49, 14, 'out', '', 31);
INSERT INTO `uebungsplattform`.`ComponentLinkage` (`CL_id`, `CO_id_owner`, `CL_name`, `CL_relevanz`, `CO_id_target`) VALUES (50, 14, 'out', '', 30);
INSERT INTO `uebungsplattform`.`ComponentLinkage` (`CL_id`, `CO_id_owner`, `CL_name`, `CL_relevanz`, `CO_id_target`) VALUES (51, 14, 'out', '', 29);
INSERT INTO `uebungsplattform`.`ComponentLinkage` (`CL_id`, `CO_id_owner`, `CL_name`, `CL_relevanz`, `CO_id_target`) VALUES (52, 14, 'out', '', 28);
INSERT INTO `uebungsplattform`.`ComponentLinkage` (`CL_id`, `CO_id_owner`, `CL_name`, `CL_relevanz`, `CO_id_target`) VALUES (53, 14, 'out', '', 32);
INSERT INTO `uebungsplattform`.`ComponentLinkage` (`CL_id`, `CO_id_owner`, `CL_name`, `CL_relevanz`, `CO_id_target`) VALUES (54, 14, 'out', '', 33);
INSERT INTO `uebungsplattform`.`ComponentLinkage` (`CL_id`, `CO_id_owner`, `CL_name`, `CL_relevanz`, `CO_id_target`) VALUES (55, 14, 'out', '', 34);
INSERT INTO `uebungsplattform`.`ComponentLinkage` (`CL_id`, `CO_id_owner`, `CL_name`, `CL_relevanz`, `CO_id_target`) VALUES (56, 34, 'controller', '', 14);
INSERT INTO `uebungsplattform`.`ComponentLinkage` (`CL_id`, `CO_id_owner`, `CL_name`, `CL_relevanz`, `CO_id_target`) VALUES (57, 33, 'controller', '', 14);
INSERT INTO `uebungsplattform`.`ComponentLinkage` (`CL_id`, `CO_id_owner`, `CL_name`, `CL_relevanz`, `CO_id_target`) VALUES (58, 17, 'controller', '', 14);
INSERT INTO `uebungsplattform`.`ComponentLinkage` (`CL_id`, `CO_id_owner`, `CL_name`, `CL_relevanz`, `CO_id_target`) VALUES (59, 15, 'controller', '', 14);
INSERT INTO `uebungsplattform`.`ComponentLinkage` (`CL_id`, `CO_id_owner`, `CL_name`, `CL_relevanz`, `CO_id_target`) VALUES (60, 32, 'controller', '', 14);
INSERT INTO `uebungsplattform`.`ComponentLinkage` (`CL_id`, `CO_id_owner`, `CL_name`, `CL_relevanz`, `CO_id_target`) VALUES (61, 31, 'controller', '', 14);
INSERT INTO `uebungsplattform`.`ComponentLinkage` (`CL_id`, `CO_id_owner`, `CL_name`, `CL_relevanz`, `CO_id_target`) VALUES (62, 16, 'controller', '', 14);
INSERT INTO `uebungsplattform`.`ComponentLinkage` (`CL_id`, `CO_id_owner`, `CL_name`, `CL_relevanz`, `CO_id_target`) VALUES (63, 30, 'controller', '', 14);
INSERT INTO `uebungsplattform`.`ComponentLinkage` (`CL_id`, `CO_id_owner`, `CL_name`, `CL_relevanz`, `CO_id_target`) VALUES (64, 29, 'controller', '', 14);
INSERT INTO `uebungsplattform`.`ComponentLinkage` (`CL_id`, `CO_id_owner`, `CL_name`, `CL_relevanz`, `CO_id_target`) VALUES (65, 28, 'controller', '', 14);
INSERT INTO `uebungsplattform`.`ComponentLinkage` (`CL_id`, `CO_id_owner`, `CL_name`, `CL_relevanz`, `CO_id_target`) VALUES (67, 37, 'controller', '', 14);
INSERT INTO `uebungsplattform`.`ComponentLinkage` (`CL_id`, `CO_id_owner`, `CL_name`, `CL_relevanz`, `CO_id_target`) VALUES (69, 14, 'out', '', 37);
INSERT INTO `uebungsplattform`.`ComponentLinkage` (`CL_id`, `CO_id_owner`, `CL_name`, `CL_relevanz`, `CO_id_target`) VALUES (70, 4, 'out', '', 38);
INSERT INTO `uebungsplattform`.`ComponentLinkage` (`CL_id`, `CO_id_owner`, `CL_name`, `CL_relevanz`, `CO_id_target`) VALUES (71, 38, 'out', '', 13);
COMMIT;