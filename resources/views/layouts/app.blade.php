<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="icon" type="image/png" href="{{ Vite::asset('resources/images/imagenes/favicon.png') }}">
  <title>@yield('title', 'Mi Aplicación')</title>
  @vite(['resources/css/app.css', 'resources/js/app.js'])
  @yield('styles')
</head>
<body class="bg-lightBg text-lightText dark:bg-darkBg dark:text-darkText min-h-screen flex flex-col font-sans relative">
  <!-- Header con gradiente mejorado y efectos glow -->
  <header class="bg-gradient-to-r from-primary to-secondary text-white p-4 shadow-xl backdrop-blur-sm sticky top-0 z-40 border-b border-accentBlue/30">
    <div class="container mx-auto flex flex-col md:flex-row justify-between items-center gap-4">
      <!-- Logo y nombre con efecto hover mejorado -->
      <a href="{{ route('home') }}" class="flex items-center space-x-2 group">
        <img src="{{ Vite::asset('resources/images/imagenes/logo.png') }}" alt="Logo" 
             class="w-9 h-9 drop-shadow-[0_0_8px_rgba(0,209,255,0.7)] group-hover:drop-shadow-[0_0_12px_rgba(255,45,247,0.8)] transition-all duration-300">
        <span class="text-2xl font-bold tracking-wide group-hover:text-accentPink transition-colors duration-300">
          <span class="text-transparent bg-clip-text bg-gradient-to-r from-accentBlue to-accentPink">Eventos</span>
        </span>
      </a>

      <!-- Menú de navegación rediseñado -->
      <nav>
        <ul class="flex flex-wrap items-center gap-3 text-sm font-medium">
          @if (Auth::guard('externo')->check())
            <li>
              <a href="{{ route('inscripciones.index') }}" 
                 class="px-3 py-1.5 rounded-xl hover:bg-white/10 hover:text-accentBlue transition-colors duration-200 flex items-center gap-2 group">
                <i class="fas fa-calendar-alt text-sm group-hover:text-accentPink transition-colors duration-200"></i>
                <span>Eventos</span>
              </a>
            </li>
            <li>
              <a href="{{ route('pedidos.index') }}" 
                 class="px-3 py-1.5 rounded-xl hover:bg-white/10 hover:text-accentBlue transition-colors duration-200 flex items-center gap-2 group">
                <i class="fas fa-clipboard-list text-sm group-hover:text-accentPink transition-colors duration-200"></i>
                <span>Mis Pedidos</span>
              </a>
            </li>
          @endif

          @if (Auth::guard('empleado')->check() && in_array(Auth::user()->rol, ['superadmin', 'master','admin']))
            <li>
              <a href="{{ route('eventos.admin') }}" 
                 class="px-3 py-1.5 rounded-xl hover:bg-white/10 hover:text-accentBlue transition-colors duration-200 flex items-center gap-2 group">
                <i class="fas fa-tasks text-sm group-hover:text-accentPink transition-colors duration-200"></i>
                <span>Eventos</span>
              </a>
            </li>
            <li>
              <a href="{{ route('catalogos.index') }}" 
                 class="px-3 py-1.5 rounded-xl hover:bg-white/10 hover:text-accentBlue transition-colors duration-200 flex items-center gap-2 group">
                <i class="fas fa-boxes text-sm group-hover:text-accentPink transition-colors duration-200"></i>
                <span>Catálogos</span>
              </a>
            </li>
          @endif

          @if (Auth::guard('empleado')->check() && in_array(Auth::user()->rol, ['superadmin', 'master']))
            <li>
              <a href="{{ route('eventos.create') }}" 
                 class="bg-gradient-to-r from-accentBlue to-accentPink hover:from-accentBlue/90 hover:to-accentPink/90 text-white px-4 py-2 rounded-xl shadow-lg hover:shadow-[0_0_15px_rgba(0,209,255,0.4)] transition-all duration-300 flex items-center gap-2">
                <i class="fas fa-plus text-sm"></i>
                <span>Nuevo Evento</span>
              </a>
            </li>
          @endif

          <!-- Botón de Cerrar Sesión rediseñado -->
          <li>
            <form action="{{ route('logout') }}" method="POST">
              @csrf
              <button type="submit" 
                      class="bg-gradient-to-r from-danger to-danger/80 hover:from-danger/90 hover:to-danger text-white px-4 py-2 rounded-xl shadow-lg hover:shadow-[0_0_15px_rgba(255,42,109,0.4)] transition-all duration-300 flex items-center gap-2">
                <i class="fas fa-sign-out-alt text-sm"></i>
                <span>Cerrar Sesión</span>
              </button>
            </form>
          </li>
        </ul>
      </nav>
    </div>
  </header>

  <!-- Contenedor principal mejorado -->
  <main class="container mx-auto flex-1 my-8 px-4 sm:px-6">
    <div class="bg-lightCard/90 dark:bg-darkCard/90 rounded-2xl shadow-2xl backdrop-blur-sm border border-primary/30 dark:border-accentBlue/40 p-6 transition-all duration-300 hover:shadow-[0_0_25px_rgba(0,198,255,0.2)]">
      @yield('content')
    </div>
  </main>

  <!-- Footer mejorado -->
  <footer class="bg-lightCard/80 dark:bg-darkCard/80 border-t border-primary/30 dark:border-accentBlue/40 py-5 mt-8 backdrop-blur-sm">
    <div class="container mx-auto text-center text-sm opacity-90">
      <p class="text-primary dark:text-accentBlue/90">&copy; {{ date('Y') }} Sistema de Eventos. Todos los derechos reservados.</p>
    </div>
  </footer>

  <!-- Botón modo oscuro mejorado con efecto glow -->
  <button type="button" onclick="toggleDarkMode()" 
          class="fixed bottom-6 right-6 p-3 rounded-full bg-darkCard/90 backdrop-blur-sm border border-accentBlue/30 text-accentBlue shadow-[0_0_10px_rgba(0,209,255,0.6)] hover:shadow-[0_0_15px_rgba(255,45,247,0.8)] transition-all duration-300 hover:scale-110 hover:text-accentPink z-50">
    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
      <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 12.728l-.707-.707M6.343 17.657l-.707-.707"/>
    </svg>
  </button>

  <!-- Script para modo oscuro optimizado -->
  <script>
    function toggleDarkMode() {
      const html = document.documentElement;
      html.classList.toggle('dark');
      localStorage.theme = html.classList.contains('dark') ? 'dark' : 'light';
      
      // Feedback visual mejorado
      const button = document.querySelector('[onclick="toggleDarkMode()"]');
      button.classList.add('animate-[glow_0.5s_ease-in-out]');
      setTimeout(() => button.classList.remove('animate-[glow_0.5s_ease-in-out]'), 500);
    }

    // Inicialización del tema
    if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
      document.documentElement.classList.add('dark');
    } else {
      document.documentElement.classList.remove('dark');
    }
  </script>
</body>
</html>