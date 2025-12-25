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
  const token = localStorage.getItem("user_token");
  const decoded = token ? Utils.parseJwt(token) : null;
  const user = decoded?.user;

  const isLoggedIn = !!user;
  const role = (user?.role || "").toString().toLowerCase();
  const isAdmin = role === (Constants.ADMIN_ROLE || "admin").toString().toLowerCase();

  const $login = $("#nav-login");
  const $logout = $("#nav-logout");
  const $dashboard = $("#nav-dashboard");
  const $admin = $("#nav-admin");

  if (!isLoggedIn) {
    $login.removeClass("d-none");
    $logout.addClass("d-none");
    $dashboard.addClass("d-none");
    $admin.addClass("d-none");
    return;
  }

  $login.addClass("d-none");
  $logout.removeClass("d-none");

  if (isAdmin) {
    $admin.removeClass("d-none");
    $dashboard.addClass("d-none");

    if (window.location.hash === "#dashboard") {
      window.location.hash = "#admin";
    }
  } else {
    $dashboard.removeClass("d-none");
    $admin.addClass("d-none");

    if (window.location.hash === "#admin") {
      window.location.hash = "#dashboard";
    }
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
