<?php

namespace App\Http\Controllers;

use App\Mail\confirmemail;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Log;
use App\Models\feesstructure;
use App\Models\news;
use App\Models\transactions;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Paynow\Payments\Paynow;
use Twilio\Rest\Client;
require_once(base_path('vendor/autoload.php'));
 

use function Termwind\render;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function twillie($message,...$arguments){
        $sid    = "AC838c6ff7a1664215d97cfc2e8ebb6d30";
        $token  = "581c28be261aba963f30bbf2ee042e85";
        $twilio = new Client($sid, $token);

        foreach($arguments as $arguments){
            

        }

        $message = $twilio->messages
        ->create("whatsapp:+263786103016", // to
            array(
            "from" => "whatsapp:+14155238886",
            "body" => $message
            )
        );

    }
    public function twillie_with_media($message,$arguments){
        $sid    = "AC838c6ff7a1664215d97cfc2e8ebb6d30";
        $token  = "581c28be261aba963f30bbf2ee042e85";
        $twilio = new Client($sid, $token);
 

        $message = $twilio->messages
        ->create("whatsapp:+263786103016", // to
            array(
            "from" => "whatsapp:+14155238886",
            "body" => $message,
            "mediaUrl"=>$arguments
            )
        );

    }


    public function webhooks(Request $request){
            // Extract message details
    $message = $request->input('Body');
    $from = $request->input('From');
    $to = $request->input('To');

    if ($message=='hie'){
        $this->twillie($message);
        dump($message);
        dump($from);
        dump($to);
    }
    
 
    return response()->json(['status' => 'success','message'=>$message,'from'=>$from,'to'=>$to]);

    }
 


    public function ecocash(){
     

        $paynow = new Paynow(
            '16238',
            '6e31a47e-3622-40a9-8378-57673a40e84a',
            'http://676a-41-221-159-214.ngrok-free.app/smallwebhooks',
            'http://676a-41-221-159-214.ngrok-free.app/smallwebhooks',
        );

 

        $payment = $paynow->createPayment('Dacs Payment', 'chirovemunyaradzi@gmail.com');

        $payment->add('Sadza and Beans', Session::get('price'));

        $response = $paynow->sendMobile($payment,Session::get('mobileNumberDacs'),'ecocash');


        if($response->success()) {
            

            $pollUrl = $response->pollUrl();
            $status = $paynow->pollTransaction($pollUrl);


            $transaction= new transactions();


            $a=Session::get('platDacs');
            $b=Session::get('regNumberDacs');
            $c=Session::get('methodPaymentDacs');
            $d=Session::get('mobileNumberDacs');
            dump('***********');
            dump($a);
            dump($b);
            dump($pollUrl);
            dump($status);
            dump('***********');


            
          
           


            $transaction->regnumber=Session::get('regNumberDacs');
            $transaction->ecocashnumber=Session::get('mobileNumberDacs');
            $transaction->plate=Session::get('platDacs');
            $transaction->price=Session::get('price');
            $transaction->status=$status;
            $transaction->pollurl=$pollUrl;
            $transaction->save();

            dump('done');
            return response('Hello, world!', 200);


        }
        else{
            return response('noo', 200);

        }
     
        
    }
    public function smallwebhook(Request $request){
          // Get the webhook payload
          $payload = $request->all();

          // Echo the payload to the console
          echo json_encode($payload);

          dump($request);
  
          // Return a response to the webhook sender
          return response()->json(['message' => 'Webhook received']);
        // return ['status'=>'webhook received'];
        
    }





    public function sendmails($email,$data){
        // try{

            Mail::to('munyaradzichirove@gmail.com')
            ->send(new confirmemail($data));
           

        // } 
        // catch(\Throwable $th){
        //     Session()->flash("fail","Please Check Your Internet and Try again!");
        // }
    }
    
  

    public $price1=3000;
    public $price2=3000*2;
    public $price3=3000*2.8;
    public $price4=3000*4.5;

    public function webhook(Request $request){
        $value = $request->input('Body');
        $from = $request->input('From');
        $to = $request->input('To');

        dump(Session::get('step8'));
        dump($value);
   
        if (strtolower($value) =="hie" or strtolower($value)=='hi' or strtolower($value)=="hello") {
            Session::flush();
            Session::put('step1', true);
            $arrayString = trim(
                "Welcome to Uz Self Service\n\nPlease choose action from below:\n\n\n1...Login\n2...Register\n3...Fees Structure\n4...View Results\n5...School Dates\n6...Search Lecturer\n7...Department Details\n8...Dacs\n9...Book / View Free Classes\n10...Campus News / Updates\n11...Paid Promotions\n12...Emhare Link\n13...University Website\n14...Developer Details");
            

            
            return $this->twillie($arrayString);
                
        }
        elseif (Session::get('step1')==true and $value==1 and  Session::get('step8')==null and Session::get('step10')==null) {
            Session::flush();
            return $this->twillie("ddddd111");
           
        }

        elseif (Session::get('step1')==true and  $value==2 and  Session::get('step8')==null and Session::get('step10')==null) {
            Session::put('step2ai', true);
            return $this->twillie('Please enter your Reg number here');

        } 

        elseif (Session::get('step1')==true and Session::get('step2')==null and Session::get('step2ai')==true) {
            Session::put('step2', true);
            return $this->twillie('Please enter the email here');

        } 
        elseif (Session::get('step1')==true and Session::get('step2')==true and Session::get('step2a')==null ) {


            $pattern = '/^[a-zA-Z0-9_]+\.[a-zA-Z0-9_]+@students\.uz\.ac\.zw$/';
            

            if (preg_match($pattern,  $value) and ! DB::table('users')->where('email', $value)->exists()) {
                Session::put('step2a', true);
                Session::put('email', $value);
                return $this->twillie("Please enter password here");
       
            } elseif(DB::table('users')->where('email', $value)->exists()){
                return $this->twillie("Invalid Email, Already Registered. Please re-enter email");
 
            
            } else{
                return $this->twillie("Invalid Email, Please re-enter email");

            }

        }
        elseif (Session::get('step1')==true and Session::get('step2')==true and Session::get('step2a')==true and Session::get('step2b')==null  and  Session::get('step8')==null and Session::get('step10')==null) {
            Session::put('step2b', true);
            Session::put('password1',  $value);
            
            
            // if(DB->table('users')->where('id'))
            return $this->twillie("Please re-enter password");

        }
        elseif (Session::get('step1')==true and Session::get('step2')==true and Session::get('step2a')==true and Session::get('step2b')==true and Session::get('step2c')==null ) {
           
            $password2= $value;

            if($password2==Session::get('password1')){
                Session::put('step2c', true);
                $numbers = "";

                for ($i = 0; $i < 6; $i++) {
                    $randomNumber = rand(1, 9); 
                    $numbers=$numbers.$randomNumber;
                }
                // Session::flush();
                
                $data=[
                    'name'=>"munya",
                    'email'=>"email",
                    'subject'=>"subject",
                    'message'=>$numbers,
                
                ];
              

                if(Mail::to('munyaradzichirove@gmail.com')
                ->send(new confirmemail($data))){
                    Session::put('code',$numbers);
                    Session::put('trials',3);
                    $this->twillie("Please enter the confirmation code sent to your email to confirm. You have 3 trial!");
                }
                else{ 
                    $this->twillie("Something went wrong. Please check your email and try again!");

                }

            }
            else{
                Session::put('step2b', null);

                return $this->twillie("Password not matched. Re-enter password");

            }
          
        }
        elseif (Session::get('step1')==true and Session::get('step2')==true and Session::get('step2a')==true and Session::get('step2b')==true and Session::get('step2c')==true and Session::get('step2d')==null ) {
       
            if (Session::get('code')== $value){

                $user = new User();
                $user->email = Session::get('email');
                $user->email_verified = false;
                $user->password = bcrypt(Session::get('password1'));
                
                // Save the user to the database
                $user->save();
                Session::flush();
                return $this->twillie("Confirmed. You account has been created!");
            }
            else{
                Session::put('step2d',null);
                $val=session('trials') - 1;
                Session::put('trials',$val);
                   
                return $this->twillie("Code error.Please enter again and you have ".Session::get('trials')." trials!");

            }

        }


        elseif (Session::get('step1')==true and  $value==3  and  Session::get('step8')==null and Session::get('step10')==null) {
            $structure=DB::table('feesstructures')->where('id',1)->first();
            // $structure=feesstructure::where('id',1)->first();
            Session::flush();
            return $this->twillie('The fees structure is as follows+pdf:'.$structure->description);


            } 
        elseif (Session::get('step1')==true and  $value==4  and  Session::get('step8')==null and Session::get('step10')==null) {
            return ['step1'=>true,
                    'value'=>4];

        } 
  
        elseif (Session::get('step1')==true and  $value==5 and  Session::get('step8')==null and Session::get('step10')==null) {

            $string=DB::table('schooldates')->first();
            Session::flush();
            $media = public_path('sample.pdf');
            return $this->twillie($string->schedule);
             
        } 
        elseif (Session::get('step1')==true and  $value==6  and  Session::get('step8')==null and Session::get('step10')==null) {
            return ['step1'=>true,
                    'value'=>6];
     
        } 
                        
        elseif (Session::get('step1')==true and  $value==7  and  Session::get('step8')==null and Session::get('step10')==null) {
            return ['step1'=>true,
                    'value'=>7];
            
        } 
        // elseif (Session::get('step1')==true and  $value==2) {
        //     return ['step1'=>true,
        //             'value'=>2];
    
                        
        // } 
        elseif (Session::get('step1')==true and  $value==8 and  Session::get('step8')==null and Session::get('step10')==null) {
            Session::put('step8',true);
            return $this->twillie("Choose Action Below for Dacs:\n\n1...Dacs Topup\n2...View Balance\n3...Plate Transfer");

    
        } 
        elseif (Session::get('step1')==true and Session::get('step8')==true and Session::get('step8a')==null and $value==1) {
        
            Session::put('step8a',true);


            return $this->twillie("Choose Desired Plates from Below:\n\n1...1 plate at {$this->price1}\n2...2 Plates at {$this->price2}\n3...3 Plates at {$this->price3}\n4...5 Plates at {$this->price4}");

    
        } 
        elseif (Session::get('step1')==true and Session::get('step8')==true and Session::get('step8a')==true and Session::get('step8b')==null) {
            Session::put('retry',true);
            if($value==1){
                Session::put('price',$this->price1);
                Session::put('plate',true);
                Session::put('step8b',true);
                Session::put('platDacs',"1 plate at {$this->price1}");
                return $this->twillie("Choose Payment Method Below:\n\n1...Ecocash\n2...Innbucks");

            }
            elseif($value==2){
                Session::put('price',$this->price2);
                Session::put('plate',true);
                Session::put('step8b',true);
                Session::put('platDacs',"2 plates at {$this->price2}");
                return $this->twillie("Choose Payment Method Below:\n\n1...Ecocash\n2...Innbucks");

            }
            elseif($value==3){
                Session::put('price',$this->price3);
                Session::put('plate',true);
                Session::put('step8b',true);
                Session::put('platDacs',"3 plates at {$this->price3}");
                return $this->twillie("Choose Payment Method Below:\n\n1...Ecocash\n2...Innbucks");

            }
            elseif($value==4){
                Session::put('price',$this->price4);
                Session::put('plate',true);
                Session::put('step8b',true);
                Session::put('platDacs',"5 plates at {$this->price4}");
                return $this->twillie("Choose Payment Method Below:\n\n1...Ecocash\n2...Innbucks");

            }
            else{
               
                return $this->twillie("Invalid Option\nPlease choose again\n\n1...1 plate at {$this->price1}\n2...2 Plates at {$this->price2}\n3...3 Plates at {$this->price3}\n4... Plates at {$this->price4}");

            }
           

        } 
        elseif (Session::get('step1')==true and Session::get('step8')==true and Session::get('step8a')==true and Session::get('step8b')==true and Session::get('step8c')==false ) {
            if($value==1){
                Session::put('step8c',true);
                Session::put('methodPaymentDacs',"Ecocash");
                return $this->twillie("Please enter number you wish to use for ecocash");

            }
            elseif($value==2){
                Session::put('step8c',true);
                Session::flush();
                return $this->twillie("Inbucks unavailable right now. Try again later.");

            }
            else{
                return $this->twillie("Invalid, Please choose again:\n\n1...Ecocash\n2...Innbucks");

            }
           


        }
        elseif (Session::get('step1')==true and Session::get('step8')==true and Session::get('step8a')==true and Session::get('step8b')==true and Session::get('step8c')==true and Session::get('step8d')==null ) {
            $pattern = '/^(?:\+2637|07)[78]\d{7}$/';

            if (preg_match($pattern, $value)) {
                Session::put('mobileNumberDacs',$value);
                Session::put('step8d',true);
                return $this->twillie( "Please enter your Reg Number");
            } else {
                return $this->twillie("Invalid mobile number, Please re-enter ecocash number for Payment");
            }



        }
        elseif (Session::get('step1')==true and Session::get('step8')==true and Session::get('step8a')==true and Session::get('step8b')==true and Session::get('step8c')==true and Session::get('step8d')==true and Session::get('step8e')==null) {
            $pattern = '/^(?:\+2637|07)[78]\d{7}$/';

            if (preg_match($pattern, $value)) {
                Session::put('step8e',true);
                Session::put('regNumberDacs',$value);
                $a=Session::get('platDacs');
                $b=Session::get('regNumberDacs');
                $c=Session::get('methodPaymentDacs');
                $d=Session::get('mobileNumberDacs');

                return $this->twillie( "Checkout Overview\n\nStudent Reg Number: {$b} \nPlate: {$a}\nPayment Method: {$c}\nEcocash Number: {$d}\n\nIf you are satisfied Proceed else Cancel:\n\n1...Proceed\n2...Cancel");
            } else {
                return $this->twillie("Invalid Reg Number, Please re-enter");
            }

        }
        elseif (Session::get('step1')==true and Session::get('step8')==true and Session::get('step8a')==true and Session::get('step8b')==true and Session::get('step8c')==true and Session::get('step8d')==true and Session::get('step8e')==true) {
           
                $d=Session::get('mobileNumberDacs');
                if ($value==1){
                $this->twillie( "Please check your phone with number: {$d} and enter ecocash password if you are satisified.");
                return $this->ecocash();

                }
                else{

                return $this->twillie( "None.");

                }

           
        }
        
        
    
        
    

        elseif (Session::get('step1')==true and  $value==9  and  Session::get('step8')==null and Session::get('step10')==null) {
            return ['step1'=>true,
                    'value'=>9];
    
    
        } 
        elseif (Session::get('step1')==true and  ($value==10 or $value=="00") and  Session::get('step8')==null  and Session::get('step10a')==null and Session::get('step10i')==null) {
            $allnews="----------------------------------------------------------------\n\nUNIVERSITY OF ZIMBABWE NEWS\n\n----------------------------------------------------------------\n\n     ******Headlines*****\n\n";
            $count=1;
            $map=array();;
          
            foreach(news::all() as $i){
                $allnews.=$count.")..".$i->headline.".\n\n";
                $map[$count] = $i->id;
                $count++;
            }
            
            dump($map['7']);
            Session::put("map",$map);
            dump($map);
  
            Session::put("step10",true);
            Session::put("step10i",true);
            return $this->twillie($allnews);
        } 

        elseif (Session::get('step1')==true and  Session::get('step8')==null and Session::get('step10')==true and Session::get('step10i')==true and Session::get('step10a')==null) {
            if (array_key_exists($value, Session::get("map"))){
                $news=news::where('id',Session::get("map")[$value])->first();
                dump($news);
                $extra="\n\nIf you wish to go back to headline, Please send 00";
                $detailedNews="----------------------------------------------------------------\n\nUNIVERSITY OF ZIMBABWE NEWS\n\n----------------------------------------------------------------\n\n".$news->headline."\n\n".$news->details."\n\nFor more info, please visit: https://fakeadress.co.zw".$extra;
                Session::put('step10i')==null;
                return $this->twillie($detailedNews);

            }
            else{
                return $this->twillie("no news");
            }
            
        }
      



        elseif (Session::get('step1')==true and  $value==11  and  Session::get('step8')==null and Session::get('step10')==null) {
            return $this->twillie("Paid Promotions Intialized\n\n1...Uz Tax\n2...Food\n3...External Accomodation\n4...External Accomodation Agents\n5...Technology/gadgets\n6...Other");    
        } 
        elseif (Session::get('step1')==true and  $value==12  and  Session::get('step8')==null and Session::get('step10')==null) {
            Session::flush();
            return $this->twillie('http://dev.emhare.uz.ac.zw/Users/login');
    
    
        } 
        elseif (Session::get('step1')==true and  $value==13  and  Session::get('step8')==null and Session::get('step10')==null) {
            return $this->twillie('Please visit the following website: www.uz.ac.zw/');
    
    
        } 
        elseif (Session::get('step1')==true and  $value==14  and  Session::get('step8')==null and Session::get('step10')==null) {
            Session::flush();
            return $this->twillie('Please visit the following website: https://mrchiroveonline.web.app');
    
    
        } 
        

        else {
            // dump('eslee');
            // return $this->twillie('Please type Hi to restart session');
        }
        


      
 

     

    }
}
   
