<?php

/*
** Add Your Search Conditions Below!
**
** Directions are listed above each Search Condition.
** All text you add should go inbetween the quotation marks.
*/

// ADD THE NAME OF THE DIRECTORY YOU WANT TO SEARCH.
$_Directory        = "";

// ADD YOUR PHRASE HERE, BETWEEN THE QUOTATION MARKS.
$_SearchPhrase     = "";

// DO YOU WANT TO IGNORE CASE SENSITIVITY? "yes" or "no".
$_IgnoreWordCase   = "";

// DO YOU WANT TO SEARCH ALL DIRECTORIES WITHIN CURRENT LOCATION? "yes" or "no"
$_SearchNestedDirs = "";

/*
** Do Not Edit Beyond This Point!
**
** If you accidentally change something or the application no longer works,
** delete this file and redownload it from GitHub where you got it:
** https://github.com/AndrewG13/DirectoryContentsSearch
*/

// get current directory
$currentDir = __DIR__;
// append dir to search inside of
$dirToSearch = $currentDir . "/" . $_Directory;

promptUser();

/*
** Prompt User
**
*/
function promptUser() {
  global $_Directory;
  global $_SearchPhrase;
  global $_IgnoreWordCase;
  global $_SearchNestedDirs;

  $prompt = ">";
  echo "\n\n";

  // @todo: Make this input process into one function that will be called for each global variable

  // @todo: If predefintions are invalid, have the script create a Search_ERRORS.txt,
  //        and auto remove it whenever a script runs successfully.

  // Obtain Directory info
  if ($_Directory == "") {
    while ($_Directory == "") {
      echo "Please Enter the Directory Name You Wish to Search Inside:\n";
      $input = trim(readline($prompt));
      if ($input != "") {
        $_Directory = __DIR__ . "/" . $input;
        echo "\n";
      } else {
        echo "<!> ERROR: No Input Received\n\n";
      }
    }
  } else {
    echo "<!> Directory Predefined to: [{$_Directory}] in Search.php\n\n";
  }

  // Obtain Search Phrase
  if ($_SearchPhrase == "") {
    while ($_SearchPhrase == "") {
      echo "Please Enter the Search Phrase You Wish to Look For:\n";
      $input = trim(readline($prompt));
      if ($input != "") {
        $_SearchPhrase = $input;
        echo "\n";
      } else {
        echo "<!> ERROR: No Input Received\n\n";
      }
    }
  } else {
    echo "<!> Search Phrase Predefined to: [{$_SearchPhrase}] in Search.php\n\n";
  }

  // Obtain Ignorecase flag
  if ($_IgnoreWordCase == "") {
    while ($_IgnoreWordCase == "" || ($_IgnoreWordCase != "YES" && $_IgnoreWordCase != "NO") ) {
      echo "Would You Like to Ignore Case Sensitivity?\n[Yes|No]\n";
      $input = strtoupper(trim(readline($prompt)));
      if ($input != "") {
        if ($input == "YES" || $input == "NO") {
          $_IgnoreWordCase = $input;
          echo "\n";
        } else {
          echo "<!> ERROR: Invalid Input Received\n\n";
        }
      } else {
        echo "<!> ERROR: No Input Received\n\n";
      }
    }
  } else {
    $_IgnoreWordCase = strtoupper($_IgnoreWordCase);
    if ($_IgnoreWordCase == "YES" || $_IgnoreWordCase == "NO") {
      echo "<!> Ignore Case Flag Predefined to: [{$_IgnoreWordCase}] in Search.php\n\n";
    } else {
      echo "<!> ERROR: Ignore Case Flag Predefinition Invalid: [{$_IgnoreWordCase}] in Search.php\n\n";
      // terminate script
    }
  }

  // Obtain Nested Directory flag
  if ($_SearchNestedDirs == "") {
    while ($_SearchNestedDirs == "" || ($_SearchNestedDirs != "YES" && $_SearchNestedDirs != "NO") ) {
      echo "Would You Like to Also Search in Nested Directories?\n[Yes|No]\n";
      $input = strtoupper(trim(readline($prompt)));
      if ($input != "") {
        if ($input == "YES" || $input == "NO") {
          $_SearchNestedDirs = $input;
          echo "\n";
        } else {
          echo "<!> ERROR: Invalid Input Received\n\n";
        }
      } else {
        echo "<!> ERROR: No Input Received\n\n";
      }
    }
  } else {
    $_SearchNestedDirs = strtoupper($_SearchNestedDirs);
    if ($_SearchNestedDirs == "YES" || $_SearchNestedDirs == "NO") {
      echo "<!> Nested Directories Flag Predefined to: [{$_SearchNestedDirs}] in Search.php\n\n";
    } else {
      echo "<!> ERROR: Nested Directories Flag Predefinition Invalid: [{$_SearchNestedDirs}] in Search.php\n\n";
      // terminate script
    }
  }

  echo "D: {$_Directory}\nP: {$_SearchPhrase}\nI: {$_IgnoreWordCase}\nN: {$_SearchNestedDirs}\n";

}

/*
** Look Inside Directory
**
*/
function lookInsideDir() {

}

/*
** Examine File Contents
**
*/
function examineFileContents() {

}

?>