<?php



    class Pages extends Controller{
        public $userModel;
        public function __construct()
        {

        }
        public function index() {

            $data = [
                'title' => 'You Welcomee to Our Website',

            ];

            
            $this->view('pages/index' , $data );
        }

    
        
        
        public function registration(){
           if(isset($_POST['addRegister'])){
            $userId = uniqid();
            $username = $_POST['username'];
            $pw = $_POST['pw'];
            $email = $_POST['email'];

            $userToAdd = new AppUser();
            $userToAdd->setUserId($userId);
            $userToAdd->setUsername($username);
            $userToAdd->setPw($pw);
            $userToAdd->setEmail($email);


            $role = new Role();
            $role->setRoleName("autuer");


            $roleOfUser = new RoleOfUser();
            $roleOfUser->setUser($userToAdd);
            $roleOfUser->setRole($role);
            $SecurityService = new SecurityServiceImp();
            $roleOfUserService = new RoleOfUserServicesImp();

            try{
                $SecurityService->register($userToAdd);
                $roleOfUserService->addRoleOfUser($roleOfUser); 
                header("Location:". URLROOT ."/pages/login"); 
            }catch(PDOException $e){
                die($e->getMessage());
            }

           }
            $this->view('pages/registration');
        }


        public function login() {
            if (isset($_POST["login"])) {
                $username = $_POST["username"];
                $password = $_POST["pw"]; 
        
                $logging = new AppUser();
                $logging->setUsername($username);
                $logging->setPw($password);
        
                $securityService = new SecurityServiceImp();
        
                try {
                    $loggingUserData = $securityService->login($logging);
                    if ($loggingUserData) {
                        
                        $_SESSION["username"] = $username;
                        $_SESSION["userId"] = $loggingUserData->userId;
        
                        $role = $securityService->checkForRole($loggingUserData->userId);
                        $_SESSION["roleName"] = $role->roleName;
        
                        if ($role->roleName == "autuer") {
                            $_SESSION["roleName"] = "autuer";
                            header("Location:". URLROOT . "/customer/home");

                        }else if($role->roleName == "admin") {
                            $_SESSION["roleName"] = "admin";
                            header("Location:" . URLROOT . "/admin/admin");
                            exit();
                        }
                    }
                } catch (PDOException $e) {
                    die($e->getMessage());
                }
            }
            $this->view('pages/login');
        }

    }

?>