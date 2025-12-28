(function () {

  function waitForDeps() {
    if (
      typeof Utils === "undefined" ||
      typeof Constants === "undefined" ||
      typeof CourtService === "undefined" ||
      typeof TimeslotService === "undefined" ||
      typeof RestClient === "undefined" ||
      typeof $ === "undefined"
    ) {
      setTimeout(waitForDeps, 100);
      return;
    }

    const user = Utils.parseJwt(localStorage.getItem("user_token"))?.user;

    if (!user || String(user.role).toLowerCase() !== String(Constants.ADMIN_ROLE).toLowerCase()) {
      toastr.error("Access denied. Admins only.");
      window.location.hash = "#home";
      return;
    }

    AdminPanel.init();
  }

  function applyNavbarTheme() {
    const nav = document.getElementById("mainNav");
    if (!nav) return;

    const hash = window.location.hash || "#home";

    if (hash === "#dashboard" || hash === "#login" || hash === "#admin") {
      nav.classList.add("navbar-shrink");
      return;
    }

    if (window.scrollY === 0) nav.classList.remove("navbar-shrink");
    else nav.classList.add("navbar-shrink");
  }
  window.addEventListener("DOMContentLoaded", applyNavbarTheme);
  window.addEventListener("hashchange", applyNavbarTheme);
  window.addEventListener("scroll", applyNavbarTheme);

  function safeDateLabel(r) {
    const raw = r.reservation_date || r.date || r.created_at;
    if (!raw) return "N/A";
    if (/^\d{4}-\d{2}-\d{2}$/.test(String(raw))) return raw;
    const d = new Date(raw);
    if (isNaN(d.getTime())) return String(raw);
    return d.toLocaleDateString();
  }

  const AdminReservationApi = {
    getAll: function (callback) {
      RestClient.get("reservations", function (data) {
        const list = Array.isArray(data) ? data : (data.data || []);
        callback && callback(list);
      }, function (jqXHR) {
        toastr.error(jqXHR?.responseJSON?.message || jqXHR?.responseJSON?.error || "Failed to load reservations");
      });
    },
    update: function (id, payload, callback) {
      RestClient.put("reservations/" + id, payload, function (res) {
        callback && callback(res);
      }, function (jqXHR) {
        toastr.error(jqXHR?.responseJSON?.message || jqXHR?.responseJSON?.error || "Failed to update reservation");
      });
    }
  };

  const AdminUserApi = {
    cache: {},
    getById: function (id, callback) {
      if (!id) return callback && callback(null);
      if (AdminUserApi.cache[id]) return callback && callback(AdminUserApi.cache[id]);

      RestClient.get("users/" + id, function (data) {
        AdminUserApi.cache[id] = data;
        callback && callback(data);
      }, function () {
        callback && callback(null);
      });
    }
  };

  const AdminCourtApi = {
    cache: {},
    getById: function (id, callback) {
      if (!id) return callback && callback(null);
      if (AdminCourtApi.cache[id]) return callback && callback(AdminCourtApi.cache[id]);

      CourtService.getById(id, function (court) {
        AdminCourtApi.cache[id] = court;
        callback && callback(court);
      });
    }
  };

  const AdminTimeslotApi = {
    map: {},
    loadAll: function (callback) {
      TimeslotService.getAll(function (slots) {
        AdminTimeslotApi.map = {};
        (slots || []).forEach(s => {
          AdminTimeslotApi.map[s.id] = {
            date: s.date,
            label: `${s.start_time} - ${s.end_time}`
          };
        });
        callback && callback();
      });
    },
    label: function (id) {
      return AdminTimeslotApi.map[id]?.label || (id ? `Slot #${id}` : "N/A");
    },
    date: function (id) {
      return AdminTimeslotApi.map[id]?.date || "N/A";
    }
  };

  const AdminPanel = {
    init: function () {
      $('#tab-courts').on('click', function () { AdminPanel.showTab('courts'); });
      $('#tab-reservations').on('click', function () { AdminPanel.showTab('reservations'); });

      $('#btnAddCourt').on('click', AdminCourts.openAdd);
      $('#btnSaveCourt').on('click', AdminCourts.save);

      $('#courtFilterStatus').on('change', AdminCourts.render);
      $('#courtSearchBox').on('input', AdminCourts.render);

      $('#btnRefreshReservations').on('click', AdminReservations.load);
      $('#filterStatus').on('change', AdminReservations.render);
      $('#searchBox').on('input', AdminReservations.render);

      AdminCourts.load();

      AdminTimeslotApi.loadAll(function () {
        AdminReservations.load();
      });
    },

    showTab: function (tab) {
      $('#tab-courts').toggleClass('active', tab === 'courts');
      $('#tab-reservations').toggleClass('active', tab === 'reservations');
      $('#panel-courts').toggle(tab === 'courts');
      $('#panel-reservations').toggle(tab === 'reservations');
    }
  };

  const AdminCourts = {
    raw: [],

    load: function () {
      CourtService.getAll(function (courts) {
        AdminCourts.raw = courts || [];
        AdminCourts.render();
      });
    },

    render: function () {
      const statusFilter = ($('#courtFilterStatus').val() || '').trim();
      const q = ($('#courtSearchBox').val() || '').toLowerCase().trim();

      let list = [...(AdminCourts.raw || [])];

      if (statusFilter) {
        list = list.filter(c => String(c.status) === String(statusFilter));
      }

      if (q) {
        list = list.filter(c => {
          const name = (c.name || '').toLowerCase();
          const type = (c.type || '').toLowerCase();
          const idStr = String(c.id || '');
          return name.includes(q) || type.includes(q) || idStr.includes(q);
        });
      }

      const total = (AdminCourts.raw || []).length;
      const available = (AdminCourts.raw || []).filter(c => String(c.status).toLowerCase() === 'available').length;
      const unavailable = total - available;
      $('#courtsCounts').text(`Total: ${total} | Available: ${available} | Unavailable: ${unavailable}`);

      const tbody = $('#courts-table-body');
      tbody.empty();

      if (!list.length) {
        tbody.append(`<tr><td colspan="6" class="text-center text-muted py-4">No courts found</td></tr>`);
        return;
      }

      list.forEach(c => {
        const statusBadge =
          String(c.status).toLowerCase() === 'available'
            ? `<span class="badge bg-success">Available</span>`
            : `<span class="badge bg-secondary">Unavailable</span>`;

        const price = (c.price_per_hour !== undefined && c.price_per_hour !== null)
          ? `${Number(c.price_per_hour).toFixed(2)} KM`
          : 'N/A';

        tbody.append(`
          <tr>
            <td>${c.id}</td>
            <td><strong>${c.name || ''}</strong></td>
            <td>${c.type || ''}</td>
            <td>${price}</td>
            <td>${statusBadge}</td>
            <td class="text-nowrap">
              <div class="d-flex flex-row flex-nowrap gap-2">
                <button class="btn btn-sm btn-outline-primary" onclick="AdminCourts.openEdit(${c.id})">Edit</button>
                <button class="btn btn-sm btn-outline-danger" onclick="AdminCourts.remove(${c.id})">Delete</button>
              </div>
            </td>
          </tr>
        `);
      });
    },

    openAdd: function () {
      $('#courtModalTitle').text("Add Court");
      $('#court_id').val('');
      $('#court_name').val('');
      $('#court_type').val('');
      $('#court_price').val('');
      $('#court_status').val('Available');
      $('#courtModal').modal('show');
    },

    openEdit: function (id) {
      CourtService.getById(id, function (c) {
        $('#courtModalTitle').text("Edit Court");
        $('#court_id').val(c.id);
        $('#court_name').val(c.name);
        $('#court_type').val(c.type);
        $('#court_price').val(c.price_per_hour);
        $('#court_status').val(c.status);
        $('#courtModal').modal('show');
      });
    },

    save: function () {
      const id = $('#court_id').val();

      const basePayload = {
        name: String($('#court_name').val() || '').trim(),
        type: String($('#court_type').val() || '').trim(),
        price_per_hour: parseFloat($('#court_price').val()),
        status: $('#court_status').val()
      };

      if (!basePayload.name || !basePayload.type || isNaN(basePayload.price_per_hour) || basePayload.price_per_hour <= 0) {
        toastr.error("All fields are required");
        return;
      }

      if (id) {
        CourtService.getById(id, function (existing) {
          const payload = {
            name: basePayload.name,
            type: basePayload.type,
            price_per_hour: basePayload.price_per_hour,
            status: basePayload.status,
            location: existing?.location ?? ""
          };

          CourtService.update(id, payload, function () {
            $('#courtModal').modal('hide');
            AdminCourts.load();
          });
        });

      } else {
        const payload = { ...basePayload, location: "" };

        CourtService.create(payload, function () {
          $('#courtModal').modal('hide');
          AdminCourts.load();
        });
      }
    },

    remove: function (id) {
      if (!confirm("Delete this court?")) return;

      AdminCourts.raw = (AdminCourts.raw || []).filter(c => String(c.id) !== String(id));
      AdminCourts.render();

      CourtService.delete(id, function () {
        toastr.success("Court deleted");
        AdminCourts.load();
      });
    }
  };
  window.AdminCourts = AdminCourts;

  const AdminReservations = {
    raw: [],
    enriched: [],

    load: function () {
      $('#admin-reservations-body').html(`<tr><td colspan="8" class="text-center text-muted py-4">Loading...</td></tr>`);

      AdminReservationApi.getAll(async function (reservations) {
        AdminReservations.raw = reservations || [];

        const tasks = AdminReservations.raw.map(r => new Promise(resolve => {
          AdminUserApi.getById(r.user_id, function (u) {
            AdminCourtApi.getById(r.court_id, function (c) {
              resolve({ ...r, __user: u, __court: c });
            });
          });
        }));

        AdminReservations.enriched = await Promise.all(tasks);
        AdminReservations.render();
      });
    },

    render: function () {
      const statusFilter = $('#filterStatus').val();
      const q = ($('#searchBox').val() || '').toLowerCase().trim();

      let list = [...(AdminReservations.enriched || [])];

      if (statusFilter) {
        list = list.filter(r => String(r.status) === String(statusFilter));
      }

      if (q) {
        list = list.filter(r => {
          const userName = (r.__user?.name || '').toLowerCase();
          const userEmail = (r.__user?.email || '').toLowerCase();
          const courtName = (r.__court?.name || '').toLowerCase();
          const idStr = String(r.id || '');
          return userName.includes(q) || userEmail.includes(q) || courtName.includes(q) || idStr.includes(q);
        });
      }

      const total = (AdminReservations.enriched || []).length;
      const pending = (AdminReservations.enriched || []).filter(r => r.status === 'Pending').length;
      const confirmed = (AdminReservations.enriched || []).filter(r => r.status === 'Confirmed').length;
      const cancelled = (AdminReservations.enriched || []).filter(r => r.status === 'Cancelled').length;
      $('#reservationCounts').text(`Total: ${total} | Pending: ${pending} | Confirmed: ${confirmed} | Cancelled: ${cancelled}`);

      const tbody = $('#admin-reservations-body');
      tbody.empty();

      if (!list.length) {
        tbody.append(`<tr><td colspan="8" class="text-center text-muted py-4">No reservations found</td></tr>`);
        return;
      }

      list.forEach(r => {
        const userLabel = r.__user
          ? `<div><strong>${r.__user.name || 'N/A'}</strong></div><div class="text-muted small">${r.__user.email || ''}</div>`
          : `<div><strong>User #${r.user_id}</strong></div>`;

        const dateLabel = safeDateLabel(r) !== "N/A" ? safeDateLabel(r) : AdminTimeslotApi.date(r.slot_id);
        const courtLabel = r.__court?.name || `Court #${r.court_id}`;
        const slot = r.slot_id ? AdminTimeslotApi.label(r.slot_id) : "N/A";

        const price =
          (r.total_price !== undefined && r.total_price !== null)
            ? `${Number(r.total_price).toFixed(2)} KM`
            : "N/A";

        const statusBadge =
          r.status === 'Pending' ? `<span class="badge bg-warning text-dark">Pending</span>` :
          r.status === 'Confirmed' ? `<span class="badge bg-success">Confirmed</span>` :
          `<span class="badge bg-secondary">Cancelled</span>`;

        const canConfirm = r.status !== 'Confirmed';
        const canCancel = r.status !== 'Cancelled';
        const canPending = r.status !== 'Pending';

        tbody.append(`
          <tr>
            <td>${r.id}</td>
            <td>${userLabel}</td>
            <td>${dateLabel}</td>
            <td>${courtLabel}</td>
            <td>${slot}</td>
            <td>${price}</td>
            <td>${statusBadge}</td>
            <td class="text-nowrap">
              <div class="d-flex flex-row flex-nowrap gap-2 justify-content-center align-items-center">
                <button class="btn btn-sm btn-outline-success" ${canConfirm ? '' : 'disabled'}
                  onclick="AdminReservations.setStatus(${r.id}, 'Confirmed')">
                  Confirm
                </button>

                <button class="btn btn-sm btn-outline-warning" ${canPending ? '' : 'disabled'}
                  onclick="AdminReservations.setStatus(${r.id}, 'Pending')">
                  Set Pending
                </button>

                <button class="btn btn-sm btn-outline-secondary" ${canCancel ? '' : 'disabled'}
                  onclick="AdminReservations.setStatus(${r.id}, 'Cancelled')">
                  Cancel
                </button>
              </div>
            </td>
          </tr>
        `);
      });
    },

    setStatus: function (id, status) {
      const r = (AdminReservations.raw || []).find(x => String(x.id) === String(id));
      if (!r) {
        toastr.error("Reservation not found in memory");
        return;
      }

      const payload = {
        user_id: r.user_id,
        court_id: r.court_id,
        reservation_date: r.reservation_date || r.date || AdminTimeslotApi.date(r.slot_id),
        slot_id: r.slot_id,
        total_price: r.total_price,
        status: status,
      };

      AdminReservationApi.update(id, payload, function () {
        toastr.success("Reservation updated");
        AdminReservations.load();
      });
    }
  };
  window.AdminReservations = AdminReservations;

  function startAdmin() {
  if ((window.location.hash || "").toLowerCase() !== "#admin") return;
  if ($("#tab-courts").length === 0) {
    setTimeout(startAdmin, 100);
    return;
  }
  waitForDeps();
}

window.addEventListener("hashchange", startAdmin);
window.addEventListener("DOMContentLoaded", startAdmin);


})();
