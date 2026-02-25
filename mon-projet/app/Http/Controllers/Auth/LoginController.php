<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    /**
     * The user has been authenticated.
     * Redirige l'utilisateur vers son dashboard selon son rôle
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        // Vérifier le rôle de l'utilisateur et rediriger vers le dashboard approprié
        if ($user->role === 'admin') {
            return redirect()->route('admin.dashboard');
        } elseif ($user->role === 'enseignant') {
            return redirect()->route('enseignant.dashboard');
        } elseif ($user->role === 'etudiant') {
            return redirect()->route('etudiant.dashboard');
        }

        // Par défaut, si le rôle n'est pas reconnu, aller vers /home
        return redirect('/home');
    }
}