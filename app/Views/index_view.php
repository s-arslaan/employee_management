<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- CSS -->
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>/assets/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>/assets/css/toastr.min.css" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="<?= base_url() ?>/assets/css/dataTables.bootstrap5.min.css" />

    <title><?= $title ?></title>
</head>

<body>

    <div class="container">
        <div class="row my-3">
            <div class="col-12">

                <div class="d-flex align-items-center my-3">
                    <h1>Employees</h1>
                    <!-- <button type="button" class="btn btn-outline-primary ms-auto" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">Add Employee</button> -->
                    <button type="button" class="btn btn-outline-primary ms-auto" onclick="location.href='employees/addEditEmployee'">Add Employee</button></button>
                </div>

                <table class="table table-striped table-bordered" id="employees_table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Name</th>
                            <th scope="col">Email</th>
                            <th scope="col">Department</th>
                            <th scope="col">DOB</th>
                            <th scope="col">Phone</th>
                            <th scope="col">Salary</th>
                            <th scope="col">Status</th>
                            <th scope="col"></th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                </table>
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
    <script type="text/javascript" src="<?= base_url() ?>/assets/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>/assets/js/dataTables.bootstrap5.min.js"></script>
    <script type="text/javascript" src="<?= base_url() ?>/assets/js/toastr.min.js"></script>

    <script>
        $(document).ready(function() {

            // Datatable
            $('#employees_table').DataTable({
                "processing": true,
                "serverSide": true,
                "ajax": {
                    "url": "<?= base_url('employees/get_employees_api') ?>",
                    "type": "POST"
                },
                "columns": [{
                        "data": "emp_id"
                    },
                    {
                        "data": "name"
                    },
                    {
                        "data": "email"
                    },
                    {
                        "data": "department"
                    },
                    {
                        "data": "dob"
                    },
                    {
                        "data": "phone",
                        "orderable": false,
                    },
                    {
                        "data": "salary"
                    },
                    {
                        "data": "status"
                    },
                    {
                        "data": null,
                        "orderable": false,
                        mRender: function(data, type, row) {
                            return '<a class="btn btn-outline-info btn-sm" href="<?= base_url('employees/edit/') ?>' + row['emp_id'] + '">EDIT</a>';
                        }
                    },
                    /* DELETE */
                    {
                        "data": null,
                        "orderable": false,
                        mRender: function(data, type, row) {
                            return '<a class="btn btn-outline-danger btn-sm delete_btn" href="<?= base_url('employees/deleteEmployee/') ?>' + row['emp_id'] + '">Del</a>';
                        }
                    },
                ]
            });

            $('#employees_table').on('click', 'a.delete_btn', function() {
                return confirm('Are you sure you want to delete this employee?');
            });

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