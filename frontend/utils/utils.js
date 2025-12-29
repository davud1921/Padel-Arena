let Utils = {
  parseJwt: function (token) {
    if (!token) return null;
    try {
      const payload = token.split('.')[1];
      const decoded = atob(payload.replace(/-/g, '+').replace(/_/g, '/'));
      return JSON.parse(decoded);
    } catch (e) {
      console.error("Invalid JWT token", e);
      return null;
    }
  },

  currentUser: function () {
    const token = localStorage.getItem("user_token");
    const parsed = Utils.parseJwt(token);
    return parsed && parsed.user ? parsed.user : null;
  },

  isAdmin: function () {
    const u = Utils.currentUser();
    return !!u && u.role === Constants.ADMIN_ROLE;
  },

  requireAuth: function () {
    const token = localStorage.getItem("user_token");
    if (!token) {
      window.location.hash = "#login";
      toastr.info("Please login first.");
      return false;
    }
    return true;
  }
};
