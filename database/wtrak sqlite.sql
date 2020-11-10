--
-- File generated with SQLiteStudio v3.2.1 on Tue Nov 10 11:48:05 2020
--
-- Text encoding used: System
--
PRAGMA foreign_keys = off;
BEGIN TRANSACTION;

-- Table: wdata
DROP TABLE IF EXISTS wdata;
CREATE TABLE "wdata" (
	"dataid"	INTEGER PRIMARY KEY AUTOINCREMENT,
	"userid"	TEXT,
	"wdate"	TEXT,
	"wgt"	TEXT,
	"wnote"	TEXT
);

-- Table: wuser
DROP TABLE IF EXISTS wuser;
CREATE TABLE `wuser` (`userid`, `email`, `username`, `password`, `scode`, `wgoal`, `wgoaldate`, `wactive`, `lastlogin`, `useradmin`, `selector`, `token`, `expires`);

COMMIT TRANSACTION;
PRAGMA foreign_keys = on;
