create table if not exists address
(
    id   INT          NOT NULL AUTO_INCREMENT,
    street varchar(200) NOT NULL,
    number int NOT NULL,
    complement varchar(200) NULL,
    primary key (id)
);