let CourtService = {
  getAll: function (callback) {
    RestClient.get("courts", function (data) {
      const list = Array.isArray(data) ? data : (data.data || []);
      if (callback) callback(list);
    }, function (jqXHR, status, error) {
      console.error("Error fetching courts:", error);
      const msg = jqXHR?.responseJSON?.message || jqXHR?.responseJSON?.error || "Failed to load courts";
      toastr.error(msg);
    });
  },

  getById: function (id, callback) {
    RestClient.get("courts/" + id, function (data) {
      if (callback) callback(data);
    }, function (jqXHR, status, error) {
      console.error("Error fetching court:", error);
      const msg = jqXHR?.responseJSON?.message || jqXHR?.responseJSON?.error || "Failed to load court";
      toastr.error(msg);
    });
  },

  getByStatus: function (status, callback) {
    RestClient.get("courts/status/" + status, function (data) {
      const list = Array.isArray(data) ? data : (data.data || []);
      if (callback) callback(list);
    }, function (jqXHR, statusText, error) {
      console.error("Error fetching courts by status:", error);
      const msg = jqXHR?.responseJSON?.message || jqXHR?.responseJSON?.error || "Failed to load courts by status";
      toastr.error(msg);
    });
  },

  create: function (payload, callback) {
    RestClient.post("courts", payload, function (response) {
      toastr.success("Court created successfully");
      if (callback) callback(response);
    }, function (jqXHR, status, error) {
      console.error("Error creating court:", error);
      const msg = jqXHR?.responseJSON?.message || jqXHR?.responseJSON?.error || "Failed to create court";
      toastr.error(msg);
    });
  },

  update: function (id, payload, callback) {
    RestClient.put("courts/" + id, payload, function (response) {
      toastr.success("Court updated successfully");
      if (callback) callback(response);
    }, function (jqXHR, status, error) {
      console.error("Error updating court:", error);
      const msg = jqXHR?.responseJSON?.message || jqXHR?.responseJSON?.error || "Failed to update court";
      toastr.error(msg);
    });
  },

  delete: function (id, callback) {
    RestClient.delete("courts/" + id, null, function (response) {
      toastr.success("Court deleted successfully");
      if (callback) callback(response);
    }, function (jqXHR, status, error) {
      console.error("Error deleting court:", error);
      const msg = jqXHR?.responseJSON?.message || jqXHR?.responseJSON?.error || "Failed to delete court";
      toastr.error(msg);
    });
  }
};
