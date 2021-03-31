<?php

namespace App\Http\Controllers;
use session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Mail;
use App\Mail\InvitationMail;
use App\Mail\VerifiyEmail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
class UserController extends Controller
{
  public function signupInvitation(){

  	$email = $_REQUEST['email'];
	 $details = [
        'email' => base64_encode($email),
    ];

    $emailResponse = Mail::to($email)->send(new InvitationMail($details));

    echo json_encode($emailResponse);exit;

    //return redirect()->back()->with('message', 'Check your Email Please!');
  }

  public function createAccount(Request $request){
	  	/*$validatedData = $request->validate([
	        'user_name' => 'required|unique:users',
	         
	    ]);*/

	    $validator = Validator::make($request->all(), [
            'user_name' => 'required|unique:users|min:4|max:20',
            'password' => 'required',

        ]);

	  	$userName = $_REQUEST['user_name'];
	  	$password = $_REQUEST['password'];

	  	$user = new User([
	  		'user_name' => $_REQUEST['user_name'],
	  		'password'  => Hash::make($_REQUEST['password']),
	  		'registered_at'=>date("Y-m-d H:i:s"),
	  		'created_at'=>date("Y-m-d H:i:s"),
	  	]);

	  	if ($validator->fails()) {
	  		$msg = "";
	  		foreach ($validator->errors()->all() as $error){
	  			$msg .= "\n".$error;
	  		}

	  	}else if($user->save()){
	  		$msg = "Account created successfully";
	  	}else{
	  		$msg = "Problem, try again";
	  	}
	  	echo $msg;
  	}


  	public function userLogin(Request $request)
    {
       $validator = Validator::make($request->all(), [
            'user_name' => 'required',
            'password' => 'required',

        ]);


        $credentials = $request->except(['_token']);
        $userName = $_REQUEST['user_name'];
        $user = User::where('user_name',$request->user_name)->first();

        $email = $user->email;
  	    if (!empty($user)){

  	    	// generate here rnadom number
  	    	$number = mt_rand(10000000, 99999999);	
  	    	$user->verifiy_code = $number;
    	   	$user->update();
  	    	// send here email
  	    	$emailResponse = Mail::to($email)->send(new VerifiyEmail($number));

  	    	
    	   
  	    	
  	    	
  	    	$msg = "Check your email, verification code has been sent.";
  	  
        }/*else if(auth()->attempt($credentials)) {
   			 $msg = "Logged in successfully.";
        }*/else{
             $msg = "Problem, try again.";
        }
        echo $msg;
    }


    public function userVerify(Request $request)
    {
       $validator = Validator::make($request->all(), [
            'verifiy_code' => 'required',

        ]);
 
        $user = User::where('verifiy_code',$request->verifiy_code)->first();

        $credentials = array();
        if (!empty($user)){
        	$credentials['user_name'] = $user->user_name;
        	// $credentials['password'] = '12345';
        }

        if(Auth::loginUsingId($user->id)/*auth()->attempt($credentials)*/) {
        	$user->verifiy_code = '';
        	$user->update();
   			 $msg = "Logged in successfully.";
        }else{
             $msg = "Problem, try again.";
        }
        echo $msg;
    } 

    public function logout()
    {
        \Auth::logout();

        echo "Logged out successfully.";
    }

  	public function userUpdateAccount(Request $request){
  		error_reporting(0);
  		$userName = $request['user_name'];
	  	
  		$user = User::where('user_name',$userName)->first();

  	    if (!Auth::check()) {
  	    	$msg = "You are not logged in, log in and then try.";
  		}else if (!empty($user)){
  			$name     = $request['name'];
  			$avatar   = $request['avatar'];
  			$email    = $request['email'];
  			$password = $request['password'];
  			$userRole = $request['user_role'];
  			$updatedAt = date("Y-m-d H:i:s");

  			$user->name = $name;
  			$user->avatar = $avatar;
  			$user->email = $email;
  			if(!empty($password)){
  				$user->password = Hash::make($password);
  			}
  			$user->user_role = $userRole;
  			$user->updated_at = $updatedAt;
	  		if($user->update()){
	  			 $msg = "Updated successfully.";
	  		}else{
	  			$msg = "Problem, try again.";
	  		}
  			
  		}else{
  			$msg = "User with this record not found.";
  		}
  		echo $msg;

  	}
}
