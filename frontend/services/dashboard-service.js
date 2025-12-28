(function () {

  function getRawToken() {
    let token = localStorage.getItem("user_token") || localStorage.getItem("token");
    if (!token) return null;
    if (token.startsWith("Bearer ")) token = token.substring(7);
    return token;
  }

  function startDashboard() {
    if ((window.location.hash || "").toLowerCase() !== "#dashboard") return;

    if ($("#dashboard-username").length === 0 || $("#dashboard-reservations-body").length === 0) {
      setTimeout(startDashboard, 100);
      return;
    }

    if (
      typeof Utils === "undefined" ||
      typeof ReservationService === "undefined" ||
      typeof CourtService === "undefined" ||
      typeof TimeslotService === "undefined" ||
      typeof $ === "undefined"
    ) {
      setTimeout(startDashboard, 100);
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

    const displayName = user.full_name || user.fullName || user.name || user.email || "User";
    $("#dashboard-username").text(displayName);

    DashboardService.init();
  }

  window.DashboardService = {
    courtsMap: {},
    slotsMap: {},

    init: function () {
      const loadCourtsP = new Promise((resolve, reject) => {
        CourtService.getAll((courts) => {
          try {
            DashboardService.courtsMap = {};
            (courts || []).forEach((c) => {
              DashboardService.courtsMap[c.id] = c;
            });
            resolve();
          } catch (e) {
            reject(e);
          }
        });
      });

      const loadSlotsP = new Promise((resolve, reject) => {
        TimeslotService.getAll((slots) => {
          try {
            DashboardService.slotsMap = {};
            (slots || []).forEach((s) => {
              DashboardService.slotsMap[s.id] = s;
            });
            resolve();
          } catch (e) {
            reject(e);
          }
        });
      });

      Promise.all([loadCourtsP, loadSlotsP])
        .then(() => DashboardService.loadReservations())
        .catch((e) => {
          console.error(e);
          toastr.error("Failed to load courts/slots");
        });
    },

    statusBadge: function (status) {
      const s = String(status || "").toLowerCase();
      if (s === "pending") return `<span class="badge bg-warning text-dark">Pending</span>`;
      if (s === "confirmed" || s === "approved") return `<span class="badge bg-success">Confirmed</span>`;
      if (s === "cancelled" || s === "canceled") return `<span class="badge bg-secondary">Cancelled</span>`;
      return `<span class="badge bg-info text-dark">${status || "N/A"}</span>`;
    },

    setStats: function (mine) {
      const total = mine.length;
      const pending = mine.filter((r) => String(r.status).toLowerCase() === "pending").length;
      const confirmed = mine.filter((r) => ["confirmed", "approved"].includes(String(r.status).toLowerCase())).length;
      const cancelled = mine.filter((r) => ["cancelled", "canceled"].includes(String(r.status).toLowerCase())).length;

      $("#reservation-count").text(total);
      $("#reservation-pending").text(pending);
      $("#reservation-confirmed").text(confirmed);
      $("#reservation-cancelled").text(cancelled);
    },

    loadReservations: function () {
      const token = getRawToken();
      const decoded = token ? Utils.parseJwt(token) : null;
      const user = decoded?.user || decoded;

      ReservationService.getAll(function (data) {
        const tbody = $("#dashboard-reservations-body");
        tbody.empty();

        const mine = (data || []).filter((r) => String(r.user_id) === String(user.id));
        DashboardService.setStats(mine);

        if (mine.length === 0) {
          tbody.append(`
            <tr>
              <td colspan="7" class="text-center text-muted py-5">
                <i class="bi bi-calendar-x fs-3 d-block mb-2"></i>
                No reservations yet
              </td>
            </tr>
          `);
          return;
        }

        mine.forEach((r) => {
          const courtName =
            DashboardService.courtsMap[r.court_id]?.name ||
            r.court_name ||
            "N/A";

          const slotObj = DashboardService.slotsMap[r.slot_id];

          const dateLabel =
            r.reservation_date ||
            r.date ||
            (slotObj && slotObj.date ? slotObj.date : null) ||
            "N/A";

          const slotLabel =
            (slotObj ? `${slotObj.start_time} - ${slotObj.end_time}` : null) ||
            r.slot_label ||
            (r.start_time && r.end_time ? `${r.start_time} - ${r.end_time}` : null) ||
            "N/A";

          const price =
            r.total_price !== undefined && r.total_price !== null
              ? `${Number(r.total_price).toFixed(2)} KM`
              : "N/A";

          const statusHtml = DashboardService.statusBadge(r.status);
          const isPending = String(r.status).toLowerCase() === "pending";

          tbody.append(`
            <tr>
              <td>${r.id}</td>
              <td>${courtName}</td>
              <td>${dateLabel}</td>
              <td>${slotLabel}</td>
              <td>${price}</td>
              <td>${statusHtml}</td>
              <td>
                ${
                  isPending
                    ? `<button class="btn btn-sm btn-outline-danger" onclick="DashboardService.cancel(${r.id})">
                        <i class="bi bi-x-circle me-1"></i> Cancel
                      </button>`
                    : `<span class="text-muted">—</span>`
                }
              </td>
            </tr>
          `);
        });
      });
    },

    cancel: function (id) {
      if (!confirm("Are you sure you want to cancel this reservation?")) return;

      ReservationService.delete(id);

      setTimeout(() => {
        toastr.success("Reservation cancelled");
        DashboardService.loadReservations();
      }, 300);
    },
  };

  window.addEventListener("hashchange", startDashboard);
  window.addEventListener("DOMContentLoaded", startDashboard);

})();
