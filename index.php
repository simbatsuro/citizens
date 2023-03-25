<?php
    require_once('env.php');

    //Initialize variables
    $firstname = $surname = $idNumber = $dob = $error_msg = $success_msg = '';

    if (isset($_POST['post_button'])) {

        // Validate firstname
        if (empty($_POST['firstname'])) {
            $firstnameErr = 'Firstname is required!';
        } else {
            $firstname = clean_data($_POST['firstname']);
            // Check for letters only
            if (!preg_match("/^[a-zA-Z-]*$/",$firstname)) {
                $firstnameErr = 'Only English alphabet letters allowed!';
            }
        }

        // Validate surname
        if (empty($_POST['surname'])) {
            $surnameErr = 'Surname is required!';
        } else {
            $surname = clean_data($_POST['surname']);
            // Check for letters only
            if (!preg_match('/^[a-zA-Z-]*$/',$surname)) {
                $surnameErr = 'Only English alphabet letters allowed!';
            }
        }

        // Validate ID Number
        if (empty($_POST['idNumber'])) {
            $idNumberErr = 'ID Number is required!';
        } else {
            $idNumber = clean_data($_POST['idNumber']);
            // Check for digits only
            if (!preg_match('/^[0-9]*$/', $idNumber)) {
                $idNumberErr = 'Only digits allowed!';
            }
            //Check for digits length  
            if (strlen((string)$idNumber) !== 13) {
                $idNumberErr = 'ID Number must be 13 digits long!';
            }
        }

        // Validate date of birth
        if (empty($_POST['dob'])) {
            $dobErr = 'Date of birth is required!';
        } else {
            $dobStr = $_POST['dob'];

            //format date to dd/mm/YYYY from YYYY-mm-dd
            $dob = date("d-m-Y", strtotime($dobStr));

            //verify format
            // $dateString = $dob;
            // $format = 'd/m/Y';
            // $dateTime = DateTime::createFromFormat($format, $dateString);
            // if ($dateTime && $dateTime->format($format) === $dateString) {
            //     echo "The date string has the expected format.";
            // } else {
            //     $dobErr = "The date format is invalid";
            // }
       
        }

        // If there are no input errors, save the citizen
        if (empty($firstnameErr) && empty($surnameErr) && empty($idNumberErr) && empty($dobErr)) {
 
            $existingCitizen = $citizens->findOne(array('idNumber'=>$idNumber));

            if (!$existingCitizen) {
                $result = $citizens->insertOne(['firstname'=>$firstname, 'surname'=>$surname, 'idNumber'=>$idNumber, 'dob'=>$dob]);
                $success_msg = 'Citizen '.$idNumber.' registered succesfully.'; //echo 'Done '.$result->getInsertedId();
                //header("Location: #");
            } else {
                $error_msg = 'ID number '.$idNumber.' is already registered! ';
            }  
        }      
    }

    // If there is an error store form data in session
    if (!empty($error_msg) || !empty($firstnameErr) || !empty($surnameErr) || !empty($idNumberErr || !empty($dobErr))) {
        session_start();
        $_SESSION['form_data'] = array(
            'firstname' => $firstname,
            'surname' => $surname,
            'idNumber' => $idNumber,
            'dob' => $dob
        );
    }

    // Function to clean and validate user input
    function clean_data($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
?>

<body>
    <h2>Citizen's Database</h2>
    <?php if (!empty($success_msg)) { ?>
        <p class="success"><?php echo $success_msg ?></p>
    <?php } ?>
    <?php if (!empty($error_msg)) { ?>
        <p class="error"><?php echo $error_msg; ?></p>
    <?php } ?>
    <form action="" method="post">
        <fieldset>
            <label for="firstname">Firstname:</label><br>
            <input type="text" id="firstname" name="firstname" value="<?php if(isset($_SESSION['form_data'])) echo htmlspecialchars($_SESSION['form_data']['firstname']); ?>" placeholder="Enter firstname"><br>
            <span class="error"><?php echo $firstnameErr; ?></span><br>

            <label for="surname">Surname:</label><br>
            <input type="text" id="surname" name="surname" value="<?php if(isset($_SESSION['form_data'])) echo htmlspecialchars($_SESSION['form_data']['surname']); ?>" placeholder="Enter lastname"><br>
            <span class="error"><?php echo $surnameErr; ?></span><br>

            <label for="id_number">ID Number:</label><br>
            <input type="number" id="idNumber" name="idNumber" value="<?php if(isset($_SESSION['form_data'])) echo htmlspecialchars($_SESSION['form_data']['idNumber']); ?>" placeholder="Enter SA ID Number"><br>
            <span class="error"><?php echo $idNumberErr; ?></span><br>

            <label for="dob">Date of Birth:</label><br>
            <input type="date" id="dob" name="dob" value="<?php if(isset($_SESSION['form_data'])) echo htmlspecialchars($_SESSION['form_data']['dob']); ?>" placeholder="Enter some text"><br><br>
            <span class="error"><?php echo $dobErr; ?></span><br>

            <div>
                <input type="reset" name="cancel_button" value="Cancel">
                <input type="submit" name="post_button" value="Post">
            </div>
        </fieldset>
    </form>
</body>

<?php
   // Clear form data from session
   unset($_SESSION['form_data']);
?>