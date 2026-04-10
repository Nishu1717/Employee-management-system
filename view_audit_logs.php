<?php
session_start();

// Check if the user is logged in and is a manager or admin
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

if (!isset($_SESSION['role']) || ($_SESSION['role'] !== 'Manager' && $_SESSION['role'] !== 'Admin')) {
    header("Location: login.php");
    exit;
}

require_once 'db_connection.php'; // Include database connection

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Audit Logs</title>
    <link rel="stylesheet" href="styles/sidebar.css">
    <link rel="stylesheet" href="styles/manage_users.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
    <div class="container">
        <!-- Sidebar -->
        <?php include 'manager_sidebar.php'; ?>

        <!-- Main Content -->
        <div class="main-content">
            <header>
                <h1>Audit Logs</h1>
                <div class="search-form">
                    <input type="text" id="searchBox" placeholder="Search logs...">
                </div>
            </header>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Log ID</th>
                            <th>Action</th>
                            <th>Performed By</th>
                            <th>Timestamp</th>
                        </tr>
                    </thead>
                    <tbody id="auditLogsTableBody">
                        <!-- Audit logs rows will be dynamically inserted here -->
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div class="pagination" id="paginationLinks">
                <!-- Pagination links will be dynamically inserted here -->
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            // Fetch and display audit logs
            function fetchAuditLogs(search = '', page = 1) {
                $.ajax({
                    url: 'fetch_audit_logs.php',
                    type: 'GET',
                    data: { search: search, page: page },
                    success: function (data) {
                        const response = JSON.parse(data);
                        $('#auditLogsTableBody').html(response.html);
                        $('#paginationLinks').html(response.pagination);
                    },
                    error: function () {
                        alert('Error fetching audit logs.');
                    }
                });
            }

            // Initial fetch
            fetchAuditLogs();

            // Handle search input
            $('#searchBox').on('input', function () {
                const search = $(this).val();
                fetchAuditLogs(search);
            });

            // Handle pagination click
            $(document).on('click', '.pagination a', function (e) {
                e.preventDefault();
                const page = $(this).data('page');
                const search = $('#searchBox').val();
                fetchAuditLogs(search, page);
            });
        });
    </script>
</body>
</html>
