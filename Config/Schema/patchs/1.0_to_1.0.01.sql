BEGIN;
--Section : Annexes
INSERT INTO modelvalidations (id, modelvariable_id, modelsection_id, modeltype_id, min, max, actif) VALUES (1313, NULL, 3, 3, 0, 1, true);
INSERT INTO modelvalidations (id, modelvariable_id, modelsection_id, modeltype_id, min, max, actif) VALUES (1314, NULL, 3, 4, 0, 1, true);
INSERT INTO modelvalidations (id, modelvariable_id, modelsection_id, modeltype_id, min, max, actif) VALUES (1315, NULL, 3, 5, 0, 1, true);
INSERT INTO modelvalidations (id, modelvariable_id, modelsection_id, modeltype_id, min, max, actif) VALUES (1326, NULL, 3, 6, 0, 1, true);
INSERT INTO modelvalidations (id, modelvariable_id, modelsection_id, modeltype_id, min, max, actif) VALUES (1327, NULL, 3, 7, 0, 1, true);
UPDATE modelvalidations SET min=1,max=2 WHERE id=25;
UPDATE modelvalidations SET min=0,max=1 WHERE id=1272;

--Variable : nombre_annexe
INSERT INTO modelvalidations (id, modelvariable_id, modelsection_id, modeltype_id, min, max, actif) VALUES (1318, 52, 2, 4, 0, 0, true);
UPDATE modelvalidations SET modelsection_id=1 WHERE id=414;
UPDATE modelvalidations SET modelsection_id=1 WHERE id=415;
UPDATE modelvalidations SET modelsection_id=2 WHERE id=416;
UPDATE modelvalidations SET modelsection_id=2 WHERE id=417;
UPDATE modelvalidations SET modelsection_id=2 WHERE id=418;
UPDATE modelvalidations SET modelsection_id=2 WHERE id=419;

COMMIT;