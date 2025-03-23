<?php

namespace App\Http\Controllers;

use App\Mail\CodeVerificationMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use ReCaptcha\ReCaptcha;

class VerificationController extends Controller
{

    /**
     * Sends a verification code to the specified user via email.
     *
     * This method checks if the user exists and, if they don't have an active 
     * verification code, generates a new one, hashes it, and saves it with a 
     * 10-minute expiration time. The code is then sent to the user's email.
     *
     * @param string $id The unique identifier of the user.
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\View\View
     */
    public function sendAuthCode(int $id)
    {
        $user = User::find($id);

        if (!$user) {
            return back()->withErrors(['error' => 'Usuario no encontrado.']);
        }

        if (!$user->code) {
            $verificationCode = strval(random_int(100000, 999999));
            $user->code_expires_at = now()->addMinutes(10);
            $user->code = Hash::make($verificationCode);
            $user->save();

            Mail::to($user->email)->send(new CodeVerificationMail($verificationCode, $user));
        }

        return view('verify_code', ['id' => $user->id]);
    }

    /**
     * Verifies the authentication code provided by the user.
     *
     * This method validates the input code, checks if the user exists, 
     * verifies if the code has expired, and authenticates the user if the code is correct.
     * If the verification is successful, the user is logged in, and the code is invalidated.
     * Otherwise, an error message is returned.
     *
     * @param \Illuminate\Http\Request $request The HTTP request containing the verification code.
     * @param string $id The user ID to verify the code against.
     * @return \Illuminate\Http\RedirectResponse Redirects the user based on verification success or failure.
     */
    public function verifyCode(Request $request, int $id)
    {
        $request->validate([
            'code' => 'required|string|min:6|max:6',
            'g-recaptcha-response' => 'required',
        ], [
            'code.required' => 'El código es obligatorio.',
            'code.numeric'=>'El código debe ser numerico',            
            'code.min' => 'El código debe tener exactamente 6 caracteres.',
            'code.max' => 'El código debe tener exactamente 6 caracteres.',
            'g-recaptcha-response.required' => 'Es necesario completar la verificación de CAPTCHA.',
        ]);
        
        $recaptcha = new ReCaptcha(config('captcha.secret'));
        $response = $recaptcha->verify($request->input('g-recaptcha-response'), $request->ip());        
        if (!$response->isSuccess()) {
            Log::info('entro');
            return redirect()->back()->withErrors(['errors' => 'Por favor, verifica que no eres un robot.']);
        }

        $user = User::find($id);

        if (!$user) {
            return back()->with('error', 'User not found.');
        }        

        if ($user->code_expires_at && now()->greaterThan($user->code_expires_at)) {
            $user->code = null;
            $user->code_expires_at = null;
            $user->save();

            return back()->withErrors(['errors' => 'El código de verificación ha expirado.']);
        }

        if (Hash::check($request->code, $user->code)) {
            Auth::login($user);

            $user->code = null;
            $user->code_expires_at = null;
            $user->save();

            return redirect('home')->with('success', 'Código verificado exitosamente.');
        }

        return back()->withErrors(['errors'=> 'Código de verificación inválido.']);
    }
}
