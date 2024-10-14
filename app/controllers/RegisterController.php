<?php 

class RegisterController {

    public function index() {
        header("HTTP/1.0 200 OK");
        render("register");
    }

    private function isUniqueEmail(string $email) : bool {
        $exists = UserRepository::getUserByEmail($email);
        if ($exists) {
            return false;
        }
        return true;
    }
    

    private function registerUser(string $username, string $email, string $password, int $role=1, bool $redirect=true) : string {
        if (!empty($username) && !empty($email) && !empty($password)) {
            // Ensure that the email is unique. 
            $error = null;

            if (!$this->isUniqueEmail($email)) {
                if ($redirect){
                    header("HTTP/1.0 400 Bad Request");
                    header('Location: /register?error=Email already exists');
                    die();
                }

                return "Email already exists";
            }

            // Ensure the password matches requirements
            if (!$this->IsStrongPassword($password)) {
                if ($redirect){
                    header("HTTP/1.0 400 Bad Request");
                    header('Location: /register?error=Your password is not secure enough. Try again.');
                    die();
                }

                return "Password is not strong enough!";
            }

            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);  

            // Store the user information in the database
            $store_result  = UserRepository::createUser($username, $email, $hashed_password, $role);
            
            // On successful storage, redirect to login page

            if (!$store_result) {
                if ($redirect){
                    header("HTTP/1.0 500 Internal Server Error");
                    header("Location: /500?error=Could not register you at this time.");
                    die();
                }

                return "Could not register user at this time.";
            }
            
            if ($redirect) {
                header("HTTP/1.0 301 OK");
                header('Location: /login', true);
                die();
            }

            return "";
            die();
        }
        else {
            header("HTTP/1.0 400 Bad Request");
            echo json_encode(["error" => "Bad Request. Missing data."]);
            die();
        }
    }

    public function handleSubmission() {
        if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password'])) {
            // Ensure each one has a value. In case XSS
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            $this->registerUser($username, $email, $password);

        }

        else {
            header("HTTP/1.0 400 Bad Request");
            echo json_encode(["error" => "Bad Request"]);
            die();
        }
    }

    public function adminAddNewUser() {
        if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['role'])) {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $role = $_POST['role'];

            if (!empty($username) && !empty($email) && !empty($password) && !empty($role)) {
                
                $error = $this->registerUser($username, $email, $password, $role, false);
                if (!empty($error)) {
                    header("HTTP/1.0 400 Bad Request");
                    header("Location: /dashboard/admin?action=addUser&error=$error");
                    die();
                } else{
                    header("HTTP/1.0 301 OK");
                    header('Location: /dashboard/admin?action=viewUsers');
                    die();
                }
            } else {
                header("HTTP/1.0 400 Bad Request");
                header('Location: /dashboard/admin?action=addUser&error=Bad Request. Empty data.');
                die();
            }
        }

        else {
            header("HTTP/1.0 400 Bad Request");
            header('Location: /dashboard/admin?action=addUser&error=Bad Request. Missing data.');
            die();
        }
    }

    public function GetNISTNumBits($password, $repeatcalc = false)
    {
        $y = strlen($password);
        if ($repeatcalc)
        {
            // Variant on NIST rules to reduce long sequences of repeated characters.
            $result = 0;
            $charmult = array_fill(0, 256, 1);
            for ($x = 0; $x < $y; $x++)
            {
                $tempchr = ord(substr($password, $x, 1));
                if ($x > 19)  $result += $charmult[$tempchr];
                else if ($x > 7)  $result += $charmult[$tempchr] * 1.5;
                else if ($x > 0)  $result += $charmult[$tempchr] * 2;
                else  $result += 4;

                $charmult[$tempchr] *= 0.75;
            }

            return $result;
        }
        else
        {
            if ($y > 20)  return 4 + (7 * 2) + (12 * 1.5) + $y - 20;
            if ($y > 8)  return 4 + (7 * 2) + (($y - 8) * 1.5);
            if ($y > 1)  return 4 + (($y - 1) * 2);

            return ($y == 1 ? 4 : 0);
        }
    }

    public function IsStrongPassword($password, $minbits = 18, $usedict = false, $minwordlen = 4)
    {
        // NIST password strength rules allow up to 6 extra bits for mixed case and non-alphabetic.
        $upper = false;
        $lower = false;
        $numeric = false;
        $other = false;
        $space = false;
        $y = strlen($password);
        for ($x = 0; $x < $y; $x++)
        {
            $tempchr = ord(substr($password, $x, 1));
            if ($tempchr >= ord("A") && $tempchr <= ord("Z"))  $upper = true;
            else if ($tempchr >= ord("a") && $tempchr <= ord("z"))  $lower = true;
            else if ($tempchr >= ord("0") && $tempchr <= ord("9"))  $numeric = true;
            else if ($tempchr == ord(" "))  $space = true;
            else  $other = true;
        }
        $extrabits = ($upper && $lower && $other ? ($numeric ? 6 : 5) : ($numeric && !$upper && !$lower ? ($other ? -2 : -6) : 0));
        if (!$space)  $extrabits -= 2;
        else if (count(explode(" ", preg_replace('/\s+/', " ", $password))) > 3)  $extrabits++;
        $result = $this->GetNISTNumBits($password, true) + $extrabits;

        $password = strtolower($password);
        $revpassword = strrev($password);
        $numbits = $this->GetNISTNumBits($password) + $extrabits;
        if ($result > $numbits)  $result = $numbits;

        // Remove QWERTY strings.
        $qwertystrs = array(
            "1234567890-qwertyuiopasdfghjkl;zxcvbnm,./",
            "1qaz2wsx3edc4rfv5tgb6yhn7ujm8ik,9ol.0p;/-['=]:?_{\"+}",
            "1qaz2wsx3edc4rfv5tgb6yhn7ujm8ik9ol0p",
            "qazwsxedcrfvtgbyhnujmik,ol.p;/-['=]:?_{\"+}",
            "qazwsxedcrfvtgbyhnujmikolp",
            "]\"/=[;.-pl,0okm9ijn8uhb7ygv6tfc5rdx4esz3wa2q1",
            "pl0okm9ijn8uhb7ygv6tfc5rdx4esz3wa2q1",
            "]\"/[;.pl,okmijnuhbygvtfcrdxeszwaq",
            "plokmijnuhbygvtfcrdxeszwaq",
            "014725836914702583697894561230258/369*+-*/",
            "abcdefghijklmnopqrstuvwxyz"
        );
        foreach ($qwertystrs as $qwertystr)
        {
            $qpassword = $password;
            $qrevpassword = $revpassword;
            $z = 6;
            do
            {
                $y = strlen($qwertystr) - $z;
                for ($x = 0; $x < $y; $x++)
                {
                    $str = substr($qwertystr, $x, $z);
                    $qpassword = str_replace($str, "*", $qpassword);
                    $qrevpassword = str_replace($str, "*", $qrevpassword);
                }

                $z--;
            } while ($z > 2);

            $numbits = $this->GetNISTNumBits($qpassword) + $extrabits;
            if ($result > $numbits)  $result = $numbits;
            $numbits = $this->GetNISTNumBits($qrevpassword) + $extrabits;
            if ($result > $numbits)  $result = $numbits;

            if ($result < $minbits)  return false;
        }

        if ($usedict && $result >= $minbits)
        {
            $passwords = array();

            // Add keyboard shifting password variants.
            $keyboardmap_down_noshift = array(
                "z" => "", "x" => "", "c" => "", "v" => "", "b" => "", "n" => "", "m" => "", "," => "", "." => "", "/" => "", "<" => "", ">" => "", "?" => ""
            );
            if ($password == str_replace(array_keys($keyboardmap_down_noshift), array_values($keyboardmap_down_noshift), $password))
            {
                $keyboardmap_downright = array(
                    "a" => "z",
                    "q" => "a",
                    "1" => "q",
                    "s" => "x",
                    "w" => "s",
                    "2" => "w",
                    "d" => "c",
                    "e" => "d",
                    "3" => "e",
                    "f" => "v",
                    "r" => "f",
                    "4" => "r",
                    "g" => "b",
                    "t" => "g",
                    "5" => "t",
                    "h" => "n",
                    "y" => "h",
                    "6" => "y",
                    "j" => "m",
                    "u" => "j",
                    "7" => "u",
                    "i" => "k",
                    "8" => "i",
                    "o" => "l",
                    "9" => "o",
                    "0" => "p",
                );

                $keyboardmap_downleft = array(
                    "2" => "q",
                    "w" => "a",
                    "3" => "w",
                    "s" => "z",
                    "e" => "s",
                    "4" => "e",
                    "d" => "x",
                    "r" => "d",
                    "5" => "r",
                    "f" => "c",
                    "t" => "f",
                    "6" => "t",
                    "g" => "v",
                    "y" => "g",
                    "7" => "y",
                    "h" => "b",
                    "u" => "h",
                    "8" => "u",
                    "j" => "n",
                    "i" => "j",
                    "9" => "i",
                    "k" => "m",
                    "o" => "k",
                    "0" => "o",
                    "p" => "l",
                    "-" => "p",
                );

                $password2 = str_replace(array_keys($keyboardmap_downright), array_values($keyboardmap_downright), $password);
                $passwords[] = $password2;
                $passwords[] = strrev($password2);

                $password2 = str_replace(array_keys($keyboardmap_downleft), array_values($keyboardmap_downleft), $password);
                $passwords[] = $password2;
                $passwords[] = strrev($password2);
            }

            // Deal with LEET-Speak substitutions.
            $leetspeakmap = array(
                "@" => "a",
                "!" => "i",
                "$" => "s",
                "1" => "i",
                "2" => "z",
                "3" => "e",
                "4" => "a",
                "5" => "s",
                "6" => "g",
                "7" => "t",
                "8" => "b",
                "9" => "g",
                "0" => "o"
            );

            $password2 = str_replace(array_keys($leetspeakmap), array_values($leetspeakmap), $password);
            $passwords[] = $password2;
            $passwords[] = strrev($password2);

            $leetspeakmap["1"] = "l";
            $password3 = str_replace(array_keys($leetspeakmap), array_values($leetspeakmap), $password);
            if ($password3 != $password2)
            {
                $passwords[] = $password3;
                $passwords[] = strrev($password3);
            }

            // Process the password, while looking for words in the dictionary.
            $a = ord("a");
            $z = ord("z");
            $data = file_get_contents(SSO_ROOT_PATH . "/" . SSO_SUPPORT_PATH . "/dictionary.txt");
            foreach ($passwords as $num => $password)
            {
                $y = strlen($password);
                for ($x = 0; $x < $y; $x++)
                {
                    $tempchr = ord(substr($password, $x, 1));
                    if ($tempchr >= $a && $tempchr <= $z)
                    {
                        for ($x2 = $x + 1; $x2 < $y; $x2++)
                        {
                            $tempchr = ord(substr($password, $x2, 1));
                            if ($tempchr < $a || $tempchr > $z)  break;
                        }

                        $found = false;
                        while (!$found && $x2 - $x >= $minwordlen)
                        {
                            $word = "/\\n" . substr($password, $x, $minwordlen);
                            for ($x3 = $x + $minwordlen; $x3 < $x2; $x3++)  $word .= "(" . $password[$x3];
                            for ($x3 = $x + $minwordlen; $x3 < $x2; $x3++)  $word .= ")?";
                            $word .= "\\n/";

                            preg_match_all($word, $data, $matches);
                            if (!count($matches[0]))
                            {
                                $password[$x] = "*";
                                $x++;
                                $numbits = $this->GetNISTNumBits(substr($password, 0, $x)) + $extrabits;
                                if ($numbits >= $minbits)  $found = true;
                            }
                            else
                            {
                                foreach ($matches[0] as $match)
                                {
                                    $password2 = str_replace(trim($match), "*", $password);
                                    $numbits = $this->GetNISTNumBits($password2) + $extrabits;
                                    if ($result > $numbits)  $result = $numbits;

                                    if ($result < $minbits)  return false;
                                }

                                $found = true;
                            }
                        }

                        if ($found)  break;

                        $x = $x2 - 1;
                    }
                }
            }
        }

        return $result >= $minbits;
    }
}