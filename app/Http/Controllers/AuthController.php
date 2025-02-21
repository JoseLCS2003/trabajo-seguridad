<?php

namespace App\Http\Controllers;

use App\Mail\CodeVerificationMail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Validation\Rules\Password;
use ReCaptcha\ReCaptcha;

class AuthController extends Controller
{

    /**
     * Returns the login view.
     * 
     * This method is responsible for rendering the login page view.
     * 
     * @return \Illuminate\View\View Returns the view for the login page.
     */
    public function loginView()
    {
        return view('auth.login');
    }

    /**
     * Returns the home view.
     * 
     * This method is responsible for rendering the home page view.
     * 
     * @return \Illuminate\View\View Returns the view for the home page.
     */
    public function homeView()
    {
        return view('home');
    }

    /**
     * Returns the registration view.
     * 
     * This method is responsible for rendering the registration page view.
     * 
     * @return \Illuminate\View\View Returns the view for the registration page.
     */
    public function registerView()
    {
        return view('auth.register');
    }

    /**
     * Creates a new user in the system.
     * 
     * This method handles the creation of a new user by validating the incoming request data,
     * sanitizing the input, hashing the password, and saving the user to the database.
     * Upon successful creation, the user is redirected to the login page with a success message.
     * 
     * @param \Illuminate\Http\Request $request The incoming HTTP request containing user data.
     * @return \Illuminate\Http\RedirectResponse Redirects to the login route with a status message.
     */
    public function createUser(Request $request)
    {
        $request->validate([
            'name' => ['required','max:50','min:3','string','regex:/^[A-Za-zÀ-ÖØ-öø-ÿ\s]+$/u'],
            'email' => 'required|max:60|email|unique:users',
            'password' => ['required', 'string', 'min:8', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_]).{8,}$/'],
            'g-recaptcha-response' => 'required',
        ], [
            'name.required' => 'El campo nombre es obligatorio.',
            'name.max' => 'El nombre no puede tener más de 50 caracteres.',
            'name.min' => 'El nombre debe tener al menos 3 caracteres.',
            'name.regex' => 'El nombre solo puede contener letras y espacios, sin números ni caracteres especiales.',
            'name.string' => 'El nombre debe ser una cadena de texto.',
            'email.required' => 'El campo correo electrónico es obligatorio.',
            'email.max' => 'El correo electrónico no puede tener más de 60 caracteres.',
            'email.email' => 'El correo electrónico debe ser válido.',
            'email.unique' => 'Este correo electrónico ya está registrado.',
            'password.required' => 'El campo contraseña es obligatorio.',
            'password.min' => 'La contraseña debe tener al menos 8 caracteres.',
            'password.regex' => 'La contraseña debe contener al menos una letra mayúscula, una letra minúscula, un número y un símbolo.',
            'g-recaptcha-response.required' => 'Es necesario completar la verificación de CAPTCHA.',
        ]);


        $recaptcha = new ReCaptcha(env('RECAPTCHA_SECRET_KEY'));
        $response = $recaptcha->verify($request->input('g-recaptcha-response'), $request->ip());
        if (!$response->isSuccess()) {
            return redirect()->back()->withErrors(['errors' => 'Por favor, verifica que no eres un robot.']);
        }

        $newUser = new User();
        $newUser->name = preg_replace('/\s+/', ' ', trim($request->name));
        $newUser->email = $request->email;
        $newUser->password = Hash::make($request->password);
        $newUser->save();

        return redirect()->route('login')->with('status', 'Usuario registrado exitosamente!');
    }

    /**
     * Handles the login process, including validation and CAPTCHA verification.
     *
     * This method validates the user's email, password, and CAPTCHA response. 
     * If the CAPTCHA is successful, it checks if the user's credentials are correct. 
     * If valid, a signed URL is generated and the user is redirected to it for further verification. 
     * If the credentials are incorrect or CAPTCHA fails, an error message is returned.
     *
     * @param \Illuminate\Http\Request $request The incoming request containing user input.
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
            'g-recaptcha-response' => 'required',
        ], [
            'email.required' => 'El campo de correo electrónico es obligatorio.',
            'email.email' => 'El correo electrónico debe ser válido.',
            'password.required' => 'El campo de contraseña es obligatorio.',
            'g-recaptcha-response.required' => 'Es necesario completar la verificación de CAPTCHA.',
        ]);

        $recaptcha = new ReCaptcha(env('RECAPTCHA_SECRET_KEY'));

        $response = $recaptcha->verify($request->input('g-recaptcha-response'), $request->ip());

        if (!$response->isSuccess()) {
            return redirect()->back()->withErrors(['errors' => 'Por favor, verifica que no eres un robot.']);
        }

        $user = User::where('email', $request->email)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            $signedUrl = URL::temporarySignedRoute(
                'verify.code',
                now()->addMinutes(30),
                ['id' => $user->id]
            );

            return redirect()->to($signedUrl);
        }

        return redirect()->back()->withErrors(['errors' => 'Las credenciales son incorrectas']);
    }

    /**
     * Handles user logout.
     * 
     * This method logs out the currently authenticated user, invalidates the session,
     * and regenerates the CSRF token to prevent session fixation attacks. After logging out,
     * the user is redirected to the login page.
     * 
     * @param \Illuminate\Http\Request $request The incoming HTTP request.
     * @return \Illuminate\Http\RedirectResponse Redirects to the login page after logout.
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
