let ReservationService = {

  getAll: function (callback) {
    RestClient.get(
      "reservations",
      function (data) {
        const list = Array.isArray(data) ? data : (data.data || []);
        if (callback) callback(list);
      },
      function (jqXHR) {
        console.error("Error fetching reservations:", jqXHR);
        const msg =
          jqXHR?.responseJSON?.message ||
          jqXHR?.responseJSON?.error ||
          "Failed to load reservations";
        toastr.error(msg);
      }
    );
  },

  getById: function (id, callback) {
    RestClient.get(
      "reservations/" + id,
      function (data) {
        if (callback) callback(data);
      },
      function (jqXHR) {
        console.error("Error fetching reservation:", jqXHR);
        const msg =
          jqXHR?.responseJSON?.message ||
          jqXHR?.responseJSON?.error ||
          "Failed to load reservation";
        toastr.error(msg);
      }
    );
  },

  create: function (payload, callback) {
  if (!payload || !payload.reservation_date) {
    toastr.error("Reservation date is required.");
    return;
  }

  RestClient.post(
    "reservations",
    payload,
    function (response) {
      toastr.success("Reservation created successfully");
      if (callback) callback(response);
    },
    function (jqXHR) {
      console.error("Error creating reservation:", jqXHR);
      const msg =
        jqXHR?.responseJSON?.message ||
        jqXHR?.responseJSON?.error ||
        "Failed to create reservation";
      toastr.error(msg);
    }
  );
},


  update: function (id, payload, callback) {
    RestClient.put(
      "reservations/" + id,
      payload,
      function (response) {
        toastr.success("Reservation updated successfully");
        if (callback) callback(response);
      },
      function (jqXHR) {
        console.error("Error updating reservation:", jqXHR);
        const msg =
          jqXHR?.responseJSON?.message ||
          jqXHR?.responseJSON?.error ||
          "Failed to update reservation";
        toastr.error(msg);
      }
    );
  },

  patch: function (id, payload, callback) {
    RestClient.patch(
      "reservations/" + id,
      payload,
      function (response) {
        toastr.success("Reservation updated");
        if (callback) callback(response);
      },
      function (jqXHR) {
        console.error("Error cancelling reservation:", jqXHR);
        const msg =
          jqXHR?.responseJSON?.message ||
          jqXHR?.responseJSON?.error ||
          "Failed to cancel reservation";
        toastr.error(msg);
      }
    );
  },

  delete: function (id, callback) {
    RestClient.delete(
      "reservations/" + id,
      null,
      function (response) {
        toastr.success("Reservation deleted successfully");
        if (callback) callback(response);
      },
      function (jqXHR) {
        console.error("Error deleting reservation:", jqXHR);
        const msg =
          jqXHR?.responseJSON?.message ||
          jqXHR?.responseJSON?.error ||
          "Failed to delete reservation";
        toastr.error(msg);
      }
    );
  }
};
