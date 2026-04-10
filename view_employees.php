<?php
session_start();

// Check if the user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// Include database connection
require_once 'db_connection.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Employees</title>
    <link rel="stylesheet" href="styles/sidebar.css">
    <link rel="stylesheet" href="styles/manage_users.css">
    <link rel="stylesheet" href="styles/modal.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <?php include 'manager_sidebar.php'; ?>

        <!-- Main Content -->
        <div class="main-content">
            <header>
                <h1>Employee Directory</h1>
                <div class="search-form">
                    <input type="text" id="searchBox" placeholder="Search employees...">
                </div>
            </header>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Employee ID</th>
                            <th>Username</th>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Performance Score</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="employeeTableBody">
                        <!-- Employee rows will be dynamically inserted here -->
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="pagination" id="paginationLinks">
                <!-- Pagination links will be dynamically inserted here -->
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">Edit Performance Score</div>
            <div class="modal-body">
                <form id="editForm" method="POST" action="update_performance.php">
                    <input type="hidden" id="employeeId" name="employee_id">
                    <label for="performanceScore">New Performance Score:</label>
                    <input type="number" id="performanceScore" name="performance_score" min="0" max="100" required>
                </form>
            </div>
            <div class="modal-footer">
                <button class="save-btn" type="submit" form="editForm">Save</button>
                <button class="cancel-btn" onclick="closeModal()">Cancel</button>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            // Fetch and display employee data
            function fetchEmployees(search = '', page = 1) {
                $.ajax({
                    url: 'fetch_employees.php',
                    type: 'GET',
                    data: { search: search, page: page },
                    success: function (data) {
                        const response = JSON.parse(data);
                        $('#employeeTableBody').html(response.html);
                        $('#paginationLinks').html(response.pagination);
                    },
                    error: function () {
                        alert('Error fetching employee data.');
                    }
                });
            }

            // Initial fetch
            fetchEmployees();

            // Handle search input
            $('#searchBox').on('input', function () {
                const search = $(this).val();
                fetchEmployees(search);
            });

            // Handle pagination click
            $(document).on('click', '.pagination a', function (e) {
                e.preventDefault();
                const page = $(this).data('page');
                const search = $('#searchBox').val();
                fetchEmployees(search, page);
            });
        });

        // Ensure the modal is hidden by default
        document.addEventListener('DOMContentLoaded', function() {
            closeModal();
        });

        // Open modal and populate fields
        function openModal(employeeId, currentScore) {
            document.getElementById('employeeId').value = employeeId;
            document.getElementById('performanceScore').value = currentScore;
            document.getElementById('editModal').style.display = 'flex'; // Show modal
        }

        // Close the modal
        function closeModal() {
            document.getElementById('editModal').style.display = 'none'; // Hide modal
        }
    </script>
</body>
</html>
