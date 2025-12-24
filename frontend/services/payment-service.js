let PaymentService = {
  getAll: function (callback) {
    RestClient.get("payments", function (data) {
      const list = Array.isArray(data) ? data : (data.data || []);
      if (callback) callback(list);
    }, function (jqXHR, status, error) {
      console.error("Error fetching payments:", error);
      const msg = jqXHR?.responseJSON?.message || jqXHR?.responseJSON?.error || "Failed to load payments";
      toastr.error(msg);
    });
  },

  getById: function (id, callback) {
    RestClient.get("payments/" + id, function (data) {
      if (callback) callback(data);
    }, function (jqXHR, status, error) {
      console.error("Error fetching payment:", error);
      const msg = jqXHR?.responseJSON?.message || jqXHR?.responseJSON?.error || "Failed to load payment";
      toastr.error(msg);
    });
  },

  create: function (payload, callback) {
    RestClient.post("payments", payload, function (response) {
      toastr.success("Payment created successfully");
      if (callback) callback(response);
    }, function (jqXHR, status, error) {
      console.error("Error creating payment:", error);
      const msg = jqXHR?.responseJSON?.message || jqXHR?.responseJSON?.error || "Failed to create payment";
      toastr.error(msg);
    });
  },

  update: function (id, payload, callback) {
    RestClient.put("payments/" + id, payload, function (response) {
      toastr.success("Payment updated successfully");
      if (callback) callback(response);
    }, function (jqXHR, status, error) {
      console.error("Error updating payment:", error);
      const msg = jqXHR?.responseJSON?.message || jqXHR?.responseJSON?.error || "Failed to update payment";
      toastr.error(msg);
    });
  },

  delete: function (id, callback) {
    RestClient.delete("payments/" + id, null, function (response) {
      toastr.success("Payment deleted successfully");
      if (callback) callback(response);
    }, function (jqXHR, status, error) {
      console.error("Error deleting payment:", error);
      const msg = jqXHR?.responseJSON?.message || jqXHR?.responseJSON?.error || "Failed to delete payment";
      toastr.error(msg);
    });
  }
};
