(function ($) {
  "use strict";

  var spinner = function () {
    setTimeout(function () {
      if ($("#spinner").length > 0) {
        $("#spinner").removeClass("show");
      }
    }, 1);
  };
  spinner();

  new WOW().init();

  $(window).scroll(function () {
    if ($(this).scrollTop() > 300) {
      $(".sticky-top").css("top", "0px");
    } else {
      $(".sticky-top").css("top", "-100px");
    }
  });

  const $dropdown = $(".dropdown");
  const $dropdownToggle = $(".dropdown-toggle");
  const $dropdownMenu = $(".dropdown-menu");
  const showClass = "show";

  $(window).on("load resize", function () {
    if (this.matchMedia("(min-width: 992px)").matches) {
      $dropdown.hover(
        function () {
          const $this = $(this);
          $this.addClass(showClass);
          $this.find($dropdownToggle).attr("aria-expanded", "true");
          $this.find($dropdownMenu).addClass(showClass);
        },
        function () {
          const $this = $(this);
          $this.removeClass(showClass);
          $this.find($dropdownToggle).attr("aria-expanded", "false");
          $this.find($dropdownMenu).removeClass(showClass);
        }
      );
    } else {
      $dropdown.off("mouseenter mouseleave");
    }
  });

  $(window).scroll(function () {
    if ($(this).scrollTop() > 300) {
      $(".back-to-top").fadeIn("slow");
    } else {
      $(".back-to-top").fadeOut("slow");
    }
  });
  $(".back-to-top").click(function () {
    $("html, body").animate({ scrollTop: 0 }, 1500, "easeInOutExpo");
    return false;
  });

  $(".header-carousel").owlCarousel({
    autoplay: true,
    smartSpeed: 1500,
    items: 1,
    dots: false,
    loop: true,
    nav: true,
    navText: [
      '<i class="bi bi-chevron-left"></i>',
      '<i class="bi bi-chevron-right"></i>',
    ],
  });

  $(".testimonial-carousel").owlCarousel({
    autoplay: true,
    smartSpeed: 1000,
    center: true,
    margin: 24,
    dots: true,
    loop: true,
    nav: false,
    responsive: {
      0: {
        items: 1,
      },
      768: {
        items: 2,
      },
      992: {
        items: 3,
      },
    },
  });
})(jQuery);

function validateForm() {
  let valid = true;

  document.getElementById("nameError").textContent = "";
  document.getElementById("emailError").textContent = "";
  document.getElementById("passwordError").textContent = "";
  document.getElementById("confirmPasswordError").textContent = "";

  const name = document.getElementById("name").value.trim();
  const email = document.getElementById("email").value.trim();
  const password = document.getElementById("password").value.trim();
  const confirmPassword = document
    .getElementById("confirm_password")
    .value.trim();

  if (name.length < 2) {
    document.getElementById("nameError").textContent =
      "Name must be at least 2 characters long.";
    valid = false;
  }

  const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!emailRegex.test(email)) {
    document.getElementById("emailError").textContent =
      "Please enter a valid email address.";
    valid = false;
  }

  const passwordRegex = /^(?=.*[a-zA-Z])(?=.*\d)[A-Za-z\d]{8,}$/;
  if (!passwordRegex.test(password)) {
    document.getElementById("passwordError").textContent =
      "Password must be at least 8 characters long and contain both letters and numbers.";
    valid = false;
  }

  const confirmPasswordRegex = /^(?=.*[a-zA-Z])(?=.*\d)[A-Za-z\d]{8,}$/;
  if (!confirmPasswordRegex.test(confirmPassword)) {
    document.getElementById("confirmPasswordError").textContent =
      "confirm Password must be at least 8 characters long and contain both letters and numbers.";
    valid = false;
  }

  if (password !== confirmPassword) {
    document.getElementById("confirmPasswordError").textContent =
      "Passwords do not match.";
    valid = false;
  }

  if (valid) {
    $.ajax({
      url: "../../Model/user/register.php",
      type: "POST",
      data: {
        name: name,
        email: email,
        password: password,
      },
      success: function (response) {
        const jsonResponse = JSON.parse(response);
        if (jsonResponse.status === "success") {
          $("#responseMessage").html(
            '<p style="color: green;">' + jsonResponse.message + "</p>"
          );

          setTimeout(function () {
            window.location.href = "login.php";
          }, 3000);
        } else {
          $("#responseMessage").html(
            '<p style="color: red;">' + jsonResponse.message + "</p>"
          );
        }
      },
      error: function () {
        $("#responseMessage").html(
          '<p style="color: red;">Something went wrong with the request.</p>'
        );
      },
    });
  }
}

$(document).ready(function () {
  $("#registerForm").submit(function (event) {
    event.preventDefault();
    validForm();
  });
});

function clearError(errorId) {
  document.getElementById(errorId).textContent = "";
}

function validateFormLogin(event) {
  event.preventDefault();

  let valid = true;
  document.getElementById("responseMessage").innerHTML = "";
  document.getElementById("emailError").textContent = "";
  document.getElementById("passwordError").textContent = "";

  const email = document.getElementById("email").value.trim();
  const password = document.getElementById("password").value.trim();

  if (email === "") {
    document.getElementById("emailError").textContent = "Email is required.";
    valid = false;
  }

  if (password === "") {
    document.getElementById("passwordError").textContent =
      "Password is required.";
    valid = false;
  }

  if (valid) {
    $.ajax({
      url: "../../Model/user/login.php",
      type: "POST",
      data: {
        email: email,
        password: password,
      },
      success: function (response) {
        const jsonResponse = JSON.parse(response);
        const responseMessage = document.getElementById("responseMessage");

        if (jsonResponse.status === "success") {
          responseMessage.innerHTML = `<p style="color: green;">${jsonResponse.message}</p>`;
          setTimeout(function () {
            window.location.href = "index.php";
          }, 3000);
        } else {
          responseMessage.innerHTML = `<p style="color: red;">${jsonResponse.message}</p>`;
        }
      },
      error: function () {
        document.getElementById("responseMessage").innerHTML =
          '<p style="color: red;">Something went wrong with the request.</p>';
      },
    });
  }
}

let profile = document.querySelector(".profile");
let menu = document.querySelector(".menu");

profile.onclick = function () {
  menu.classList.toggle("active");
};
