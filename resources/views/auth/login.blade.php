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
    <link rel="icon" href="{{asset('newicon.ico')}}" type="image/png">
    
    <!--begin::Fonts-->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@200..1000&display=swap" rel="stylesheet">
    <!--end::Fonts-->
    
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Cairo', sans-serif;
            background: linear-gradient(135deg, #1e1e2e 0%, #2d3748 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        /* خلفية متحركة */
        .bg-animation {
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            z-index: 0;
            opacity: 0.1;
        }

        .bg-animation span {
            position: absolute;
            display: block;
            width: 40px;
            height: 40px;
            background: rgba(255, 255, 255, 0.1);
            animation: float 25s infinite;
            border-radius: 50%;
        }

        .bg-animation span:nth-child(1) {
            left: 10%;
            top: 20%;
            animation-delay: 0s;
            animation-duration: 20s;
        }

        .bg-animation span:nth-child(2) {
            left: 20%;
            top: 60%;
            animation-delay: 2s;
            animation-duration: 18s;
        }

        .bg-animation span:nth-child(3) {
            left: 60%;
            top: 30%;
            animation-delay: 4s;
            animation-duration: 22s;
        }

        .bg-animation span:nth-child(4) {
            left: 80%;
            top: 70%;
            animation-delay: 6s;
            animation-duration: 24s;
        }

        .bg-animation span:nth-child(5) {
            left: 40%;
            top: 80%;
            animation-delay: 8s;
            animation-duration: 19s;
        }

        @keyframes float {
            0%, 100% {
                transform: translateY(0) rotate(0deg);
                opacity: 0.1;
            }
            50% {
                transform: translateY(-100px) rotate(180deg);
                opacity: 0.3;
            }
        }

        /* بطاقة تسجيل الدخول */
        .login-wrapper {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 500px;
            padding: 0 20px;
        }

        .login-card {
            background: rgba(255, 255, 255, 0.98);
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5);
            overflow: hidden;
            backdrop-filter: blur(10px);
        }

        .login-header {
            background: linear-gradient(135deg, #009ef7 0%, #0095e8 100%);
            padding: 50px 40px;
            text-align: center;
            position: relative;
        }

        .login-header::before {
            content: '';
            position: absolute;
            width: 200px;
            height: 200px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            top: -100px;
            right: -50px;
        }

        .login-header::after {
            content: '';
            position: absolute;
            width: 150px;
            height: 150px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            bottom: -50px;
            left: -30px;
        }

        .login-logo {
            position: relative;
            z-index: 1;
            margin-bottom: 20px;
        }

        .login-logo i {
            font-size: 60px;
            color: white;
        }

        .login-title {
            position: relative;
            z-index: 1;
            color: white;
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .login-subtitle {
            position: relative;
            z-index: 1;
            color: rgba(255, 255, 255, 0.9);
            font-size: 1rem;
        }

        .login-body {
            padding: 45px 40px;
        }

        .form-group {
            margin-bottom: 25px;
        }

        .form-label {
            display: block;
            color: #181c32;
            font-weight: 600;
            font-size: 0.95rem;
            margin-bottom: 10px;
        }

        .form-control {
            width: 100%;
            padding: 14px 18px;
            border: 1px solid #e4e6ef;
            border-radius: 10px;
            font-size: 1rem;
            transition: all 0.3s ease;
            background: #f9f9f9;
            color: #181c32;
        }

        .form-control:focus {
            outline: none;
            border-color: #009ef7;
            background: white;
            box-shadow: 0 0 0 3px rgba(0, 158, 247, 0.1);
        }

        .form-control::placeholder {
            color: #a1a5b7;
        }

        .form-control.is-invalid {
            border-color: #f1416c;
            background: #fff5f8;
        }

        .invalid-feedback {
            color: #f1416c;
            font-size: 0.875rem;
            margin-top: 6px;
            display: block;
        }

        .remember-section {
            display: flex;
            align-items: center;
            margin-bottom: 30px;
        }

        .form-check {
            display: flex;
            align-items: center;
        }

        .form-check-input {
            width: 20px;
            height: 20px;
            margin-left: 10px;
            cursor: pointer;
            accent-color: #009ef7;
        }

        .form-check-label {
            cursor: pointer;
            color: #5e6278;
            font-size: 0.95rem;
            font-weight: 500;
        }

        .btn-login {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #009ef7 0%, #0095e8 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 158, 247, 0.3);
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(0, 158, 247, 0.4);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        /* تأثير التحميل */
        .btn-login.loading {
            position: relative;
            color: transparent;
        }

        .btn-login.loading::after {
            content: '';
            position: absolute;
            width: 20px;
            height: 20px;
            top: 50%;
            left: 50%;
            margin-left: -10px;
            margin-top: -10px;
            border: 3px solid rgba(255, 255, 255, 0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.6s linear infinite;
        }

        @keyframes spin {
            to { transform: rotate(360deg); }
        }

        /* استجابة للموبايل */
        @media (max-width: 576px) {
            .login-header {
                padding: 40px 25px;
            }

            .login-title {
                font-size: 1.6rem;
            }

            .login-body {
                padding: 35px 25px;
            }
        }
    </style>
</head>
<body>
    <div class="bg-animation">
        <span></span>
        <span></span>
        <span></span>
        <span></span>
        <span></span>
    </div>

    <div class="login-wrapper">
        <div class="login-card">
            <div class="login-header">
                <div class="login-logo">
                    <svg width="60" height="60" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 2L2 7V17L12 22L22 17V7L12 2Z" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M12 22V12" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M22 7L12 12L2 7" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <h1 class="login-title">أهلاً بعودتك</h1>
                <p class="login-subtitle">سجل دخولك للوصول إلى حسابك</p>
            </div>

            <div class="login-body">
                <form method="POST" action="{{ route('login') }}" id="loginForm">
                    @csrf
                    
                    <div class="form-group">
                        <label for="login" class="form-label">الإيميل أو رقم الهاتف أو الرقم القومي</label>
                        <input id="login" 
                               type="text" 
                               name="login" 
                               value="{{ old('login') }}" 
                               class="form-control @error('login') is-invalid @enderror" 
                               required 
                               autofocus 
                               placeholder="أدخل بيانات الدخول الخاصة بك">
                        @error('login')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">كلمة المرور</label>
                        <input id="password" 
                               type="password" 
                               name="password" 
                               class="form-control @error('password') is-invalid @enderror" 
                               required 
                               placeholder="أدخل كلمة المرور">
                        @error('password')
                            <span class="invalid-feedback">{{ $message }}</span>
                        @enderror
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
                        تسجيل الدخول
                    </button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // تأثير التحميل عند الضغط على زر تسجيل الدخول
        document.getElementById('loginForm').addEventListener('submit', function() {
            const btn = document.getElementById('btnLogin');
            btn.classList.add('loading');
            btn.disabled = true;
        });
    </script>
</body>
</html>