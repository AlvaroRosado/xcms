#/**
# *  \details &copy; 2011  Open Ximdex Evolution SL [http://www.ximdex.org]
# *
# *  Ximdex a Semantic Content Management System (CMS)
# *
# *  This program is free software: you can redistribute it and/or modify
# *  it under the terms of the GNU Affero General Public License as published
# *  by the Free Software Foundation, either version 3 of the License, or
# *  (at your option) any later version.
# *
# *  This program is distributed in the hope that it will be useful,
# *  but WITHOUT ANY WARRANTY; without even the implied warranty of
# *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
# *  GNU Affero General Public License for more details.
# *
# *  See the Affero GNU General Public License for more details.
# *  You should have received a copy of the Affero GNU General Public License
# *  version 3 along with Ximdex (see LICENSE file).
# *
# *  If not, visit http://gnu.org/licenses/agpl-3.0.html.
# *
# *  @author Ximdex DevTeam <dev@ximdex.com>
# *  @version $Revision$
# */


-- Activation of the actions associated to ximIO

CREATE TABLE `XimIONodeTranslations` (
  `IdNodeTranslation` int(11) NOT NULL auto_increment,
  `IdXimioExportation` int(11) NOT NULL,
  `IdExportationNode` int(11) NOT NULL,
  `IdImportationNode` int(11) NOT NULL,
  `IdExportationParent` int(11) NOT NULL,
  `status` int(3) NOT NULL,
  `path` varchar(255) NULL,
  PRIMARY KEY  (`IdNodeTranslation`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

CREATE TABLE `XimIOExportations` (
  `idXimIOExportation` int(11) NOT NULL auto_increment,
  `idXimIO` int(11) NOT NULL,
  `timeStamp` varchar(200) NOT NULL,
  PRIMARY KEY  (`idXimIOExportation`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- Activation of the roles for ximIO actions


INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6617, 8);
INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6617, 7);
INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6615, NULL);
INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6566, NULL);
INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6516, NULL);
INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6614, NULL);
INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6565, NULL);
INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6515, NULL);
-- INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6613, NULL);
INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6564, NULL);
INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6514, NULL);
INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6621, NULL);
INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6569, NULL);
INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6519, NULL);
INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6616, NULL);
-- INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6629, 8);
-- INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6629, 7);
INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6602, NULL);
INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6553, NULL);
INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6503, NULL);
INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6601, NULL);
INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6552, NULL);
INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6502, NULL);
-- INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6625, NULL);
-- INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6624, NULL);
-- INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6628, 8);
-- INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6628, 7);
INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6627, 8);
INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6627, 7);
INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6626, 8);
INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6626, 7);
INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6612, NULL);
INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6563, NULL);
INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6513, NULL);
INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6622, NULL);
INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6570, NULL);
INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6520, NULL);
INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6606, 8);
INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6606, 7);
INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6557, 8);
INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6557, 7);
INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6507, 8);
INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6507, 7);
-- INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6605, 8);
-- INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6605, 7);
-- INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6556, 8);
-- INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6556, 7);
-- INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6506, 8);
-- INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6506, 7);
INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6604, 8);
INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6604, 7);
INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6555, 8);
INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6555, 7);
INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6505, 8);
INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6505, 7);
-- INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6603, 8);
-- INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6603, 7);
INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6554, 8);
INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6554, 7);
INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6504, 8);
INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6504, 7);
INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6610, NULL);
INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6561, NULL);
INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6511, NULL);
-- INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6609, NULL);
INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6560, NULL);
INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6510, NULL);
-- INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6611, NULL);
INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6562, NULL);
INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6512, NULL);
INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6608, 8);
INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6608, 7);
INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6559, 8);
INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6559, 7);
INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6509, 8);
INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6509, 7);
-- INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6607, 8);
-- INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6607, 7);
-- INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6558, 8);
INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 204, 6617, 8);
INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 204, 6617, 7);
INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 204, 6615, NULL);
INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 204, 6614, NULL);
-- INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 204, 6613, NULL);
INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 204, 6621, NULL);
INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 204, 6616, NULL);
INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 204, 6629, 8);
INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 204, 6629, 7);
INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 204, 6602, NULL);
INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 204, 6601, NULL);
-- INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 204, 6625, NULL);
-- INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 204, 6624, NULL);
INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 204, 6628, 8);
INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 204, 6628, 7);
-- INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 204, 6627, 8);
-- INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 204, 6627, 7);
INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 204, 6626, 8);
INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 204, 6626, 7);
INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 204, 6612, NULL);
INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 204, 6622, NULL);
INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 204, 6606, 8);
INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 204, 6606, 7);
-- INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 204, 6605, 8);
-- INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 204, 6605, 7);
-- INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 204, 6604, 8);
-- INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 204, 6604, 7);
-- INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 204, 6603, 8);
-- INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 204, 6603, 7);
INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 204, 6610, NULL);
-- INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 204, 6609, NULL);
-- INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 204, 6611, NULL);
-- INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 204, 6608, 8);
-- INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 204, 6608, 7);
-- INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6558, 7);
-- INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6508, 8);
-- INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6508, 7);
-- INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 204, 6607, 8);
-- INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 204, 6607, 7);
-- INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 204, 6620, 8);
-- INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 204, 6620, 7);
-- INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 204, 6619, 8);
-- INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 204, 6619, 7);
-- INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6620, 8);
-- INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6620, 7);
-- INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6568, 8);
-- INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6568, 7);
INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6518, 8);
INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6518, 7);
INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6619, 8);
INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6619, 7);
INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6567, 8);
INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6567, 7);
INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6517, 8);
INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6517, 7);
INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6550, NULL);
INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6500, NULL);
INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6551, NULL);
INSERT INTO `RelRolesActions` (IdRel, IdRol, IdAction, IdState) VALUES(NULL, 201, 6501, NULL);
