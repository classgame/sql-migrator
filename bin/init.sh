#!/bin/sh

#-----------------------------------------------------
# Start Project Config
#-----------------------------------------------------

# Vars
BASEDIR=$(dirname "$0")
ROOT_PATH="$BASEDIR"/../.

# Commands:

# Install dependencies
composer install -d "$ROOT_PATH"

# pre-commit check and formate previous committed files .php
cp "$BASEDIR"/pre-commit.sh .git/hooks/pre-commit &&
  chmod +x "$ROOT_PATH"/.git/hooks/pre-commit
