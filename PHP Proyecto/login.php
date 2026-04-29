<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Acceso al Sistema</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="./css/login.css">
</head>
<body class="bg-dark d-flex align-items-center vh-100">
     <div class="wrapper">
    <div class="login-card">
      <div class="brand">
        <h1>Login</h1>
        <span>Secure access</span>
      </div>

      <div class="eye-zone">
        <div class="eye-wrap">
          <svg class="eye-svg" viewBox="0 0 307 275" xmlns="http://www.w3.org/2000/svg">
            <path d="M3 85C52.288 126.551 182.655 190.522 305.5 85C251.67 139.917 155.83 252.356 3 85Z" fill="black"/>
            <path d="M2 86C49.7363 22.6116 177.167 -66.1321 305 86C180.785 -25.2967 51.2438 39.6264 2 86Z" fill="black"/>
            <circle cx="154.5" cy="85.5" r="30" fill="#2b0000"/>
            <circle cx="154.5" cy="85.5" r="18" fill="#050505"/>
          </svg>
        </div>
        <div class="tear-row">
          <div class="tear"></div>
          <div class="tear"></div>
          <div class="tear"></div>
        </div>
      </div>

      <form class="form" id="loginForm">
        <div class="field">
          <input type="text" id="username" name="username" placeholder=" " autocomplete="off" required />
          <label for="username">Enter Username</label>
        </div>

        <div class="pass-wrap">
          <div class="field">
            <input type="password" id="password" name="password" placeholder=" " autocomplete="off" required />
            <label for="password">Enter Password</label>
          </div>

          <button type="button" class="eye-btn" id="togglePassword" aria-label="Mostrar contraseña">
            <svg id="eyeOpen" viewBox="0 0 576 512">
              <path d="M572.52 241.4C518.29 135.59 410.93 64 288 64S57.68 135.64 3.48 241.41a32.35 32.35 0 0 0 0 29.19C57.71 376.41 165.07 448 288 448s230.32-71.64 284.52-177.41a32.35 32.35 0 0 0 0-29.19zM288 400a144 144 0 1 1 144-144 143.93 143.93 0 0 1-144 144zm0-240a95.31 95.31 0 0 0-25.31 3.79 47.85 47.85 0 0 1-66.9 66.9A95.78 95.78 0 1 0 288 160z"/>
            </svg>
            <svg id="eyeClosed" viewBox="0 0 640 512" style="display:none;">
              <path d="M320 400c-75.85 0-137.25-58.71-142.9-133.11L72.2 185.82c-13.79 17.3-26.48 35.59-36.72 55.59a32.35 32.35 0 0 0 0 29.19C89.71 376.41 197.07 448 320 448c26.91 0 52.87-4 77.89-10.46L346 397.39a144.13 144.13 0 0 1-26 2.61zm313.82 58.1l-110.55-85.44a331.25 331.25 0 0 0 81.25-102.07 32.35 32.35 0 0 0 0-29.19C550.29 135.59 442.93 64 320 64a308.15 308.15 0 0 0-147.32 37.7L45.46 3.37A16 16 0 0 0 23 6.18L3.37 31.45A16 16 0 0 0 6.18 53.9l588.36 454.73a16 16 0 0 0 22.46-2.81l19.64-25.27a16 16 0 0 0-2.82-22.45z"/>
            </svg>
          </button>
        </div>

        <div class="actions">
          <a class="link" href="#">Forgot password?</a>
        </div>
        <button class="btn" type="submit">LOGIN</button>
      </form>
    </div>
  </div>


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="./js/login.js"></script>
</body>
</html>