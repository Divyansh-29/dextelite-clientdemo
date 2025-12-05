<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <title>Organic Leads System for Lawyers</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- DM Sans (like Dextelite) -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link
      href="https://fonts.googleapis.com/css2?family=DM+Sans:wght@400;500;600&display=swap"
      rel="stylesheet"
    />

    <!-- Your CSS (put styles.css in Laravel's public/ folder) -->
    <link rel="stylesheet" href="{{ asset('styles.css') }}" />
  </head>
  <body>
    <!-- Loader overlay -->
    <div id="loaderOverlay" class="loader-overlay" aria-hidden="true">
      <div class="loader"></div>
    </div>

    <div id="app">
      <!-- Header -->
      <header class="site-header">
        <div class="logo-wrap">
          <span class="brand-text">Dextelite.</span>
        </div>
      </header>

      <main>
        <!-- STEP 1: Hero + Benefits -->
        <section id="step-1" class="view active">
          <section class="hero">
            <!-- Top: text + form + preview -->
            <div class="hero-top">
                <!-- LEFT: copy + preview video -->
                <div class="hero-left">
                <div class="hero-text">
                    <p class="eyebrow">
                    For Solo Attorneys, Small Law Firms &amp; Legal Practitioners
                    </p>

                    <h1 class="hero-heading">
                    The Simple, Predictable 4-Step System That Gets Lawyers
                    <span class="hero-highlight">
                        15–40 Qualified Organic Leads Every Month
                    </span>
                    </h1>

                    <p class="hero-subheading">
                    Still relying on referrals to keep your pipeline full?
                    </p>

                    <p class="hero-body">
                    No networking marathons. No paid ads. This system is built for
                    lawyers who want consistent, high-quality case inquiries every
                    month without chasing introductions or betting everything on
                    SEO algorithms.
                    </p>

                    <p class="hero-body hero-body-secondary">
                    Watch the free training below to see exactly how it works — and
                    how you can plug it into your practice in the next 30–60 days.
                    </p>
                </div>

                <!-- Preview video directly under the text -->
                <div class="hero-preview-block">
                    <div class="hero-media" id="previewVideoWrapper">
                    <video
                        id="previewVideo"
                        class="hero-video"
                        autoplay
                        muted
                        playsinline
                    >
                        <source src="{{ asset('video.mp4') }}" type="video/mp4" />
                        Your browser does not support the video tag.
                    </video>

                    <button
                        type="button"
                        id="previewOverlay"
                        class="hero-video-overlay"
                        aria-label="Preview the training video"
                    >
                        <svg viewBox="0 0 24 24" aria-hidden="true" class="hero-play-icon">
                        <polygon points="8,5 19,12 8,19"></polygon>
                        </svg>
                    </button>
                    </div>
                </div>
                </div>

                <!-- RIGHT: form -->
                <div class="hero-top-right">
                <div class="hero-form-wrap">
                    <h2 class="form-heading">Get Access to the Free Training</h2>
                    <p class="form-subheading">
                    Tell us a bit about your practice and we’ll send you the
                    training and next steps. No spam, no high-pressure calls.
                    </p>

                    <!-- Notice after clicking preview video -->
                    <p id="accessNotice" class="access-notice">
                    Please share your details to get instant access to the full
                    training.
                    </p>

                    <form id="leadForm" novalidate>
                    <div class="form-group">
                        <label for="name">
                        Full Name<span aria-hidden="true">*</span>
                        </label>
                        <input
                        id="name"
                        name="name"
                        type="text"
                        required
                        minlength="2"
                        autocomplete="name"
                        placeholder="Enter your full name"
                        />
                        <p class="error-text" data-error-for="name"></p>
                    </div>

                    <div class="form-group">
                        <label for="phone">
                        Phone Number<span aria-hidden="true">*</span>
                        </label>
                        <input
                        id="phone"
                        name="phone"
                        type="tel"
                        required
                        pattern="^[6-9][0-9]{9}$"
                        title="Enter a valid 10-digit mobile number starting with 6, 7, 8, or 9"
                        autocomplete="tel"
                        placeholder="10-digit mobile number"
                        />
                        <p class="error-text" data-error-for="phone"></p>
                    </div>

                    <div class="form-group">
                        <label for="email">
                        Email Address<span aria-hidden="true">*</span>
                        </label>
                        <input
                        id="email"
                        name="email"
                        type="email"
                        required
                        autocomplete="email"
                        placeholder="name@yourfirm.com"
                        />
                        <p class="error-text" data-error-for="email"></p>
                    </div>

                    <div class="form-group">
                        <label for="comment">
                        What practice area do you focus on? (optional)
                        </label>
                        <textarea
                        id="comment"
                        name="comment"
                        rows="4"
                        placeholder="e.g. Personal injury, family law, criminal defense, immigration..."
                        ></textarea>
                    </div>

                    <button type="submit" class="btn btn-red-primary">
                        Yes, I Want Consistent Organic Leads
                    </button>

                    <p class="form-note">
                        100% success rate — 100+ lawyers trained. Your details are
                        kept confidential.
                    </p>

                    <p
                        id="leadStatus"
                        class="status-text"
                        aria-live="polite"
                    ></p>
                    </form>
                </div>
                </div>
            </div>
          </section>


          <!-- Inside this free training -->
          <section class="about-section">
            <div class="about-inner">
              <p class="eyebrow">
                Inside This Free Training, You’ll Discover How To:
              </p>
              <h2>
                Turn Your Expertise into a Predictable Stream of Qualified Cases
              </h2>
              <p>
                You don’t need to become a full-time marketer to grow your
                practice. This training walks you through a simple system you
                can plug into your existing workload without adding more chaos.
              </p>

              <div class="about-grid">
                <div class="about-card">
                  <h3>Generate 15–40 Leads / Month</h3>
                  <p>
                    Learn the exact framework that brings in a steady flow of
                    qualified organic leads — without chasing referrals or cold
                    outreach.
                  </p>
                </div>
                <div class="about-card">
                  <h3>Attract the Right Clients</h3>
                  <p>
                    Position your firm so you attract clients in your exact
                    practice area — not random inquiries that waste time.
                  </p>
                </div>
                <div class="about-card">
                  <h3>Go Beyond SEO Algorithms</h3>
                  <p>
                    Tap into organic channels that do not depend on ranking
                    tricks or constant algorithm changes.
                  </p>
                </div>
                <div class="about-card">
                  <h3>Own Your Pipeline</h3>
                  <p>
                    Build a lead-gen system you control — not your partners,
                    networking events or agencies.
                  </p>
                </div>
                <div class="about-card">
                  <h3>Grow Without Extra Overhead</h3>
                  <p>
                    Generate predictable case flow without hiring a big internal
                    team or burning money on ads.
                  </p>
                </div>
              </div>
            </div>
          </section>
        </section>

        <!-- STEP 2: Video training -->
        <section id="step-2" class="view">
          <section class="step2-wrapper">
            <div class="step2-inner">
              <div class="step2-copy">
                <p class="eyebrow">
                  For Solo Attorneys, Small or Big Law Firms &amp; Legal
                  Professionals
                </p>
                <h2>
                  The Simple, Proven System That Helps Lawyers Attract Quality
                  Clients Consistently
                </h2>
                <p>
                  This is the same system that’s helping various big law firms
                  generate predictable case inquiries every month — across
                  different practice areas. Watch the free training now and see
                  how you can implement it in your firm.
                </p>
              </div>

              <div class="full-video-shell">
                <video
                  id="step2Video"
                  class="full-video"
                  preload="metadata"
                  poster="{{ asset('video-thumbnail.jpg') }}"
                >
                  <source src="{{ asset('video.mp4') }}" type="video/mp4" />
                  Your browser does not support the video tag.
                </video>

                <button
                  id="videoOverlayIcon"
                  class="video-overlay-icon"
                  type="button"
                  aria-label="Play training video"
                >
                  <svg viewBox="0 0 24 24" aria-hidden="true">
                    <polygon points="8,5 19,12 8,19"></polygon>
                  </svg>
                </button>

                <div class="video-controls">
                  <button
                    type="button"
                    class="video-icon-btn circle"
                    id="playPauseBtn"
                    aria-label="Play/Pause"
                  >
                    <svg class="icon-play" viewBox="0 0 24 24">
                      <polygon points="8,5 19,12 8,19"></polygon>
                    </svg>
                    <svg class="icon-pause" viewBox="0 0 24 24">
                      <rect x="7" y="5" width="4" height="14"></rect>
                      <rect x="13" y="5" width="4" height="14"></rect>
                    </svg>
                  </button>

                  <span class="time-display" id="currentTime">0:00</span>

                  <div class="timeline-wrap">
                    <input
                      type="range"
                      id="timeline"
                      min="0"
                      value="0"
                      step="0.01"
                    />
                  </div>

                  <span class="time-display" id="totalTime">0:00</span>

                  <button
                    type="button"
                    class="video-icon-btn small"
                    id="back10Btn"
                    aria-label="Back 10 seconds"
                  >
                    ⏪ 10
                  </button>

                  <button
                    type="button"
                    class="video-icon-btn small"
                    id="forward10Btn"
                    aria-label="Forward 10 seconds"
                  >
                    10 ⏩
                  </button>

                  <button
                    type="button"
                    class="video-icon-btn circle"
                    id="muteBtn"
                    aria-label="Mute/Unmute"
                  >
                    <svg class="icon-volume-on" viewBox="0 0 24 24">
                      <path d="M5 9v6h4l4 4V5L9 9H5z"></path>
                      <path d="M16.5 8.5a4 4 0 010 7"></path>
                    </svg>
                    <svg class="icon-volume-off" viewBox="0 0 24 24">
                      <path d="M5 9v6h4l4 4V5L9 9H5z"></path>
                      <line x1="18" y1="8" x2="22" y2="16"></line>
                      <line x1="22" y1="8" x2="18" y2="16"></line>
                    </svg>
                  </button>

                  <div class="volume-wrap">
                    <input
                      type="range"
                      id="volumeSlider"
                      min="0"
                      max="1"
                      step="0.05"
                      value="0.5"
                    />
                  </div>

                  <button
                    type="button"
                    class="video-icon-btn circle"
                    id="fullscreenBtn"
                    aria-label="Toggle fullscreen"
                  >
                    <svg class="icon-full" viewBox="0 0 24 24">
                      <polyline points="4 9 4 4 9 4"></polyline>
                      <polyline points="15 4 20 4 20 9"></polyline>
                      <polyline points="20 15 20 20 15 20"></polyline>
                      <polyline points="9 20 4 20 4 15"></polyline>
                    </svg>
                    <svg class="icon-exit-full" viewBox="0 0 24 24">
                      <polyline points="9 4 9 9 4 9"></polyline>
                      <polyline points="15 4 15 9 20 9"></polyline>
                      <polyline points="15 20 15 15 20 15"></polyline>
                      <polyline points="9 20 9 15 4 15"></polyline>
                    </svg>
                  </button>
                </div>
              </div>

              <div class="full-video-cta">
                <button id="consultBtn" class="btn primary-btn">
                  Yes, I Want More Consistent Clients
                </button>
              </div>

              <p class="training-social-proof">
                ⭐⭐⭐⭐⭐ Rated 4.9/5 with 97%+ success rate — based on 100+ lawyer
                reviews.
              </p>
            </div>
          </section>
        </section>

        <!-- STEP 3: Scheduler -->
        <section id="step-3" class="view">
          <section id="scheduleContent" class="scheduler-section">
            <div class="scheduler-inner">
              <p class="eyebrow">Schedule</p>
              <h2>Book Your 1:1 Consultation Slot</h2>
              <p class="scheduler-intro">
                After you’ve watched the training, choose a date and time that
                works for you. We’ll review your details and confirm by email.
              </p>

              <form id="scheduleForm" novalidate>
                <div class="calendar-wrapper">
                  <div class="calendar-header">
                    <button type="button" class="cal-nav" id="calPrev">
                      &#10094;
                    </button>
                    <div class="cal-current">
                      <span id="calMonth"></span>
                      <span id="calYear"></span>
                    </div>
                    <button type="button" class="cal-nav" id="calNext">
                      &#10095;
                    </button>
                  </div>
                  <div class="calendar-grid calendar-weekdays">
                    <span>Sun</span><span>Mon</span><span>Tue</span
                    ><span>Wed</span><span>Thu</span><span>Fri</span
                    ><span>Sat</span>
                  </div>
                  <div class="calendar-grid" id="calendarDays"></div>
                  <p class="selected-date-label">
                    Selected date:
                    <span id="selectedDateLabel">None</span>
                  </p>
                  <input type="hidden" id="scheduleDate" name="date" />
                  <p class="error-text" data-error-for="scheduleDate"></p>
                </div>

                <div class="form-group">
                  <label for="timeSlot">
                    Preferred Time Slot<span aria-hidden="true">*</span>
                  </label>
                  <select id="timeSlot" name="timeSlot" required>
                    <option value="">Select a time slot</option>
                  </select>
                  <p class="error-text" data-error-for="timeSlot"></p>
                </div>

                <button type="submit" class="btn primary-btn">
                  Confirm Consultation
                </button>

                <p class="form-note">
                  You’ll receive an email with your confirmed date &amp; time,
                  plus a quick summary of what we’ll cover together.
                </p>

                <p
                  id="scheduleStatus"
                  class="status-text"
                  aria-live="polite"
                ></p>
              </form>
            </div>
          </section>

          <section id="finalMessage" class="final-message-section">
            <div class="final-message-inner">
              <h2>Thank You — You’re All Set</h2>
              <p>
                Your consultation request and preferred schedule have been
                submitted. We’ll review your details and send a confirmation to
                your email shortly.
              </p>
              <p>
                If you need to update anything, simply reply to the confirmation
                email once you receive it.
              </p>
            </div>
          </section>
        </section>
      </main>

      <footer class="site-footer">
        <p>© 2025 Dextelite. All rights reserved.</p>
      </footer>
    </div>

    <!-- Your JS (put script.js in public/ folder) -->
    <script src="{{ asset('script.js') }}"></script>
  </body>
</html>
