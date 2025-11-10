<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
  <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap');
    
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Inter', sans-serif;
    }
    
    body {
      display: flex;
      min-height: 100vh;
      background-image: url('/images/background.jpeg');
      background-size: cover;
      background-position: top;
      position: relative;
    }
    
    .overlay {
      position: absolute;
      inset: 0;
      background: linear-gradient(135deg, rgba(255,255,255,0.7) 0%, rgba(255,255,255,0.5) 100%);
      z-index: 1;
    }
    
    .container {
      display: flex;
      width: 100%;
      max-width: 1200px;
      margin: auto;
      position: relative;
      z-index: 10;
      box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
      border-radius: 16px;
      overflow: hidden;
    }
    
    .logo-section {
      flex: 1;
      background: linear-gradient(135deg, #067c04ff 0%, #06D001 100%);
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      padding: 2rem;
      color: white;
      text-align: center;
    }
    
    .logo-container {
      width: 180px;
      height: 180px;
      border-radius: 50%;
      background-color: white;
      display: flex;
      align-items: center;
      justify-content: center;
      margin-bottom: 2rem;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
    }
    
    .logo {
      width: 140px;
      height: 140px;
      border-radius: 50%;
      object-fit: cover;
    }
    
    .logo-text {
      margin-top: 1.5rem;
    }
    
    .logo-title {
      font-size: 2rem;
      font-weight: 700;
      margin-bottom: 0.5rem;
    }
    
    .logo-subtitle {
      font-size: 1rem;
      opacity: 0.9;
      max-width: 400px;
      line-height: 1.5;
    }
    
    .login-section {
      flex: 1;
      background: rgba(255, 255, 255, 0.9);
      backdrop-filter: blur(10px);
      padding: 3rem;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }
    
    .login-form {
      max-width: 400px;
      margin: 0 auto;
      width: 100%;
    }
    
    .login-header {
      text-align: center;
      margin-bottom: 2rem;
    }
    
    .login-title {
      font-size: 1.75rem;
      font-weight: 700;
      color: #059212;
      margin-bottom: 0.5rem;
    }
    
    .login-subtitle {
      font-size: 0.9rem;
      color: #64748b;
    }
    
    .form-group {
      margin-bottom: 1.5rem;
      position: relative;
    }
    
    .form-label {
      display: block;
      font-weight: 500;
      margin-bottom: 0.5rem;
      color: #078A04;
      font-size: 0.9rem;
    }
    
    .form-input {
      width: 100%;
      padding: 0.875rem 1rem;
      border: 1.5px solid #e2e8f0;
      border-radius: 8px;
      font-size: 1rem;
      transition: all 0.3s ease;
      background: white;
    }
    
    .form-input:focus {
      outline: none;
      border-color: #059212;
      box-shadow: 0 0 0 3px hsla(119, 99%, 41%, 0.15);
    }
    
    .password-container {
      position: relative;
    }
    
    .toggle-password {
      position: absolute;
      right: 12px;
      top: 50%;
      transform: translateY(-50%);
      background: none;
      border: none;
      color: #648b76ff;
      cursor: pointer;
      font-size: 0.9rem;
    }
    
    .toggle-password:hover {
      color: #3b82f6;
    }
    
    .error-message {
      color: #ef4444;
      font-size: 0.8rem;
      margin-top: 0.5rem;
      display: block;
    }
    
    .remember-container {
      display: flex;
      align-items: center;
      margin-bottom: 1.5rem;
    }
    
    .remember-checkbox {
      margin-right: 0.5rem;
      width: 18px;
      height: 18px;
      accent-color: #3b82f6;
    }
    
    .remember-label {
      font-size: 0.9rem;
      color: #475569;
    }
    
    .submit-button {
      width: 100%;
      padding: 0.875rem;
      background: linear-gradient(135deg, #3eb928ff 0%, #06D001 100%);
      color: white;
      border: none;
      border-radius: 8px;
      font-size: 1rem;
      font-weight: 600;
      cursor: pointer;
      transition: all 0.3s ease;
      position: relative;
      overflow: hidden;
    }
    
    .submit-button:hover {
      transform: translateY(-2px);
      box-shadow: 0 5px 15px rgba(59, 130, 246, 0.4);
    }
    
    .submit-button:active {
      transform: translateY(0);
    }
    
    .submit-button.loading {
      pointer-events: none;
      opacity: 0.8;
    }
    
    .submit-button.loading::after {
      content: "";
      position: absolute;
      width: 20px;
      height: 20px;
      top: 50%;
      left: 50%;
      margin-left: -10px;
      margin-top: -10px;
      border: 2px solid rgba(255, 255, 255, 0.3);
      border-radius: 50%;
      border-top-color: white;
      animation: spin 1s ease-in-out infinite;
    }
    
    @keyframes spin {
      to { transform: rotate(360deg); }
    }
    
    .register-link {
      text-align: center;
      margin-top: 1.5rem;
      font-size: 0.9rem;
      color: #64748b;
    }
    
    .register-link a {
      color: #3b82f6;
      text-decoration: none;
      font-weight: 500;
      transition: color 0.2s ease;
    }
    
    .register-link a:hover {
      color: #1e3a8a;
      text-decoration: underline;
    }
    
    /* Responsive adjustments */
    @media (max-width: 900px) {
      .container {
        flex-direction: column;
        max-width: 500px;
        margin: 2rem auto;
      }
      
      .logo-section {
        padding: 2rem 1.5rem;
      }
      
      .logo-container {
        width: 120px;
        height: 120px;
      }
      
      .logo {
        width: 90px;
        height: 90px;
      }
      
      .logo-title {
        font-size: 1.5rem;
      }
    }
    
    @media (max-width: 480px) {
      .login-section {
        padding: 2rem 1.5rem;
      }
      
      .login-title {
        font-size: 1.5rem;
      }
    }
  </style>
</head>

<body>
  <!-- White overlay with gradient -->
  <div class="overlay"></div>

  <div class="container">
    <div class="logo-section">
      <div class="logo-container">
        <img src="/images/logo.png" alt="Company Logo" class="logo">
      </div>
      <div class="logo-text">
        <h1 class="logo-title">Department of Psychology</h1>
        <p class="logo-subtitle">"Welcome to the Library Management System, your digital assistant for organizing, tracking, and managing books efficiently."</p>
      </div>
    </div>
    
    <div class="login-section">
      <form method="POST" action="{{ route('login') }}" class="login-form">
        @csrf
        <div class="login-header">
          <h1 class="login-title">Welcome Back</h1>
          <p class="login-subtitle">Sign in to your account to continue</p>
        </div>

        <div class="form-group">
          <label for="email" class="form-label">Email Address</label>
          <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                 class="form-input" placeholder="you@example.com">
          @error('email')
            <span class="error-message">{{ $message }}</span>
          @enderror
        </div>

        <div class="form-group">
          <label for="password" class="form-label">Password</label>
          <div class="password-container">
            <input id="password" type="password" name="password" required
                   class="form-input" placeholder="Enter your password">
            <button type="button" class="toggle-password" id="togglePassword">
              Show
            </button>
          </div>
          @error('password')
            <span class="error-message">{{ $message }}</span>
          @enderror
        </div>

        <button type="submit" class="submit-button" id="submitButton">
          Sign In
        </button>
      </form>
    </div>
  </div>

  <script>
    document.addEventListener('DOMContentLoaded', function() {
      // Password visibility toggle
      const togglePassword = document.getElementById('togglePassword');
      const passwordInput = document.getElementById('password');
      
      togglePassword.addEventListener('click', function() {
        const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
        passwordInput.setAttribute('type', type);
        this.textContent = type === 'password' ? 'Show' : 'Hide';
      });
      
      // Form submission loading state
      const form = document.querySelector('.login-form');
      const submitButton = document.getElementById('submitButton');
      
      form.addEventListener('submit', function() {
        submitButton.classList.add('loading');
        submitButton.disabled = true;
      });
      
      // Input focus effects
      const inputs = document.querySelectorAll('.form-input');
      inputs.forEach(input => {
        input.addEventListener('focus', function() {
          this.parentElement.classList.add('focused');
        });
        
        input.addEventListener('blur', function() {
          this.parentElement.classList.remove('focused');
        });
      });
    });
  </script>
</body>
</html>