/*!
* Start Bootstrap - Creative v7.0.7 (https://startbootstrap.com/theme/creative)
* Copyright 2013-2023 Start Bootstrap
* Licensed under MIT (https://github.com/StartBootstrap/startbootstrap-creative/blob/master/LICENSE)
*/
//
// Scripts
//

var app = $.spapp({
  defaultView: "home",
  templateDir: "views/"
});

window.addEventListener('DOMContentLoaded', event => {

  var navbarShrink = function () {
    const navbarCollapsible = document.body.querySelector('#mainNav');
    if (!navbarCollapsible) return;

    if (window.scrollY === 0) {
      navbarCollapsible.classList.remove('navbar-shrink');
    } else {
      navbarCollapsible.classList.add('navbar-shrink');
    }
  };

  navbarShrink();

  document.addEventListener('scroll', navbarShrink);

  const mainNav = document.body.querySelector('#mainNav');
  if (mainNav) {
    new bootstrap.ScrollSpy(document.body, {
      target: '#mainNav',
      rootMargin: '0px 0px -40%',
    });
  }

  const navbarToggler = document.body.querySelector('.navbar-toggler');
  const responsiveNavItems = [].slice.call(
    document.querySelectorAll('#navbarResponsive .nav-link')
  );

  responsiveNavItems.map(function (responsiveNavItem) {
    responsiveNavItem.addEventListener('click', () => {
      if (window.getComputedStyle(navbarToggler).display !== 'none') {
        navbarToggler.click();
      }
    });
  });

  new SimpleLightbox({
    elements: '#portfolio a.portfolio-box'
  });

  if (window.UserService && typeof UserService.syncNav === "function") {
    UserService.syncNav();
  }
});

function setActiveLink() {
  const current = window.location.hash || '#home';
  document.querySelectorAll('#navbarResponsive .nav-link').forEach(a => {
    a.classList.toggle('active', a.getAttribute('href') === current);
  });
}

window.addEventListener('hashchange', setActiveLink);
window.addEventListener('DOMContentLoaded', setActiveLink);

window.addEventListener('hashchange', function () {
  const hash = window.location.hash.substring(1); 
  const section = document.getElementById(hash);
  if (section && section.dataset.load) {
    if ($.fn.spapp && $("#spapp").length) {
      $("#spapp").spapp("load", hash);
    }
  }

  if (window.UserService && typeof UserService.syncNav === "function") {
    UserService.syncNav();
  }
});

function applyNavbarTheme() {
  const nav = document.getElementById("mainNav");
  if (!nav) return;

  const hash = window.location.hash || "#home";

  if (hash === "#dashboard" || hash === "#login" || hash === "#admin") {
    nav.classList.add("navbar-shrink");
    return;
  }

  if (window.scrollY === 0) {
    nav.classList.remove("navbar-shrink");
  } else {
    nav.classList.add("navbar-shrink");
  }
}

window.addEventListener("DOMContentLoaded", applyNavbarTheme);
window.addEventListener("hashchange", applyNavbarTheme);
window.addEventListener("scroll", applyNavbarTheme);

app.run();
