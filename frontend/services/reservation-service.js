let ReservationService = {
  getAll: function (callback) {
    RestClient.get(
      "reservations",
      function (data) {
        const list = Array.isArray(data) ? data : data.data || [];
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

  create: function (payload, callback, errorCallback) {
    if (!payload || !payload.reservation_date) {
      toastr.error("Reservation date is required.");
      if (errorCallback) errorCallback({ message: "Reservation date is required." });
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

        let msg =
          jqXHR?.responseJSON?.error ||
          jqXHR?.responseJSON?.message ||
          "Failed to create reservation";

        if (jqXHR?.status === 403) {
          msg = "Admins are not allowed to create reservations.";
        }

        toastr.error(msg);
        if (errorCallback) errorCallback(jqXHR);
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
  },
};

(function () {
  function getRawToken() {
    let token =
      localStorage.getItem("user_token") || localStorage.getItem("token");
    if (!token) return null;
    if (token.startsWith("Bearer ")) token = token.substring(7);
    return token;
  }

  function resetReservationUI() {
    try {
      $("#submitSuccessMessage").addClass("d-none");
      $("#submitErrorMessage").addClass("d-none");
      $("#submitButton").prop("disabled", false);
    } catch (e) {}
  }

  function clearReservationForm() {
    try {
      $("#court").val("");
      $("#date").val("");
      $("#slot").val("");
    } catch (e) {}
  }

  function waitForDepsAndInit() {
    if ((window.location.hash || "").toLowerCase() !== "#reservation") return;

    if ($("#reservationForm").length === 0) {
      setTimeout(waitForDepsAndInit, 100);
      return;
    }

    resetReservationUI();

    if (
      typeof Utils === "undefined" ||
      typeof ReservationService === "undefined" ||
      typeof CourtService === "undefined" ||
      typeof TimeslotService === "undefined" ||
      typeof $ === "undefined"
    ) {
      setTimeout(waitForDepsAndInit, 100);
      return;
    }

    const token = getRawToken();
    if (!token) {
      toastr.error("Please login first");
      window.location.hash = "#login";
      return;
    }

    let decoded = null;
    try {
      decoded = Utils.parseJwt(token);
    } catch (e) {
      decoded = null;
    }

    const user = decoded?.user || decoded || null;
    if (!user) {
      toastr.error("Please login first");
      window.location.hash = "#login";
      return;
    }

    ReservationPage.init(user);
  }

  const ReservationPage = {
    init: function (user) {
      Promise.all([this.loadCourts(), this.loadSlots()])
        .then(() => this.bindSubmit(user))
        .catch((e) => {
          console.error(e);
          toastr.error("Failed to load courts/slots");
        });
    },

    loadCourts: function () {
      return new Promise((resolve, reject) => {
        CourtService.getAll((courts) => {
          try {
            const list = Array.isArray(courts) ? courts : courts?.data || [];
            const select = $("#court");
            select.empty();
            select.append('<option value="">Select Court</option>');

            (list || []).forEach((c) => {
              select.append(`<option value="${c.id}">${c.name}</option>`);
            });

            resolve();
          } catch (err) {
            reject(err);
          }
        });
      });
    },

    loadSlots: function () {
      return new Promise((resolve, reject) => {
        TimeslotService.getAll((slots) => {
          try {
            const list = Array.isArray(slots) ? slots : slots?.data || [];
            const select = $("#slot");
            select.empty();
            select.append('<option value="">Select time slot</option>');

            (list || []).forEach((s) => {
              select.append(
                `<option value="${s.id}">${s.start_time} - ${s.end_time}</option>`
              );
            });

            resolve();
          } catch (err) {
            reject(err);
          }
        });
      });
    },

    bindSubmit: function (user) {
      $(document)
        .off("submit.reservation")
        .on("submit.reservation", "#reservationForm", function (e) {
          e.preventDefault();

          resetReservationUI();

          const payload = {
            user_id: Number(user.id),
            court_id: Number($("#court").val()),
            reservation_date: $("#date").val(),
            slot_id: Number($("#slot").val()),
          };

          if (!payload.court_id || !payload.reservation_date || !payload.slot_id) {
            $("#submitErrorMessage").removeClass("d-none");
            toastr.error("All fields are required");
            return;
          }

          if (!/^\d{4}-\d{2}-\d{2}$/.test(String(payload.reservation_date))) {
            $("#submitErrorMessage").removeClass("d-none");
            toastr.error("Invalid date format");
            return;
          }

          $("#submitButton").prop("disabled", true);

          ReservationService.create(
            payload,
            function () {
              $("#submitSuccessMessage").removeClass("d-none");
              clearReservationForm();

              setTimeout(() => {
                resetReservationUI();
                window.location.hash = "#dashboard";

                const refresh = () => {
                  if (
                    window.DashboardService &&
                    typeof window.DashboardService.loadReservations === "function"
                  ) {
                    window.DashboardService.loadReservations();
                  } else {
                    setTimeout(refresh, 100);
                  }
                };
                refresh();
              }, 600);
            },
            function () {
              $("#submitButton").prop("disabled", false);
              $("#submitErrorMessage").removeClass("d-none");
            }
          );
        });
    },
  };

  window.addEventListener("hashchange", waitForDepsAndInit);
  window.addEventListener("DOMContentLoaded", waitForDepsAndInit);
})();
