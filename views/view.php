<?php
// Database Connection
$conn = new mysqli("localhost", "root", "", "patenio_form");
if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$res = $conn->query("SELECT * FROM tbl_record WHERE id = $id");
$member = $res->fetch_assoc();

if (!$member) { die("Record not found."); }

$dep_res = $conn->query("SELECT * FROM tbl_dependents WHERE record_id = $id");

$emp_res = $conn->query("SELECT * FROM tbl_employment_info WHERE record_id = $id");
$emp = $emp_res->fetch_assoc();

$cert_res = $conn->query("SELECT * FROM tbl_certification WHERE record_id = $id");
$cert = $cert_res->fetch_assoc();

$raw = str_pad($member['id'], 10, "0", STR_PAD_LEFT);
$formatted_ss = substr($raw, 0, 2) . "-" . substr($raw, 2, 7) . "-" . substr($raw, 9, 1);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Record - <?php echo $formatted_ss; ?></title>
    <link rel="stylesheet" href="../public/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .view-label { font-size: 10px; color: #666; text-transform: uppercase; margin-bottom: 2px; }
        .view-value { font-size: 14px; font-weight: bold; color: #000; min-height: 20px; border-bottom: 1px solid #eee; margin-bottom: 10px; padding-bottom: 2px; }
        @media print { .no-print { display: none; } }
    </style>
</head>
<body>
    <div class="container">
        <div class="no-print" style="margin-bottom: 20px; display: flex; gap: 10px;">
            <a href="../index.php" class="btn btn-reset" style="text-decoration:none;"><i class="fas fa-arrow-left"></i> Back to Records</a>
            <button onclick="window.print()" class="btn btn-submit" style="background:#333;"><i class="fas fa-print"></i> Save as PDF</button>
        </div>

        <header class="form-header">
            <div class="header-left">
                <i class="fas fa-shield-halved logo-maroon"></i>
                <div class="text-center">
                    <p class="small-text">Republic of the Philippines</p>
                    <h1 class="brand-title">SOCIAL SECURITY SYSTEM</h1>
                    <h2 class="form-name">PERSONAL RECORD SUMMARY</h2>
                </div>
            </div>
            <div class="ss-num-box">
                <div class="box-label">SS NUMBER</div>
                <div class="box-inner" style="text-align: center; font-weight: bold; font-size: 20px; color: maroon; line-height: 40px;">
                    <?php echo $formatted_ss; ?>
                </div>
            </div>
        </header>

        <section class="section">
            <div class="maroon-bar">PART 1 - PERSONAL DETAILS</div>
            <div class="row">
                <div class="field quarter"><p class="view-label">Last Name</p><div class="view-value"><?php echo $member['last_name']; ?></div></div>
                <div class="field quarter"><p class="view-label">First Name</p><div class="view-value"><?php echo $member['first_name']; ?></div></div>
                <div class="field quarter"><p class="view-label">Middle Name</p><div class="view-value"><?php echo $member['middle_name']; ?></div></div>
                <div class="field quarter"><p class="view-label">Suffix</p><div class="view-value"><?php echo $member['suffix'] ?: 'NONE'; ?></div></div>
            </div>
            <div class="row">
                <div class="field"><p class="view-label">Gender</p><div class="view-value"><?php echo $member['gender']; ?></div></div>
                <div class="field"><p class="view-label">Marital Status</p><div class="view-value"><?php echo $member['marital_status']; ?></div></div>
                <div class="field"><p class="view-label">Date of Birth</p><div class="view-value"><?php echo $member['birthdate']; ?></div></div>
                <div class="field"><p class="view-label">Nationality</p><div class="view-value"><?php echo $member['nationality']; ?></div></div>
            </div>
            <div class="row">
                <div class="field full"><p class="view-label">Home Address</p><div class="view-value"><?php echo $member['home_address']; ?></div></div>
            </div>
            <div class="row">
                <div class="field full"><p class="view-label">Place of Birth</p><div class="view-value"><?php echo $member['birthplace']; ?></div></div>
            </div>
            <div class="row">
                <div class="field"><p class="view-label">Mobile Number</p><div class="view-value"><?php echo $member['phone_number']; ?></div></div>
                <div class="field"><p class="view-label">Email Address</p><div class="view-value"><?php echo $member['email']; ?></div></div>
            </div>
        </section>

        <section class="section">
            <div class="maroon-bar">PARENTS' INFORMATION</div>
            <div class="row">
                <div class="field full">
                    <p class="view-label">Father's Full Name</p>
                    <div class="view-value"><?php echo "{$member['father_last_name']}, {$member['father_first_name']} {$member['father_middle_name']} {$member['father_suffix']}"; ?></div>
                </div>
            </div>
            <div class="row">
                <div class="field full">
                    <p class="view-label">Mother's Maiden Name</p>
                    <div class="view-value"><?php echo "{$member['mother_last_name']}, {$member['mother_first_name']} {$member['mother_middle_name']} {$member['mother_suffix']}"; ?></div>
                </div>
            </div>
        </section>

        <section class="section">
            <div class="maroon-bar">PART 2 - DEPENDENTS / BENEFICIARIES</div>
            <table class="grid-table">
                <thead>
                    <tr><th>NAME</th><th>RELATIONSHIP</th><th>DATE OF BIRTH</th></tr>
                </thead>
                <tbody>
                    <?php while($dep = $dep_res->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $dep['dep_name']; ?></td>
                            <td><?php echo $dep['relationship']; ?></td>
                            <td><?php echo $dep['date_of_birth']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </section>

        <section class="section">
            <div class="maroon-bar">C. EMPLOYMENT / OTHER INFO</div>
            <div class="row">
                <div class="field"><p class="view-label">Profession (SE)</p><div class="view-value"><?php echo $emp['profession'] ?: 'N/A'; ?></div></div>
                <div class="field"><p class="view-label">Monthly Earnings</p><div class="view-value">₱ <?php echo number_format($emp['se_monthly_earnings'] ?? 0, 2); ?></div></div>
            </div>
            <div class="row">
                <div class="field full"><p class="view-label">Foreign Address (OFW)</p><div class="view-value"><?php echo $emp['foreign_address'] ?: 'N/A'; ?></div></div>
            </div>
            <div class="row">
                <div class="field"><p class="view-label">Spouse SS Number</p><div class="view-value"><?php echo $emp['spouse_ss_number'] ?: 'N/A'; ?></div></div>
                <div class="field"><p class="view-label">Spouse Monthly Income</p><div class="view-value">₱ <?php echo number_format($emp['spouse_income'] ?? 0, 2); ?></div></div>
            </div>
        </section>

    </div>
</body>
</html>