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
    <link rel="stylesheet" href="design/style.css">
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

        <form id="sssForm" action="save.php" method="POST">
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

                <div class="row" id="birthplace_group">
                    <div class="field full">
                        <label>PLACE OF BIRTH *</label>
                        <input type="text" name="birthplace" id="birthplace" placeholder="City, Province, Country">
                        <div class="checkbox-option" style="margin-top: 5px;">
                            <label style="font-size: 11px; display: inline-flex; align-items: center; cursor: pointer;">
                                <input type="checkbox" name="same_as_home" id="same_as_home_address" style="margin-right: 4px; width: auto;" value="1">
                                The same with Home Address
                            </label>
                        </div>
                    </div>
                </div>

                <div class="row" id="home_address_container">
                    <div class="field full">
                        <label>HOME ADDRESS (House No., Street, Barangay, City, Province) *</label>
                        <input type="text" name="home_address" id="home_address" placeholder="House No., Street, Barangay, City, Province">
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
                    <div class="field half">
                        <label>FATHER'S FULL NAME</label>
                        <input type="text" name="father_name" id="father_name" placeholder="Last Name, First Name, Middle Name">
                    </div>
                    <div class="field half">
                        <label>MOTHER'S MAIDEN NAME</label>
                        <input type="text" name="mother_name" id="mother_name" placeholder="Last Name, First Name, Middle Name">
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

    <script>
        document.getElementById('same_as_home_address').addEventListener('change', function() {
            const homeAddressContainer = document.getElementById('home_address_container');
            if (this.checked) {
                homeAddressContainer.style.display = 'none';
            } else {
                homeAddressContainer.style.display = 'block';
            }
        });
    </script>
</body>
</html>