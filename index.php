<?php
    require_once('env.php');

    //Initialize variables
    $firstname = $surname = $idNumber = $dob = $error_msg = $success_msg = '';

    if (isset($_POST['post_button'])) {
        $firstname = $_POST['firstname'];
        $surname = $_POST['surname'];
        $idNumber = $_POST['idNumber'];
        $dob = $_POST['dob'];       

        $existingCitizen = $citizens->findOne(array('idNumber'=>$idNumber));

        if (!$existingCitizen) {
            $result = $citizens->insertOne(['firstname'=>$firstname, 'surname'=>$surname, 'idNumber'=>$idNumber, 'dob'=>$dob]);
            $success_msg = 'Citizen '.$idNumber.' registered succesfully.'; //echo 'Done '.$result->getInsertedId();
            header("Location: #");
        } else {
            $error_msg = 'ID number '.$idNumber.' is already registered! ';
        }        
    }

    // If there is an error store form data in session
    if (!empty($error_msg)) {
        session_start();
        $_SESSION['form_data'] = array(
            'firstname' => $firstname,
            'surname' => $surname,
            'idNumber' => $idNumber,
            'dob' => $dob
        );
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

            <label for="surname">Surname:</label><br>
            <input type="text" id="surname" name="surname" value="<?php if(isset($_SESSION['form_data'])) echo htmlspecialchars($_SESSION['form_data']['surname']); ?>" placeholder="Enter lastname"><br>

            <label for="id_number">ID Number:</label><br>
            <input type="number" id="idNumber" name="idNumber" value="<?php if(isset($_SESSION['form_data'])) echo htmlspecialchars($_SESSION['form_data']['idNumber']); ?>" placeholder="Enter SA ID Number"><br>

            <label for="dob">Date of Birth:</label><br>
            <input type="date" id="dob" name="dob" value="<?php if(isset($_SESSION['form_data'])) echo htmlspecialchars($_SESSION['form_data']['dob']); ?>" placeholder="Enter some text"><br><br>

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