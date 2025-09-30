<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول</title>
    
    <link href="{{asset('assets/css/bootstrap.rtl.min.css')}}" rel="stylesheet" />
    <link href="{{asset('assets/plugins/global/plugins.bundle.rtl.css')}}" rel="stylesheet" type="text/css" />
    <link href="{{asset('assets/css/style.bundle.rtl.css')}}" rel="stylesheet" type="text/css" />
    <link rel="shortcut icon" href="{{asset('assets/media/logos/newicon.ico')}}" />
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&display=swap" rel="stylesheet">
    
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Cairo', sans-serif;
            background: #0f172a;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        /* خلفية متدرجة متحركة */
        body::before {
            content: '';
            position: absolute;
            width: 200%;
            height: 200%;
            background: 
                radial-gradient(circle at 20% 50%, rgba(139, 92, 246, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 80% 80%, rgba(236, 72, 153, 0.15) 0%, transparent 50%),
                radial-gradient(circle at 40% 20%, rgba(59, 130, 246, 0.15) 0%, transparent 50%);
            animation: rotate 20s linear infinite;
        }

        @keyframes rotate {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* شبكة منقطة */
        .grid-background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-image: 
                linear-gradient(rgba(255, 255, 255, 0.03) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.03) 1px, transparent 1px);
            background-size: 50px 50px;
            pointer-events: none;
        }

        .login-container {
            position: relative;
            z-index: 10;
            width: 100%;
            max-width: 460px;
            padding: 20px;
        }

        .login-card {
            background: rgba(30, 41, 59, 0.8);
            backdrop-filter: blur(20px);
            border-radius: 24px;
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.5);
            overflow: hidden;
            animation: slideIn 0.6s ease-out;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateY(40px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .login-header {
            padding: 48px 36px 36px;
            text-align: center;
            background: linear-gradient(135deg, rgba(139, 92, 246, 0.1) 0%, rgba(236, 72, 153, 0.1) 100%);
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .logo-wrapper {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #8b5cf6 0%, #ec4899 100%);
            border-radius: 20px;
            margin-bottom: 24px;
            box-shadow: 0 10px 30px rgba(139, 92, 246, 0.3);
            position: relative;
        }

        .logo-wrapper::before {
            content: '';
            position: absolute;
            inset: -2px;
            background: linear-gradient(135deg, #8b5cf6, #ec4899);
            border-radius: 20px;
            z-index: -1;
            filter: blur(8px);
            opacity: 0.5;
        }

        .logo-wrapper svg {
            width: 40px;
            height: 40px;
        }

        .login-title {
            color: #f1f5f9;
            font-size: 1.875rem;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .login-subtitle {
            color: #94a3b8;
            font-size: 1rem;
        }

        .login-body {
            padding: 36px;
        }

        .form-group {
            margin-bottom: 24px;
        }

        .form-label {
            display: block;
            color: #e2e8f0;
            font-weight: 600;
            font-size: 0.9rem;
            margin-bottom: 10px;
        }

        .input-wrapper {
            position: relative;
        }

        .form-control {
            width: 100%;
            padding: 14px 18px;
            background: rgba(30, 41, 59, 0.6);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            color: #f1f5f9;
            font-size: 1rem;
            transition: all 0.3s ease;
        }

        .form-control::placeholder {
            color: #64748b;
        }

        .form-control:focus {
            outline: none;
            background: rgba(30, 41, 59, 0.8);
            border-color: #8b5cf6;
            box-shadow: 0 0 0 3px rgba(139, 92, 246, 0.2);
        }

        .form-control.is-invalid {
            border-color: #ef4444;
        }

        .invalid-feedback {
            color: #fca5a5;
            font-size: 0.85rem;
            margin-top: 6px;
            display: block;
        }

        .remember-section {
            display: flex;
            align-items: center;
            margin-bottom: 28px;
        }

        .form-check {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .form-check-input {
            width: 18px;
            height: 18px;
            border: 2px solid rgba(255, 255, 255, 0.2);
            border-radius: 4px;
            background: transparent;
            cursor: pointer;
            transition: all 0.3s ease;
        }

        .form-check-input:checked {
            background: linear-gradient(135deg, #8b5cf6, #ec4899);
            border-color: #8b5cf6;
        }

        .form-check-label {
            color: #cbd5e1;
            font-size: 0.9rem;
            cursor: pointer;
        }

        .btn-login {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #8b5cf6 0%, #ec4899 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 1.05rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 20px rgba(139, 92, 246, 0.4);
            position: relative;
            overflow: hidden;
        }

        .btn-login::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s ease;
        }

        .btn-login:hover::before {
            left: 100%;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 30px rgba(139, 92, 246, 0.5);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .btn-login.loading {
            pointer-events: none;
            opacity: 0.8;
        }

        .btn-login.loading::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            top: 50%;
            left: 50%;
            margin: -10px 0 0 -10px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* تأثيرات إضافية */
        .floating-shapes {
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            pointer-events: none;
            z-index: 1;
        }

        .shape {
            position: absolute;
            border-radius: 50%;
            filter: blur(60px);
            opacity: 0.3;
            animation: float 20s infinite ease-in-out;
        }

        .shape:nth-child(1) {
            width: 300px;
            height: 300px;
            background: rgba(139, 92, 246, 0.2);
            top: -150px;
            right: -150px;
            animation-delay: 0s;
        }

        .shape:nth-child(2) {
            width: 250px;
            height: 250px;
            background: rgba(236, 72, 153, 0.2);
            bottom: -125px;
            left: -125px;
            animation-delay: 5s;
        }

        @keyframes float {
            0%, 100% {
                transform: translate(0, 0) scale(1);
            }
            50% {
                transform: translate(50px, -50px) scale(1.1);
            }
        }

        @media (max-width: 576px) {
            .login-header {
                padding: 36px 24px 24px;
            }

            .login-body {
                padding: 28px 24px;
            }

            .login-title {
                font-size: 1.5rem;
            }

            .logo-wrapper {
                width: 70px;
                height: 70px;
            }
        }
    </style>
</head>
<body>
    <div class="grid-background"></div>
    <div class="floating-shapes">
        <div class="shape"></div>
        <div class="shape"></div>
    </div>

    <div class="login-container">
        <div class="login-card">
            <div class="login-header">
                <div class="logo-wrapper">
                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M19 21V5C19 3.89543 18.1046 3 17 3H7C5.89543 3 5 3.89543 5 5V21" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M9 7H11" stroke="white" stroke-width="2" stroke-linecap="round"/>
                        <path d="M13 7H15" stroke="white" stroke-width="2" stroke-linecap="round"/>
                        <path d="M9 11H11" stroke="white" stroke-width="2" stroke-linecap="round"/>
                        <path d="M13 11H15" stroke="white" stroke-width="2" stroke-linecap="round"/>
                        <path d="M9 15H15" stroke="white" stroke-width="2" stroke-linecap="round"/>
                        <path d="M12 21V15" stroke="white" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                </div>
                <h1 class="login-title">تسجيل الدخول</h1>
                <p class="login-subtitle">نظام إدارة الوحدات الصحية</p>
            </div>

            <div class="login-body">
                <form method="POST" action="{{ route('login') }}" id="loginForm">
                    @csrf
                    
                    <div class="form-group">
                        <label for="login" class="form-label">اسم المستخدم</label>
                        <div class="input-wrapper">
                            <input id="login" 
                                   type="text" 
                                   name="login" 
                                   value="{{ old('login') }}" 
                                   class="form-control @error('login') is-invalid @enderror" 
                                   required 
                                   autofocus 
                                   placeholder="الإيميل، الهاتف أو الرقم القومي">
                            @error('login')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">كلمة المرور</label>
                        <div class="input-wrapper">
                            <input id="password" 
                                   type="password" 
                                   name="password" 
                                   class="form-control @error('password') is-invalid @enderror" 
                                   required 
                                   placeholder="••••••••">
                            @error('password')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>

                    <div class="remember-section">
                        <div class="form-check">
                            <input type="checkbox" 
                                   name="remember" 
                                   class="form-check-input" 
                                   id="remember">
                            <label class="form-check-label" for="remember">تذكرني</label>
                        </div>
                    </div>

                    <button type="submit" class="btn-login" id="btnLogin">
                        <span class="btn-text">دخول</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', function() {
            const btn = document.getElementById('btnLogin');
            btn.classList.add('loading');
            btn.querySelector('.btn-text').style.opacity = '0';
            btn.disabled = true;
        });
    </script>
</body>
</html>