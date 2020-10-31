#!/bin/sh

PROJECT=$(php -r "echo dirname(dirname(dirname(realpath('$0'))));")
STAGED_FILES_CMD=$(git diff --cached --name-only --diff-filter=ACMR HEAD | grep .php)

# Determine if a file list is passed
if [ "$#" -eq 1 ]; then
  oIFS=$IFS
  IFS='
	'
  SFILES="$1"
  IFS=$oIFS
fi
SFILES=${SFILES:-$STAGED_FILES_CMD}

if [ -z "$FILES" ] || [ "$FILES" = "" ]; then
  exit 0;
fi

echo ""
echo "|----------------------------------"
echo "|  Checking PHP Lint..."
echo "|----------------------------------"
echo ""

for FILE in $SFILES; do
  php -l -d display_errors=0 "$PROJECT/$FILE"
  if [ $? != 0 ]; then
    echo " Fix the error before commit."
    echo ""
    exit 1
  fi
  FILES="$FILES $PROJECT/$FILE"
done

echo ""

sleep 0.4

if [ -n "$FILES" ] || [ "$FILES" != "" ]; then
  echo ""
  echo "|----------------------------------"
  echo "|  Applying PHP CS Fixer..."
  echo "|----------------------------------"
  echo ""

  for FILE in $SFILES; do
    echo "  Fixing:" "$FILE"
    "$PROJECT"/vendor/bin/php-cs-fixer fix --config="$PROJECT"/.php_cs.php "$PROJECT/$FILE" -q

    if [ $? != 0 ]; then
      echo "Fix fail."
      echo ""
      exit 1
    fi
  done

  echo ""

  sleep 0.4

  git add $SFILES

  echo ""
  echo " Fix applied!"
  echo ""
  echo "|----------------------------------"
  echo "|  Running PHP Code Sniffer..."
  echo "|----------------------------------"
  echo ""

  for FILE in $SFILES; do
    echo "  Fixing:" "$FILE"
    "$PROJECT"/vendor/bin/phpcs --standard="$PROJECT"/phpcs.xml --encoding=utf-8 -n -p "$PROJECT/$FILE"

    if [ $? != 0 ]; then
      echo " Errors found not fixable automatically"
      exit 1
    fi
  done
fi

echo ""
echo " Good Job!"
echo ""

exit $?
