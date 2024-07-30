
<!DOCTYPE html>
<html>
<head>

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
<link rel="stylesheet" type="text/css" href="includes/styles.css">
<meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Added AJAX jquery for SQL help -->
  <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"></script>


</head>

<body>
    <div class="header">
        <div class="top-bar">
            <img src="igt_logo.png" alt="IGT Logo">
            <div class="centered-text">GameDev Versions Tracker</div>
        </div>
  
    <div class="tab">
        <button class="tablinks" onclick="openTab(event, 'Add New')" id="defaultOpen">Add New</button>
        <button class="tablinks" onclick="openTab(event, 'Versions Table')" id="versionsTableTab">Versions Table</button>
        <button class="tablinks" onclick="openTab(event, 'Search Existing')">Search Existing</button>
    </div>

    </div>
    <div class="content"> 
    </div> 

<div class="form-container">
   
   <!----------------- Default Versions tab area ----------------->
    <div id="Versions Table" class="tabcontent">

        <?php 
          include 'includes/Database.inc'; 
        
        $strQuery = "SELECT * FROM MarketVersionTracker ORDER BY ID Desc";
        DisplayQuery($strQuery);
          

        ?>
    </div>
  
  <!----------------- Add New tab area ----------------->
    <div id="Add New" class="tabcontent">
       <form>
        <label for="next-Version">Next Version # available:</label>
        <input type="text" id="next-Version" name="nextVersionInput" value="<?php echo getLatestVersion(); ?>" readonly>
      
        <label for="market-input">Market:</label>
        <input type="text" id="market-input" name="marketInput">
           
        <label for="cabinet-input">Cabinet:</label>
        <input type="text" id="cabinet-input" name="cabinetInput">
    
        <label for="platform-input">Platform:</label>
        <input type="text" id="platform-input" name="platformInput">
          
        <label for="dept-input">Department:</label>
        <input type="text" id="dept-input" name="deptInput">
         
        <label for="leader-input">Leader:</label>
        <input type="text" id="leader-input" name="leaderInput">
        <div class="button-row">
          <button type="button" class="addMarket-button">Add Market</button>
        
        </form> 
      </div>
 
<?php
require_once 'includes/Database.inc';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'add_market') {
        if (empty($_POST['market_input'])) {
        echo 'Market must have a value before submitting.';
        exit;
        }
        echo addMarket(
            $_POST['next_version'],
            $_POST['market_input'],
            $_POST['cabinet_input'],
            $_POST['platform_input'],
            $_POST['dept_input'],
            $_POST['leader_input']
        );
    }
    exit;
}

?>
        
  <?php 
 
    $searchPerformed = false; // Initialize variable

    // Input search area
 if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['searchInput'])) {
    $searchPerformed = true;
    $keyword = $_POST['searchInput'];
    $keyword = trim($keyword);
    if (empty($keyword)) {
     
    } else {
        // returns the results
        $results = searchMarketVersionTracker($keyword);
    }
    }
          
    ?>    
    </div>
 <!----------------- Search Existing tab area ----------------->
   <div id="Search Existing" class="tabcontent">
    <div class="search-existing-container">
     <div class="search-existing-child1">
       <form id="searchForm" method="post" action="">
          <div class="search-group">
            <label for="search">Search Version, Market or Leader:</label>
            <input type="text" id="search" name="searchInput">
          </div>
          <div class="button-search">
            <button type="submit" class="search-button">Search</button>
          </div>
         </form>

        <h1 id="search-results">Search Results</h1>
            <?php if ($searchPerformed) : ?>
                <?php if (!empty($results)) : ?>
 <table border="1">
    <thead>
        <tr>
            <th>Version#</th>
            <th>Market</th>
            <th>Cabinet</th>
            <th>Platform</th>
            <th>Department</th>
            <th>Leader</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($results as $row) : ?>
            <tr onclick="highlightRow(this)">
                <td><?php echo htmlspecialchars($row['Version#']); ?></td>
                <td><?php echo htmlspecialchars($row['Market']); ?></td>
                <td><?php echo htmlspecialchars($row['Cabinet']); ?></td>
                <td><?php echo htmlspecialchars($row['Platform']); ?></td>
                <td><?php echo htmlspecialchars($row['Department']); ?></td>
                <td><?php echo htmlspecialchars($row['Leader']); ?></td>
            </tr>
        <?php endforeach; ?>
    </tbody>
    </table>
                <?php else : ?>
                    <p>No results found.</p>
                <?php endif; ?>
            <?php else : ?>
                <p>Enter a Version, Market or Lead.</p>
            <?php endif; ?> 
</div>     
    <div class="search-existing-child2">
        <form id="updateForm" onsubmit="return false;">
    <h2 id="Update">Modify the fields to update.</h2>
    <input type="text" id="Version" name="Version" readonly/>
    <input type="text" id="Market" name="Market" />
    <input type="text" id="Cabinet" name="Cabinet" />
    <input type="text" id="Platform" name="Platform" />
    <input type="text" id="Department" name="Department" />
    <input type="text" id="Leader" name="Leader" />
    <input type="hidden" id="updateAction" name="updateAction" value=""/>
        <div class="button-search">
             <button type="button" class="search-Update" name="search-Update" onclick="updateRecord()">Update</button>
        </div>
     </div>
     </form>
   
    </div>
 <?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['updateAction']) && $_POST['updateAction'] === 'updateRecord') {
        $version = $_POST['version'];
        $market = $_POST['market'];
        $cabinet = $_POST['cabinet'];
        $platform = $_POST['platform'];
        $department = $_POST['department'];
        $leader = $_POST['leader'];

        // Call the function with the required arguments
        updateRecordInDatabase($version, $market, $cabinet, $platform, $department, $leader);

    }
  
}
?>
  </div>   
   <script src="includes/scripts.js"></script>
</body>
    
</html>