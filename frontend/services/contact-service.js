let ContactService = {
  getAll: function (callback) {
    RestClient.get("contactmessages", function (data) {
      const list = Array.isArray(data) ? data : (data.data || []);
      if (callback) callback(list);
    }, function (jqXHR, status, error) {
      console.error("Error fetching contact messages:", error);
      const msg = jqXHR?.responseJSON?.message || jqXHR?.responseJSON?.error || "Failed to load contact messages";
      toastr.error(msg);
    });
  },

  getById: function (id, callback) {
    RestClient.get("contactmessages/" + id, function (data) {
      if (callback) callback(data);
    }, function (jqXHR, status, error) {
      console.error("Error fetching contact message:", error);
      const msg = jqXHR?.responseJSON?.message || jqXHR?.responseJSON?.error || "Failed to load contact message";
      toastr.error(msg);
    });
  },

  getByUserId: function (user_id, callback) {
    RestClient.get("contactmessages/user/" + user_id, function (data) {
      const list = Array.isArray(data) ? data : (data.data || []);
      if (callback) callback(list);
    }, function (jqXHR, status, error) {
      console.error("Error fetching user contact messages:", error);
      const msg = jqXHR?.responseJSON?.message || jqXHR?.responseJSON?.error || "Failed to load user contact messages";
      toastr.error(msg);
    });
  },

  create: function (payload, callback) {
    RestClient.post("contactmessages", payload, function (response) {
      toastr.success("Message sent successfully");
      if (callback) callback(response);
    }, function (jqXHR, status, error) {
      console.error("Error creating contact message:", error);
      const msg = jqXHR?.responseJSON?.message || jqXHR?.responseJSON?.error || "Failed to send message";
      toastr.error(msg);
    });
  },

  update: function (id, payload, callback) {
    RestClient.put("contactmessages/" + id, payload, function (response) {
      toastr.success("Message updated successfully");
      if (callback) callback(response);
    }, function (jqXHR, status, error) {
      console.error("Error updating contact message:", error);
      const msg = jqXHR?.responseJSON?.message || jqXHR?.responseJSON?.error || "Failed to update message";
      toastr.error(msg);
    });
  },

  delete: function (id, callback) {
    RestClient.delete("contactmessages/" + id, null, function (response) {
      toastr.success("Message deleted successfully");
      if (callback) callback(response);
    }, function (jqXHR, status, error) {
      console.error("Error deleting contact message:", error);
      const msg = jqXHR?.responseJSON?.message || jqXHR?.responseJSON?.error || "Failed to delete message";
      toastr.error(msg);
    });
  }
};
