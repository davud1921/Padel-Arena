var RestClient = {
  getToken: function () {
    return localStorage.getItem("user_token") || localStorage.getItem("token");
  },

  request: function (url, method, data, success, error) {
  if (!url.startsWith("http")) {
    const base = (Constants.PROJECT_BASE_URL || "").replace(/\/+$/, "");
    const path = (url || "").replace(/^\/+/, "");
    url = base + "/" + path;
  }

  var token = RestClient.getToken();
  if (token && token.startsWith("Bearer ")) token = token.substring(7);

  var ajaxOptions = {
    url: url,
    type: method,
    contentType: "application/json",
    dataType: "json",
    success: success,
    error: function (xhr) {
      if (xhr.status === 401 && !token) {
        if (error) error(xhr);
        return;
      }

      if (xhr.status === 401 && token) {
        try { if (window.toastr) toastr.error("Session expired. Please login again."); } catch (e) {}
        localStorage.removeItem("user_token");
        localStorage.removeItem("token");
        if (window.UserService && typeof UserService.syncNav === "function") UserService.syncNav();
        window.location.hash = "#login";
      }

      if (error) error(xhr);
    }
  };

  if (data) ajaxOptions.data = JSON.stringify(data);

  if (token) {
    ajaxOptions.beforeSend = function (xhr) {
      xhr.setRequestHeader("Authentication", token);
      xhr.setRequestHeader("Authorization", "Bearer " + token);
    };
  }

  $.ajax(ajaxOptions);
},

  get: function (url, success, error) {
    RestClient.request(url, "GET", null, success, error);
  },

  post: function (url, data, success, error) {
    RestClient.request(url, "POST", data, success, error);
  },

  put: function (url, data, success, error) {
    RestClient.request(url, "PUT", data, success, error);
  },

  patch: function (url, data, success, error) {
    RestClient.request(url, "PATCH", data, success, error);
  },

  delete: function (url, success, error) {
    RestClient.request(url, "DELETE", null, success, error);
  }
};
