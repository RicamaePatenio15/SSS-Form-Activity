<?php
$conn = new mysqli("localhost", "root", "", "patenio_form");

if ($conn->connect_error) {
    $next_ss_number = 1; 
} else {
    $result = $conn->query("SELECT MAX(id) AS max_id FROM tbl_record");
    $row = $result->fetch_assoc();

    $next_ss_number = ($row['max_id'] === null) ? 1 : $row['max_id'] + 1;
}

$raw = str_pad($next_ss_number, 10, "0", STR_PAD_LEFT);
$formatted_ss = substr($raw, 0, 2) . "-" . substr($raw, 2, 7) . "-" . substr($raw, 9, 1);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SS Number Issuance</title>
    <link rel="stylesheet" href="../public/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        input:focus, textarea:focus {
            outline: none;
            box-shadow: none;
        }
        
        input[name="last_name"]::placeholder,
        input[name="first_name"]::placeholder,
        input[name="middle_name"]::placeholder,
        input[name="suffix"]::placeholder {
            text-transform: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <header class="form-header">
            <div class="header-left">
                <i class="fas fa-shield-halved logo-maroon"></i>
                <div class="text-center">
                    <p class="small-text">Republic of the Philippines</p>
                    <h1 class="brand-title">SOCIAL SECURITY SYSTEM</h1>
                    <h2 class="form-name">PERSONAL RECORD</h2>
                    <p class="small-text">FOR ISSUANCE OF SS NUMBER</p>
                </div>
            </div>
            <div class="ss-num-box">
                <div class="box-label">SS NUMBER</div>
                <div class="box-inner" style="text-align: center; font-weight: bold; font-size: 20px; color: maroon; line-height: 40px;">
                    <?php echo $formatted_ss; ?>
                </div>
            </div>
        </header>
           
        <form id="sssForm">
            <input type="hidden" name="record_id" value="<?php echo isset($_GET['id']) ? $_GET['id'] : ''; ?>">
            <section class="section">
                <div class="maroon-bar">PART 1 - PERSONAL DETAILS</div>

                <div class="row">
                    <div class="field full">
                        <label>NAME</label>
                        <div class="row">
                            <div class="field quarter">
                                <label>LAST NAME</label>
                                <input type="text" name="last_name" id="last_name" placeholder="Last Name">
                            </div>
                            <div class="field quarter">
                                <label>FIRST NAME</label>
                                <input type="text" name="first_name" id="first_name" placeholder="First Name">
                            </div>
                            <div class="field quarter">
                                <label>MIDDLE NAME</label>
                                <input type="text" name="middle_name" id="middle_name" placeholder="Middle Name">
                            </div>
                            <div class="field quarter">
                                <label>SUFFIX</label>
                                <input type="text" name="suffix" id="suffix" placeholder="Suffix">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="field">
                        <label>GENDER *</label>
                        <div class="checkbox-options">
                            <label><input type="radio" name="gender" value="Male"> Male</label>
                            <label><input type="radio" name="gender" value="Female"> Female</label>
                        </div>
                    </div>
                    
                    <div class="field">
                        <label>MARITAL STATUS *</label>
                        <div class="checkbox-options">
                            <label><input type="radio" name="marital_status" value="Single"> Single</label>
                            <label><input type="radio" name="marital_status" value="Married"> Married</label>
                            <label><input type="radio" name="marital_status" value="Widowed"> Widowed</label>
                            <label><input type="radio" name="marital_status" value="Divorced"> Divorced</label>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="field">
                        <label>DATE OF BIRTH (MM/DD/YYYY) *</label>
                        <input type="date" name="birthdate" id="birthdate">
                    </div>
                    <div class="field">
                        <label>NATIONALITY *</label>
                        <input type="text" name="nationality" id="nationality" placeholder="e.g., Filipino">
                    </div>
                </div>

                <div class="row">
                    <div class="field full">
                        <label>HOME ADDRESS (House No., Street, Barangay, City, Province) *</label>
                        <input type="text" name="home_address" id="home_address" placeholder="House No., Street, Barangay, City, Province">
                    </div>
                </div>
                
                <div class="row" id="birthplace_group">
                    <div class="field full">
                        <label>PLACE OF BIRTH *</label>
                        <input type="text" name="birthplace" id="birthplace" placeholder="City, Province, Country">
                        <div class="checkbox-option" style="margin-top: 5px;">
                            <label style="font-size: 8px; display: inline-flex; align-items: center; cursor: pointer;">
                                <input type="checkbox" id="same_as_home_address" style="margin-right: 4px; width: auto;">
                                The same with Home Address
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="field">
                        <label>MOBILE NUMBER *</label>
                        <input type="text" name="phone_number" id="phone_number" placeholder="09XXXXXXXXX">
                    </div>
                    <div class="field">
                        <label>EMAIL ADDRESS *</label>
                        <input type="email" name="email" id="email" placeholder="your.email@example.com">
                    </div>
                </div>
            </section>

            <section class="section">
                <div class="maroon-bar">PARENTS' INFORMATION</div>
                <div class="row">
                    <div class="field full">
                        <label>FATHER'S NAME</label>
                        <div class="row">
                            <div class="field quarter">
                                <label>LAST NAME</label>
                                <input type="text" name="father_last_name" id="father_last_name" placeholder="Last Name">
                            </div>
                            <div class="field quarter">
                                <label>FIRST NAME</label>
                                <input type="text" name="father_first_name" id="father_first_name" placeholder="First Name">
                            </div>
                            <div class="field quarter">
                                <label>MIDDLE NAME</label>
                                <input type="text" name="father_middle_name" placeholder="Middle Name">
                            </div>
                            <div class="field quarter">
                                <label>SUFFIX</label>
                                <input type="text" name="father_suffix" placeholder="Suffix">
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="row">
                    <div class="field full">
                        <label>MOTHER'S MAIDEN NAME</label>
                        <div class="row">
                            <div class="field quarter">
                                <label>LAST NAME</label>
                                <input type="text" name="mother_last_name" id="mother_last_name" placeholder="Last Name">
                            </div>
                            <div class="field quarter">
                                <label>FIRST NAME</label>
                                <input type="text" name="mother_first_name" id="mother_first_name" placeholder="First Name">
                            </div>
                            <div class="field quarter">
                                <label>MIDDLE NAME</label>
                                <input type="text" name="mother_middle_name" placeholder="Middle Name">
                            </div>
                            <div class="field quarter">
                                <label>SUFFIX</label>
                                <input type="text" name="mother_suffix" placeholder="Suffix">
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="section">
                <div class="maroon-bar">PART 2 - DEPENDENTS / BENEFICIARIES</div>
                <table class="grid-table">
                    <thead>
                        <tr>
                            <th>NAME (LAST, FIRST, MIDDLE, SUFFIX)</th>
                            <th>RELATIONSHIP</th>
                            <th>DATE OF BIRTH</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr><td><input type="text" name="dep_name[]"></td><td><input type="text" name="dep_rel[]"></td><td><input type="date" name="dep_dob[]" class="small-input"></td></tr>
                        <tr><td><input type="text" name="dep_name[]"></td><td><input type="text" name="dep_rel[]"></td><td><input type="date" name="dep_dob[]" class="small-input"></td></tr>
                    </tbody>
                </table>
            </section>

            <section class="section">
                <div class="maroon-bar">C. FOR SELF-EMPLOYED/OVERSEAS FILIPINO WORKER/NON-WORKING SPOUSE</div>
                <div class="employment-grid">
                    <div class="col-se">
                        <div class="gray-header">SELF-EMPLOYED (SE)</div>
                        <div class="p-5">
                            <label>Profession/Business</label>
                            <input type="text" name="profession" class="underline">
                            <label>Year Started</label>
                            <input type="text" name="year_started" class="underline">
                            <label>Monthly Earnings</label>
                            <div class="currency">₱ <input type="text" name="monthly_earnings" class="underline"></div>
                        </div>
                    </div>
                    <div class="col-ofw">
                        <div class="gray-header">OVERSEAS FILIPINO WORKER (OFW)</div>
                        <div class="p-5">
                            <label>Foreign Address</label>
                            <input type="text" name="foreign_address" class="underline">
                            <label>Monthly Earnings</label>
                            <div class="currency">₱ <input type="text" name="ofw_monthly_earnings" class="underline"></div>
                        </div>
                    </div>
                    <div class="col-nws">
                        <div class="gray-header">NON-WORKING SPOUSE (NWS)</div>
                        <div class="p-5">
                            <label>SS No. of Working Spouse</label>
                            <input type="text" name="spouse_ss_number" class="spaced-digits" placeholder="__ - ________ - _">
                            <label>Monthly Income of Spouse</label>
                            <div class="currency">₱ <input type="text" name="spouse_income" class="underline"></div>
                        </div>
                    </div>
                </div>
            </section>

            <section class="section border-top">
                <div class="maroon-bar">D. CERTIFICATION</div>
                <div class="cert-container">
                    <div class="cert-text">
                        <p>I certify that the information provided in this form is true and correct.</p>
                        <div class="sig-block">
                            <div class="sig-input"><input type="text" name="printed_name"> <p>PRINTED NAME</p></div>
                            <div class="sig-input"><input type="text" name="signature"> <p>SIGNATURE</p></div>
                            <div class="sig-input"><input type="date" name="cert_date"> <p>DATE</p></div>
                        </div>
                    </div>
                    <div class="fingerprint-container">
                        <div class="f-box"><div class="ink"></div><p>RIGHT THUMB</p></div>
                        <div class="f-box"><div class="ink"></div><p>RIGHT INDEX</p></div>
                    </div>
                </div>
            </section>

            <div class="validation-section">
                <div id="formStatus" class="status-message"></div>
                <div class="button-group">
                    <button type="submit" id="submitBtn" class="btn btn-submit">
                        <i class="fas fa-paper-plane"></i> Submit Form
                    </button>
                </div>
                <p class="disclaimer-note">
                    <i class="fas fa-exclamation-triangle"></i> This is a practice form for educational purposes only.
                </p>
            </div>
        </form>
    </div>

    <script src="../public/js/script.js"></script>
</body>
</html>