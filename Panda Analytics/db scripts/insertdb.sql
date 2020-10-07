use FLOW_DB;

insert into UTENTE values(1,'MarioRossi','dullsalt','64731e162da87e7aa64ccc17bb236b586e7b7a9a3cc45e9ab0a117fd4bbf8558bc059826c9f10152c7591d55340ba0325b9e7057c52a73c35c153edfe862534927fcf580a1e4125b61d4effda5d6cf4558275890783a50f28becf56c71258e5ae2014227 ','dummymail@gmail.com','user',1);
-- password: password

insert into UTENTE values(2,'Cisco','Cisco','864c58d92ae652408cf79c9224ac66a0502c683ed01a069b268e69863885da444367dc156d7a6173bd8dc957537cda4dc9364d33bc379d100b405c021bb9c61bfa8d4d8dbcdfe697ef4db2549a5797f0df8ff183eb6e7efc8258bd864fa01c17b06eb060 ','ciscociscoclass@gmail.com','user',1);
-- password: class

insert into UTENTE values(3,'Dio Brando','ZA WARUDO','b38cdbbe0a2b36ec7617d6f95cefbb1071f5d464c86018f7d988f3e8ad0d4bd5dd717ed08c6b3493346225c48131613ff8c476e21fce13026549cf741c29498c12f7c8bce9d4037460e200ca37f3e1cac4ed6713c262ae5f74d41d3cd64f60bbd63c1856','jojomail@gmail.com','admin',1);
--password: konodioda

insert into FLOW values(1,'MarioRossiFlow',100000,1,'2019-01-01',1);
insert into FLOW values(2,'Cisco no Flow',200000000,0,'2008-12-13',2);


insert into TRANSAZIONI values (1120191,111,5814688,1,2019,default,1);
insert into TRANSAZIONI values (1120192,112,8373,1,2019,default,1);
insert into TRANSAZIONI values (1120193,121,24207478,1,2019,default,1);
insert into TRANSAZIONI values (1120194,122,125065,1,2019,default,1);
insert into TRANSAZIONI values (1120195,113,645885,1,2019,default,1);
insert into TRANSAZIONI values (1120196,123,645885,1,2019,default,1);
insert into TRANSAZIONI values (1120197,133,645885,1,2019,default,1);


insert into TRANSAZIONI values (1120198,211,  6375004,  1,  2019, default,  1);
insert into TRANSAZIONI values (1120199,221,7749287,1,2019,default,1);
insert into TRANSAZIONI values (11201910,222,56497,1,2019,default,1);
insert into TRANSAZIONI values (11201911,223,1045979,1,2019,default,1);
insert into TRANSAZIONI values (11201912,231,1096485,1,2019,default,1);



insert into TRANSAZIONI values (11201913,311,1323253,0,2019,default,1);
insert into TRANSAZIONI values (11201914,312,6099291,0,2019,default,1);
insert into TRANSAZIONI values (11201915,313,9371135,0,2019,default,1);
insert into TRANSAZIONI values (11201916,314,12973371,0,2019,default,1);
insert into TRANSAZIONI values (11201917,315,1044728,0,2019,default,1);
insert into TRANSAZIONI values (11201918,316,274366,0,2019,default,1);
insert into TRANSAZIONI values (11201919,317,148481,0,2019,default,1);
insert into TRANSAZIONI values (11201920,318,148481,0,2019,default,1);
insert into TRANSAZIONI values (11201921,321,148481,0,2019,default,1);
insert into TRANSAZIONI values (11201922,322,148481,0,2019,default,1);
insert into TRANSAZIONI values (11201923,323,148481,0,2019,default,1);

insert into TRANSAZIONI values (11201924,410,5000000,0,2019,default,1);
insert into TRANSAZIONI values (11201925,421,155753,0,2019,default,1);
insert into TRANSAZIONI values (11201926,422,70949,0,2019,default,1);
insert into TRANSAZIONI values (11201927,423,2873138,0,2019,default,1);


insert into TPrecisa values ('2019-03-11',1120191);
insert into TPrecisa values ('2019-04-11',1120192);
insert into TPrecisa values ('2019-05-11',1120193);
insert into TPrecisa values ('2019-06-11',1120194);
insert into TPrecisa values ('2019-07-11',1120195);
insert into TPrecisa values ('2019-08-11',1120196);
insert into TPrecisa values ('2019-09-11',1120197);
insert into TPrecisa values ('2019-10-11',1120198);
insert into TPrecisa values ('2019-11-11',1120199);
insert into TPrecisa values ('2019-12-11',11201910);
insert into TPrecisa values ('2019-01-11',11201911);
insert into TBOH values (1,11201912);
insert into TBOH values (2,11201913);
insert into TBOH values (3,11201914);
insert into TBOH values (4,11201915);
insert into TBOH values (5,11201916);
insert into TBOH values (6,11201917);
insert into TBOH values (7,11201918);
insert into TBOH values (8,11201919);
insert into TBOH values (9,11201920);
insert into TBOH values (10,11201921);

