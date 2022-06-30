<?php

/*
** Add Your Search Conditions Below!
**
** Directions are listed above each Search Condition.
** All text you add should go in between the quotation marks.
*/

// ADD THE NAME OF THE DIRECTORY YOU WANT TO SEARCH.
$_Directory        = "TreasureHunt";

// ADD YOUR PHRASE HERE.
$_SearchPhrase     = "treasure";

// DO YOU WANT TO IGNORE CASE SENSITIVITY? "yes" or "no".
$_IgnoreWordCase   = "yes";

// DO YOU WANT TO SEARCH ALL DIRECTORIES WITHIN CURRENT LOCATION? "yes" or "no"
$_SearchNestedDirs = "yes";

/*
** Do Not Edit Beyond This Point!
**
** If you accidentally change something or the application no longer works,
** delete this file and redownload it from GitHub where you got it:
** https://github.com/AndrewG13/DirectoryContentsSearch
*/

// establish array for found files
$_FileLog = array();
// UNUSED: establish array for unreadable/denied permission items
$_AccessDenied = array();

echo "\nThis is the Example Branch\n";
echo "It is configured to:\n";
echo "- Look in the 'TreasureHunt' folder\n";
echo "- Search for the phrase 'treasure'\n";
echo "- Ignore Word Case (So 'TREASURE' & 'treaSURE' & 'TrEaSuRe' will be found)\n";
echo "- Search in Nested Folders (So also search in any folders it comes across)\n";
echo "Mess around with the configuration in 'Search.php', towards the top of the file\n\n";

// Start script flow
promptUser();

// begin searching for the phrase, starting with the chosen directory
lookInsideDir($_Directory);

// create output file: Search_Results.txt
createOutputFile();

// @todo: Improve Readme & How To Use pages

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
  echo "Program Started\n\n";

  // Note: This input process was not streamlined into one function
  //       due to the differing nature of input conditions.

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
    if (file_exists($_Directory)) {
      $_Directory = __DIR__ . "/" . $_Directory;
      echo "<✓> Directory Predefined to: [{$_Directory}]\n\n";
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
    echo "<✓> Search Phrase Predefined to: [{$_SearchPhrase}]\n\n";
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
      echo "<✓> Ignore Case Flag Predefined to: [{$_IgnoreWordCase}]\n\n";
    } else {
      echo "<!> ERROR: Ignore Case Flag Predefinition Invalid: [{$_IgnoreWordCase}]\n\n";
      createErrorFile("_IgnoreWordCase is Invalid. Check Search.php, Line: 17");
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
      echo "<✓> Nested Directories Flag Predefined to: [{$_SearchNestedDirs}]\n\n";
    } else {
      echo "<!> ERROR: Nested Directories Flag Predefinition Invalid: [{$_SearchNestedDirs}]\n\n";
      createErrorFile("_SearchNestedDirs is Invalid. Check Search.php, Line: 20");
    }
  }

  // apply Ignore Case to phrase, if flag is set
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
  global $_AccessDenied;

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
        searchFile($dirpath . "/" . $entry, $entry);
      }
    }
  } else {
    // unable to open this directory (access is denied)
    $_AccessDenied[] = $dirpath;
  }
  closedir($handle);
}

/*
** Examine File Contents
**
*/
function searchFile($filepath, $filename) {
  global $_IgnoreWordCase;
  global $_SearchPhrase;
  global $_AccessDenied;

  // files will be read line-by-line to improve performance/decrease redundancy

  // get file handle, 'r' = read-only mode
  $handle = fopen($filepath, 'r');
  // ensure file is readable (file was verified to exist earlier)
  if ($handle) {
    $lineNum = 1;

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
        addToLog($filename, $lineNum, $filepath);
      }

      $lineNum++;
    }
  } else {
    // unable to open this dile (access is denied)
    $_AccessDenied[] = $dirpath;
  }

  fclose($handle);
}

/*
** Add To Log
**
*/
function addToLog($filename, $line, $location) {
  global $_FileLog;

  // create array of file attributes, append it to the file log
  $_FileLog[] = array("Name" => $filename,
                      "Line" => $line,
                      "Loc." => $location);
}

/*
**
**
*/
function createOutputFile() {
  global $_FileLog;
  global $_Directory;
  global $_SearchPhrase;

  $outputFile = __DIR__ . "/Search_Results.txt";
  $outputData = "Placeholder";
  $amountFound = count($_FileLog);

  // first remove an error file if it exists
  $errorFilePath = __DIR__ . "/Search_ERROR.txt";
  if (file_exists($errorFilePath)) {
    unlink($errorFilePath);
  }

  // check if any files were found
  if ($amountFound > 0) {
    // files found, output their details in new output file
    $outputData = "PHRASE FOUND \n\n" .
                  "Amount of Files: {$amountFound}\n\n";

    foreach ($_FileLog as $file) {
      $outputData .= "File Name   -> " . $file['Name'] . "\n";
      $outputData .= "Line Number -> " . $file['Line'] . "\n";
      $outputData .= "Location    -> " . $file['Loc.'] . "\n\n";
    }

  } else {
    // no files found, output "Phrase Not Found" message in new output file
    $outputData = "PHRASE NOT FOUND \n\n" .
                  "No Files Found Containing the Phrase: [{$_SearchPhrase}] \n" .
                  "Directory Searched:\n  {$_Directory}";
  }

  // create file
  file_put_contents($outputFile, $outputData);

  if (file_exists($outputFile)) {
    echo "Program Completed Successfully";
  } else {
    echo "<!> ERROR: File Creation Permissions Denied";
  }
}

function createErrorFile($errorMsg) {
  // create a Search_ERRORS.txt file
  // populate it with the error that cause the script to stop
  // errors could include:
  //   invalid inputs for IgnoreCase & Nested flags
  //   read access denied
  $errorMsg = "<!> Program Terminated due to Error:\n    " . $errorMsg;
  file_put_contents("Search_ERROR.txt", $errorMsg);
  echo $errorMsg;
  exit();
}

?>