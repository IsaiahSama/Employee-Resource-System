<?php 

require_once 'app/repositories/UserRepository.php';

class LoginController{

    public function index() {
        header("HTTP/1.0 200 OK");
        if (isset($params['error'])) {
            render('login');
        }
        else{
            render('login');
        }
    }

    private function password_verify(?User $user, string $password): bool {
        // Check if the given email exists in the database.

        if (!$user) {
            // If not, Return false.
            return false;
        }

        // Otherwise, get the password!
        $user_password = $user->getHashedPassword();

        // Compare the password!

        $password_match = password_verify($password, $user_password);

        // If the passwords match, return true.

        if ($password_match) {
            return true;
        }
        
        // Return false otherwise.
        return false;
        
    }

    public function handleSubmission() {
        // Logic for handling the submissions
        if (isset($_POST['email']) && isset($_POST['password'])) {
            $email = $_POST['email'];
            $password = $_POST['password'];

            if (!empty($email) && !empty($password)) {
                // Validate the password
                $user = UserRepository::getUserByEmail($email);
                if ($this->password_verify($user, $password)) {
                    // On successful login

                    // Store the user information in the session

                    SessionManager::set(SessionValues::USER_INFO->value, [
                        'id' => $user->getId(),
                        'username' => $user->getUsername(),
                        'auth_level' => $user->getAuthLevel()
                    ]);
                    
                    // redirect to Dashboard page
                    header("HTTP/1.0 301 OK");
                    header("Location: /dashboard/" . match($user->getAuthLevel()->value) {
                        Roles::ADMIN->value => 'admin',
                        Roles::MANAGER->value => 'manager',
                        Roles::EMPLOYEE->value => 'employee',
                        default => ''
                        }
                    );
                    die();
                }
                else {
                    header("HTTP/1.0 400 Bad Request");
                    header('Location: /login?error=Email or Password is invalid');
                    die();
                }
            }
            else {
                header("HTTP/1.0 400 Bad Request");
                echo json_encode(["error" => "Bad Request. No relevant data provided."]);
                die();
            }
        }
    }
}