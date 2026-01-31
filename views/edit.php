<?php
$conn = new mysqli("localhost", "root", "", "patenio_form");
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id <= 0) { die("Invalid ID provided."); }

$res = $conn->query("SELECT * FROM tbl_record WHERE id = $id");
$member = $res->fetch_assoc();
if (!$member) { die("Record not found."); }

$dep_res = $conn->query("SELECT * FROM tbl_dependents WHERE record_id = $id");
$dependents = [];
while($d = $dep_res->fetch_assoc()) { $dependents[] = $d; }

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
    <title>Edit Record - <?php echo $formatted_ss; ?></title>
    <link rel="stylesheet" href="../public/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <header class="form-header" style="display: flex; justify-content: space-between; align-items: center;">
            <div class="header-left" style="display: flex; align-items: center; gap: 15px;">
                <i class="fas fa-edit logo-maroon" style="font-size: 40px; color: maroon;"></i>
                <div>
                    <h1 class="brand-title">EDIT SSS RECORD</h1>
                    <p class="small-text">Updating information for SS# <?php echo $formatted_ss; ?></p>
                </div>
            </div>
        </header>

        <form id="sssForm">
            <input type="hidden" name="record_id" id="record_id" value="<?php echo $id; ?>">

            <section class="section">
                <div class="maroon-bar">PART 1 - PERSONAL DETAILS</div>
                <div class="row">
                    <div class="field quarter"><label>LAST NAME</label><input type="text" name="last_name" id="last_name" value="<?php echo $member['last_name']; ?>"></div>
                    <div class="field quarter"><label>FIRST NAME</label><input type="text" name="first_name" id="first_name" value="<?php echo $member['first_name']; ?>"></div>
                    <div class="field quarter"><label>MIDDLE NAME</label><input type="text" name="middle_name" id="middle_name" value="<?php echo $member['middle_name']; ?>"></div>
                    <div class="field quarter"><label>SUFFIX</label><input type="text" name="suffix" id="suffix" value="<?php echo $member['suffix']; ?>"></div>
                </div>

                <div class="row">
                    <div class="field">
                        <label>GENDER</label>
                        <div class="checkbox-options">
                            <label><input type="radio" name="gender" value="Male" <?php echo ($member['gender'] == 'Male') ? 'checked' : ''; ?>> Male</label>
                            <label><input type="radio" name="gender" value="Female" <?php echo ($member['gender'] == 'Female') ? 'checked' : ''; ?>> Female</label>
                        </div>
                    </div>
                    <div class="field">
                        <label>MARITAL STATUS</label>
                        <div class="checkbox-options">
                            <?php 
                            $statuses = ['Single', 'Married', 'Widowed', 'Divorced'];
                            foreach($statuses as $status) {
                                $checked = ($member['marital_status'] == $status) ? 'checked' : '';
                                echo "<label><input type='radio' name='marital_status' value='$status' $checked> $status</label>";
                            }
                            ?>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="field quarter"><label>DATE OF BIRTH</label><input type="date" name="birthdate" id="birthdate" value="<?php echo $member['birthdate']; ?>"></div>
                    <div class="field quarter"><label>NATIONALITY</label><input type="text" name="nationality" id="nationality" value="<?php echo $member['nationality']; ?>"></div>
                    <div class="field half">
                        <label>PLACE OF BIRTH</label>
                        <input type="text" name="birthplace" id="birthplace" value="<?php echo $member['birthplace']; ?>">
                        <label style="font-size: 10px; margin-top: 5px; color: #666;">
                            <input type="checkbox" id="same_as_home_address"> Same as Home Address
                        </label>
                    </div>
                </div>

                <div class="row">
                    <div class="field full"><label>HOME ADDRESS</label><input type="text" name="home_address" id="home_address" value="<?php echo $member['home_address']; ?>"></div>
                </div>

                <div class="row">
                    <div class="field"><label>MOBILE NUMBER</label><input type="text" name="phone_number" id="phone_number" value="<?php echo $member['phone_number']; ?>"></div>
                    <div class="field"><label>EMAIL ADDRESS</label><input type="email" name="email" id="email" value="<?php echo $member['email']; ?>"></div>
                </div>

                <section class="section">
    <div class="maroon-bar">PARENTS' INFORMATION</div>
    <div class="row">
        <div class="field quarter">
            <label>FATHER'S LAST NAME</label>
            <input type="text" name="father_last_name" value="<?php echo $member['father_last_name'] ?? ''; ?>">
        </div>
        <div class="field quarter">
            <label>FIRST NAME</label>
            <input type="text" name="father_first_name" value="<?php echo $member['father_first_name'] ?? ''; ?>">
        </div>
        <div class="field quarter">
            <label>MIDDLE NAME</label>
            <input type="text" name="father_middle_name" value="<?php echo $member['father_middle_name'] ?? ''; ?>">
        </div>
        <div class="field quarter">
            <label>SUFFIX</label>
            <input type="text" name="father_suffix" value="<?php echo $member['father_suffix'] ?? ''; ?>">
        </div>
    </div>
    <div class="row">
        <div class="field quarter">
            <label>MOTHER'S MAIDEN LAST NAME</label>
            <input type="text" name="mother_last_name" value="<?php echo $member['mother_last_name'] ?? ''; ?>">
        </div>
        <div class="field quarter">
            <label>FIRST NAME</label>
            <input type="text" name="mother_first_name" value="<?php echo $member['mother_first_name'] ?? ''; ?>">
        </div>
        <div class="field quarter">
            <label>MIDDLE NAME</label>
            <input type="text" name="mother_middle_name" value="<?php echo $member['mother_middle_name'] ?? ''; ?>">
        </div>
        <div class="field quarter">
            <label>SUFFIX</label>
            <input type="text" name="mother_suffix" value="<?php echo $member['mother_suffix'] ?? ''; ?>">
        </div>
    </div>
</section>
            </section>

            <section class="section">
                <div class="maroon-bar">PART 2 - DEPENDENTS / BENEFICIARIES</div>
                <table class="grid-table" style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background: #f4f4f4;"><th>NAME</th><th>RELATIONSHIP</th><th>DATE OF BIRTH</th></tr>
                    </thead>
                    <tbody>
                        <?php for($i=0; $i<2; $i++): ?>
                        <tr>
                            <td><input type="text" name="dep_name[]" style="width:100%" value="<?php echo $dependents[$i]['dep_name'] ?? ''; ?>"></td>
                            <td><input type="text" name="dep_rel[]" style="width:100%" value="<?php echo $dependents[$i]['relationship'] ?? ''; ?>"></td>
                            <td><input type="date" name="dep_dob[]" style="width:100%" value="<?php echo $dependents[$i]['date_of_birth'] ?? ''; ?>"></td>
                        </tr>
                        <?php endfor; ?>
                    </tbody>
                </table>
            </section>

            <section class="section">
                <div class="maroon-bar">PART 3 - EMPLOYMENT DATA</div>
                <div class="row">
                    <div class="field quarter"><label>PROFESSION</label><input type="text" name="profession" value="<?php echo $emp['profession'] ?? ''; ?>"></div>
                    <div class="field quarter"><label>YEAR STARTED</label><input type="text" name="year_started" value="<?php echo $emp['year_started'] ?? ''; ?>"></div>
                    <div class="field quarter"><label>MONTHLY EARNINGS</label><input type="number" name="monthly_earnings" value="<?php echo $emp['se_monthly_earnings'] ?? ''; ?>"></div>
                </div>
            </section>

            <section class="section">
                <div class="maroon-bar">PART 4 - CERTIFICATION</div>
                <div class="row">
                    <div class="field half"><label>PRINTED NAME</label><input type="text" name="printed_name" value="<?php echo $cert['printed_name'] ?? ''; ?>"></div>
                    <div class="field quarter"><label>DATE SIGNED</label><input type="date" name="cert_date" value="<?php echo $cert['cert_date'] ?? ''; ?>"></div>
                    <input type="hidden" name="signature" value="SIGNED_ELECTRONICALLY">
                </div>
            </section>

            <div class="validation-section" style="margin-top: 30px; padding-top: 20px; border-top: 2px solid #eee;">
                <div id="formStatus" class="status-message"></div>
                <div style="display: flex; justify-content: center; gap: 20px;">
                    <button type="submit" id="submitBtn" class="btn btn-submit" style="padding: 15px 40px; font-size: 16px; background: maroon; color: white; border: none; cursor: pointer;">
                        <i class="fas fa-save"></i> SAVE ALL CHANGES
                    </button>
                </div>
            </div>
        </form>
    </div>
    <script src="../public/js/script.js"></script>
</body>
</html>