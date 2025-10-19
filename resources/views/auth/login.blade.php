<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
       <form method="POST" action="{{ route('login') }}">
       @csrf

       <div>
           <label for="email">Email</label>
           <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus>
           @error('email')
               <span>{{ $message }}</span>
           @enderror
       </div>

       <div>
           <label for="password">Password</label>
           <input id="password" type="password" name="password" required>
           @error('password')
               <span>{{ $message }}</span>
           @enderror
       </div>

       <div>
           <label>
               <input type="checkbox" name="remember"> Remember Me
           </label>
       </div>

       <div>
           <button type="submit">Login</button>
       </div>
        <div>
        <p>Don't have an account? <a href="{{ route('register') }}">Register here</a></p>
    </div>
   </form>
</body>
</html>