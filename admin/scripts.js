function loadMenuItems() {
    fetch('admin/fetch_menu_details.php')
    .then(res => res.json())
    .then(data => {
        const container = document.getElementById('menuList');
        container.innerHTML = '';

        data.forEach(item => {
            const card = document.createElement('div');
            card.className = 'col-md-4 mb-3';
            card.innerHTML = `
                <div class="card">
                    <div class="card-header">
                        <h4>${item.category_name}</h4>
                    <img src="${item.image_path || 'default.jpg'}" class="card-img-top" style="height:200px; object-fit:cover">
                    <div class="card-body">
                        <h5>${item.item_name}</h5>
                        <p>${item.description}</p>
                        <p><strong>RM ${item.price}</strong></p>
                        <button class="btn btn-danger btn-sm" onclick="deleteItem(${item.id})">Delete</button>
                    </div>
                </div>`;
            container.appendChild(card);
        });
    });
}

// Call on load
document.addEventListener('DOMContentLoaded', loadMenuItems);


function deleteItem(id) {
    if (!confirm("Are you sure you want to delete this menu item?")) return;

    fetch('admin/delete_menu_item.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: `id=${id}`
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            loadMenuItems(); // Refresh list
        } else {
            alert("Delete failed: " + data.message);
        }
    });
}

