function notify(type, msg) {
  try {
    if (window.toastr && typeof window.toastr[type] === "function") {
      window.toastr[type](msg);
      return;
    }
  } catch (e) {}

  alert(msg);
}

var UserService = {
  getToken: function () {
    return localStorage.getItem("user_token") || localStorage.getItem("token");
  },

  getUser: function () {
  let token = UserService.getToken();
  if (!token) return null;

  if (token.startsWith("Bearer ")) token = token.substring(7);

  try {
    const decoded = Utils.parseJwt(token);

    return decoded?.user || decoded || null;
  } catch (e) {
    return null;
  }
},

  syncNav: function () {
  const token = UserService.getToken();
  const user = UserService.getUser();

  const isLoggedIn = !!token && !!user;
  const role = (user?.role || "").toUpperCase();
  const isAdmin = isLoggedIn && role === "ADMIN";

  const navLogin = document.getElementById("nav-login");
  const navDashboard = document.getElementById("nav-dashboard");
  const navAdmin = document.getElementById("nav-admin");
  const navLogout = document.getElementById("nav-logout");

  if (navLogin) navLogin.classList.toggle("d-none", isLoggedIn);
  if (navDashboard) navDashboard.classList.toggle("d-none", !isLoggedIn);
  if (navLogout) navLogout.classList.toggle("d-none", !isLoggedIn);
  if (navAdmin) navAdmin.classList.toggle("d-none", !isAdmin);

  const hash = (window.location.hash || "#home").replace("#", "");

  if (!isLoggedIn && (hash === "dashboard" || hash === "admin")) {
    window.location.hash = "#login";
    return;
  }

  if (isLoggedIn && !isAdmin && hash === "admin") {
    notify("error", "Access denied. Admins only.");
    window.location.hash = "#home";
  }
},

  login: function (entity) {
    $.ajax({
      url: Constants.PROJECT_BASE_URL + "auth/login",
      type: "POST",
      data: JSON.stringify(entity),
      contentType: "application/json",
      dataType: "json",
      success: function (result) {
        const token = result?.data?.token || result?.token;
        if (!token) return notify("error", "Token missing in login response.");

        localStorage.setItem("user_token", token);
        localStorage.setItem("token", token);

        notify("success", "Login successful!");
        UserService.syncNav();

        window.location.hash = "#home";
      },
      error: function (xhr) {
        notify("error", xhr?.responseJSON?.message || xhr?.responseText || "Invalid email or password");
      },
    });
  },

  register: function (entity) {
    $.ajax({
      url: Constants.PROJECT_BASE_URL + "auth/register",
      type: "POST",
      data: JSON.stringify(entity),
      contentType: "application/json",
      dataType: "json",
      success: function () {
        notify("success", "Registration successful. Please login.");
        window.location.hash = "#login";
      },
      error: function (xhr) {
        notify("error", xhr?.responseJSON?.message || xhr?.responseText || "Registration failed");
      },
    });
  },

  logout: function () {
    localStorage.removeItem("user_token");
    localStorage.removeItem("token");
    notify("info", "Logged out successfully");
    UserService.syncNav();
    window.location.hash = "#home";
  },
};

$(document)
  .off("submit.register")
  .on("submit.register", "#registerForm", function (e) {
    e.preventDefault();

    const entity = Object.fromEntries(new FormData(this).entries());

    const payload = {
      name: entity.fullname || entity.name,
      email: entity.email,
      password: entity.password,
    };

    if (!payload.name || !payload.email || !payload.password) {
      notify("error", "Name, email and password are required.");
      return;
    }

    if (entity.confirmPassword && entity.password !== entity.confirmPassword) {
      notify("error", "Passwords do not match.");
      return;
    }

    UserService.register(payload);
  });

$(document)
  .off("submit.login")
  .on("submit.login", "#loginForm", function (e) {
    e.preventDefault();

    const entity = Object.fromEntries(new FormData(this).entries());

    const payload = {
      email: entity.email,
      password: entity.password,
    };

    if (!payload.email || !payload.password) {
      notify("error", "Email and password are required.");
      return;
    }

    UserService.login(payload);
  });
