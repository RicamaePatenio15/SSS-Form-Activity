document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('sssForm');
    const submitBtn = document.getElementById('submitBtn');
    const sameAddressCheckbox = document.getElementById('same_as_home_address');
    const birthplaceInput = document.getElementById('birthplace');
    const homeAddressInput = document.getElementById('home_address');
    const formStatus = document.getElementById('formStatus');
    
    const errorMessages = {};
    
    // UPDATED: Included Parent Info IDs in the error div creation loop
    [
        'last_name', 'first_name', 'birthdate', 'nationality', 'home_address', 
        'birthplace', 'phone_number', 'email',
        'father_last_name', 'father_first_name', 'mother_last_name', 'mother_first_name'
    ].forEach(fieldId => {
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
            if (this.tagName === 'INPUT' || this.tagName === 'TEXTAREA') {
                const start = this.selectionStart;
                const end = this.selectionEnd;
                this.value = this.value.toUpperCase();
                this.setSelectionRange(start, end);
            }
        });
        
        field.addEventListener('blur', function() {
            this.value = this.value.toUpperCase();
        });
    }

    // Apply uppercase logic to text inputs
    document.querySelectorAll('input[type="text"]').forEach(applyUppercaseToField);
    
  sameAddressCheckbox.addEventListener('change', function() {
    if (this.checked) {
        birthplaceInput.value = homeAddressInput.value.toUpperCase();
        // Change 'disabled' to 'readOnly'
        birthplaceInput.readOnly = true; 
        birthplaceInput.style.backgroundColor = '#f5f5f5';
        birthplaceInput.style.cursor = 'not-allowed';
        clearError('birthplace');
    } else {
        // Change 'disabled' to 'readOnly'
        birthplaceInput.readOnly = false;
        birthplaceInput.style.backgroundColor = '';
        birthplaceInput.style.cursor = '';
    }
});
    
    homeAddressInput.addEventListener('input', function() {
        if (sameAddressCheckbox.checked) {
            birthplaceInput.value = this.value.toUpperCase();
        }
    });
    
    function validateForm() {
        let isValid = true;
        clearAllErrors();
        
        // UPDATED: Added Parents' information to the required list
        const requiredFields = [
            { id: 'last_name', name: 'Last Name' },
            { id: 'first_name', name: 'First Name' },
            { id: 'birthdate', name: 'Date of Birth' },
            { id: 'nationality', name: 'Nationality' },
            { id: 'home_address', name: 'Home Address' },
            { id: 'phone_number', name: 'Mobile Number' },
            { id: 'email', name: 'Email Address' },
            { id: 'father_last_name', name: "Father's Last Name" },
            { id: 'father_first_name', name: "Father's First Name" },
            { id: 'mother_last_name', name: "Mother's Last Name" },
            { id: 'mother_first_name', name: "Mother's First Name" }
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
            showError('phone_number', 'Please enter a valid Philippine mobile number');
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
                    formStatus.textContent = '✓ Record saved successfully!';
                    formStatus.className = 'status-message success';
                    
                    setTimeout(() => {
                        window.location.reload(); 
                    }, 2000);
                } else {
                    formStatus.textContent = '✗ ' + (data.message || 'Submission failed.');
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
        }
    });

    submitBtn.disabled = false;
});