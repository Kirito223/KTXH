<?php

namespace App\Http\Controllers\Marketing;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Ultils\MailUltils;

class MarketingController extends Controller
{
    #Send mail
    public function viewSendmail()
    {
        return view('marketing\sendmail');
    }
    public function sendMail(Request $request)
    {
        $MailUltis = new MailUltils();
        $mail = $MailUltis->IntanceMail();
        //Recipients
        $mail->setFrom('hacker11357@gmail.com', 'Mailer');
        $mail->addAddress('xuanductq1994@gmail.com', 'Joe User');     // Add a recipient
        // Content
        $mail->isHTML(true);                                  // Set email format to HTML
        $mail->Subject = $request->name;
        $mail->Body    = $request->content;
        $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
        $send = $mail->send();
        return response()->json(['code' => $send]);
    }

    public function SendSMS(Request $request)
    {
        $basic  = new \Nexmo\Client\Credentials\Basic('a7720afa', 'YFKUliat3GCI5dAz');
        $client = new \Nexmo\Client($basic);

        $message = $client->message()->send([
            'to' => '84365236082',
            'from' => 'Vonage SMS API',
            'text' => 'Hello from Vonage'
        ]);
        return response()->json($message);
    }
}
