ALTER TABLE `emails`
  ADD COLUMN `cdate` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  ADD COLUMN `mdate` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;

ALTER TABLE `reminders`
  ADD COLUMN `cdate` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP;