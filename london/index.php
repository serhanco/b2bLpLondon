<?php
$pageLang = 'en';
$pageTitle = 'Acibadem London Office | Partner Program';
$gaID = 'G-1MRJMQ4L4G';
$topEmail = 'london@acibadem.com';
$topPhoneDisplay = '+44 7387 680090';
$topPhoneUrl = '+447387680090';
include '../includes/header.php';
?>
<main>
  <!-- Hero -->
  <section class="hero" style="padding:0">
    <div class="wrap">
      <div>
        <p class="eyebrow">Clinical Patient Referral</p>
        <h1>World-class care for your patients, seamless coordination for you.</h1>
        <p class="sub">Refer your complex cases to Acibadem Healthcare Group. We provide rapid MDT reviews, transparent treatment plans, and continuous clinical reporting back to your UK practice.</p>
        <div class="hero-cta">
          <a class="btn btn-primary" href="#enquiry">Become a Partner →</a>
          <a class="btn btn-ghost-light" href="tel:+447387680090">Or call +44 7387 680090</a>
        </div>
        <div class="hero-badges">
          <div class="hero-badge">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg>
            <span>No cost to join</span>
          </div>
          <div class="hero-badge">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            <span>Reply in 2 business days</span>
          </div>
          <div class="hero-badge">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
            <span>Confidential</span>
          </div>
        </div>
        <div class="hero-stats">
          <div><div class="n">1.5M+</div><div class="l">Patients served</div></div>
          <div><div class="n">45</div><div class="l">hospitals and clinics</div></div>
          <div><div class="n">35</div><div class="l">Years of expertise</div></div>
        </div>
      </div>

      <!-- Enquiry form -->
      <div class="lead-card" id="enquiry">
        <form id="leadForm" novalidate>
          <p class="lc-eyebrow">Partnership Application</p>
          <h3 class="lc-title">Become a Partner</h3>
          <p class="fhint">Provide your details to discuss partnership opportunities, refer a patient or book a meeting at our London office.</p>

          <div class="field">
            <label class="sr-only" for="role">Your Role</label>
            <select class="control" id="role" name="role" required>
              <option value="" selected disabled>Your Role *</option>
              <option>Physician / Specialist</option>
              <option>Clinic Manager</option>
              <option>Business Owner</option>
              <option>Entrepreneur</option>
              <option>Other</option>
            </select>
            <div class="err">Please select your role.</div>
          </div>

          <div class="field">
            <label class="sr-only" for="name">Full name</label>
            <input class="control" id="name" name="name" type="text" autocomplete="name" placeholder="Contact full name *" required />
            <div class="err">Please enter your name.</div>
          </div>

          <div class="field">
            <label class="sr-only" for="email">Email address</label>
            <input class="control" id="email" name="email" type="email" autocomplete="email" placeholder="Email address *" required />
            <div class="err">Please enter a valid email.</div>
          </div>

          <div class="field">
            <label class="sr-only" for="phone">Phone number</label>
            <div class="phone-wrap">
              <select id="cc" name="country_code" aria-label="Country code">
                <option value="+44" selected>🇬🇧 +44</option>
                <option value="+353">🇮🇪 +353</option>
                <option value="+90">🇹🇷 +90</option>
                <option value="+1">🇺🇸 +1</option>
                <option value="+other">Other</option>
              </select>
              <input id="phone" name="phone" type="tel" inputmode="tel" autocomplete="tel" placeholder="Phone (WhatsApp) *" required />
            </div>
            <div class="err">Please enter your phone number.</div>
          </div>

          <div class="field">
            <label class="sr-only" for="volume">Estimated referrals / month</label>
            <select class="control" id="volume" name="volume">
              <option value="" selected>Estimated referrals / month</option>
              <option>1–5</option>
              <option>6–20</option>
              <option>21+</option>
            </select>
          </div>

          <div class="field">
            <label class="sr-only" for="source">How did you hear about us?</label>
            <select class="control" id="source" name="source">
              <option value="" selected disabled>How did you hear about us? (Optional)</option>
              <option>Search Engine (Google, Bing, etc.)</option>
              <option>Social Media (LinkedIn, Instagram, etc.)</option>
              <option>Recommendation / Word of Mouth</option>
              <option>Event / Conference</option>
              <option>Other</option>
            </select>
          </div>

          <div class="field">
            <label class="sr-only" for="message">How can we help?</label>
            <textarea class="control" id="message" name="message" rows="2" placeholder="How can we help? (e.g., I have a patient case to share, I want to book an office meeting...)"></textarea>
          </div>

          <div class="hp" aria-hidden="true"><label>Leave this field empty<input type="text" name="company" tabindex="-1" autocomplete="off" /></label></div>

          <label class="consent">
            <input type="checkbox" id="consent" name="consent" required />
            <span>I consent to Acibadem processing my details to evaluate this referral request, as described in the privacy notice. <span class="req">*</span></span>
          </label>
          <div class="field" style="margin-top:-6px;margin-bottom:6px"><div class="err" id="consentErr">Please provide your consent to continue.</div></div>

          <button type="submit" class="btn-submit" id="submitBtn">Send Request</button>
          <p class="reassure">No obligation · your details are kept strictly confidential.</p>
        </form>

        <div class="form-msg" id="formSuccess">
          <svg width="52" height="52" viewBox="0 0 24 24" fill="none" stroke="#11a39a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
          <h3>Request received</h3>
          <p>Our London clinical coordination team will review your details and get back to you shortly.</p>
        </div>
        <div class="form-msg" id="formError">
          <svg width="52" height="52" viewBox="0 0 24 24" fill="none" stroke="#ff8a7a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
          <h3>Something went wrong</h3>
          <p>We couldn't send your request. Please email <a href="mailto:london@acibadem.com">london@acibadem.com</a> or call <a href="tel:+447387680090">+44 7387 680090</a>.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Ribbon -->
  <div class="ribbon">
    <div class="wrap">
      <div class="item"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2 4 5v6c0 5 3.4 8.5 8 10 4.6-1.5 8-5 8-10V5z"/></svg> Fast MDT Evaluation</div>
      <div class="item"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg> End-to-End VIP Care</div>
      <div class="item"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6M16 13H8M16 17H8M10 9H8"/></svg> Clinical Continuity</div>
      <div class="item"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><circle cx="12" cy="11" r="3"/></svg> London-Based Hub</div>
    </div>
  </div>

  <!-- Why Refer Your Patients to Us? -->
  <section style="background:var(--bg-soft)">
    <div class="wrap">
      <div class="sec-head" style="text-align:center;margin:0 auto 48px">
        <p class="eyebrow">Why Refer Your Patients to Us?</p>
        <h2>A seamless extension of your clinical practice</h2>
        <p class="lead">We make it simple to offer world-class, multidisciplinary medical care to the patients who trust you.</p>
      </div>
      <div class="pathways">
        <div class="path">
          <div class="pn" style="background:var(--bg-soft);color:var(--navy)"><svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg></div>
          <h3>Fast MDT Evaluation</h3>
          <p>Submit medical files for a rapid review by our multidisciplinary team of specialists.</p>
          <ul class="check-list" style="margin-top:20px">
             <li><svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M20 6 9 17l-5-5"/></svg> Rapid response &amp; evaluation</li>
             <li><svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M20 6 9 17l-5-5"/></svg> Documented medical opinions</li>
             <li><svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M20 6 9 17l-5-5"/></svg> Direct specialist contact</li>
             <li><svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M20 6 9 17l-5-5"/></svg> Transparent treatment plans</li>
          </ul>
        </div>
        <div class="path">
          <div class="pn" style="background:var(--bg-soft);color:var(--navy)"><svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg></div>
          <h3>End-to-End VIP Care</h3>
          <p>From the moment they leave the UK until they return, your patients are in safe hands.</p>
          <ul class="check-list" style="margin-top:20px">
             <li><svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M20 6 9 17l-5-5"/></svg> VIP airport transfers</li>
             <li><svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M20 6 9 17l-5-5"/></svg> Premium accommodation</li>
             <li><svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M20 6 9 17l-5-5"/></svg> 24/7 multilingual support</li>
             <li><svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M20 6 9 17l-5-5"/></svg> Dedicated hospital coordination</li>
          </ul>
        </div>
        <div class="path">
          <div class="pn" style="background:var(--bg-soft);color:var(--navy)"><svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6M16 13H8M16 17H8M10 9H8"/></svg></div>
          <h3>Clinical Continuity</h3>
          <p>You remain an integral part of the care journey with comprehensive medical reporting.</p>
          <ul class="check-list" style="margin-top:20px">
             <li><svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M20 6 9 17l-5-5"/></svg> Post-treatment summaries</li>
             <li><svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M20 6 9 17l-5-5"/></svg> Translated medical records</li>
             <li><svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M20 6 9 17l-5-5"/></svg> Follow-up care coordination</li>
             <li><svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M20 6 9 17l-5-5"/></svg> Clear communication loop</li>
          </ul>
        </div>
      </div>
    </div>
  </section>

  <!-- A healthcare group your patients can trust -->
  <section>
    <div class="wrap">
      <div class="sec-head" style="text-align:center;margin:0 auto 48px">
        <p class="eyebrow">Why partner with us</p>
        <h2>A healthcare group your patients can trust</h2>
        <p class="lead">Decades of expertise, JCI-accredited hospitals, and a track record of caring for international patients.</p>
      </div>
      <div class="accr">
        <div class="a"><div class="t" style="font-size:1.8rem">45+</div><div class="s">Hospitals &amp; Clinics</div></div>
        <div class="a"><div class="t" style="font-size:1.8rem">90+</div><div class="s">Countries served</div></div>
        <div class="a"><div class="t" style="font-size:1.8rem">65+</div><div class="s">Offices</div></div>
        <div class="a"><div class="t" style="font-size:1.8rem">30,000</div><div class="s">Employees</div></div>
        <div class="a"><div class="t" style="font-size:1.8rem">35</div><div class="s">Years of expertise</div></div>
        <div class="a"><div class="t" style="font-size:1.8rem">1M+</div><div class="s">International patients</div></div>
        <div class="a"><div class="t" style="font-size:1.8rem">24/7</div><div class="s">Multilingual coordination</div></div>
        <div class="a"><div class="t" style="font-size:1.8rem">3,300+</div><div class="s">Specialists</div></div>
      </div>
    </div>
  </section>

  <!-- Timeline (How referral works) -->
  <section style="background:var(--bg-soft);border-top:1px solid var(--line);border-bottom:1px solid var(--line)">
    <div class="wrap">
      <div class="sec-head" style="text-align:center;margin:0 auto 48px">
        <p class="eyebrow">Referral Workflow</p>
        <h2>How our referral process works</h2>
        <p class="lead">From submitting the medical file to receiving the post-treatment epikrisis.</p>
      </div>
      <div class="timeline">
        <div class="t-item"><div class="num">1</div><h3>Submit Case</h3><p>Share the patient's medical summary securely with our London team.</p></div>
        <div class="t-item"><div class="num">2</div><h3>MDT Review</h3><p>Our specialists evaluate the case and provide a transparent treatment plan.</p></div>
        <div class="t-item"><div class="num">3</div><h3>Patient Transfer</h3><p>We coordinate VIP travel, accommodation, and hospital admission.</p></div>
        <div class="t-item"><div class="num">4</div><h3>Treatment</h3><p>World-class medical care at our JCI-accredited facilities in Türkiye.</p></div>
        <div class="t-item"><div class="num">5</div><h3>Clinical Reporting</h3><p>Detailed translated medical reports are sent back to you for continuous care.</p></div>
      </div>
    </div>
  </section>

  <!-- London Office Support -->
  <section>
    <div class="wrap">
      <div class="sec-head" style="text-align:center;margin:0 auto 48px">
        <p class="eyebrow">London Office Support</p>
        <h2>Dedicated support for referring clinics</h2>
        <p class="lead">Our Great Portland Street office is your local hub for seamless patient coordination.</p>
      </div>
      <div class="value">
        <div class="vcard"><div class="vic"><svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></div><h3>Dedicated Coordinator</h3><p>A single point of contact in London for every patient case.</p></div>
        <div class="vcard"><div class="vic"><svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg></div><h3>Fast Medical Review</h3><p>Rapid evaluation by the right Acibadem specialist or MDT.</p></div>
        <div class="vcard"><div class="vic"><svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6M16 13H8M16 17H8M10 9H8"/></svg></div><h3>Documented Opinions</h3><p>Clear, written medical feedback and treatment pathways.</p></div>
        <div class="vcard"><div class="vic"><svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg></div><h3>Transparent Pricing</h3><p>Clear, all-inclusive quotes with no hidden costs for patients.</p></div>
        <div class="vcard"><div class="vic"><svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polygon points="3 6 9 3 15 6 21 3 21 18 15 21 9 18 3 21 3 6"/><line x1="9" y1="3" x2="9" y2="18"/><line x1="15" y1="6" x2="15" y2="21"/></svg></div><h3>VIP Travel &amp; Hospitality</h3><p>Visa letters, transfers, hotels and full patient hospitality.</p></div>
        <div class="vcard"><div class="vic"><svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg></div><h3>Translated Records</h3><p>Post-treatment epikrisis provided to you in English.</p></div>
        <div class="vcard"><div class="vic"><svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg></div><h3>Multilingual Team</h3><p>Coordinators who speak your patients' languages fluently.</p></div>
        <div class="vcard"><div class="vic"><svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><circle cx="12" cy="11" r="3"/></svg></div><h3>Central London Hub</h3><p>Face-to-face meetings at our Great Portland Street office.</p></div>
      </div>
    </div>
  </section>

  <!-- Referral FAQ -->
  <section style="background:var(--bg-soft)">
    <div class="wrap">
      <div class="sec-head" style="text-align:center;margin:0 auto 40px">
        <p class="eyebrow">Good to know</p>
        <h2>Partner FAQ</h2>
      </div>
      <div class="faq">
        <details>
          <summary>Who can refer patients to Acibadem?</summary>
          <div class="ans">Whether you are a physician, clinic manager, medical facilitator, business owner, or entrepreneur in the UK, you can seamlessly refer complex cases to our specialists. We provide an expert medical review and a comprehensive treatment plan to support your clients and patients.</div>
        </details>
        <details>
          <summary>How do I track my patient's progress?</summary>
          <div class="ans">Your dedicated London coordinator ensures you remain an integral part of the care journey, providing continuous updates and detailed post-treatment reports once the patient returns.</div>
        </details>
        <details>
          <summary>What is the role of the London office?</summary>
          <div class="ans">We act as your local hub. We handle secure file sharing, coordinate with our MDTs in Türkiye, manage patient travel logistics, and offer face-to-face meetings at our Great Portland Street office.</div>
        </details>
        <details>
          <summary>Which treatments and specialties are available?</summary>
          <div class="ans">We offer the full range of medical services across our JCI-accredited hospitals. This includes advanced neurosurgery, complex endometriosis, oncology, cardiology, orthopedics, neurology, ophthalmology, comprehensive check-ups, aesthetics, and more.</div>
        </details>
        <details>
          <summary>How fast is the medical evaluation?</summary>
          <div class="ans">Initial medical reviews are typically completed rapidly within 24-48 hours, depending on the complexity of the case and the completeness of the provided medical files.</div>
        </details>
        <details>
          <summary>Is there any cost to join as a partner?</summary>
          <div class="ans">No, becoming a referring partner is completely free. We invest in our partnership by providing a dedicated UK-based coordination team, fast MDT evaluations, and continuous clinical support.</div>
        </details>
        <details>
          <summary>How are referring partners rewarded?</summary>
          <div class="ans">Partnership structures and rewards are performance-based and agreed transparently in your partnership agreement, tailored to your specific operational model and referral volume.</div>
        </details>
      </div>
    </div>
  </section>

  <!-- Contact -->
  <section class="contact" id="contact">
    <div class="wrap inner">
      <div class="visual" role="img" aria-label="Acibadem London Office, 68 Great Portland Street">
        <div class="badge-float"><div class="n">London Office</div><div class="l">68 Great Portland St, W1W 7NG</div></div>
      </div>
      <div>
        <p class="eyebrow">Contact</p>
        <h2 style="font-size:clamp(1.6rem,3.2vw,2.1rem);font-weight:800;margin:.5rem 0 1.2rem">Acibadem London Office</h2>
        <div class="cinfo">
          <div class="row">
            <div class="ic"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg></div>
            <div><div class="k">Address</div><div class="v"><a href="https://maps.app.goo.gl/Tzbt6ttX9q21ZCyV7" target="_blank" rel="noopener">68 Great Portland St, London W1W 7NG</a></div></div>
          </div>
          <div class="row">
            <div class="ic"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z"/></svg></div>
            <div><div class="k">Phone / WhatsApp</div><div class="v"><a href="tel:+447387680090">+44 7387 680090</a> <br><a href="tel:+442073236665">+44 2073 236665</a></div></div>
          </div>
          <div class="row">
            <div class="ic"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-10 5L2 7"/></svg></div>
        <div><div class="k">Email</div><div class="v"><a href="mailto:london@acibadem.com">london@acibadem.com</a></div></div>
      </div>
    </div>
    <div style="display:flex;gap:10px;flex-wrap:wrap;margin-top:22px">
      <a class="btn btn-primary hide-mob" href="#enquiry">Become a Partner</a>
      <a class="btn btn-navy hide-mob" href="tel:+447387680090">Call or WhatsApp</a>
      <a class="btn btn-ghost" href="https://maps.app.goo.gl/Tzbt6ttX9q21ZCyV7" target="_blank" rel="noopener">View on Map</a>
    </div>
  </div>
</div>
</section>
</main>


<?php
$formEndpoint = '../api/process_form.php';
$mbarPhoneUrl = '+442039287080';
include '../includes/footer.php';
?>