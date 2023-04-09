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

    <style>
        .is-invalid {
            border: 1px solid red;
            background-color: pink;
        }
    </style>
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
                    <form method="post" enctype="multipart/form-data" id="employee_form">
                        <!-- <label for="name" class="col-form-label">Name</label> -->
                        <input class="d-none" type="text" name="emp_id" value="<?= $id ?>">
                        <input class="form-control my-3" type="text" name="name" placeholder="Enter Name (required)" value="<?= $name ?>" required>
                        <input class="form-control my-3" type="email" name="email" placeholder="Enter Email (required)" value="<?= $email ?>" required>
                        <select class="form-select my-3" aria-label="Select Department (required)" name="department" id="department" required>
                            <option selected>Select Department (required)</option>
                            <?php foreach ($departments as $department) { ?>
                                <option value="<?= $department['id'] ?>" <?= $department_id == $department['id'] ? 'selected' : '' ?>><?= $department['name'] ?></option>
                            <?php } ?>
                        </select>
                        <select class="form-select my-3" aria-label="Select Status (required)" name="status" id="status" required>
                            <option selected>Select Status (required)</option>
                            <option value="1" <?= $status === "1" ? 'selected' : '' ?>>Enabled</option>
                            <option value="0" <?= $status === "0" ? 'selected' : '' ?>>Disabled</option>
                        </select>
                        <input class="form-control my-3" type="number" name="phone" placeholder="Enter Phone Number (required)" value="<?= $phone ?>" required>
                        <input class="form-control my-3" type="date" max="2005-12-31" name="dob" placeholder="Select Date of Birth" value="<?= $dob ?>">

                        <input class="form-control my-3" type="number" step="0.01" min="0.00" name="salary" value="<?= $salary ?>" placeholder="Enter Salary (required)" required>

                        <label for="image mb-1">Select Image <?= $id != '' ? '<strong>(Only if you want to update the image)</strong>' : '' ?></label>
                        <div class="row">
                            <div class="col-8">
                                <input class="form-control mb-3" type="file" name="image" id="image" placeholder="Select Image">
                            </div>
                            <div class="col-4">
                                <button class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#imgView" type="button">Current Image</button>
                            </div>
                        </div>

                        <input type="submit" class="btn btn-primary" value="<?= $id != '' ? 'Update':'Add' ?> Employee" id="submit-btn">
                        <a href="<?= base_url() ?>" class="btn btn-secondary">Cancel</a>
                    </form>
                </div>

            </div>
        </div>
    </div>

    <!-- Employee Image Modal -->
    <div class="modal fade" id="imgView" tabindex="-1" aria-labelledby="imgView" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imgView">Employee Image</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <div class="card">
                            <img src="<?= base_url('assets/uploads/' . $photo) ?>" class="card-img-top" id="img_view" alt="employee_img">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript" src="<?= base_url() ?>/assets/js/jquery.min.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>/assets/js/bootstrap.bundle.min.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>/assets/js/toastr.min.js"></script>

    <script>
        $(document).ready(function() {
            $('#employee_form').submit(function(e) {
                // Prevent the default form submission behavior
                e.preventDefault();

                // Validate all required fields
                var emailRegex = /^\S+@\S+\.\S+$/;
                var mobileRegex = /^[6-9]\d{9,}$/;

                var nameField = $(this).find('input[name="name"]');
                var name = nameField.val();
                if (name.length < 3) {
                    nameField.addClass('is-invalid');
                    toastr.error('Name should be atleast 3 characters long');
                }

                var emailField = $(this).find('input[name="email"]');
                if (!emailRegex.test(emailField.val())) {
                    emailField.addClass('is-invalid');
                    toastr.error('Invalid Email Address');
                }

                // Validate status field
                var departmentField = $(this).find('#department option:selected');
                console.log(departmentField);
                var department = departmentField.text();
                console.log(department);
                if (department == '' || department == null || department.includes('Select Department')) {
                    departmentField.parent().addClass('is-invalid');
                    toastr.error('Please select a department');
                } else {
                    departmentField.removeClass('is-invalid');
                }
                
                // Validate status field
                var statusField = $(this).find('#status option:selected');
                var status = statusField.text();
                if (status == '' || status == null || status.includes('Select Status')) {
                    statusField.parent().addClass('is-invalid');
                    toastr.error('Please select a status');
                } else {
                    statusField.removeClass('is-invalid');
                }

                var mobileField = $(this).find('input[name="phone"]');
                if (!mobileRegex.test(mobileField.val())) {
                    mobileField.addClass('is-invalid');
                    toastr.error('Invalid Mobile Number');
                }

                var salaryField = $(this).find('input[name="salary"]');
                var salary = salaryField.val();
                console.log(salary);
                if (salary < 1000) {
                    salaryField.addClass('is-invalid');
                    toastr.error('Salary should be atleast 1000');
                }


                // If all required fields are valid, submit the form
                if ($(this).find('.is-invalid').length == 0) {
                    $(this).unbind('submit').submit();
                }
            });
        });

        $("select, input").change(function() {
            $(this).removeClass("is-invalid");
        });

        const isValidField = (id) => {
            if ($(id).val() == undefined || $(id).val() == null || $(id).val() == "") {
                // console.log(id);
                $(id).addClass("invalid");
                toastr.error('Please fill all the fields');

                $("html, body").animate({
                    scrollTop: $(id).offset().top,
                });
                return false;
            }
            return true;
        };

        $(document).ready(function() {

            toastr.options.positionClass = "toast-top-center";
            toastr.options.timeOut = 2500;
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