<?php
namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use App\Services\AuthService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    /**
     * تسجيل الدخول - يمكن استخدام الإيميل أو الهاتف أو الرقم القومي
     */
    public function login(LoginRequest $request)
    {
        $loginField = $request->input('login');
        $password = $request->input('password');

        $authService = new AuthService();
        $user = $authService->validateCredentials($loginField, $password);

        if (!$user) {
            throw ValidationException::withMessages([
                'login' => ['بيانات الدخول غير صحيحة'],
            ]);
        }

        Auth::login($user, $request->boolean('remember'));
        $request->session()->regenerate();

        return $this->redirectBasedOnRole($user);
    }

    /**
     * تحديد نوع بيانات الدخول
     */
    private function getLoginType($login)
    {
        if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
            return 'email';
        } elseif (preg_match('/^01[0-2,5]{1}[0-9]{8}$/', $login)) {
            return 'phone';
        } elseif (preg_match('/^[0-9]{14}$/', $login)) {
            return 'national_id';
        }
        
        return 'email'; // افتراضي
    }

    /**
     * البحث عن المستخدم بناءً على نوع البيانات
     */
    private function findUserByLogin($login, $loginType)
    {
        $authService = new AuthService();
        switch ($loginType) {
            case 'email':
                return User::where('email', $login)->first();
            case 'phone':
                return User::where('phone', $login)->first();
            case 'national_id':
                return User::where('national_id', $login)->first();
            default:
                return $authService->findUserByMultipleFields($login);
        }
    }

    /**
     * توجيه المستخدم حسب دوره
     */
    private function redirectBasedOnRole($user)
    {
        switch ($user->role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'health_center_manager':
                return redirect()->route('manager.dashboard');
            default:
                return redirect()->route('dashboard');
        }
    }

    /**
     * تسجيل الخروج
     */
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect()->route('login');
    }

    /**
     * إنشاء حساب مدير وحدة صحية (للأدمن فقط)
     */
   
}