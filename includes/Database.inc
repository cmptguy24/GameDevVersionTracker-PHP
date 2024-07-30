<?php

function Connect( $DB="172.22.100.45", $USER="cspcc", $PASS="#CSPCMT!K3rnel99" )
{
     
        $connect = mssql_connect( $DB, $USER, $PASS) 
        or die ( mssql_get_last_message() );

    mssql_query( "USE CSPCMTeamMarketIDs" );
    
    
     return $connect;
     
}

function Query( $strQuery )
{
    Connect();   
    return mssql_query( $strQuery );
}

function DisplayQuery( $strQuery )
{
    $cur = Query( $strQuery );
    $rowCount = mssql_num_rows( $cur );
    # If there are no rows, don't display the table
    if ( $rowCount < 1 )
    {
        print "\nNo row count in table MarketVersionTracker.\n";
        return;
    }
    
    // The number of columns.
    $Fields = mssql_num_fields($cur);

    print "<table border='1' width='90%'><tr>";

    //print "<th bgcolor='silver'>ID</th>";
    print "<th bgcolor='silver'>Version#</th>";
    print "<th bgcolor='silver'>Market</th>";
    print "<th bgcolor='silver'>Cabinet</th>";
    print "<th bgcolor='silver'>Platform</th>";
    print "<th bgcolor='silver'>Department</th>";
    print "<th bgcolor='silver'>Leader</th>";

    // Table Body
    $Outer=0;
    while( $line = mssql_fetch_row( $cur ))
    {
        $Outer++;
        print "<tr>";

        //$ID = $line[0];
        $Version = $line[1];
        $Market = $line[2];
        $Cabinet = $line[3];
        $Platform = $line[4];
        $Department = $line[5];
        $Leader = $line[6];

        //printf("<td>%s</td>", $ID );
        printf("<td>%s</td>", $Version );
        printf("<td>%s</td>", $Market );
        printf("<td>%s</td>", $Cabinet );
        printf("<td>%s</td>", $Platform );
        printf("<td>%s</td>", $Department );
        printf("<td>%s</td>", $Leader );
        
    }
    print "</table>"; 
}

function Addconnect() {
    // Database connection details
    $serverName = "172.22.100.45";
    $username = "cspcc";
    $password = "#CSPCMT!K3rnel99";
    $database = "CSPCMTeamMarketIDs";

    // Establish the connection
    $conn = mssql_connect($serverName, $username, $password);
    if (!$conn) {
        die('Could not connect: ' . mssql_get_last_message());
    }

    // Select the database
    mssql_select_db($database, $conn);

    return $conn;
}

function addMarket($next_version, $market_input, $cabinet_input, $platform_input, $dept_input, $leader_input) {
    $conn = Addconnect();

    $next_version = mssql_real_escape_string($next_version);
    $market_input = mssql_real_escape_string($market_input);
    $cabinet_input = mssql_real_escape_string($cabinet_input);
    $platform_input = mssql_real_escape_string($platform_input);
    $dept_input = mssql_real_escape_string($dept_input);
    $leader_input = mssql_real_escape_string($leader_input);

    $sql = "INSERT INTO MarketVersionTracker ([Version#], Market, Cabinet, Platform, Department, Leader) 
            VALUES ('$next_version', '$market_input', '$cabinet_input', '$platform_input', '$dept_input', '$leader_input')";

    $result = mssql_query($sql, $conn);
    mssql_close($conn);

    return $result ? 'success' : 'Error in adding market';
}

function getLatestVersion() {
    $conn = connect();
    
    // Query to get the latest version number, stripping the '.x' suffix and converting to an integer
    $sql = "SELECT TOP 1 CAST(SUBSTRING([Version#], 1, CHARINDEX('.x', [Version#]) - 1) AS INT) AS [Version#] FROM MarketVersionTracker ORDER BY [Version#] DESC";
  
    $result = mssql_query($sql, $conn);

    if (!$result) {
        die('Query failed: ' . mssql_get_last_message());
    }

    $row = mssql_fetch_assoc($result);
    mssql_close($conn);

    if(!$row) {
    error_log("Failed to fetch row: " . mssql_get_last_message());
    } else {

        if ($row) {
            $latestVersion = $row['Version#'];
            // Extract the numeric part and increment it
            $versionNumber = intval($latestVersion);
            $newVersionNumber = $versionNumber + 1;
            return $newVersionNumber . ".x";
        } else {
            return "1.x"; // Default value if there is no existing version
        }
    }
}

  
// Utility function for escaping SQL inputs
if (!function_exists('mssql_real_escape_string')) {
    function mssql_real_escape_string($data) {
        if (!isset($data) or $data == NULL) return '';
        if (is_array($data)) {
            return array_map('mssql_real_escape_string', $data);
        }
        if (is_numeric($data)) return $data;

         $non_displayables = array(
            '/%0[0-8bcef]/',
            '/%1[0-9a-f]/',
            '/[\x00-\x08]/',
            '/\x0b/',
            '/\x0c/',
            '/[\x0e-\x1f]/'
        );

        foreach ($non_displayables as $regex) {
            $data = preg_replace($regex, '', $data);
        }

        $data = str_replace("'", "''", $data);

        return $data;
    }
}

// Calls the stored Procedure for searching Version#, Markets or Leader
function searchMarketVersionTracker($keyword) {
    // Create the connection
    $conn = Addconnect();
    
    $query = "EXEC SearchMarketVersionTracker @Keyword = '$keyword'";

    // Execute the query
    $result = mssql_query($query, $conn);

    // Fetch results
    $results = array();
    while ($row = mssql_fetch_assoc($result)) {
        $results[] = $row;
    }

    // Close the connection
    mssql_close($conn);

    return $results;
}
function updateRecordInDatabase($version, $market, $cabinet, $platform, $department, $leader) {
    $conn = Addconnect();

    $sql = "EXEC UpdateMarketVersionTracker @Version = '$version', @Market = '$market', @Cabinet = '$cabinet', @Platform = '$platform', @Department = '$department', @Leader = '$leader'";
    //error_log("SQL Query: " . $sql, 3, 'error.log');
    $result = mssql_query($sql, $conn);
    if (!$result) {
        die('Query failed: ' . mssql_get_last_message());
    }

    mssql_close($conn);

    return true;
}
?>
