let TimeslotService = {
  getAll: function (callback) {
    RestClient.get("timeslots",
      function (response) {
        const slots = Array.isArray(response)
          ? response
          : (response.data || []);

        if (callback) callback(slots);
      },
      function (jqXHR) {
        console.error("Error loading time slots:", jqXHR);
        const msg =
          jqXHR?.responseJSON?.message ||
          jqXHR?.responseJSON?.error ||
          "Failed to load time slots";
        toastr.error(msg);
      }
    );
  },

  getById: function (id, callback) {
    RestClient.get("timeslots/" + id,
      function (response) {
        if (callback) callback(response);
      },
      function (jqXHR) {
        console.error("Error loading time slot:", jqXHR);
        const msg =
          jqXHR?.responseJSON?.message ||
          jqXHR?.responseJSON?.error ||
          "Failed to load time slot";
        toastr.error(msg);
      }
    );
  }

};
