    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('sssForm');
        const submitBtn = document.getElementById('submitBtn');
        const sameAddressCheckbox = document.getElementById('same_as_home_address');
        const birthplaceInput = document.getElementById('birthplace');
        const homeAddressInput = document.getElementById('home_address');
        const formStatus = document.getElementById('formStatus');
        
        const homeAddressContainer = homeAddressInput ? homeAddressInput.closest('.field') : null;

        function applyUppercase(e) {
            const start = e.target.selectionStart;
            const end = e.target.selectionEnd;
            e.target.value = e.target.value.toUpperCase();
            if (e.type === 'input') e.target.setSelectionRange(start, end);
        }

        document.querySelectorAll('input[type="text"], textarea').forEach(el => {
            el.addEventListener('input', applyUppercase);
            el.addEventListener('blur', applyUppercase);
        });

        if (sameAddressCheckbox) {
            sameAddressCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    if (homeAddressContainer) homeAddressContainer.style.display = 'none';
                    homeAddressInput.value = birthplaceInput.value;
                } else {
                    if (homeAddressContainer) homeAddressContainer.style.display = 'block';
                }
            });

            birthplaceInput.addEventListener('input', function() {
                if (sameAddressCheckbox.checked) {
                    homeAddressInput.value = this.value;
                }
            });
        }

        function validateForm() {
            let errors = [];
            const required = [
                { el: document.getElementById('last_name'), name: 'Last Name' },
                { el: document.getElementById('first_name'), name: 'First Name' },
                { el: document.getElementById('birthdate'), name: 'Date of Birth' },
                { el: document.querySelector('input[name="gender"]:checked'), name: 'Sex', isRadio: true },
                { el: document.querySelector('input[name="marital_status"]:checked'), name: 'Civil Status', isRadio: true },
                { el: document.getElementById('nationality'), name: 'Nationality' },
                { el: document.getElementById('birthplace'), name: 'Place of Birth' },
                { el: document.getElementById('phone_number'), name: 'Mobile Number' },
                { el: document.getElementById('email'), name: 'Email Address' }
            ];

            if (!sameAddressCheckbox.checked) {
                required.push({ el: homeAddressInput, name: 'Home Address' });
            }

            required.forEach(field => {
                if (!field.el || (field.isRadio ? false : !field.el.value.trim())) {
                    errors.push(`${field.name} is required.`);
                }
            });

            if (errors.length > 0) {
                alert("Please fill in the missing fields:\n- " + errors.join("\n- "));
                return false;
            }
            return true;
        }

        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();

                if (!validateForm()) return;

                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> SAVING...';

                const formData = new FormData(form);
                
                if (sameAddressCheckbox.checked) {
                    formData.set('home_address', birthplaceInput.value);
                }

                fetch('../models/process-form.php', {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                   if (data.success) {
    formStatus.style.display = 'block';
    formStatus.innerHTML = `
        <div style="background:#d4edda; color:#155724; padding:15px; border-radius:5px; text-align:center; border:1px solid #c3e6cb; margin-bottom:20px;">
            <strong>✓ Success!</strong> Record saved.
            <br><br>
           <button type="button" 
                onclick="window.location.href='../views/view.php?id=${data.id}'" 
                style="padding:8px 15px; cursor:pointer; background:maroon; color:white; border:none; border-radius:4px; margin-right:5px;">
            <i class="fas fa-eye"></i> View Record
        </button>
        <button type="button" 
                onclick="location.reload()" 
                style="padding:8px 15px; cursor:pointer; background:#6c757d; color:white; border:none; border-radius:4px;">
            <i class="fas fa-sync"></i> Stay Here
        </button>
        </div>`;
    
    const controls = document.querySelectorAll('#submitBtn, .btn-delete, .btn-cancel, a.btn-cancel, button.cancel-btn');
    controls.forEach(btn => btn.style.display = 'none');
    
    window.scrollTo({ top: 0, behavior: 'smooth' });
} else {
                        alert("Error: " + data.message);
                    }
                })
                .catch(err => {
                    console.error(err);
                    alert("Network error. Please check your connection.");
                })
                .finally(() => {
                    const isSuccess = formStatus.innerHTML.includes('Success!');
                    if (!isSuccess) {
                        submitBtn.disabled = false;
                        const isEdit = document.querySelector('input[name="id"]');
                        submitBtn.innerHTML = isEdit ? 
                            '<i class="fas fa-save"></i> SAVE ALL CHANGES' : 
                            '<i class="fas fa-paper-plane"></i> SUBMIT FORM';
                    }
                });
            });
        }
    });

    window.deleteRecord = function(id) {
        if (confirm("Are you sure you want to delete this record?")) {
            const formData = new FormData();
            formData.append('id', id);

            fetch('delete.php', { 
                method: 'POST',
                body: formData
            })
            .then(res => {
                if (!res.ok) return fetch('views/delete.php', { method: 'POST', body: formData }).then(r => r.json());
                return res.json();
            })
            .then(data => {
                if (data.success) {
                    alert("✓ Record deleted successfully.");
                    if(window.location.href.includes('views/')) {
                        window.location.href = '../index.php';
                    } else {
                        window.location.reload();
                    }
                } else {
                    alert("Error: " + data.message);
                }
            })
            .catch(err => {
                console.error(err);
                alert("Delete failed.");
            });
        }
    };