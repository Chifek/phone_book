<?php
/**
 * Created by PhpStorm.
 * User: ruslan
 * Date: 22.07.20
 * Time: 17:03
 */

require_once 'db.php';

$db = new Database();
session_start();
/**
 * Show all persons
 */
if (isset($_POST['action']) && $_POST['action'] == "view") {
    $output = '';

    if ($_SESSION["auth"]) {
        $data = $db->getAll($_SESSION["user_id"]);

        $output .= '<table class="table" id="table">
                    <thead>
                        <tr class="text-center">
                            <th>first name</th>
                            <th>last name</th>
                            <th>phone number</th>
                            <th>action</th>
                        </tr>
                    </thead>
                    <tbody>';
        foreach ($data as $row) {
            $output .= '<tr class="text-center">
            <td>' . $row['first_name'] . '</td>
            <td>' . $row['last_name'] . '</td>
            <td>' . $row['phone'] . '</td>
            <td>
                            <a href="#" title="view"><i class="fas fa-address-card viewBtn" data-toggle="modal" data-target="#myModalView" id="' . $row['id'] . '"></i></a>&nbsp;
                            <a href="#" title="edit"><i class="fa fa-pencil-square-o editBtn" data-toggle="modal" data-target="#myModalEdit" id="' . $row['id'] . '"></i></a>&nbsp;
                            <a href="#" title="delete"><i class="fa fa-trash delBtn" id="' . $row['id'] . '"></i></a>
                        </td></tr>';
        }
        $output .= '</tbody></table>';

        print_r($output);
    }

}

/**
 * Create person
 */
if (isset($_POST['action']) && $_POST['action'] == "add") {
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $phone = $_POST['phone_number'];
    $userId = $_SESSION["user_id"];
    if (isset($_FILES['file']['name'])) {
        $dir = "uploads/" . $userId;
        if (is_dir($dir) === false) {
            mkdir($dir);
        }

        $location = $dir . '/' . $filename;

        $uploadOk = 1;
        $imageFileType = pathinfo($location, PATHINFO_EXTENSION);
        $valid_extensions = array("jpg", "png");
        if (!in_array(strtolower($imageFileType), $valid_extensions)) {
            $uploadOk = 0;
        }

        if ($uploadOk == 0) {
            echo 'Need only *.jpg or *.png extension';
        } else {
            if (move_uploaded_file($_FILES['file']['tmp_name'], $location)) {
                if ($_SESSION["user_id"]) {
                    $db->insert($firstName, $lastName, $_SESSION["user_id"], $location, $phone);
                } else {
                    echo 'Authorisation error';
                }
                echo $location;
            } else {
                echo "Can't upload image. Something went wrong";
            }
        }
    } else {
        $filename = 'image.png';
        $db->insert($firstName, $lastName, $_SESSION["user_id"], $filename, $phone);
    }

}

/**
 * Get person by id
 */
if (isset($_POST['edit_id'])) {
    $id = $_POST['edit_id'];
    $row = $db->getBookFromId($id);

    echo json_encode($row);
}

/**
 * Update person
 */
if (isset($_POST['action']) && $_POST['action'] == "update") {
    $firstName = $_POST['first_name'];
    $lastName = $_POST['last_name'];
    $phone = $_POST['phone_number'];
    $id = $_POST['id'];

    if (isset($_FILES['file']['name'])) {
        $userId = $_SESSION["user_id"];
        $filename = $_FILES['file']['name'];

        $dir = "uploads/" . $userId;
        if (is_dir($dir) === false) {
            mkdir($dir);
        }

        $location = $dir . '/' . $filename;

        $uploadOk = 1;
        $imageFileType = pathinfo($location, PATHINFO_EXTENSION);
        $valid_extensions = array("jpg", "png");
        if (!in_array(strtolower($imageFileType), $valid_extensions)) {
            $uploadOk = 0;
        }

        if ($uploadOk == 0) {
            echo 'Need only *.jpg or *.png extension';
        } else {
            if (move_uploaded_file($_FILES['file']['tmp_name'], $location)) {
                if (!empty($_SESSION["user_id"])) {
                    $db->update($id, $firstName, $lastName, $location, $phone, $_SESSION["user_id"]);
                    echo $location;
                } else {
                    echo 'Authorisation error';
                }
            } else {
                echo "Can't upload image. Something went wrong";
            }
        }
    } else {
        $db->update($id, $firstName, $lastName, null, $phone, $_SESSION["user_id"]);
    }
}

/**
 * Delete person
 */
if (isset($_POST['del_id'])) {
    $id = $_POST['del_id'];

    $db->delete($id);
}

/**
 * View person details by id
 */
if (isset($_POST['view_id'])) {
    $id = $_POST['view_id'];

    $data = $db->getBookFromId($id);

    $data['textOfNumbers'] = str_from_numbers($data['phone']);

    echo json_encode($data);
}

function str_from_numbers($value)
{
    $value = explode('.', number_format($value, 2, '.', ''));

    $f = new NumberFormatter('ru', NumberFormatter::SPELLOUT);
    $string = $f->format($value[0]);
    $string = mb_strtoupper(mb_substr($string, 0, 1)) . mb_substr($string, 1, mb_strlen($string));

    return $string;
}

/**
 * Registration new user
 */
if (isset($_POST['action']) && $_POST['action'] == "reg") {
    $login = $_POST['login'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $re_password = $_POST['re_password'];
    try {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "Invalid email. Please try again";
        } else {
            if ($password !== $re_password) {
                echo 'Error. Password field is not equal re-password';
            } else {
                if (strlen($password) > 6 && preg_match("/^[A-Za-z0-9]+$/", $password)) {
                    if (isset($login) && $login !== null) {
                        if (!preg_match("/^[A-Za-z0-9]+$/", $login)) {
                            echo 'Only Latin letters and numbers';
                        } else {
                            $ifExist = $db->checkUserById($login);
                            if ($ifExist === false) {
                                $hash = password_hash($password, PASSWORD_BCRYPT);
                                $db->createNewUser($login, $hash, $email);

                                echo 'Success';
                            } else {
                                echo 'User exist. Please use another email';
                            }
                        }

                    } else {
                        echo 'Need enter the login';
                    }
                } else {
                    echo 'Passwords need more 6 symbols and must contain numbers and letters.';
                }
            }
        }

    } catch (Exception $e) {
        echo 'Caught exception: ', $e->getMessage(), "\n";
    }
}

/**
 * Login user
 */
if (isset($_POST['action']) && $_POST['action'] == "login") {
    $login = $_POST['login'];
    $password = $_POST['password'];

    if (isset($password) && $password !== null && isset($login) && $login !== null) {
        $ifExist = $db->checkUserById($login);
        if ($ifExist !== false) {
            $hash = $ifExist['password'];
            if (password_verify($password, $hash)) {
                session_start();
                $_SESSION["auth"] = true;
                $_SESSION["user_name"] = $ifExist['email'];
                $_SESSION["user_id"] = $ifExist['id'];
                echo 'Success';
            } else {
                echo 'Password and\or login is invalid';
            }
        } else {
            echo 'User not found';
        }
    } else {
        echo 'Login or password is null';
    }
}

/**
 * Logout
 */
if (isset($_GET['action']) && $_GET['action'] == "logout") {
    session_destroy();
    session_unset();
    echo "logout";
    exit;
}
