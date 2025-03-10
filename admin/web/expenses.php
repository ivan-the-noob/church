
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HowChurch</title>
<link rel="icon" href="../../../assets/logo.png" type="image/png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.5.0/dist/sweetalert2.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.5.0/dist/sweetalert2.all.min.js"></script>
    <link rel="stylesheet" href="../css/index.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
<div class="">   
        <div class="navbar flex-column shadow-sm p-3 collapse show" id="navbar">
            <div class="navbar-header d-flex justify-content-between align-items-center w-100">
                <a class="navbar-brand d-none d-md-block logo-container" href="#">
                    <img src="../../assets/logo.png" alt="Logo">
                </a>
            </div>
            <div class="navbar-links">
                <a href="dashboard.php">
                    <i class="fa-solid fa-gauge-high"></i>
                    <span>Dashboard</span>
                </a>
                <a href="calendar.php">
                    <i class="fa-solid fa-calendar-days"></i>
                    <span>Offerings</span>
                </a>
                <a href="pending.php">
                    <i class="fa-solid fa-clock"></i>
                    <span>Expenses</span>
                </a>
               
            </div>
        </div>
    
    </div>

    </div>
    </div>
    <div class="container content flex-grow-1">
        <h1 class="mt-2">Expenses</h1>
        <div class="d-flex justify-content-between">
            <button class="add-btn mb-2" data-bs-toggle="modal" data-bs-target="#exampleModal">Add</button>
            <form method="GET" action="">
                <input type="text" name="search" class="search mb-2" placeholder="Search by name..." value="<?php echo isset($_GET['search']) ? $_GET['search'] : ''; ?>">
            </form>

        </div>
    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Add Donation</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                <form id="expenseForm">
                    <div class="mb-3">
                        <label for="name" class="form-label">Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="type" class="form-label">Type</label>
                        <select class="form-select" id="type" name="type" required>
                            <option value="tithes">Tithes</option>
                            <option value="offerings">Offerings</option>
                            <option value="love_gift">Love Gift</option>
                            <option value="first_fruit">First Fruit</option>
                            <option value="sacrificial_giving">Sacrificial Giving</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="amount" class="form-label">Amount</label>
                        <input type="number" step="0.01" class="form-control" id="amount" name="amount" required>
                    </div>
                    <button type="submit" class="btn btn-primary d-flex mx-auto w-50 align-items-center justify-content-center">Save</button>
                </form>

                </div>
            </div>
        </div>
    </div>
        
    <?php
// Include database connection
include('../../db.php');

// Fetch donations data from the database
$sql = "SELECT name, id, created_at, type, amount FROM donations ORDER BY created_at DESC"; // Adjust the table name if necessary
$result = $conn->query($sql);
?>

<table class="table">
    <thead>
        <tr>
            <th>NAME</th>
            <th>DATE</th>
            <th>TYPE</th>
            <th>AMOUNT</th>
            <th>Actions</th> 
        </tr>
    </thead>
    <tbody>
        <?php 
        // Include database connection
        include('../../db.php');

        // Set the number of results per page
        $results_per_page = 2;

        // Get the current page number
        $page = isset($_GET['page']) ? $_GET['page'] : 1;
        $start_from = ($page - 1) * $results_per_page;

        // Get the search term
        $search = isset($_GET['search']) ? $_GET['search'] : '';

        // Query to get total count of records based on the search term
        $sql_count = "SELECT COUNT(*) FROM donations WHERE name LIKE ?";
        if ($stmt = $conn->prepare($sql_count)) {
            $search_term = "%" . $search . "%";
            $stmt->bind_param("s", $search_term);
            $stmt->execute();
            $stmt->bind_result($total_records);
            $stmt->fetch();
            $stmt->close();
        }

        // Query to get records for the current page based on search term
        $sql = "SELECT * FROM donations WHERE name LIKE ? LIMIT ?, ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("sii", $search_term, $start_from, $results_per_page);
            $stmt->execute();
            $result = $stmt->get_result();

            // Loop through the result set and display each row
            while($row = $result->fetch_assoc()) {
                $formatted_date = date("F j, Y", strtotime($row['created_at']));
                echo "<tr id='donation-{$row['id']}'>";
                echo "<td>" . $row['name'] . "</td>";
                echo "<td>" . $formatted_date . "</td>";
                echo "<td>" . $row['type'] . "</td>";
                echo "<td>₱" . $row['amount'] . "</td>";
            echo "<td><button class='btn btn-warning text-white edit-btn' data-bs-toggle='modal' data-bs-target='#editModal{$row['id']}'>
                        <i class='fas fa-edit'></i> Edit</button></td>";
            echo "</tr>";

            // Modal for each row
            echo "
            <div class='modal fade' id='editModal{$row['id']}' tabindex='-1' aria-labelledby='editModalLabel' aria-hidden='true'>
                <div class='modal-dialog'>
                    <div class='modal-content'>
                        <div class='modal-header'>
                            <h5 class='modal-title' id='editModalLabel'>Edit Donation</h5>
                            <button type='button' class='btn-close' data-bs-dismiss='modal' aria-label='Close'></button>
                        </div>
                        <div class='modal-body'>
                            <form id='editDonationForm{$row['id']}'>
                                <input type='hidden' id='donationId{$row['id']}' name='id' value='{$row['id']}'>
                                <div class='mb-3'>
                                    <label for='name{$row['id']}' class='form-label'>Name</label>
                                    <input type='text' class='form-control' id='name{$row['id']}' name='name' value='{$row['name']}' required>
                                </div>
                                <div class='mb-3'>
                                    <label for='type{$row['id']}' class='form-label'>Type</label>
                                    <select class='form-select' id='type{$row['id']}' name='type' required>
                                        <option value='tithes' " . ($row['type'] == 'tithes' ? 'selected' : '') . ">Tithes</option>
                                        <option value='offerings' " . ($row['type'] == 'offerings' ? 'selected' : '') . ">Offerings</option>
                                        <option value='love_gift' " . ($row['type'] == 'love_gift' ? 'selected' : '') . ">Love Gift</option>
                                        <option value='first_fruit' " . ($row['type'] == 'first_fruit' ? 'selected' : '') . ">First Fruit</option>
                                        <option value='sacrificial_giving' " . ($row['type'] == 'sacrificial_giving' ? 'selected' : '') . ">Sacrificial Giving</option>
                                    </select>
                                </div>
                                <div class='mb-3'>
                                    <label for='amount{$row['id']}' class='form-label'>Amount</label>
                                    <input type='number' step='0.01' class='form-control' id='amount{$row['id']}' name='amount' value='{$row['amount']}' required>
                                </div>
                                <button type='submit' class='btn btn-primary'>Save changes</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>";
        }
    } else {
        // If no data is found, display a message
        echo "<tr><td colspan='5' class='text-center'>No records found.</td></tr>";
    }
    ?>
</tbody>

<div id="successMessage" class="position-fixed bottom-0 end-0 mb-3 me-3 alert alert-success" style="display: none;">
    Updated.
</div>

<script>
    $(document).ready(function () {
        // Open Edit Modal and populate fields
        $('.edit-btn').click(function () {
            const id = $(this).data('id');
            const name = $(this).data('name');
            const type = $(this).data('type');
            const amount = $(this).data('amount');
            
            $('#donationId' + id).val(id);
            $('#name' + id).val(name);
            $('#type' + id).val(type);
            $('#amount' + id).val(amount);
            
            $('#editModal' + id).modal('show');
        });

        // Handle form submission using AJAX for each modal form
        $('form[id^="editDonationForm"]').submit(function (e) {
            e.preventDefault(); // Prevent form from submitting normally

            const formId = $(this).attr('id');
            const id = $('#donationId' + formId.replace('editDonationForm', '')).val();
            const formData = $(this).serialize(); // Serialize the form data

            $.ajax({
                url: '../function/php/update_donation.php', // PHP script to update the data
                type: 'POST',
                data: formData,
                success: function (response) {
                    const result = JSON.parse(response);
                    if (result.status === 'success') {
                        // Close the modal
                        $('#editModal' + id).modal('hide');
                        
                        // Update the table row with new data
                        $(`#donation-${id} td:nth-child(1)`).text(result.data.name);
                        $(`#donation-${id} td:nth-child(3)`).text(result.data.type);
                        $(`#donation-${id} td:nth-child(4)`).text('₱' + result.data.amount);

                        // Show success message
                        $('#successMessage').fadeIn().delay(3000).fadeOut();
                    } else {
                        alert(result.message);
                    }
                },
                error: function () {
                    alert('An error occurred while updating.');
                }
            });
        });
    });
</script>


    </tbody>
</table>

<?php
// Pagination logic
$total_pages = ceil($total_records / $results_per_page);
?>
<div class="d-flex justify-content-end">
    <div class="pagination">
        <?php if($page > 1): ?>
            <a href="?page=1&search=<?php echo $search; ?>" class="page-item page-link">First</a>
            <a href="?page=<?php echo $page - 1; ?>&search=<?php echo $search; ?>" class="page-item page-link">Previous</a>
        <?php endif; ?>

        <?php for($i = 1; $i <= $total_pages; $i++): ?>
            <a href="?page=<?php echo $i; ?>&search=<?php echo $search; ?>" class="page-item page-link <?php echo $i == $page ? 'active' : ''; ?>"><?php echo $i; ?></a>
        <?php endfor; ?>
    </div>  
</div>

<?php
// Close the database connection
$conn->close();
?>


</div>
</body>

<div id="successMessage" class="position-fixed bottom-0 end-0 mb-3 me-3 alert alert-success" style="display: none;">
        Offering added.
    </div>

    <script>
    // AJAX form submission for expenses
    $(document).ready(function () {
        $('#expenseForm').on('submit', function (e) {
            e.preventDefault(); // Prevent form from submitting the traditional way

            // Get form data
            var formData = $(this).serialize(); // Serializes form data into a query string format

            // Perform AJAX request
            $.ajax({
                url: '../function/php/save_expense.php',  // URL of your PHP script to process the form (update if necessary)
                type: 'POST',
                data: formData,
                success: function (response) {
                    // On success, show the success message
                    $('#successMessage').fadeIn().delay(3000).fadeOut();
                    $('#expenseForm')[0].reset();  // Optionally reset the form fields after submission
                },
                error: function (xhr, status, error) {
                    // Handle error (optional)
                    alert("An error occurred: " + error);
                }
            });
        });
    });
</script>


       
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>




</html>