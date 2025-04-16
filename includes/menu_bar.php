<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Responsive Navbar</title>
  <style>
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    body {
      min-height: 100vh;
      background-color: #f5f5f5;
    }
    
    .navbar {
  position: relative;
  display: flex;
  justify-content: space-between;
  align-items: center;
  background-image: url('../assets/images/puzzle.png');
  background-size: 20%;
  background-position: center;
  color: white;
  padding: 1rem 2rem;
}

.navbar::before {
  content: "";
  position: absolute;
  top: 0;
  left: 0;
  height: 100%;
  width: 100%;
  background-color: rgba(0, 0, 0, 0.5); /* semi-transparent overlay */
  z-index: 0;
}

.navbar > * {
  z-index: 1; /* puts navbar content above the overlay */
}


    
    .navbar-brand {
      font-size: 1.5rem;
      font-weight: bold;
      color: white;
    }
    
    .navbar-links {
      display: flex;
      list-style: none;
    }
    
    .navbar-links li {
      padding: 0 1rem;
    }
    
    .navbar-links a {
      color: white;
      text-decoration: none;
      font-size: 1.1rem;
      transition: color 0.3s ease;
    }
    
    .navbar-links a:hover {
      color: #60a5fa;
    }
    
    .hamburger {
      display: none;
      background: none;
      border: none;
      cursor: pointer;
      padding: 0.5rem;
      color: white;
    }
    
    .hamburger svg {
      width: 24px;
      height: 24px;
    }
    
    .sidebar {
      position: fixed;
      top: 0;
      right: -250px;
      width: 250px;
      height: 100vh;
      background-color: #0f172a;
      transition: right 0.3s ease;
      z-index: 999;
      padding-top: 60px;
    }
    
    .sidebar.active {
      right: 0;
    }
    
    .sidebar-links {
      list-style: none;
      padding: 0;
    }
    
    .sidebar-links li {
      padding: 1rem 2rem;
      border-bottom: 1px solid #2d3748;
    }
    
    .sidebar-links a {
      color: white;
      text-decoration: none;
      font-size: 1.1rem;
      display: block;
      transition: color 0.3s ease;
    }
    
    .sidebar-links a:hover {
      color: #60a5fa;
    }
    
    .close-btn {
      position: absolute;
      top: 20px;
      right: 20px;
      background: none;
      border: none;
      color: white;
      cursor: pointer;
    }
    
    .overlay {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-color: rgba(0, 0, 0, 0.5);
      display: none;
      z-index: 998;
    }
    
    .content {
      padding: 2rem;
    }
    
    @media (max-width: 768px) {
      .navbar-links {
        display: none;
      }
      
      .hamburger {
        display: block;
      }
    }
  </style>
</head>
<body>
  <nav class="navbar">
    <div class="navbar-brand">The Racing Puzzle</div>
    <ul class="navbar-links">
      <li><a href="../dashboard">Dashboard</a></li>
      <li><a href="../horse-tracker">Tracker</a></li>
      <li><a href="../bet-record">Bet Record</a></li>
      <li><a href="../racecards">Racecards</a></li>
      <li><a href="../leagues">Leagues</a></li>
      <li><a href="../index.html">Log Out</a></li>
    </ul>
    <button class="hamburger" aria-label="Menu">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
      </svg>
    </button>
  </nav>
  
  <div class="sidebar">
    <button class="close-btn" aria-label="Close">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" width="24" height="24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
      </svg>
    </button>
    <ul class="sidebar-links">
      <li><a href="../dashboard">Dashboard</a></li>
      <li><a href="../horse-tracker">Tracker</a></li>
      <li><a href="../bet-record">Bet Record</a></li>
      <li><a href="../racecards">Racecards</a></li>
      <li><a href="../leagues">Leagues</a></li>
      <li><a href="../index.html">Log Out</a></li>
    </ul>
  </div>
  
  <div class="overlay"></div>
  
  

  <script>
    const hamburger = document.querySelector('.hamburger');
    const sidebar = document.querySelector('.sidebar');
    const closeBtn = document.querySelector('.close-btn');
    const overlay = document.querySelector('.overlay');
    
    hamburger.addEventListener('click', () => {
      sidebar.classList.add('active');
      overlay.style.display = 'block';
      document.body.style.overflow = 'hidden';
    });
    
    closeBtn.addEventListener('click', closeSidebar);
    overlay.addEventListener('click', closeSidebar);
    
    function closeSidebar() {
      sidebar.classList.remove('active');
      overlay.style.display = 'none';
      document.body.style.overflow = 'auto';
    }
  </script>
</body>
</html>