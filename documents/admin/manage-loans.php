
<?php
require_once '../config.php';

// Check if admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: login.php");
    exit;
}

$page_title = "Manage Loan Categories";

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['action'])) {
        try {
            switch ($_POST['action']) {
                case 'add':
                    $stmt = $pdo->prepare("INSERT INTO loan_categories (name, icon, description, key_point_1, key_point_2, key_point_3, image_url, min_amount, max_amount, interest_rate, is_featured, sort_order) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                    $stmt->execute([
                        $_POST['name'], $_POST['icon'], $_POST['description'],
                        $_POST['key_point_1'], $_POST['key_point_2'], $_POST['key_point_3'],
                        $_POST['image_url'], $_POST['min_amount'], $_POST['max_amount'],
                        $_POST['interest_rate'], isset($_POST['is_featured']) ? 1 : 0, $_POST['sort_order']
                    ]);
                    $success = "Loan category added successfully!";
                    break;
                    
                case 'update':
                    $stmt = $pdo->prepare("UPDATE loan_categories SET name=?, icon=?, description=?, key_point_1=?, key_point_2=?, key_point_3=?, image_url=?, min_amount=?, max_amount=?, interest_rate=?, is_featured=?, sort_order=? WHERE id=?");
                    $stmt->execute([
                        $_POST['name'], $_POST['icon'], $_POST['description'],
                        $_POST['key_point_1'], $_POST['key_point_2'], $_POST['key_point_3'],
                        $_POST['image_url'], $_POST['min_amount'], $_POST['max_amount'],
                        $_POST['interest_rate'], isset($_POST['is_featured']) ? 1 : 0, $_POST['sort_order'], $_POST['id']
                    ]);
                    $success = "Loan category updated successfully!";
                    break;
                    
                case 'delete':
                    $stmt = $pdo->prepare("UPDATE loan_categories SET is_active = 0 WHERE id = ?");
                    $stmt->execute([$_POST['id']]);
                    $success = "Loan category deleted successfully!";
                    break;
            }
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
    }
}

// Fetch all loan categories
$stmt = $pdo->query("SELECT * FROM loan_categories ORDER BY sort_order, name");
$categories = $stmt->fetchAll();

include '../includes/header.php';
?>

<div class="container-fluid py-4">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="text-danger fw-bold">Manage Loan Categories</h2>
                <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#addCategoryModal">
                    <i class="fas fa-plus me-2"></i>Add New Category
                </button>
            </div>

            <?php if (isset($success)): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?php echo $success; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>

            <?php if (isset($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show">
                <?php echo $error; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table table-striped">
                    <thead class="table-danger">
                        <tr>
                            <th>Sort</th>
                            <th>Name</th>
                            <th>Icon</th>
                            <th>Featured</th>
                            <th>Amount Range</th>
                            <th>Interest Rate</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($categories as $category): ?>
                        <tr>
                            <td><?php echo $category['sort_order']; ?></td>
                            <td><?php echo $category['name']; ?></td>
                            <td><i class="<?php echo $category['icon']; ?> text-danger"></i></td>
                            <td>
                                <?php if ($category['is_featured']): ?>
                                    <span class="badge bg-success">Featured</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Regular</span>
                                <?php endif; ?>
                            </td>
                            <td>₹<?php echo number_format($category['min_amount']); ?> - ₹<?php echo number_format($category['max_amount']); ?></td>
                            <td><?php echo $category['interest_rate']; ?></td>
                            <td>
                                <?php if ($category['is_active']): ?>
                                    <span class="badge bg-success">Active</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Inactive</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-outline-primary me-1" onclick="editCategory(<?php echo htmlspecialchars(json_encode($category)); ?>)">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?php echo $category['id']; ?>">
                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Add/Edit Category Modal -->
<div class="modal fade" id="addCategoryModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form method="POST" id="categoryForm">
                <div class="modal-header">
                    <h5 class="modal-title text-danger">Add/Edit Loan Category</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="action" id="formAction" value="add">
                    <input type="hidden" name="id" id="categoryId">
                    
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Loan Name *</label>
                            <input type="text" class="form-control" name="name" id="categoryName" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Icon Class *</label>
                            <input type="text" class="form-control" name="icon" id="categoryIcon" placeholder="fas fa-home" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Description</label>
                            <textarea class="form-control" name="description" id="categoryDescription" rows="2"></textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Key Point 1</label>
                            <input type="text" class="form-control" name="key_point_1" id="keyPoint1">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Key Point 2</label>
                            <input type="text" class="form-control" name="key_point_2" id="keyPoint2">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Key Point 3</label>
                            <input type="text" class="form-control" name="key_point_3" id="keyPoint3">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Image URL</label>
                            <input type="url" class="form-control" name="image_url" id="imageUrl">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Min Amount</label>
                            <input type="number" class="form-control" name="min_amount" id="minAmount" value="10000">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Max Amount</label>
                            <input type="number" class="form-control" name="max_amount" id="maxAmount" value="1000000">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Interest Rate</label>
                            <input type="text" class="form-control" name="interest_rate" id="interestRate" placeholder="7% onwards">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Sort Order</label>
                            <input type="number" class="form-control" name="sort_order" id="sortOrder" value="0">
                        </div>
                        <div class="col-md-6">
                            <div class="form-check mt-4">
                                <input class="form-check-input" type="checkbox" name="is_featured" id="isFeatured">
                                <label class="form-check-label" for="isFeatured">
                                    Featured Product
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Save Category</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editCategory(category) {
    document.getElementById('formAction').value = 'update';
    document.getElementById('categoryId').value = category.id;
    document.getElementById('categoryName').value = category.name;
    document.getElementById('categoryIcon').value = category.icon;
    document.getElementById('categoryDescription').value = category.description || '';
    document.getElementById('keyPoint1').value = category.key_point_1 || '';
    document.getElementById('keyPoint2').value = category.key_point_2 || '';
    document.getElementById('keyPoint3').value = category.key_point_3 || '';
    document.getElementById('imageUrl').value = category.image_url || '';
    document.getElementById('minAmount').value = category.min_amount;
    document.getElementById('maxAmount').value = category.max_amount;
    document.getElementById('interestRate').value = category.interest_rate || '';
    document.getElementById('sortOrder').value = category.sort_order;
    document.getElementById('isFeatured').checked = category.is_featured == 1;
    
    new bootstrap.Modal(document.getElementById('addCategoryModal')).show();
}

// Reset form when modal is closed
document.getElementById('addCategoryModal').addEventListener('hidden.bs.modal', function() {
    document.getElementById('categoryForm').reset();
    document.getElementById('formAction').value = 'add';
    document.getElementById('categoryId').value = '';
});
</script>

<?php include '../includes/footer.php'; ?>
