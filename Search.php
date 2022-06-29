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

// Start script flow
promptUser();

//echo "D: {$_Directory}\nP: {$_SearchPhrase}\nI: {$_IgnoreWordCase}\nN: {$_SearchNestedDirs}\n";

// begin searching for the phrase, starting with the chosen directory
lookInsideDir($_Directory);

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

  // @todo Immediately: Create the array to store all file names that contain the phrase (line 225)
  //                    Output the results at the end of the entire search.
  //                    Create a new Text file with the results.

  // @todo: Make this input process into one function that will be called for each global variable

  // @todo: If predefintions are invalid, have the script create a Search_ERRORS.txt,
  //        and auto remove it whenever a script runs successfully.

  // Obtain Directory info
  if ($_Directory == "") {
    while ($_Directory == "") {
      echo "Please Enter the Directory Name You Wish to Search:\n";
      $input = trim(readline($prompt));
      if ($input != "") {
        if (file_exists($input)) {
          $_Directory = __DIR__ . "/" . $input;
          echo "\n";
        } else {
          echo "<!> ERROR: No Such Directory [{$input}] Exists\n\n";
        }
      } else {
        echo "<!> ERROR: No Input Received\n\n";
      }
    }
  } else {
    if (file_exists($input)) {
      $_Directory = __DIR__ . "/" . $input;
      echo "<!> Directory Predefined to: [{$_Directory}] in Search.php\n\n";
    } else {
      echo "<!> ERROR: No Such Directory [{$input}] Exists\n\n";
    }
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

  // handle Ignore Case with phrase
  if ($_IgnoreWordCase == "YES") {
    $_SearchPhrase = strtoupper($_SearchPhrase);
  }
}

/*
** Look Inside Directory
**
*/
function lookInsideDir($dirpath) {
  global $_SearchNestedDirs;

  // get directory handle
  $handle = opendir($dirpath);
  // ensure directory can be opened (it was verfied to exist earlier)
  if ($handle) {

    // observe entire structure of directory:
    //  if $entry is a file: examine its contents
    //  if $entry is a  dir: look inside ONLY if Nested flag is "YES", skip otherwise

    while (false !== ($entry = readdir($handle))) {
      if (is_dir($dirpath . "/" . $entry)) {
        // entry is a directory, check if Nested flag is on
        if ($_SearchNestedDirs == "YES") {
          // Nested flag is true, ensure entry is not a parent
          if (stripos($entry, '.') !== 0) {
            // recursive call to search this directory too!
            //echo $entry . "\n";
            lookInsideDir($dirpath . "/" . $entry);
          }
        }
      } else {
        // entry is a file, examine its contents
        //echo $entry . "\n";
        searchFile($dirpath . "/" . $entry);
      }
    }
  } else {
    echo "<!> ERROR: Directory Access Permissions Denied\n";
    // terminate
  }
  closedir($handle);
}

/*
** Examine File Contents
**
*/
function searchFile($filepath) {
  global $_IgnoreWordCase;

  // files will be read line-by-line to improve performance/decrease redundancy

  // get file handle
  $handle = fopen($filepath);
  // ensure file is readable (file was verified to exist earlier)
  if ($handle) {

    // iterate through lines while phrase not found & end of file not reached
    while (!feof($handle)) {
      // get current line text
      $line = fgets($handle);
      // check if Ignore Case flag is true
      if ($_IgnoreWordCase == "YES") {
        // capitalize entire line. Done to match casing
        $line = strtoupper($line);
      }

      // finally, search line for the phrase
      if (str_contains($line, $_SearchPhrase)) {
        // ...add to array...
      }
    }
  }

  fclose($handle);

}

?>