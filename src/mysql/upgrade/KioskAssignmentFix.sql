/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Author:  Charles
 * Created: Aug 14, 2018
 */

DROP TABLE kioskassginment_kasm;

ALTER TABLE kioskdevice_kdev ADD COLUMN kdev_AssignmentData TEXT AFTER kdev_PendingCommands;