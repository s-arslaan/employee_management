<?php

namespace App\Controllers;

class DBCreate extends BaseController
{
    public $session;
    public function __construct()
    {
        $this->session = \Config\Services::session();
    }

    public function index()
    {

        $servername = getenv('database.default.hostname');
        $username = getenv('database.default.username');
        $password = getenv('database.default.password');
        $db_name = getenv('database.default.database');

        // Create connection
        $conn = mysqli_connect($servername, $username, $password);
        // Check connection
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // Create database
        // $sql = "DROP DATABASE IF EXISTS `$db_name`";

        $sql = "CREATE DATABASE IF NOT EXISTS `$db_name`";
        if (mysqli_query($conn, $sql)) {
            echo 'Database created :: ' . $db_name . '<br>';
            mysqli_close($conn);

            if ($this->tables_and_data()) {
                $this->session->setTempdata('success', 'Database (' . $db_name . ') & Tables created successfully', 3);
                return redirect()->to(base_url());
            } else {
                echo 'Error creating tables and data';
            }
        } else {
            echo "Error creating database: " . mysqli_error($conn);
            mysqli_close($conn);
        }


        // $forge = \Config\Database::forge();

        // try {
        //     if ($forge->createDatabase($db_name, true)) {
        //         $this->tables_and_data();
        //     }
        // } catch (\Exception $e) {
        //     exit($e->getMessage());
        // }
    }

    private function tables_and_data()
    {
        try {

            $db = db_connect();

            $dept_sql = 'DROP TABLE IF EXISTS `departments`; CREATE TABLE `departments` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `name` varchar(50) NOT NULL,
                    `status` tinyint(1) NOT NULL DEFAULT 1,
                    `created` timestamp NOT NULL DEFAULT current_timestamp(),
                    `modified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
                    PRIMARY KEY (`id`)
                )';

            if ($db->simpleQuery($dept_sql)) {
                echo 'Table created :: departments <br>';

                $dept_data = [
                    [
                        'name' => 'IT',
                    ],
                    [
                        'name' => 'HR',
                    ],
                    [
                        'name' => 'Sales',
                    ],
                    [
                        'name' => 'Marketing',
                    ],
                    [
                        'name' => 'Accounts',
                    ],
                ];

                $builder = $db->table('departments');

                if ($builder->insertBatch($dept_data)) {
                    echo 'Data inserted :: departments <br>';

                    $emp_sql = 'DROP TABLE IF EXISTS `employees`; CREATE TABLE `employees` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        `department_id` int(11) NOT NULL,
                        `name` varchar(50) NOT NULL,
                        `dob` date DEFAULT NULL,
                        `phone` varchar(12) NOT NULL,
                        `photo` varchar(64) DEFAULT NULL,
                        `email` varchar(50) NOT NULL,
                        `salary` decimal(10,2) NOT NULL,
                        `status` tinyint(1) NOT NULL DEFAULT 1,
                        `created` timestamp NOT NULL DEFAULT current_timestamp(),
                        `modified` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
                        PRIMARY KEY (`id`),
                        UNIQUE KEY `email` (`email`),
                        KEY `department_id` (`department_id`),
                        CONSTRAINT `employees_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`)
                    )';

                    if ($db->simpleQuery($emp_sql)) {
                        echo 'Table created :: employees <br>';

                        $names = ["Azaan", "Azedine", "Azeem", "Azim", "Aziz", "Azlan", "Azzam", "Azzedine", "Babatunmise", "Babur", "Bader", "Badr", "Badsha", "Bailee", "Bailey", "Bailie", "Bailley", "Baillie", "Baley", "Balian", "Banan", "Barath", "Barkley", "Barney", "Baron", "Barrie", "Barry", "Bartlomiej", "Bartosz", "Basher", "Basile", "Baxter", "Baye", "Bayley", "Beau", "Beinn", "Bekim", "Believe", "Ben", "Bendeguz", "Benedict", "Benjamin", "Benjamyn", "Benji", "Benn", "Bennett", "Benny", "Benoit", "Bentley", "Berkay", "Bernard", "Bertie", "Bevin", "Bezalel", "Bhaaldeen", "Bharath", "Bilal", "Bill", "Billy", "Binod", "Bjorn", "Blaike", "Blaine", "Blair", "Blaire", "Blake", "Blazej", "Blazey", "Blessing", "Blue", "Blyth", "Bo", "Boab", "Bob", "Bobby", "Bobby-Lee", "Bodhan", "Boedyn", "Bogdan", "Bohbi", "Bony", "Bowen", "Bowie", "Boyd", "Bracken", "Brad", "Bradan", "Braden", "Bradley", "Bradlie", "Bradly", "Brady", "Bradyn", "Braeden", "Braiden", "Brajan", "Brandan", "Branden", "Brandon", "Brandonlee", "Brandon-Lee", "Brandyn", "Brannan", "Brayden", "Braydon", "Braydyn", "Breandan", "Brehme", "Brendan", "Brendon", "Brendyn", "Breogan", "Bret", "Brett", "Briaddon", "Brian", "Brodi", "Brodie", "Brody", "Brogan", "Broghan", "Brooke", "Brooklin", "Brooklyn", "Bruce", "Bruin", "Bruno", "Brunon", "Charly", "Chase", "Che", "Chester", "Chevy", "Chi", "Chibudom", "Chidera", "Chimsom", "Chin", "Chintu", "Chiqal", "Chiron", "Chris", "Chris-Daniel", "Chrismedi", "Christian", "Christie", "Christoph", "Christopher", "Christopher-Lee", "Christy", "Chu", "Chukwuemeka", "Cian", "Ciann", "Ciar", "Ciaran", "Ciarian", "Cieran", "Cillian", "Cillin", "Cinar", "CJ", "C-Jay", "Clark", "Clarke", "Clayton", "Clement", "Clifford", "Clyde", "Cobain", "Coban", "Coben", "Cobi", "Cobie", "Coby", "Codey", "Codi", "Codie", "Cody", "Cody-Lee", "Coel", "Cohan", "Cohen", "Colby", "Cole", "Colin", "Coll", "Colm", "Colt", "Colton", "Colum", "Colvin", "Comghan", "Conal", "Conall", "Conan", "Conar", "Conghaile", "Conlan", "Conley", "Conli", "Conlin", "Conlly", "Conlon", "Conlyn", "Connal", "Connall", "Connan", "Connar", "Connel", "Connell", "Conner", "Connolly", "Connor", "Connor-David", "Conor", "Conrad", "Cooper", "Copeland", "Coray", "Corben", "Corbin", "Corey", "Corey-James", "Corey-Jay", "Cori", "Corie", "Corin", "Cormac", "Cormack", "Cormak", "Corran", "Corrie", "Cory", "Cosmo", "Coupar", "Craig", "Craig-James", "Crawford", "Creag", "Crispin", "Cristian", "Crombie", "Cruiz", "Cruz", "Cuillin", "Cullen", "Cullin", "Curtis", "Cyrus", "Daanyaal", "Daegan", "Daegyu", "Dafydd", "Dagon", "Dailey", "Daimhin", "Daithi", "Dakota", "Daksh", "Dale", "Dalong", "Dalton", "Damian", "Damien", "Damon", "Dan", "Danar", "Dane", "Danial", "Daniel", "Daniele", "Daniel-James", "Daniels", "Daniil", "Danish", "Doire", "Dolan", "Domanic", "Domenico", "Domhnall", "Dominic", "Dominick", "Dominik", "Donald", "Donnacha", "Donnie", "Dorian", "Dougal", "Douglas", "Dougray", "Drakeo", "Dre", "Dregan", "Drew", "Dugald", "Duncan", "Duriel", "Dustin", "Dylan", "Dylan-Jack", "Dylan-James", "Dylan-John", "Dylan-Patrick", "Dylin", "Dyllan", "Dyllan-James", "Dyllon", "Eadie", "Eagann", "Eamon", "Eamonn", "Eason", "Eassan", "Easton", "Ebow", "Ed", "Eddie", "Eden", "Ediomi", "Edison", "Eduardo", "Eduards", "Edward", "Edwin", "Edwyn", "Eesa", "Efan", "Efe", "Ege", "Ehsan", "Ehsen", "Eiddon", "Eidhan", "Eihli", "Eimantas", "Eisa", "Eli", "Elias", "Elijah", "Eliot", "Elisau", "Eljay", "Eljon", "Elliot", "Elliott", "Ellis", "Ellisandro", "Elshan", "Elvin", "Elyan", "Emanuel", "Emerson", "Emil", "Emile", "Emir", "Emlyn", "Emmanuel", "Emmet", "Eng", "Eniola", "Enis", "Ennis", "Enrico", "Enrique", "Enzo", "Eoghain", "Eoghan", "Eoin", "Eonan", "Erdehan", "Eren", "Erencem", "Eric", "Ericlee", "Erik", "Eriz", "Ernie-Jacks", "Eroni", "Eryk", "Eshan", "Frazer", "Fred", "Freddie", "Frederick", "Fruin", "Fyfe", "Fyn", "Fynlay", "Fynn", "Gabriel", "Gallagher", "Gareth", "Garren", "Garrett", "Garry", "Gary", "Gavin", "Gavin-Lee", "Gene", "Geoff", "Geoffrey", "Geomer", "Geordan", "Geordie", "George", "Georgia", "Georgy", "Gerard", "Ghyll", "Giacomo", "Gian", "Giancarlo", "Gianluca", "Gianmarco", "Gideon", "Gil", "Gio", "Girijan", "Girius", "Gjan", "Glascott", "Glen", "Glenn", "Gordon", "Grady", "Graeme", "Graham", "Grahame", "Grant", "Grayson", "Greg", "Gregor", "Gregory", "Greig", "Griffin", "Griffyn", "Grzegorz", "Guang", "Guerin", "Guillaume", "Gurardass", "Gurdeep", "Gursees", "Gurthar", "Gurveer", "Gurwinder", "Gus", "Gustav", "Guthrie", "Guy", "Gytis", "Habeeb", "Hadji", "Hadyn", "Hagun", "Hristomir", "Hubert", "Hugh", "Hugo", "Humza", "Hunter", "Husnain", "Hussain", "Hussan", "Hussnain", "Hussnan", "Hyden", "I", "Iagan", "Iain", "Ian", "Ibraheem", "Ibrahim", "Idahosa", "Idrees", "Idris", "Iestyn", "Ieuan", "Igor", "Ihtisham", "Ijay", "Ikechukwu", "Ikemsinachukwu", "Ilyaas", "Ilyas", "Iman", "Immanuel", "Inan", "Indy", "Ines", "Innes", "Ioannis", "Ireayomide", "Ireoluwa", "Irvin", "Irvine", "Isa", "Isaa", "Isaac", "Isaiah", "Isak", "Isher", "Ishwar", "Isimeli", "Isira", "Ismaeel", "Ismail", "Israel", "Issiaka", "Ivan", "Ivar", "Izaak", "J", "Jaay", "Jac", "Jace", "Jack", "Jacki", "Jackie", "Jack-James", "Jackson", "Jacky", "Jacob", "Jacques", "Jad", "Jaden", "Jadon", "Jadyn", "Jae", "Jagat", "Jago", "Jaheim", "Jahid", "Jahy", "Jai", "Jaida", "Jaiden", "Jaidyn", "Jaii", "Jaime", "Jai-Rajaram", "Jaise", "Jak", "Jake", "Jakey", "Jakob", "Jaksyn", "Jakub", "Jamaal", "Jamal", "Jameel", "Kyie", "Kylan", "Kylar", "Kyle", "Kyle-Derek", "Kylian", "Kym", "Kynan", "Kyral", "Kyran", "Kyren", "Kyrillos", "Kyro", "Kyron", "Kyrran", "Lachlainn", "Lachlan", "Lachlann", "Lael", "Lagan", "Laird", "Laison", "Lakshya", "Lance", "Lancelot", "Landon", "Lang", "Sunny", "Suraj", "Surien", "Sweyn", "Syed", "Sylvain", "Symon", "Szymon", "Tadd", "Taddy", "Tadhg", "Taegan", "Taegen", "Tai", "Tait", "Taiwo", "Talha", "Taliesin", "Talon", "Talorcan", "Tamar", "Tamiem", "Tammam", "Tanay", "Tane", "Tanner"];

                        $sql = 'INSERT INTO `employees` (`department_id`, `name`, `dob`, `phone`, `email`, `salary`) VALUES ';

                        $date_from = strtotime('01-01-1970');
                        $date_to = strtotime('31-12-2000');

                        foreach ($names as $name) {
                            $sql .= "(" . mt_rand(1, 5) . ", '$name', '" . date('Y-m-d', mt_rand($date_from, $date_to)) . "', '" . mt_rand(7874015432, 9987855869) . "', '" . strtolower($name) . "@gmail.com', '" . mt_rand(30000, 150000) . "'), ";
                        }

                        $sql = rtrim($sql, ', ');

                        if ($db->simpleQuery($sql)) {
                            echo 'Employees Data inserted :: ' . $db->affectedRows() . '<br>';

                            return 1;
                        }
                    }
                }
            }
        } catch (\Exception $e) {
            exit($e->getMessage());
        }
    }
}
