document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('sssForm');
    const submitBtn = document.getElementById('submitBtn');
    const sameAddressCheckbox = document.getElementById('same_as_home_address');
    const birthplaceInput = document.getElementById('birthplace');
    const homeAddressInput = document.getElementById('home_address');
    const formStatus = document.getElementById('formStatus');
    
    const errorMessages = {};
    
    ['last_name', 'first_name', 'birthdate', 'nationality', 'home_address', 'birthplace', 'phone_number', 'email'].forEach(fieldId => {
        const input = document.getElementById(fieldId);
        if (input) {
            const errorDiv = document.createElement('div');
            errorDiv.className = 'error-message';
            errorDiv.id = `${fieldId}_error`;
            input.parentNode.appendChild(errorDiv);
            errorMessages[fieldId] = errorDiv;
        }
    });
    
    const genderField = document.querySelector('.field:has(input[name="gender"])');
    if (genderField) {
        const errorDiv = document.createElement('div');
        errorDiv.className = 'error-message gender-error';
        errorDiv.id = 'gender_error';
        genderField.appendChild(errorDiv);
        errorMessages['gender'] = errorDiv;
    }
    
    const maritalStatusField = document.querySelector('.field:has(input[name="marital_status"])');
    if (maritalStatusField) {
        const errorDiv = document.createElement('div');
        errorDiv.className = 'error-message status-error';
        errorDiv.id = 'marital_status_error';
        maritalStatusField.appendChild(errorDiv);
        errorMessages['marital_status'] = errorDiv;
    }
    
    // Function to apply uppercase to any input or textarea
    function applyUppercaseToField(field) {
        if (!field) return;
        
        field.addEventListener('input', function() {
            // Save cursor position for inputs and textareas
            if (this.tagName === 'INPUT' || this.tagName === 'TEXTAREA') {
                const start = this.selectionStart;
                const end = this.selectionEnd;
                
                // Convert to uppercase
                this.value = this.value.toUpperCase();
                
                // Restore cursor position
                this.setSelectionRange(start, end);
            }
        });
        
        field.addEventListener('blur', function() {
            this.value = this.value.toUpperCase();
        });
    }
    
    // Apply uppercase to basic fields
    const uppercaseFields = [
        'last_name', 'first_name', 'middle_name', 'suffix', 
        'nationality', 'birthplace'
    ];
    
    uppercaseFields.forEach(fieldId => {
        const field = document.getElementById(fieldId);
        applyUppercaseToField(field);
    });
    
    // Apply uppercase to home address (textarea)
    if (homeAddressInput) {
        applyUppercaseToField(homeAddressInput);
    }
    
    // Apply uppercase to dependent fields
    function applyUppercaseToDependentFields() {
        // Dependent name fields
        document.querySelectorAll('input[name="dependent_name[]"]').forEach(field => {
            applyUppercaseToField(field);
        });
        
        // Dependent relationship fields
        document.querySelectorAll('input[name="dependent_relationship[]"]').forEach(field => {
            applyUppercaseToField(field);
        });
    }
    
    // Apply uppercase to employment section fields
    function applyUppercaseToEmploymentFields() {
        // Profession/Business field
        const professionField = document.querySelector('input[name="profession"]');
        applyUppercaseToField(professionField);
        
        // Foreign Address field
        const foreignAddressField = document.querySelector('input[name="foreign_address"]');
        applyUppercaseToField(foreignAddressField);
        
        // Monthly Earnings (SE) - only the currency amount, not the ₱ symbol
        const monthlyEarningsField = document.querySelector('input[name="monthly_earnings"]');
        if (monthlyEarningsField) {
            // Don't apply uppercase to currency fields as they should be numbers
            // This field should remain as numbers only
        }
        
        // OFW Monthly Earnings - only the currency amount, not the ₱ symbol
        const ofwMonthlyEarningsField = document.querySelector('input[name="ofw_monthly_earnings"]');
        if (ofwMonthlyEarningsField) {
            // Don't apply uppercase to currency fields as they should be numbers
            // This field should remain as numbers only
        }
        
        // Spouse Income - only the currency amount, not the ₱ symbol
        const spouseIncomeField = document.querySelector('input[name="spouse_income"]');
        if (spouseIncomeField) {
            // Don't apply uppercase to currency fields as they should be numbers
            // This field should remain as numbers only
        }
        
        // Year Started field (should be numbers only)
        const yearStartedField = document.querySelector('input[name="year_started"]');
        if (yearStartedField) {
            // Don't apply uppercase as this should be numbers
        }
        
        // SS Number of Working Spouse
        const spouseSSNumberField = document.querySelector('input[name="spouse_ss_number"]');
        applyUppercaseToField(spouseSSNumberField);
    }
    
    // Apply uppercase to certification fields
    function applyUppercaseToCertificationFields() {
        // Printed Name field
        const printedNameField = document.querySelector('input[name="printed_name"]');
        applyUppercaseToField(printedNameField);
        
        // Signature field
        const signatureField = document.querySelector('input[name="signature"]');
        applyUppercaseToField(signatureField);
    }
    
    // Initialize all uppercase conversions
    applyUppercaseToDependentFields();
    applyUppercaseToEmploymentFields();
    applyUppercaseToCertificationFields();
    
    sameAddressCheckbox.addEventListener('change', function() {
        if (this.checked) {
            birthplaceInput.value = homeAddressInput.value.toUpperCase();
            birthplaceInput.disabled = true;
            birthplaceInput.style.backgroundColor = '#f5f5f5';
            birthplaceInput.style.cursor = 'not-allowed';
            clearError('birthplace');
        } else {
            birthplaceInput.disabled = false;
            birthplaceInput.style.backgroundColor = '';
            birthplaceInput.style.cursor = '';
        }
    });
    
    // Update birthplace when home address changes
    homeAddressInput.addEventListener('input', function() {
        if (sameAddressCheckbox.checked) {
            birthplaceInput.value = this.value.toUpperCase();
        }
    });
    
    function validateForm() {
        let isValid = true;
        clearAllErrors();
        
        const requiredFields = [
            { id: 'last_name', name: 'Last Name' },
            { id: 'first_name', name: 'First Name' },
            { id: 'birthdate', name: 'Date of Birth' },
            { id: 'nationality', name: 'Nationality' },
            { id: 'home_address', name: 'Home Address' },
            { id: 'phone_number', name: 'Mobile Number' },
            { id: 'email', name: 'Email Address' }
        ];
        
        requiredFields.forEach(field => {
            const element = document.getElementById(field.id);
            if (!element || !element.value.trim()) {
                showError(field.id, `${field.name} is required`);
                isValid = false;
            }
        });
        
        if (!sameAddressCheckbox.checked && !birthplaceInput.value.trim()) {
            showError('birthplace', 'Place of Birth is required');
            isValid = false;
        }
        
        const email = document.getElementById('email').value;
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        if (email && !emailRegex.test(email)) {
            showError('email', 'Please enter a valid email address');
            isValid = false;
        }
        
        const phone = document.getElementById('phone_number').value;
        const phoneRegex = /^(09|\+639)\d{9}$/;
        if (phone && !phoneRegex.test(phone.replace(/\D/g, ''))) {
            showError('phone_number', 'Please enter a valid Philippine mobile number (09XXXXXXXXX or +639XXXXXXXXX)');
            isValid = false;
        }
        
        const genderSelected = document.querySelector('input[name="gender"]:checked');
        if (!genderSelected) {
            showError('gender', 'Please select your gender');
            isValid = false;
        }
        
        const maritalStatusSelected = document.querySelector('input[name="marital_status"]:checked');
        if (!maritalStatusSelected) {
            showError('marital_status', 'Please select your marital status');
            isValid = false;
        }
        
        const birthdate = document.getElementById('birthdate').value;
        if (birthdate) {
            const birthDate = new Date(birthdate);
            const today = new Date();
            if (birthDate >= today) {
                showError('birthdate', 'Date of Birth must be in the past');
                isValid = false;
            }
        }
        
        return isValid;
    }
    
    function showError(fieldId, message) {
        const errorElement = errorMessages[fieldId];
        const inputElement = document.getElementById(fieldId);
        
        if (errorElement) {
            errorElement.textContent = message;
            errorElement.classList.add('show');
        }
        
        if (inputElement) {
            inputElement.classList.add('error-field');
        }
    }
    
    function clearError(fieldId) {
        const errorElement = errorMessages[fieldId];
        const inputElement = document.getElementById(fieldId);
        
        if (errorElement) {
            errorElement.textContent = '';
            errorElement.classList.remove('show');
        }
        
        if (inputElement) {
            inputElement.classList.remove('error-field');
        }
    }
    
    function clearAllErrors() {
        Object.keys(errorMessages).forEach(fieldId => {
            clearError(fieldId);
        });
    }
    
    const realTimeFields = ['last_name', 'first_name', 'nationality', 'home_address', 'birthplace', 'phone_number', 'email'];
    
    realTimeFields.forEach(fieldId => {
        const field = document.getElementById(fieldId);
        if (field) {
            field.addEventListener('blur', function() {
                if (this.value.trim()) {
                    clearError(fieldId);
                }
            });
        }
    });
    
    document.getElementById('email').addEventListener('blur', function() {
        const email = this.value.trim();
        if (email) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                showError('email', 'Please enter a valid email address');
            } else {
                clearError('email');
            }
        }
    });
    
    document.getElementById('phone_number').addEventListener('blur', function() {
        const phone = this.value.trim();
        if (phone) {
            const phoneRegex = /^(09|\+639)\d{9}$/;
            if (!phoneRegex.test(phone.replace(/\D/g, ''))) {
                showError('phone_number', 'Please enter a valid Philippine mobile number (09XXXXXXXXX or +639XXXXXXXXX)');
            } else {
                clearError('phone_number');
            }
        }
    });
    
    document.getElementById('birthdate').addEventListener('change', function() {
        if (this.value) {
            const birthDate = new Date(this.value);
            const today = new Date();
            if (birthDate >= today) {
                showError('birthdate', 'Date of Birth must be in the past');
            } else {
                clearError('birthdate');
            }
        }
    });
    
    document.querySelectorAll('input[name="gender"]').forEach(radio => {
        radio.addEventListener('change', function() {
            clearError('gender');
        });
    });
    
    document.querySelectorAll('input[name="marital_status"]').forEach(radio => {
        radio.addEventListener('change', function() {
            clearError('marital_status');
        });
    });
    
    form.addEventListener('submit', function(event) {
        event.preventDefault();
        
        const isValid = validateForm();
        
        if (isValid) {
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Submitting...';
            submitBtn.disabled = true;
            
            const formData = new FormData(form);
            
            fetch('process-form.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    formStatus.textContent = '✓ Form submitted successfully! Data has been saved to the database.';
                    formStatus.className = 'status-message success';
                    
                    setTimeout(() => {
                        form.reset();
                        submitBtn.disabled = false;
                        formStatus.textContent = '';
                        formStatus.className = '';
                        if (sameAddressCheckbox.checked) {
                            birthplaceInput.disabled = false;
                            birthplaceInput.style.backgroundColor = '';
                            birthplaceInput.style.cursor = '';
                        }
                    }, 3000);
                } else {
                    formStatus.textContent = '✗ ' + (data.errors && data.errors.length > 0 ? data.errors[0] : 'Submission failed. Please try again.');
                    formStatus.className = 'status-message error';
                }
            })
            .catch(error => {
                formStatus.textContent = '✗ Network error. Please check your connection.';
                formStatus.className = 'status-message error';
            })
            .finally(() => {
                submitBtn.innerHTML = '<i class="fas fa-paper-plane"></i> Submit Form';
                submitBtn.disabled = false;
                formStatus.scrollIntoView({ behavior: 'smooth' });
            });
        } else {
            formStatus.textContent = '✗ Please fix the errors in the form before submitting.';
            formStatus.className = 'status-message error';
            submitBtn.disabled = false;
            formStatus.scrollIntoView({ behavior: 'smooth' });
        }
    });

    submitBtn.disabled = false;
});