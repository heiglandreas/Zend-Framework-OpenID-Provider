#!/bin/bash
# loads database tables from file
sudo -u postgres psql < ./sql/database.sql
sudo -u postgres psql --dbname openid2 < ./sql/schema.sql
