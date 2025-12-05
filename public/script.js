// script.js

document.addEventListener("DOMContentLoaded", () => {
  const loaderOverlay = document.getElementById("loaderOverlay");

  // ===== CSRF TOKEN FOR LARAVEL =====
  const csrfMeta = document.querySelector('meta[name="csrf-token"]');
  const CSRF_TOKEN = csrfMeta ? csrfMeta.getAttribute("content") : "";
  // ==================================

  if (loaderOverlay) {
    loaderOverlay.classList.add("visible");
    setTimeout(() => loaderOverlay.classList.remove("visible"), 500);
  }

  const header = document.querySelector(".site-header");
  window.addEventListener("scroll", () => {
    if (!header) return;
    header.classList.toggle("header-scrolled", window.scrollY > 10);
  });

  function showStep(stepId) {
    document.querySelectorAll(".view").forEach((view) => {
      view.classList.toggle("active", view.id === stepId);
    });
    window.scrollTo({ top: 0, behavior: "smooth" });
  }

  function setFieldError(input, message) {
    if (!input) return;
    const id = input.getAttribute("id");
    const errorEl = document.querySelector(`[data-error-for="${id}"]`);
    if (message) {
      input.classList.add("field-error");
      if (errorEl) errorEl.textContent = message;
    } else {
      input.classList.remove("field-error");
      if (errorEl) errorEl.textContent = "";
    }
  }

  // ===== UPDATED: sends CSRF with request =====
  async function sendEmailToServer(type, data) {
    const params = new URLSearchParams();
    params.append("type", type);
    params.append("payload", JSON.stringify(data));

    const response = await fetch("/send-email", {
      method: "POST",
      headers: {
        "X-CSRF-TOKEN": CSRF_TOKEN,
        "X-Requested-With": "XMLHttpRequest",
        "Content-Type": "application/x-www-form-urlencoded;charset=UTF-8",
        "Accept": "application/json",
      },
      body: params.toString(),
    });

    let json;
    try {
      json = await response.json();
    } catch (e) {
      console.error("Non-JSON response from /send-email:", await response.text());
      json = {
        success: false,
        message: "Server returned an invalid response.",
      };
    }
    return json;
  }
  // ============================================

  let leadData = null;

  // STEP 1 – lead form
  const leadForm = document.getElementById("leadForm");
  const leadStatus = document.getElementById("leadStatus");

  if (leadForm) {
    leadForm.addEventListener("submit", async (e) => {
      e.preventDefault();

      const nameInput = document.getElementById("name");
      const phoneInput = document.getElementById("phone");
      const emailInput = document.getElementById("email");
      const commentInput = document.getElementById("comment");

      let valid = true;

      if (!nameInput.value.trim()) {
        setFieldError(nameInput, "Please enter your full name.");
        valid = false;
      } else setFieldError(nameInput, "");

      const phoneRegex = /^[6-9][0-9]{9}$/;
      if (!phoneRegex.test(phoneInput.value.trim())) {
        setFieldError(
          phoneInput,
          "Enter a valid 10-digit mobile number starting with 6, 7, 8 or 9."
        );
        valid = false;
      } else setFieldError(phoneInput, "");

      if (!emailInput.validity.valid) {
        setFieldError(emailInput, "Please enter a valid email address.");
        valid = false;
      } else setFieldError(emailInput, "");

      if (!valid) {
        if (leadStatus) {
          leadStatus.textContent = "Please fix the highlighted fields.";
        }
        return;
      }

      leadData = {
        name: nameInput.value.trim(),
        phone: phoneInput.value.trim(),
        email: emailInput.value.trim(),
        comment: commentInput ? commentInput.value.trim() : "",
      };

      if (leadStatus) leadStatus.textContent = "Submitting your details…";
      if (loaderOverlay) loaderOverlay.classList.add("visible");

      let result;
      try {
        result = await sendEmailToServer("lead", leadData);
        console.log("/send-email (lead) result:", result);
      } catch (err) {
        console.error(err);
        result = {
          success: false,
          message: "Network error while sending email.",
        };
      } finally {
        if (loaderOverlay) loaderOverlay.classList.remove("visible");
      }

      if (leadStatus) {
        if (result.success) {
          leadStatus.textContent =
            "Thank you! Your details have been submitted. Check your email.";
        } else {
          leadStatus.textContent =
            "We captured your details, but email failed: " +
            (result.message || "Unknown error.");
        }
      }

      showStep("step-2");
    });
  }

  // Preview video – top hero (loop first 5 seconds)
  const previewVideo = document.getElementById("previewVideo");
  const previewWrapper = document.getElementById("previewVideoWrapper");
  const accessNotice = document.getElementById("accessNotice");

  if (previewVideo) {
    previewVideo.addEventListener("timeupdate", () => {
      if (previewVideo.currentTime > 5) {
        previewVideo.currentTime = 0;
      }
    });
  }

  function scrollToFormAndNotify() {
    if (leadForm) {
      const rect = leadForm.getBoundingClientRect();
      const offset = 120;
      const top = window.pageYOffset + rect.top - offset;
      window.scrollTo({ top, behavior: "smooth" });
    }
    if (accessNotice) {
      accessNotice.classList.add("visible");
      setTimeout(() => accessNotice.classList.remove("visible"), 4000);
    }
  }

  if (previewWrapper) {
    previewWrapper.addEventListener("click", scrollToFormAndNotify);
  }

  // STEP 2 – video player
  const video = document.getElementById("step2Video");
  const overlayBtn = document.getElementById("videoOverlayIcon");
  const playPauseBtn = document.getElementById("playPauseBtn");
  const back10Btn = document.getElementById("back10Btn");
  const forward10Btn = document.getElementById("forward10Btn");
  const muteBtn = document.getElementById("muteBtn");
  const volumeSlider = document.getElementById("volumeSlider");
  const timeline = document.getElementById("timeline");
  const currentTimeEl = document.getElementById("currentTime");
  const totalTimeEl = document.getElementById("totalTime");
  const fullscreenBtn = document.getElementById("fullscreenBtn");
  const fullShell = document.querySelector(".full-video-shell");
  const consultBtn = document.getElementById("consultBtn");

  function formatTime(sec) {
    if (isNaN(sec)) return "0:00";
    const m = Math.floor(sec / 60);
    const s = Math.floor(sec % 60)
      .toString()
      .padStart(2, "0");
    return `${m}:${s}`;
  }

  if (video) {
    video.addEventListener("loadedmetadata", () => {
      if (timeline) timeline.max = video.duration || 0;
      if (totalTimeEl) totalTimeEl.textContent = formatTime(video.duration);
      if (fullShell) fullShell.classList.add("overlay-visible");
    });

    video.addEventListener("timeupdate", () => {
      if (timeline && !timeline.dragging) {
        timeline.value = video.currentTime;
        const progress = (video.currentTime / (video.duration || 1)) * 100;
        timeline.style.setProperty("--progress", `${progress}%`);
      }
      if (currentTimeEl)
        currentTimeEl.textContent = formatTime(video.currentTime);
    });

    const togglePlay = () => {
      if (video.paused) {
        video.play();
        if (playPauseBtn) playPauseBtn.classList.add("is-playing");
        if (fullShell) {
          fullShell.classList.add("controls-visible");
          fullShell.classList.remove("overlay-visible");
        }
      } else {
        video.pause();
        if (playPauseBtn) playPauseBtn.classList.remove("is-playing");
        if (fullShell) fullShell.classList.add("overlay-visible");
      }
    };

    if (overlayBtn) overlayBtn.addEventListener("click", togglePlay);
    if (playPauseBtn) playPauseBtn.addEventListener("click", togglePlay);

    if (back10Btn) {
      back10Btn.addEventListener("click", () => {
        video.currentTime = Math.max(0, video.currentTime - 10);
      });
    }

    if (forward10Btn) {
      forward10Btn.addEventListener("click", () => {
        video.currentTime = Math.min(
          video.duration || 0,
          video.currentTime + 10
        );
      });
    }

    if (muteBtn) {
      muteBtn.addEventListener("click", () => {
        video.muted = !video.muted;
        muteBtn.classList.toggle("is-muted", video.muted);
      });
    }

    if (volumeSlider) {
      volumeSlider.addEventListener("input", () => {
        video.volume = parseFloat(volumeSlider.value);
        video.muted = video.volume === 0;
        if (muteBtn) muteBtn.classList.toggle("is-muted", video.muted);
      });
    }

    if (timeline) {
      timeline.addEventListener("input", () => {
        timeline.dragging = true;
        video.currentTime = parseFloat(timeline.value);
      });
      timeline.addEventListener("change", () => {
        timeline.dragging = false;
      });
    }

    if (fullscreenBtn && fullShell) {
      fullscreenBtn.addEventListener("click", () => {
        const isFull =
          document.fullscreenElement ||
          document.webkitFullscreenElement ||
          document.msFullscreenElement;

        if (!isFull) {
          if (fullShell.requestFullscreen) fullShell.requestFullscreen();
          else if (fullShell.webkitRequestFullscreen)
            fullShell.webkitRequestFullscreen();
          else if (fullShell.msRequestFullscreen)
            fullShell.msRequestFullscreen();
          fullscreenBtn.classList.add("is-fullscreen");
        } else {
          if (document.exitFullscreen) document.exitFullscreen();
          else if (document.webkitExitFullscreen)
            document.webkitExitFullscreen();
          else if (document.msExitFullscreen) document.msExitFullscreen();
          fullscreenBtn.classList.remove("is-fullscreen");
        }
      });
    }
  }

  if (consultBtn) {
    consultBtn.addEventListener("click", () => {
      showStep("step-3");
    });
  }

  // STEP 3 – scheduler
  const calendarDays = document.getElementById("calendarDays");
  const calMonth = document.getElementById("calMonth");
  const calYear = document.getElementById("calYear");
  const calPrev = document.getElementById("calPrev");
  const calNext = document.getElementById("calNext");
  const scheduleDateInput = document.getElementById("scheduleDate");
  const selectedDateLabel = document.getElementById("selectedDateLabel");
  const timeSlotSelect = document.getElementById("timeSlot");
  const scheduleForm = document.getElementById("scheduleForm");
  const scheduleStatus = document.getElementById("scheduleStatus");
  const scheduleContent = document.getElementById("scheduleContent");
  const finalMessage = document.getElementById("finalMessage");

  let currentMonth, currentYear, selectedDate;

  function buildSlotsForDate(date) {
    if (!timeSlotSelect) return;
    timeSlotSelect.innerHTML = '<option value="">Select a time slot</option>';
    const hours = [10, 11, 12, 14, 15, 16, 17, 18];
    hours.forEach((h) => {
      const label = `${h}:00`;
      const opt = document.createElement("option");
      opt.value = label;
      opt.textContent = label;
      timeSlotSelect.appendChild(opt);
    });
  }

  function renderCalendar(month, year) {
    if (!calendarDays || !calMonth || !calYear) return;
    calendarDays.innerHTML = "";

    const now = new Date();
    const firstDay = new Date(year, month, 1);
    const lastDay = new Date(year, month + 1, 0);
    const daysInMonth = lastDay.getDate();
    const offset = firstDay.getDay();

    calMonth.textContent = firstDay.toLocaleString("default", {
      month: "long",
    });
    calYear.textContent = year;

    for (let i = 0; i < offset; i++) {
      const span = document.createElement("span");
      span.className = "calendar-day disabled";
      span.textContent = "";
      calendarDays.appendChild(span);
    }

    for (let d = 1; d <= daysInMonth; d++) {
      const btn = document.createElement("button");
      btn.type = "button";
      btn.className = "calendar-day";
      btn.textContent = d;

      const date = new Date(year, month, d);
      const isPast =
        date.setHours(0, 0, 0, 0) <
        new Date(
          now.getFullYear(),
          now.getMonth(),
          now.getDate()
        ).setHours(0, 0, 0, 0);

      if (isPast) {
        btn.classList.add("disabled");
        btn.disabled = true;
      } else {
        btn.addEventListener("click", () => {
          selectedDate = new Date(year, month, d);
          document
            .querySelectorAll(".calendar-day")
            .forEach((el) => el.classList.remove("selected"));
          btn.classList.add("selected");

          const iso = selectedDate.toISOString().split("T")[0];
          if (scheduleDateInput) scheduleDateInput.value = iso;
          if (selectedDateLabel)
            selectedDateLabel.textContent = selectedDate.toDateString();

          buildSlotsForDate(selectedDate);
        });
      }

      calendarDays.appendChild(btn);
    }
  }

  if (calendarDays) {
    const today = new Date();
    currentMonth = today.getMonth();
    currentYear = today.getFullYear();
    renderCalendar(currentMonth, currentYear);

    if (calPrev) {
      calPrev.addEventListener("click", () => {
        currentMonth--;
        if (currentMonth < 0) {
          currentMonth = 11;
          currentYear--;
        }
        renderCalendar(currentMonth, currentYear);
      });
    }

    if (calNext) {
      calNext.addEventListener("click", () => {
        currentMonth++;
        if (currentMonth > 11) {
          currentMonth = 0;
          currentYear++;
        }
        renderCalendar(currentMonth, currentYear);
      });
    }
  }

  if (scheduleForm) {
    scheduleForm.addEventListener("submit", async (e) => {
      e.preventDefault();

      let ok = true;

      if (!scheduleDateInput || !scheduleDateInput.value) {
        setFieldError(
          scheduleDateInput,
          "Please select a date from the calendar."
        );
        ok = false;
      } else setFieldError(scheduleDateInput, "");

      if (!timeSlotSelect || !timeSlotSelect.value) {
        setFieldError(timeSlotSelect, "Please choose a preferred time slot.");
        ok = false;
      } else setFieldError(timeSlotSelect, "");

      if (!ok) {
        if (scheduleStatus)
          scheduleStatus.textContent = "Please complete the required fields.";
        return;
      }

      const base = leadData || {
        name: "",
        phone: "",
        email: "",
        comment: "",
      };

      const schedulePayload = {
        ...base,
        date: scheduleDateInput.value,
        timeSlot: timeSlotSelect.value,
      };

      if (scheduleStatus)
        scheduleStatus.textContent = "Submitting your request…";
      if (loaderOverlay) loaderOverlay.classList.add("visible");

      let result;
      try {
        result = await sendEmailToServer("schedule", schedulePayload);
        console.log("/send-email (schedule) result:", result);
      } catch (err) {
        console.error(err);
        result = {
          success: false,
          message: "Network error while sending schedule email.",
        };
      } finally {
        if (loaderOverlay) loaderOverlay.classList.remove("visible");
      }

      if (scheduleStatus) {
        if (result.success) {
          scheduleStatus.textContent =
            "Thank you! Your schedule request has been submitted.";
        } else {
          scheduleStatus.textContent =
            "We saved your schedule, but email failed: " +
            (result.message || "Unknown error.");
        }
      }

      if (scheduleContent && finalMessage) {
        scheduleContent.style.display = "none";
        finalMessage.classList.add("active-final");
      }
    });
  }
});
