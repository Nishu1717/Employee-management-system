<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header("Location: login.php");
    exit;
}

// Include the database connection
require_once 'db_connection.php';

// Fetch tasks for the logged-in user
$userId = $_SESSION['employee_id'];
$query = "SELECT t.task_id, t.task_name, t.status, t.due_date, p.project_name
          FROM task t
          INNER JOIN project p ON t.project_id = p.project_id
          WHERE p.project_id IN (
              SELECT project_id
              FROM project_assignment
              WHERE employee_id = ?
          )";
$stmt = $conn->prepare($query);
$stmt->bind_param('i', $userId);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Tasks</title>
    <link rel="stylesheet" href="styles/manage_users.css">
    <link rel="stylesheet" href="styles/modal.css">
    <link rel="stylesheet" href="styles/sidebar.css">
</head>

<body>
    <div class="container">
        <!-- Sidebar -->
        <?php include 'user_sidebar.php'; ?>

        <!-- Main content -->
        <div class="main-content">
            <header>
                <h1>Your Tasks</h1>
                <div class="search-form">
                    <input type="text" id="searchBox" placeholder="Search tasks...">
                </div>
            </header>

            <div class="table-container">
                <table id="tasksTable">
                    <thead>
                        <tr>
                            <th>Task ID</th>
                            <th>Task Name</th>
                            <th>Status</th>
                            <th>Due Date</th>
                            <th>Project</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if ($result->num_rows > 0): ?>
                            <?php while ($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['task_id']); ?></td>
                                    <td><?php echo htmlspecialchars($row['task_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['status']); ?></td>
                                    <td><?php echo htmlspecialchars($row['due_date']); ?></td>
                                    <td><?php echo htmlspecialchars($row['project_name']); ?></td>
                                    <td>
                                        <div class="action-buttons">
                                            <button class="btn edit" onclick="openModal(<?php echo $row['task_id']; ?>, '<?php echo $row['task_name']; ?>', '<?php echo $row['status']; ?>', '<?php echo $row['due_date']; ?>')">Edit</button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center">No tasks found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div id="editTaskModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">Edit Task</div>
            <div class="modal-body">
                <form id="editTaskForm" method="POST" action="update_task.php">
                    <input type="hidden" id="taskId" name="task_id">
                    <label for="taskName">Task Name:</label>
                    <input type="text" id="taskName" name="task_name" required>

                    <label for="taskStatus">Status:</label>
                    <select id="taskStatus" name="status" required>
                        <option value="Completed">Completed</option>
                        <option value="In Progress">In Progress</option>
                        <option value="Pending">Pending</option>
                    </select>

                    <label for="dueDate">Due Date:</label>
                    <input type="date" id="dueDate" name="due_date" required>
                </form>
            </div>
            <div class="modal-footer">
                <button class="save-btn" type="submit" form="editTaskForm">Save</button>
                <button class="cancel-btn" type="button" onclick="closeModal()">Cancel</button>
            </div>
        </div>
    </div>

    <script>
        // Open modal and populate fields
        function openModal(taskId, taskName, status, dueDate) {
            document.getElementById('taskId').value = taskId;
            document.getElementById('taskName').value = taskName;
            document.getElementById('taskStatus').value = status;
            document.getElementById('dueDate').value = dueDate;
            document.getElementById('editTaskModal').style.display = 'flex';
        }

        // Close modal
        function closeModal() {
            document.getElementById('editTaskModal').style.display = 'none';
        }

        // Search functionality
        document.getElementById('searchBox').addEventListener('input', function () {
            const searchValue = this.value.toLowerCase();
            const rows = document.querySelectorAll('#tasksTable tbody tr');
            rows.forEach(row => {
                const taskName = row.children[1].textContent.toLowerCase();
                row.style.display = taskName.includes(searchValue) ? '' : 'none';
            });
        });

        // Close modal on page load
        document.addEventListener('DOMContentLoaded', closeModal);
    </script>
</body>

</html>
