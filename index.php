<?php
$conn = new mysqli("localhost", "root", "", "patenio_form");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT id, last_name, first_name, middle_name FROM tbl_record ORDER BY id DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SSS Member Records</title>
    <link rel="stylesheet" href="public/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <header class="form-header">
            <div class="header-left">
                <i class="fas fa-shield-halved logo-maroon"></i>
                <div class="text-center">
                    <h1 class="brand-title">SSS MEMBER RECORDS</h1>
                    <p class="small-text">Registered Personal Records List</p>
                </div>
            </div>
            <a href="models/form.php" class="btn btn-submit" style="text-decoration: none;">
                <i class="fas fa-plus"></i> Add New Member
            </a>
        </header>

        <section class="section">
            <div class="maroon-bar">MEMBER LIST</div>
            <table class="grid-table" style="margin-bottom: 20px;">
                <thead>
                    <tr>
                        <th>SS NUMBER</th>
                        <th>FULL NAME</th>
                        <th style="width: 250px;">ACTIONS</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): 
                            // Format SS Number logic
                            $raw = str_pad($row['id'], 10, "0", STR_PAD_LEFT);
                            $formatted_ss = substr($raw, 0, 2) . "-" . substr($raw, 2, 7) . "-" . substr($raw, 9, 1);
                        ?>
                            <tr>
                                <td style="text-align: center; font-weight: bold;"><?php echo $formatted_ss; ?></td>
                                <td><?php echo strtoupper($row['last_name'] . ", " . $row['first_name'] . " " . $row['middle_name']); ?></td>
                                <td style="text-align: center;">
                                    <div class="button-group" style="margin: 0; gap: 5px;">
                                        <button onclick="window.location.href='views/view.php?id=<?php echo $row['id']; ?>'" class="btn-action btn-view">
                                        <i class="fas fa-eye"></i> View
                                    </button>
                                    <button onclick="window.location.href='views/edit.php?id=<?php echo $row['id']; ?>'" class="btn-action btn-edit">
                                        <i class="fas fa-edit"></i> Edit
                                    </button>
                                    <button onclick="if(confirm('Delete?')) window.location.href='views/delete.php?id=<?php echo $row['id']; ?>'" class="btn-action btn-delete">
                                        <i class="fas fa-trash"></i> Delete
                                        </button>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3" style="text-align: center; padding: 20px;">No records found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
    </div>
</body>
</html>