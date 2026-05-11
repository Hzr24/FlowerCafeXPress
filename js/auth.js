    // Login logic
        document.getElementById('loginForm')?.addEventListener('submit', function (e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch('login.php', {
                method: 'POST',
                body: formData,
                credentials: 'include' // Include session!
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    $('#loginModal').modal('hide');
                    const role = data.role;
                    window.location.href = 'index.php';
                } else {
                    alert('Login failed: ' + data.message);
                }
            })
            .catch(error => {
                console.error('Login Error:', error);
                alert('An unexpected error occurred.');
            });
        });

    // Register logic
        document.getElementById('registerForm')?.addEventListener('submit', function(e) {
            e.preventDefault();
            const formData = new FormData(this);

            fetch('register.php', {
                method: 'POST',
                body: formData,
                credentials: 'include'
            })
            .then(response => response.text())
            .then(data => {
                if (data.trim() === "success") {
                    $('#signupModal').modal('hide');
                    setTimeout(() => $('#successModal').modal('show'), 500);
                } else {
                    alert("Registration failed: " + data);
                }
            })
            .catch(error => {
                console.error("Registration Error:", error);
                alert("An unexpected error occurred.");
            });
        });

    
    //add menu item logic
        document.getElementById('addMenuForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);

            fetch('admin/add_menu_item.php', {
                method: 'POST',
                body: formData,
                credentials: 'include'
            })
            .then(res => {
                if (!res.ok) {
                    throw new Error('Network response was not OK');
                }
                return res.json(); // Might still throw if response isn't valid JSON
            })
            .then(data => {
                if (data.success) {
                    $('#addMenuModal').modal('hide');
                    alert("Item added!");
                    this.reset(); // Optional: reset form fields
                    loadMenuItems();
                } else {
                    alert("Failed: " + data.message);
                }
            })
            .catch(err => {
                console.error("Fetch error:", err);
                alert("An error occurred. Please check the console for details.");
            });
        });

    // Function to load menu items dynamically
        function loadMenuItems() {
            fetch('menu_list.php')
                .then(res => res.text())
                .then(html => {
                    document.getElementById('menuItemsContainer').innerHTML = html;
                })
                .catch(err => {
                    console.error("Failed to reload menu items:", err);
                });
        }

    // Edit menu item logic
        document.querySelectorAll('.edit-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                document.getElementById('edit-item-id').value = this.dataset.id;
                document.getElementById('edit-item-name').value = this.dataset.name;
                document.getElementById('edit-description').value = this.dataset.description;
                document.getElementById('edit-price').value = this.dataset.price;
                document.getElementById('edit-availability').value = this.dataset.availability;
                $('#editMenuModal').modal('show');
            });
        });
        document.getElementById('editMenuForm').addEventListener('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);

        fetch('admin/update_menu_item.php', {
            method: 'POST',
            body: formData,
            credentials: 'include'
        })
            .then(res => res.json())
            .then(data => {
            if (data.success) {
                $('#editMenuModal').modal('hide');
                alert("Item updated!");
                location.reload();
            } else {
                alert("Failed to update: " + data.message);
            }
            })
            .catch(err => {
            console.error(err);
            alert("An error occurred.");
            });
        });
