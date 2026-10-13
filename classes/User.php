<?php

require_once 'Database.php';

class User extends Database {
    // the logic of the app will be placed here (CRUD, data manipulation, arithmetic operations,etc.)
    // this function will store the data from the form in view/register.php into the database
    // $request holds the data from the form. This will catch the value of $_POST from actions/register.php
    // $request = al, san, al,12345678
    public function store($request) {
        $first_name = $request['first_name']; // al
        $last_name = $request['last_name']; // san
        $username = $request['username']; // al
        $password = $request['password']; // 12345678

        $password = password_hash($password, PASSWORD_DEFAULT);
        
        $sql = "INSERT INTO users (`first_name`, `last_name`, `username`, `password`) VALUES ('$first_name', '$last_name', '$username', '$password') ";

        if($this->conn->query($sql)) {
            header('location: ../views');
            exit;
        }else{
            die("Error creating the user: " . $this->conn->error);
        }
    }

    // this function will login the user by checking if the username and password are correct
    public function login($request){
        $username = $request['username'];
        $password = $request['password'];
        
        $sql = "SELECT * FROM users WHERE username = '$username'";

        $result = $this->conn->query($sql);

        # cheak the username
        if($result->num_rows == 1) {
            # cheak if the password is correct
            $user = $result->fetch_assoc();

            if(password_verify($password, $user['password'])) {
                # create session variables for future use
                session_start();
                $_SESSION['id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['full_name'] = $user['first_name'] . ' ' . $user['last_name'];

                header('location: ../views/dashboard.php');
                exit;
            }else {
                die("Incorrect password.");
            }
        }else {
            die("Username not found.");
        }
    }


    // this function will logout the user by destroying the session
    public function logout(){
        session_start(); // start the session to access the session variables
        session_unset(); // remove all session variables
        session_destroy(); // destroy the session
        header('location: ../views'); // redirect to the login page
        exit;
    }

    // this function will get all the users from the database
    public function getAllUsers(){
        $sql= "SELECT id, first_name, last_name, username, photo FROM users";

        // $result = 3
        if($result = $this->conn->query($sql)) {
            return $result;
        }else {
            die("Error retrieving users: " . $this->conn->error);
        }
    }

    // this function will get a single user by id from the database
    public function getUser($id) {
        $sql = "SELECT * FROM users WHERE id = $id";

        if ($result = $this->conn->query($sql)) {
            return $result->fetch_assoc();
        }else{
            die("Error retrieving user: " . $this->conn->error);
        }
    }

    // this function will update te user data in the database
    public function update($request, $file) {
        session_start();
        $id = $_SESSION['id'];
        $first_name = $request ['first_name'];
        $last_name = $request['last_name'];
        $username = $request['username'];
        $photo = $file['photo']['name'];
        $tmp_name = $file['photo']['tmp_name'];

        $sql = "UPDATE users SET first_name = '$first_name', last_name = '$last_name', username = '$username' WHERE id = $id";

        if($this->conn->query($sql)) {
            $_SESSION['username'] = $username;
            $_SESSION['full_name'] = $first_name . ' ' . $last_name;
        
            // if there is an uploaded photo, save it to the db and save the file images folder
            if($photo) {
                $sql = "UPDATE users SET photo = '$photo' WHERE id = $id";
                $destination = "../assets/images/$photo";

                // save the image name to db
                if($this->conn->query($sql)) {
                    // save the file to images folder
                    if (move_uploaded_file($tmp_name, $destination)) {
                        header('location: ../views/dashboard.php');
                        exit;
                    } else {
                        die("Error uploading photo.");
                    }
                    
                } else {
                    die("Error updating user photo: " . $this->conn->error);
                }
            }
            header('location: ../views/dashboard.php');
            exit;
        } else {
            die ("Error updating user: " . $this->conn->error);
        }
    }

    // this function will delete the user froom the database
    public function delete() {
        session_start();
        $id = $_SESSION['id'];

        $sql = "DELETE FROM users WHERE id = $id";

        if ($this->conn->query($sql)) {
            $this->logout();
        } else {
            die("Error deleting user: " . $this->conn->error);
        }
    }
}

?>