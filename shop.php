<?php require_once('includes/showMessage.php'); ?>
<?php
session_start();
if (!isset($_SESSION['user_type'])) {
    header('location: login.php');
    exit();
}

require 'includes/functions.php';
include("connection.php");

// Добавить товар
if (isset($_POST['add_item'])) {
    $itemName = $_POST['item_name'];
    $price = $_POST['price'];
    $description = $_POST['description'];
    $image = '';

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageTmpName = $_FILES['image']['tmp_name'];
        $imageName = basename($_FILES['image']['name']);
        $uploadDir = 'uploads/';
        $imagePath = $uploadDir . $imageName;

        if (move_uploaded_file($imageTmpName, $imagePath)) {
            $image = $imageName;
        }
    }

    $sql = "INSERT INTO shop (item_name, price, description, image) VALUES (?, ?, ?, ?)";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("siss", $itemName, $price, $description, $image);
    $stmt->execute();
    $stmt->close();
    $_SESSION['message'] = "Товар успешно добавлен!";
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Редактировать товар
if (isset($_POST['edit_item'])) {
    $id = $_POST['id'];
    $itemName = $_POST['edit_item_name'];
    $price = $_POST['edit_price'];
    $description = $_POST['edit_description'];
    $image = '';

    if (isset($_FILES['edit_image']) && $_FILES['edit_image']['error'] === UPLOAD_ERR_OK) {
        $imageTmpName = $_FILES['edit_image']['tmp_name'];
        $imageName = basename($_FILES['edit_image']['name']);
        $uploadDir = 'uploads/';
        $imagePath = $uploadDir . $imageName;

        if (move_uploaded_file($imageTmpName, $imagePath)) {
            $image = $imageName;
        }
    }

    if ($image) {
        $sql = "UPDATE shop SET item_name = ?, price = ?, description = ?, image = ? WHERE id = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("sissi", $itemName, $price, $description, $image, $id);
    } else {
        $sql = "UPDATE shop SET item_name = ?, price = ?, description = ? WHERE id = ?";
        $stmt = $con->prepare($sql);
        $stmt->bind_param("sisi", $itemName, $price, $description, $id);
    }
    $stmt->execute();
    $stmt->close();
    $_SESSION['message'] = "Товар успешно обновлен!";
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}

// Delete item
if (isset($_POST['delete_item'])) {
    $id = $_POST['delete_id'];

    $sql = "SELECT image FROM shop WHERE id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($image);
    $stmt->fetch();
    $stmt->close();

    if ($image) {
        unlink("uploads/" . $image);
    }

    $sql = "DELETE FROM shop WHERE id = ?";
    $stmt = $con->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();
    $_SESSION['message'] = "Товар успешно удален!";
    header("Location: " . $_SERVER['PHP_SELF']);
    exit();
}
displaySessionMessage();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Магазин</title>
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f4f7fa;
        }

        .container {
            background-color: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
            margin-top: 30px;
        }

        table {
            margin-left: 3%;
            margin-top: 20px;
        }


        .table th, .table td {
            text-align: center;
            vertical-align: middle;
        }

        .table th {
            background-color: #343a40;
            color: white;
        }

        .btn {
            border-radius: 5px;
            font-weight: 500;
        }

        .btn-success {
            background-color: #28a745;
        }

        .btn-info {
            background-color: #17a2b8;
        }

        .btn-danger {
            background-color: #dc3545;
        }

        .modal-header {
            background-color: #343a40;
            color: white;
        }

        .modal-footer .btn {
            width: 100%;
        }

        .logo-img {
            width: 80px;
            height: 80px;
            object-fit: cover;
        }

        .search-box {
            margin: 20px auto;
            width: 300px;
        }

        .search-box input {
            border-radius: 25px;
            padding: 10px;
            font-size: 16px;
        }

        .form-group label {
            font-weight: 500;
        }

        .modal-body {
            padding: 30px;
        }
        
        .text-muted {
            font-size: 14px;
            color: #6c757d;
        }

        .modal-dialog {
            max-width: 600px;
        }

        /* Responsiveness for smaller devices */
        @media (max-width: 768px) {
            .container {
                padding: 15px;
            }

            .search-box input {
                width: 100%;
            }

            .logo-img {
                width: 60px;
                height: 60px;
            }

            .table th, .table td {
                font-size: 14px;
            }
        }
    </style>
</head>

<body>
    <?php include('includes/admin-nav.php'); ?>

    <div class="container">
        <h2 class="text-center text-dark">Управление товарами</h2>

        <!-- Search -->
        <div class="d-flex justify-content-center search-box">
            <input type="text" id="search" class="form-control" placeholder="Поиск товара">
        </div>

        <!-- Add Item Button -->
        <div class="d-flex justify-content-center mb-4">
            <button type="button" class="btn btn-success" data-toggle="modal" data-target="#addItemModal">Добавить товар</button>
        </div>

        <!-- Items Table -->
        <table class="table table-striped table-bordered">
            <thead>
                <tr>
                    <th>Изображение</th>
                    <th>Название</th>
                    <th>Цена</th>
                    <th>Описание</th>
                    <th>Действия</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $sql = "SELECT * FROM shop";
                $result = $con->query($sql);

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>";
                        echo "<td><img src='uploads/" . htmlspecialchars($row['image']) . "' class='logo-img'></td>";
                        echo "<td>" . htmlspecialchars($row['item_name']) . "</td>";
                        echo "<td>" . htmlspecialchars($row['price']) . " ₽</td>";
                        echo "<td>" . htmlspecialchars($row['description']) . "</td>";
                        echo "<td>";
                        echo "<button class='btn btn-info btn-sm edit-item' data-id='" . $row['id'] . "' data-name='" . htmlspecialchars($row['item_name']) . "' data-price='" . htmlspecialchars($row['price']) . "' data-description='" . htmlspecialchars($row['description']) . "' data-toggle='modal' data-target='#editItemModal'>Редактировать</button>";
                        echo "<button class='btn btn-danger btn-sm delete-item' data-id='" . $row['id'] . "' data-toggle='modal' data-target='#deleteItemModal'>Удалить</button>";
                        echo "</td>";
                        echo "</tr>";
                    }
                } else {
                    echo "<tr><td colspan='5' class='text-center text-muted'>Нет данных для отображения.</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <!-- Add Item Modal -->
        <div class="modal fade" id="addItemModal" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Добавление товара</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="modal-body">
                            <div class="form-group">
                                <label>Название товара:</label>
                                <input type="text" class="form-control" name="item_name" required>
                            </div>
                            <div class="form-group">
                                <label>Цена:</label>
                                <input type="number" class="form-control" name="price" required>
                            </div>
                            <div class="form-group">
                                <label>Описание:</label>
                                <textarea class="form-control" name="description"></textarea>
                            </div>
                            <div class="form-group">
                                <label>Изображение:</label>
                                <input type="file" class="form-control" name="image" accept="image/*">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" name="add_item">Добавить товар</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Edit Item Modal -->
        <div class="modal fade" id="editItemModal" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Редактирование товара</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <form action="" method="post" enctype="multipart/form-data">
                        <div class="modal-body">
                            <input type="hidden" name="id" id="edit_id">
                            <div class="form-group">
                                <label>Название товара:</label>
                                <input type="text" class="form-control" name="edit_item_name" id="edit_item_name" required>
                            </div>
                            <div class="form-group">
                                <label>Цена:</label>
                                <input type="number" class="form-control" name="edit_price" id="edit_price" required>
                            </div>
                            <div class="form-group">
                                <label>Описание:</label>
                                <textarea class="form-control" name="edit_description" id="edit_description"></textarea>
                            </div>
                            <div class="form-group">
                                <label>Изображение:</label>
                                <input type="file" class="form-control" name="edit_image" accept="image/*">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" name="edit_item">Сохранить изменения</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Delete Item Modal -->
        <div class="modal fade" id="deleteItemModal" tabindex="-1" role="dialog">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Удаление товара</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <form action="" method="post">
                        <div class="modal-body">
                            <input type="hidden" name="delete_id" id="delete_id">
                            <p>Вы уверены, что хотите удалить этот товар?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-danger" name="delete_item">Удалить</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        // Fill Edit Modal
        $('.edit-item').on('click', function () {
            $('#edit_id').val($(this).data('id'));
            $('#edit_item_name').val($(this).data('name'));
            $('#edit_price').val($(this).data('price'));
            $('#edit_description').val($(this).data('description'));
        });

        // Fill Delete Modal
        $('.delete-item').on('click', function () {
            $('#delete_id').val($(this).data('id'));
        });

        // Search Filter
        $('#search').on('keyup', function () {
            var value = $(this).val().toLowerCase();
            $('tbody tr').filter(function () {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
            });
        });
    </script>
</body>

</html>