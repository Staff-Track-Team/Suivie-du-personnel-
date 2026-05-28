<?php

namespace App\Http\Controllers;

use App\Models\Otp;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\OtpMail;

class AuthController extends Controller
{
    // ==========================================
    // LOGIQUE DE CONNEXION (LOGIN)
    // ==========================================

    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Vérification credentials sans connecter l'utilisateur
        if (Auth::validate($credentials)) {
            $user = User::where('email', $request->email)->first();

            // Vérifier les conditions d'accès (Compte activé)
            $accessCheck = $this->canUserLogin($user);

            if (!$accessCheck['can_login']) {
                return redirect()->back()
                    ->withErrors(['email' => $accessCheck['message']])
                    ->withInput();
            }
            
            // TEMPORAIRE : Connexion directe sans OTP pour le développement
            Auth::login($user);
            $request->session()->regenerate();
            return redirect()->intended('/dashboard')->with('success', 'Connexion réussie !');

            /* TODO: Décommenter cette partie plus tard pour réactiver l'OTP
            // Génération OTP Login
            $otpCode = strtoupper(Str::random(6));
            Otp::where('email', $user->email)->delete();
            Otp::create([
                'email' => $user->email,
                'code' => $otpCode,
                'expires_at' => Carbon::now()->addMinutes(10),
            ]);

            // Envoi Email OTP
            try {
                Mail::to($user->email)->send(new OtpMail($otpCode));
            } catch (\Exception $e) {
                // ECHEC ENVOI : On annule tout et on retourne une erreur
                return redirect()->back()->with('error', 'Impossible d\'envoyer l\'email de vérification. Veuillez vérifier votre connexion ou contacter l\'administrateur.');
            }

            // Stocker ID utilisateur temporairement en session pour verification
            $request->session()->put('login_user_id', $user->id);

            return redirect()->route('login.verify')->with('success', 'Identifiants corrects. Un code de vérification a été envoyé à votre email.');
            */
        }

        return back()->withErrors([
            'email' => 'Identifiants incorrects.',
        ])->onlyInput('email');
    }

    public function showLoginVerify()
    {
        if (!session()->has('login_user_id')) {
            return redirect()->route('login');
        }
        return view('auth.verify-login');
    }

    public function verifyLogin(Request $request)
    {
        $request->validate(['otp' => 'required|string']);

        $userId = session('login_user_id');
        if (!$userId) return redirect()->route('login');

        $user = User::findOrFail($userId);

        $otpRecord = Otp::where('email', $user->email)
                        ->where('code', $request->otp)
                        ->where('expires_at', '>', Carbon::now())
                        ->first();

        if ($otpRecord) {
            // OTP Valide -> Connexion Réele
            Auth::login($user);
            session()->forget('login_user_id');
            $otpRecord->delete();
            $request->session()->regenerate();

            return redirect()->intended('/dashboard')->with('success', 'Connexion réussie !');
        }

        return back()->withErrors(['otp' => 'Code OTP invalide ou expiré.']);
    }

    public function resendLoginOtp(Request $request)
    {
        $userId = session('login_user_id');
        if (!$userId) return redirect()->route('login');

        $user = User::findOrFail($userId);

        // Limite de renvoi (optionnel mais recommandé pour éviter le spam, 
        // ici on reste simple mais on pourrait check le temps depuis le dernier OTP)
        
        $otpCode = strtoupper(Str::random(6));
        Otp::where('email', $user->email)->delete();
        Otp::create([
            'email' => $user->email,
            'code' => $otpCode,
            'expires_at' => Carbon::now()->addMinutes(10),
        ]);

        try {
            Mail::to($user->email)->send(new OtpMail($otpCode));
        } catch (\Exception $e) {
            return back()->with('error', 'Impossible de renvoyer le code. Problème d\'envoi d\'email.');
        }

        return back()->with('success', 'Nouveau code envoyé !');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }

    // ==========================================
    // LOGIQUE MOT DE PASSE OUBLIÉ (FORGOT PASSWORD)
    // ==========================================

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendOtp(Request $request)
    {
        $request->validate(['email' => 'required|email|exists:users,email']);

        $user = User::where('email', $request->email)->first();

        // Vérifier les conditions d'accès
        $accessCheck = $this->canUserLogin($user);

        if (!$accessCheck['can_login']) {
            return redirect()->back()
                ->withErrors(['email' => $accessCheck['message']])
                ->withInput();
        }

        $otpCode = strtoupper(Str::random(6));
        
        Otp::where('email', $request->email)->delete();
        Otp::create([
            'email' => $request->email,
            'code' => $otpCode,
            'expires_at' => Carbon::now()->addMinutes(15),
        ]);

        // Envoi Email OTP
        try {
            Mail::to($request->email)->send(new OtpMail($otpCode));
        } catch (\Exception $e) {
             // ECHEC ENVOI : On retourne une erreur bloquante
             return back()->with('error', 'Erreur critique : Impossible d\'envoyer le code par email. Réessayez plus tard.');
        }

        return redirect()->route('password.verify-otp', ['email' => $request->email])->with('success', 'Code de réinitialisation envoyé par email.');
    }

    public function showVerifyResetOtp(Request $request)
    {
        if (!$request->has('email')) {
            return redirect()->route('password.request');
        }
        return view('auth.verify-email-otp');
    }

    public function verifyResetOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
            'otp' => 'required|string',
        ]);

        $otpRecord = Otp::where('email', $request->email)
                        ->where('code', $request->otp)
                        ->where('expires_at', '>', Carbon::now())
                        ->first();

        if ($otpRecord) {
            session(['reset_email' => $request->email]);
            session(['reset_verified' => true]);
            $otpRecord->delete();

            return redirect()->route('password.reset')->with('success', 'Code vérifié. Veuillez définir votre nouveau mot de passe.');
        }

        return back()->withErrors(['otp' => 'Code OTP invalide ou expiré.'])->withInput();
    }

    public function showResetPassword()
    {
        if (!session('reset_verified') || !session('reset_email')) {
            return redirect()->route('password.request')->with('error', 'Veuillez vérifier votre code d\'abord.');
        }
        return view('auth.reset-password');
    }

    public function updatePassword(Request $request)
    {
        if (!session('reset_verified') || !session('reset_email')) {
            return redirect()->route('password.request');
        }

        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $email = session('reset_email');
        $user = User::where('email', $email)->first();
        
        $user->update([
            'password' => Hash::make($request->password)
        ]);

        session()->forget(['reset_email', 'reset_verified']);

        return redirect()->route('login')->with('success', 'Mot de passe réinitialisé avec succès ! Connectez-vous.');
    }

    /**
     * Vérifier si l'utilisateur peut se connecter ou réinitialiser son mot de passe
     * Retourne array avec 'can_login' (bool) et 'message' (string|null)
     */
    private function canUserLogin($user)
    {
        // Vérifier si l'utilisateur est Inactif
        if ($user->status === 'Inactif') {
            return [
                'can_login' => false,
                'message' => 'Votre compte est inactif. Veuillez contacter l\'administrateur.'
            ];
        }

        // Vérifier si l'utilisateur est Suspendu
        if ($user->status === 'Suspendu') {
            return [
                'can_login' => false,
                'message' => 'Votre compte a été suspendu. Veuillez contacter l\'administrateur.'
            ];
        }

        return [
            'can_login' => true,
            'message' => null
        ];
    }
}
