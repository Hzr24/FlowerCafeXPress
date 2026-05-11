    <!-- Login Modal -->
            <div class="modal fade" id="loginModal" tabindex="-1" role="dialog">
                <div class="modal-dialog" role="document">
                    <div class="modal-content">
                    <form id="loginForm" method="POST" action="login.php">
                            <div class="modal-header">
                                <h5 class="modal-title">Login</h5>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body">
                                <div class="form-group">
                                <label>Email</label>
                                <input type="text" name="email" class="form-control" required>
                                </div>
                                <div class="form-group">
                                <label>Password</label>
                                <input type="password" name="password" class="form-control" required>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="submit" class="btn btn-primary">Login</button>
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

    <!-- Register Modal -->
        <div class="modal fade" id="registerModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <form id="registerForm" method="POST" action="register.php">
                        <div class="modal-header">
                            <h5 class="modal-title">Register</h5>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Full Name</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Phone</label>
                                <input type="text" name="phone" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" name="register" class="btn btn-primary">Register</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    <!-- Registration Success Modal -->
        <div class="modal fade" id="successModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Registration Successful</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <p>You have successfully registered. You can now log in.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-dismiss="modal" data-toggle="modal" data-target="#loginModal">Login Now</button>
                        </div>        
                </div>
            </div>
        </div>        

    <!-- Add Menu Modal -->
        <div class="modal fade" id="addMenuModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <form id="addMenuForm" enctype="multipart/form-data" method="POST" action="admin/add_menu_item.php">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Add New Menu Item</h5>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Item Name</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label>Description</label>
                                <textarea name="description" class="form-control" required></textarea>
                            </div>
                            <div class="form-group">
                                <label>Price (RM)</label>
                                <input type="number" name="price" class="form-control" step="0.01" required>
                            </div>
                            <div class="form-group">
                                <label>Category</label>
                                <select name="category" class="form-control" required>
                                    <option value="drinks">Drinks</option>
                                    <option value="lunch">Lunch</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Availability</label>
                                <select name="availability" class="form-control" required>
                                    <option value="available">Available</option>
                                    <option value="unavailable">Unavailable</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Upload Image</label>
                                <input type="file" name="image" accept="image/*" class="form-control-file" required>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success">Add Item</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

   <!-- Edit Menu Modal -->
<div class="modal fade" id="editMenuModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <form id="editMenuForm" enctype="multipart/form-data">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Edit Menu Item</h5>
          <button type="button" class="close" data-dismiss="modal">&times;</button>
        </div>
        <div class="modal-body">
          <input type="hidden" name="item_id" id="edit-item-id">

          <div class="form-group">
            <label>Item Name</label>
            <input type="text" name="name" id="edit-item-name" class="form-control" required>
          </div>

          <div class="form-group">
            <label>Description</label>
            <textarea name="description" id="edit-description" class="form-control" required></textarea>
          </div>

          <div class="form-group">
            <label>Price (RM)</label>
            <input type="number" name="price" id="edit-price" step="0.01" class="form-control" required>
          </div>

          <div class="form-group">
            <label>Availability</label>
            <select name="availability" id="edit-availability" class="form-control">
              <option value="1">Available</option>
              <option value="0">Unavailable</option>
            </select>
          </div>

          <div class="form-group">
            <label>Change Image (optional)</label>
            <input type="file" name="image" class="form-control-file">
          </div>
        </div>

        <div class="modal-footer">
          <button type="submit" class="btn btn-primary">Save Changes</button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
        </div>
      </div>
    </form>
  </div>
</div>
