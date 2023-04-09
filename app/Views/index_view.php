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

        <!-- ALL Employees :: Search, sort, edit, delete -->
        <div class="row my-3">
            <div class="col-12">

                <div class="d-flex align-items-center my-3">
                    <h1>Employees</h1>
                    <!-- <button type="button" class="btn btn-outline-primary ms-auto" data-bs-toggle="modal" data-bs-target="#addEmployeeModal">Add Employee</button> -->
                    <button type="button" class="btn btn-outline-primary ms-auto" onclick="location.href='employees/addEditEmployee'">Add Employee</button></button>
                </div>

                <table class="table table-striped table-hover table-bordered" id="employees_table">
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
                            <th scope="col">Photo</th>
                            <th scope="col"></th>
                            <th scope="col"></th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>

        <hr>
        <!-- Salary Range Wise Employee Count -->
        <div class="row my-3">
            <div class="col-md-12">
                <h2>Salary Range Wise Employee Count</h2>
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Range</th>
                            <th>Employees</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($salary_range_wise_emp as $range) : ?>
                            <tr>
                                <td><?= $range['salary_range'] ?></td>
                                <td><?= $range['employee_count'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <hr>
        <!-- Highest Salaries in Each Department -->
        <div class="row my-3">
            <div class="col-md-12">
                <h2>Highest Salaries in Each Department</h2>
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Salary</th>
                            <th>Department</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($highest_department_salaries as $salary) : ?>
                            <tr>
                                <td><?= $salary['name'] ?></td>
                                <td><?= number_format($salary['salary']) ?></td>
                                <td><?= $salary['department'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <hr>
        <!-- Youngest Employees in Each Department -->
        <div class="row my-3">
            <div class="col-md-12">
                <h2>Youngest Employees in Each Department</h2>
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Age</th>
                            <th>Department</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($youngest_employees_by_department as $employee) : ?>
                            <tr>
                                <td><?= $employee['employee_name'] ?></td>
                                <td><?= $employee['age'] ?></td>
                                <td><?= $employee['department'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
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
                                <img src="..." class="card-img-top" id="img_view" alt="employee_img">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script type="text/javascript" src="<?= base_url() ?>/assets/js/jquery.min.js"></script>
        <script type="text/javascript" src="<?= base_url() ?>/assets/js/bootstrap.bundle.min.js"></script>
        <script type="text/javascript" src="<?= base_url() ?>/assets/js/jquery.dataTables.min.js"></script>
        <script type="text/javascript" src="<?= base_url() ?>/assets/js/dataTables.bootstrap5.min.js"></script>
        <script type="text/javascript" src="<?= base_url() ?>/assets/js/toastr.min.js"></script>

        <script>
            // Image View
            function imgView(img) {

                if (img == null || img == '' || !img)
                    img = 'default.png';

                $('#img_view').attr('src', '<?= base_url() ?>assets/uploads/' + img);
                $('#imgView').modal('show');
            }

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
                                // return '<img src="<?= base_url() ?>/assets/images/' + row['photo'] + '" width="50" height="50" />';
                                return '<button class="btn btn-outline-primary btn-sm" onCLick="imgView(\'' + (row['photo'] == null ? '' : row['photo']) + '\')">View</button>';
                            }
                        },
                        {
                            "data": null,
                            "orderable": false,
                            mRender: function(data, type, row) {
                                return '<a class="btn btn-outline-info btn-sm" href="<?= base_url('employees/addEditEmployee/') ?>' + row['emp_id'] + '">EDIT</a>';
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



                // Toastr
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