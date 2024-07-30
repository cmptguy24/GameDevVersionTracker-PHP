$(document).ready(function() {
    $('.addMarket-button').click(function() {
            var marketInput = $('#market-input').val();
        if (!marketInput) {
        alert('Market must have a value before submitting.');
        return false;
        }
        var data = {
            action: 'add_market',
            next_version: $('#next-Version').val(),
            market_input: marketInput,
            cabinet_input: $('#cabinet-input').val(),
            platform_input: $('#platform-input').val(),
            dept_input: $('#dept-input').val(),
            leader_input: $('#leader-input').val()
        };

        $.post('index.php', data, function(response) {
            setTimeout(function() {
            // Switch to the tab and refresh the page after the post request is successful
            document.getElementById("versionsTableTab").click(); // Switch to the tab
            location.reload();
            }, 1000); 
        });
    });
});

 function openTab(evt, tabName) {
    // Declare all variables
    var i, tabcontent, tablinks;

    // Get all elements with class="tabcontent" and hide them
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

    // Get all elements with class="tablinks" and remove the class "active"
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }

    // Show the current tab, and add an "active" class to the button that opened the tab
    document.getElementById(tabName).style.display = "block";
    evt.currentTarget.className += " active";

    // Save the active tab in localStorage
    localStorage.setItem('activeTab', tabName);
}

// Get the active tab from localStorage and open it. Added for Search tab
document.addEventListener('DOMContentLoaded', function () {
    var activeTab = localStorage.getItem('activeTab');
    if (activeTab) {
        document.getElementById(activeTab).style.display = "block";
        var tablinks = document.getElementsByClassName("tablinks");
        for (var i = 0; i < tablinks.length; i++) {
            if (tablinks[i].getAttribute('onclick').includes(activeTab)) {
                tablinks[i].className += " active";
                break;
            }
        }
    } else {
        // If no tab is active, open the default tab
        document.getElementById("defaultOpen").click();
    }
});

$(document).ready(function() {
    $('.search-button').click(function() {
        var keyword = $('#search').val().trim();

        if (keyword !== '') {
            $.ajax({
                url: 'index.php',
                type: 'POST',
                data: { searchInput: keyword },
                success: function(data) {
                    $('#search-results').html(data);
                  }
            });
        }
    });
});

function highlightRow(row) {
    var rows = document.getElementsByTagName('tr');
    for (var i = 0; i < rows.length; i++) {
        rows[i].style.backgroundColor = ''; // clear previous highlight
    }
    row.style.backgroundColor = 'yellow'; // highlight clicked row
    populateInputs(row); // populate input boxes
        // Get the .search-existing-child2 container
    var container = document.querySelector('.search-existing-child2');

    // Check if the container exists
    if (container) {
        // Set the display property to block
        container.style.display = 'block';
    }
}
function populateInputs(row) {
    var cells = row.getElementsByTagName('td');
    for (var i = 0; i < cells.length; i++) {
        var input;
        switch(i) {
            case 0:
                input = document.getElementById('Version');
                break;
            case 1:
                input = document.getElementById('Market');
                break;
            case 2:
                input = document.getElementById('Cabinet');
                break;
            case 3:
                input = document.getElementById('Platform');
                break;
            case 4:
                input = document.getElementById('Department');
                break;
            case 5:
                input = document.getElementById('Leader');
                break;
        }
        input.value = cells[i].innerText;
    }
}
function updateRecord() {
    document.getElementById("updateAction").value = "updateRecord";
    
    var version = document.getElementById('Version').value;
    var market = document.getElementById('Market').value;
    var cabinet = document.getElementById('Cabinet').value;
    var platform = document.getElementById('Platform').value;
    var department = document.getElementById('Department').value;
    var leader = document.getElementById('Leader').value;

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "index.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            location.reload(); // Refresh the current page
        }
    };
    xhr.send("updateAction=" + encodeURIComponent(document.getElementById("updateAction").value) +
             "&version=" + encodeURIComponent(version) + 
             "&market=" + encodeURIComponent(market) + 
             "&cabinet=" + encodeURIComponent(cabinet) + 
             "&platform=" + encodeURIComponent(platform) + 
             "&department=" + encodeURIComponent(department) + 
             "&leader=" + encodeURIComponent(leader));
}












