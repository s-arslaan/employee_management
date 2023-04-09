<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>/assets/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>/assets/css/toastr.min.css" rel="stylesheet" />

    <title><?= $title ?></title>
</head>

<body>

    <div class="container">
        <div class="row my-3">
            <div class="col-6">

                <h2 class="my-3"><?= $heading ?></h2>

                <?php
                    if (isset($validation)) {
                        echo $validation->listErrors();
                    }
                ?>

                <div class="mb-3">
                    <form method="post" enctype="multipart/form-data">
                        <!-- <label for="name" class="col-form-label">Name</label> -->
                        <input class="form-control my-2" type="text" name="name" placeholder="Enter Name" value="<?= $name ?>" required>
                        <input class="form-control my-2" type="email" name="email" placeholder="Enter Email" value="<?= $email ?>" required>
                        <select class="form-select my-2" aria-label="select department" name="department" required>
                            <option selected>Select Department</option>
                            <?php foreach ($departments as $department) { ?>
                                <option value="<?= $department['id'] ?>" <?= $department_id == $department['id'] ? 'selected':'' ?>><?= $department['name'] ?></option>
                            <?php } ?>
                        </select>
                        <input class="form-control my-2" type="number" name="phone" placeholder="Enter Phone Number" value="<?= $phone ?>" required>
                        <input class="form-control my-2" type="date" max="2005-12-31" name="dob" placeholder="Select Date of Birth" value="<?= $dob ?>" required>
                        <input class="form-control my-2" type="file" name="image" placeholder="Select Image" required>
                        <input class="form-control my-2" type="number" step="0.01" min="0.00" name="salary" value="<?= $salary ?>" placeholder="Enter Salary" required>
                        <input type="submit" class="btn btn-primary" value="Add Employee" id="submit-btn">
                    </form>
                </div>

            </div>
        </div>
    </div>

    <!-- Add Employee File Modal -->
    <div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-labelledby="addEmployeeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addEmployeeModalLabel">Add Employee</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="<?= base_url('employees/addEmployee') ?>" method="post" enctype="multipart/form-data">
                    <div class="modal-body">
                        <div class="mb-3">
                            <!-- <label for="name" class="col-form-label">Name</label> -->
                            <input class="form-control my-2" type="text" name="name" placeholder="Enter Name" required>
                            <input class="form-control my-2" type="email" name="email" placeholder="Enter Email" required>
                            <select class="form-select my-2" aria-label="select department" required>
                                <option selected>Select Department</option>
                                <?php foreach ($departments as $department) { ?>
                                    <option value="<?= $department['id'] ?>"><?= $department['name'] ?></option>
                                <?php } ?>
                            </select>
                            <input class="form-control my-2" type="number" name="phone" placeholder="Enter Phone Number" required>
                            <input class="form-control my-2" type="date" max="2005-12-31" name="dob" placeholder="Select Date of Birth" required>
                            <input class="form-control my-2" type="file" name="image" placeholder="Select Image" required>
                            <input class="form-control my-2" type="number" step="0.01" min="0.00" name="salary" placeholder="Enter Salary" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <input type="submit" class="btn btn-primary" value="Add Employee" id="submit-btn">
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="<?= base_url() ?>/assets/js/jquery.min.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>/assets/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>/assets/js/toastr.min.js"></script>

    <script>
        $(document).ready(function() {

            toastr.options.positionClass = "toast-top-center";
            toastr.options.timeOut = 2000;
            toastr.options.extendedTimeOut = 1000;

            <?php if (session()->getTempdata('success')) : ?>
                toastr.success(<?= "'" . session()->getTempdata('success') . "'"; ?>);
                <?php session()->removeTempdata('success'); ?>
            <?php endif; ?>

            <?php if (session()->getTempdata('error')) : ?>
                toastr.error(<?= "'" . session()->getTempdata('error') . "'"; ?>);
                <?php session()->removeTempdata('error'); ?>
            <?php endif; ?>
        });
    </script>
</body>

</html>