/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
/**
 * Author:  Charles
 * Created: Dec 14, 2017
 */

ALTER TABLE user_usr 
ALTER COLUMN `usr_showSince` set default '2016-01-01',
ALTER COLUMN `usr_LastLogin` set default '2016-01-01';