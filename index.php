<?php
$slug = isset($_GET['slug']) ? $_GET['slug'] : '';
$isGlobal = empty($slug);

$office = null;
if (!$isGlobal) {
    // Fetch from API
    $apiData = @file_get_contents(__DIR__ . '/api/offices.json');
    if ($apiData) {
        $json = json_decode($apiData, true);
        if (isset($json['data'])) {
            foreach ($json['data'] as $officeItem) {
                if ($officeItem['slug'] === $slug) {
                    $office = $officeItem;
                    break;
                }
            }
        }
    }
    
    // Fallback to global if slug not found
    if (!$office) {
        $isGlobal = true;
    }
}

require_once __DIR__ . '/includes/i18n.php';

if ($isGlobal) {
    $officeSlug = '';
    $city = 'Istanbul';
    $place = t('place_hq');
    $pageTitle = t('meta_title_global');
    $supportEyebrow = t('support_eyebrow_global');

    $topEmail = 'international@acibadem.com';
    $topPhoneDisplay = '+90 216 444 5544';
    $topPhoneUrl = '+902164445544';

    $badgeAddress = 'Ataşehir, İstanbul';
    $contactAddress = 'Atatürk Mah. Feza Sok. No:3 Ataşehir / İstanbul';
    $contactMap = 'https://maps.google.com/?q=Acıbadem+Sağlık+Grubu+Genel+Müdürlüğü,+Ataşehir,+İstanbul';
} else {
    $officeSlug = $office['slug'];
    $city = htmlspecialchars($office['display_name']);
    $place = t('place_office', ['city' => $city]);
    $pageTitle = t('meta_title_office', ['place' => $place]);
    $supportEyebrow = t('support_eyebrow_office', ['place' => $place]);

    $topEmail = htmlspecialchars($office['email']);
    $topPhoneDisplay = htmlspecialchars($office['phone']);
    $topPhoneUrl = htmlspecialchars(str_replace(' ', '', $office['phone']));

    $badgeAddress = htmlspecialchars($office['address']);
    $contactAddress = htmlspecialchars($office['address']);
    $contactMap = "https://www.google.com/maps/search/?api=1&query=" . urlencode($office['latitude'] . "," . $office['longitude']);
}

// Phone numbers and emails stay left-to-right inside RTL text.
$phoneHtml = '<span dir="ltr">' . $topPhoneDisplay . '</span>';
$phoneLink = '<a href="tel:' . $topPhoneUrl . '">' . $phoneHtml . '</a>';
$emailLink = '<a href="mailto:' . $topEmail . '" dir="ltr">' . $topEmail . '</a>';

$gaID = 'G-1MRJMQ4L4G';
include 'includes/header.php';
?>
<main>
  <!-- Hero -->
  <section class="hero" style="padding:0">
    <div class="wrap">
      <div>
        <p class="eyebrow"><?= t('hero_eyebrow') ?></p>
        <h1><?= t('hero_title') ?></h1>
        <p class="sub"><?= t('hero_sub') ?></p>
        <div class="hero-cta">
          <a class="btn btn-primary" href="#enquiry"><?= t('hero_cta') ?></a>
          <a class="btn btn-ghost-light" href="tel:<?= $topPhoneUrl ?>"><?= t('hero_call', ['phone' => $phoneHtml]) ?></a>
        </div>
        <div class="hero-badges">
          <div class="hero-badge">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 6L9 17l-5-5"/></svg>
            <span><?= t('badge_free') ?></span>
          </div>
          <div class="hero-badge">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
            <span><?= t('badge_reply') ?></span>
          </div>
          <div class="hero-badge">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
            <span><?= t('badge_confidential') ?></span>
          </div>
        </div>
        <div class="hero-stats">
          <div><div class="n">1.5M+</div><div class="l"><?= t('stat_patients') ?></div></div>
          <div><div class="n">45</div><div class="l"><?= t('stat_hospitals') ?></div></div>
          <div><div class="n">35</div><div class="l"><?= t('stat_years') ?></div></div>
        </div>
      </div>

      <!-- Enquiry form -->
      <div class="lead-card" id="enquiry">
        <form id="leadForm" novalidate>
          <p class="lc-eyebrow"><?= t('form_eyebrow') ?></p>
          <h3 class="lc-title"><?= t('form_title') ?></h3>
          <p class="fhint"><?= t('form_hint', ['place' => $place]) ?></p>

          <div class="field">
            <label class="sr-only" for="role"><?= t('role_label') ?></label>
            <select class="control" id="role" name="role" required>
              <option value="" selected disabled><?= t('role_placeholder') ?></option>
              <option value="Physician / Specialist"><?= t('role_physician') ?></option>
              <option value="Clinic Manager"><?= t('role_clinic') ?></option>
              <option value="Business Owner"><?= t('role_business') ?></option>
              <option value="Entrepreneur"><?= t('role_entrepreneur') ?></option>
              <option value="Other"><?= t('role_other') ?></option>
            </select>
            <div class="err"><?= t('role_err') ?></div>
          </div>

          <div class="field">
            <label class="sr-only" for="name"><?= t('name_label') ?></label>
            <input class="control" id="name" name="name" type="text" autocomplete="name" placeholder="<?= t('name_placeholder') ?>" required />
            <div class="err"><?= t('name_err') ?></div>
          </div>

          <div class="field">
            <label class="sr-only" for="email"><?= t('email_label') ?></label>
            <input class="control" id="email" name="email" type="email" autocomplete="email" placeholder="<?= t('email_placeholder') ?>" required />
            <div class="err"><?= t('email_err') ?></div>
          </div>

          <div class="field">
            <label class="sr-only" for="phone"><?= t('phone_label') ?></label>
            <div class="phone-wrap" dir="ltr">
              <select id="cc" name="country_code" aria-label="<?= t('cc_label') ?>">
                <option value="+90" selected>🇹🇷 +90</option>
                <option value="+44">🇬🇧 +44</option>
                <option value="+1">🇺🇸 +1</option>
                <option value="+49">🇩🇪 +49</option>
                <option value="+33">🇫🇷 +33</option>
                <option value="+39">🇮🇹 +39</option>
                <option value="+34">🇪🇸 +34</option>
                <option value="+7">🇷🇺 +7</option>
                <option value="+971">🇦🇪 +971</option>
                <option value="+966">🇸🇦 +966</option>
                <option value="+other"><?= t('cc_other') ?></option>
              </select>
              <input id="phone" name="phone" type="tel" inputmode="tel" autocomplete="tel" placeholder="<?= t('phone_placeholder') ?>" required />
            </div>
            <div class="err"><?= t('phone_err') ?></div>
          </div>

          <div class="field">
            <label class="sr-only" for="volume"><?= t('volume_label') ?></label>
            <select class="control" id="volume" name="volume">
              <option value="" selected><?= t('volume_label') ?></option>
              <option>1–5</option>
              <option>6–20</option>
              <option>21+</option>
            </select>
          </div>

          <div class="field">
            <label class="sr-only" for="source"><?= t('source_label') ?></label>
            <select class="control" id="source" name="source">
              <option value="" selected disabled><?= t('source_placeholder') ?></option>
              <option value="Search Engine"><?= t('source_search') ?></option>
              <option value="Social Media"><?= t('source_social') ?></option>
              <option value="Recommendation / Word of Mouth"><?= t('source_referral') ?></option>
              <option value="Event / Conference"><?= t('source_event') ?></option>
              <option value="Other"><?= t('source_other') ?></option>
            </select>
          </div>

          <div class="field">
            <label class="sr-only" for="message"><?= t('message_label') ?></label>
            <textarea class="control" id="message" name="message" rows="2" placeholder="<?= t('message_placeholder') ?>"></textarea>
          </div>

          <div class="hp" aria-hidden="true"><label><?= t('honeypot_label') ?><input type="text" name="company" tabindex="-1" autocomplete="off" /></label></div>

          <label class="consent">
            <input type="checkbox" id="consent" name="consent" required />
            <span><?= t('consent') ?> <span class="req">*</span></span>
          </label>
          <div class="field" style="margin-top:-6px;margin-bottom:6px"><div class="err" id="consentErr"><?= t('consent_err') ?></div></div>

          <button type="submit" class="btn-submit" id="submitBtn"><?= t('submit') ?></button>
          <p class="reassure"><?= t('reassure') ?></p>
        </form>

        <div class="form-msg" id="formSuccess">
          <svg width="52" height="52" viewBox="0 0 24 24" fill="none" stroke="#11a39a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="m9 12 2 2 4-4"/></svg>
          <h3><?= t('success_title') ?></h3>
          <p><?= t('success_text') ?></p>
        </div>
        <div class="form-msg" id="formError">
          <svg width="52" height="52" viewBox="0 0 24 24" fill="none" stroke="#ff8a7a" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><path d="M12 8v4M12 16h.01"/></svg>
          <h3><?= t('error_title') ?></h3>
          <p><?= t('error_text', ['email' => $emailLink, 'phone' => $phoneLink]) ?></p>
        </div>
      </div>
    </div>
  </section>

  <!-- Ribbon -->
  <div class="ribbon">
    <div class="wrap">
      <div class="item"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2 4 5v6c0 5 3.4 8.5 8 10 4.6-1.5 8-5 8-10V5z"/></svg> <?= t('pill_mdt') ?></div>
      <div class="item"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg> <?= t('pill_vip') ?></div>
      <div class="item"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6M16 13H8M16 17H8M10 9H8"/></svg> <?= t('pill_continuity') ?></div>
      <div class="item"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><circle cx="12" cy="11" r="3"/></svg> <?= t('pill_hub') ?></div>
    </div>
  </div>

  <!-- Why Refer Your Patients to Us? -->
  <section style="background:var(--bg-soft)">
    <div class="wrap">
      <div class="sec-head" style="text-align:center;margin:0 auto 48px">
        <p class="eyebrow"><?= t('why_eyebrow') ?></p>
        <h2><?= t('why_title') ?></h2>
        <p class="lead"><?= t('why_lead') ?></p>
      </div>
      <div class="pathways">
        <div class="path">
          <div class="pn" style="background:var(--bg-soft);color:var(--navy)"><svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M22 12h-4l-3 9L9 3l-3 9H2"/></svg></div>
          <h3><?= t('pill_mdt') ?></h3>
          <p><?= t('path_mdt_text') ?></p>
          <ul class="check-list" style="margin-top:20px">
             <li><svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M20 6 9 17l-5-5"/></svg> <?= t('path_mdt_1') ?></li>
             <li><svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M20 6 9 17l-5-5"/></svg> <?= t('path_mdt_2') ?></li>
             <li><svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M20 6 9 17l-5-5"/></svg> <?= t('path_mdt_3') ?></li>
             <li><svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M20 6 9 17l-5-5"/></svg> <?= t('path_mdt_4') ?></li>
          </ul>
        </div>
        <div class="path">
          <div class="pn" style="background:var(--bg-soft);color:var(--navy)"><svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg></div>
          <h3><?= t('pill_vip') ?></h3>
          <p><?= t('path_vip_text') ?></p>
          <ul class="check-list" style="margin-top:20px">
             <li><svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M20 6 9 17l-5-5"/></svg> <?= t('path_vip_1') ?></li>
             <li><svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M20 6 9 17l-5-5"/></svg> <?= t('path_vip_2') ?></li>
             <li><svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M20 6 9 17l-5-5"/></svg> <?= t('path_vip_3') ?></li>
             <li><svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M20 6 9 17l-5-5"/></svg> <?= t('path_vip_4') ?></li>
          </ul>
        </div>
        <div class="path">
          <div class="pn" style="background:var(--bg-soft);color:var(--navy)"><svg width="20" height="20" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6M16 13H8M16 17H8M10 9H8"/></svg></div>
          <h3><?= t('pill_continuity') ?></h3>
          <p><?= t('path_cont_text') ?></p>
          <ul class="check-list" style="margin-top:20px">
             <li><svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M20 6 9 17l-5-5"/></svg> <?= t('path_cont_1') ?></li>
             <li><svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M20 6 9 17l-5-5"/></svg> <?= t('path_cont_2') ?></li>
             <li><svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M20 6 9 17l-5-5"/></svg> <?= t('path_cont_3') ?></li>
             <li><svg width="18" height="18" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path d="M20 6 9 17l-5-5"/></svg> <?= t('path_cont_4') ?></li>
          </ul>
        </div>
      </div>
    </div>
  </section>

  <!-- A healthcare group your patients can trust -->
  <section>
    <div class="wrap">
      <div class="sec-head" style="text-align:center;margin:0 auto 48px">
        <p class="eyebrow"><?= t('trust_eyebrow') ?></p>
        <h2><?= t('trust_title') ?></h2>
        <p class="lead"><?= t('trust_lead') ?></p>
      </div>
      <div class="accr">
        <div class="a"><div class="t" style="font-size:1.8rem">45+</div><div class="s"><?= t('trust_hospitals') ?></div></div>
        <div class="a"><div class="t" style="font-size:1.8rem">90+</div><div class="s"><?= t('trust_countries') ?></div></div>
        <div class="a"><div class="t" style="font-size:1.8rem">65+</div><div class="s"><?= t('trust_offices') ?></div></div>
        <div class="a"><div class="t" style="font-size:1.8rem">30,000</div><div class="s"><?= t('trust_employees') ?></div></div>
        <div class="a"><div class="t" style="font-size:1.8rem">35</div><div class="s"><?= t('trust_years') ?></div></div>
        <div class="a"><div class="t" style="font-size:1.8rem">1M+</div><div class="s"><?= t('trust_intl') ?></div></div>
        <div class="a"><div class="t" style="font-size:1.8rem">24/7</div><div class="s"><?= t('trust_multilingual') ?></div></div>
        <div class="a"><div class="t" style="font-size:1.8rem">3,300+</div><div class="s"><?= t('trust_specialists') ?></div></div>
      </div>
    </div>
  </section>

  <!-- Timeline (How referral works) -->
  <section style="background:var(--bg-soft);border-top:1px solid var(--line);border-bottom:1px solid var(--line)">
    <div class="wrap">
      <div class="sec-head" style="text-align:center;margin:0 auto 48px">
        <p class="eyebrow"><?= t('flow_eyebrow') ?></p>
        <h2><?= t('flow_title') ?></h2>
        <p class="lead"><?= t('flow_lead') ?></p>
      </div>
      <div class="timeline">
        <div class="t-item"><div class="num">1</div><h3><?= t('flow_1_title') ?></h3><p><?= t('flow_1_text') ?></p></div>
        <div class="t-item"><div class="num">2</div><h3><?= t('flow_2_title') ?></h3><p><?= t('flow_2_text') ?></p></div>
        <div class="t-item"><div class="num">3</div><h3><?= t('flow_3_title') ?></h3><p><?= t('flow_3_text') ?></p></div>
        <div class="t-item"><div class="num">4</div><h3><?= t('flow_4_title') ?></h3><p><?= t('flow_4_text') ?></p></div>
        <div class="t-item"><div class="num">5</div><h3><?= t('flow_5_title') ?></h3><p><?= t('flow_5_text') ?></p></div>
      </div>
    </div>
  </section>

  <section>
    <div class="wrap">
      <div class="sec-head" style="text-align:center;margin:0 auto 48px">
        <p class="eyebrow"><?= $supportEyebrow ?></p>
        <h2><?= t('support_title') ?></h2>
        <p class="lead"><?= t('support_lead', ['place' => $place]) ?></p>
      </div>
      <div class="value">
        <div class="vcard"><div class="vic"><svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></div><h3><?= t('v_coord_title') ?></h3><p><?= t('v_coord_text', ['city' => $city]) ?></p></div>
        <div class="vcard"><div class="vic"><svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"/></svg></div><h3><?= t('v_review_title') ?></h3><p><?= t('v_review_text') ?></p></div>
        <div class="vcard"><div class="vic"><svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><path d="M14 2v6h6M16 13H8M16 17H8M10 9H8"/></svg></div><h3><?= t('v_opinion_title') ?></h3><p><?= t('v_opinion_text') ?></p></div>
        <div class="vcard"><div class="vic"><svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 0 0 0 7h5a3.5 3.5 0 0 1 0 7H6"/></svg></div><h3><?= t('v_pricing_title') ?></h3><p><?= t('v_pricing_text') ?></p></div>
        <div class="vcard"><div class="vic"><svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><polygon points="3 6 9 3 15 6 21 3 21 18 15 21 9 18 3 21 3 6"/><line x1="9" y1="3" x2="9" y2="18"/><line x1="15" y1="6" x2="15" y2="21"/></svg></div><h3><?= t('v_travel_title') ?></h3><p><?= t('v_travel_text') ?></p></div>
        <div class="vcard"><div class="vic"><svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"/><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"/></svg></div><h3><?= t('v_records_title') ?></h3><p><?= t('v_records_text') ?></p></div>
        <div class="vcard"><div class="vic"><svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><line x1="2" y1="12" x2="22" y2="12"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/></svg></div><h3><?= t('v_team_title') ?></h3><p><?= t('v_team_text') ?></p></div>
        <div class="vcard"><div class="vic"><svg width="22" height="22" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"/><circle cx="12" cy="11" r="3"/></svg></div><h3><?= $place ?></h3><p><?= t('v_meet_text') ?></p></div>
      </div>
    </div>
  </section>

  <!-- Referral FAQ -->
  <section style="background:var(--bg-soft)">
    <div class="wrap">
      <div class="sec-head" style="text-align:center;margin:0 auto 40px">
        <p class="eyebrow"><?= t('faq_eyebrow') ?></p>
        <h2><?= t('faq_title') ?></h2>
      </div>
      <div class="faq">
<?php for ($i = 1; $i <= 7; $i++): ?>
        <details>
          <summary><?= t("faq_{$i}_q", ['place' => $place]) ?></summary>
          <div class="ans"><?= t("faq_{$i}_a") ?></div>
        </details>
<?php endfor; ?>
      </div>
    </div>
  </section>

  <!-- Contact -->
  <section class="contact" id="contact">
    <div class="wrap inner">
      <div class="visual" role="img" aria-label="<?= $place ?>">
        <div class="badge-float"><div class="n"><?= $place ?></div><div class="l"><?= $badgeAddress ?></div></div>
      </div>
      <div>
        <p class="eyebrow"><?= t('contact_eyebrow') ?></p>
        <h2 style="font-size:clamp(1.6rem,3.2vw,2.1rem);font-weight:800;margin:.5rem 0 1.2rem"><?= t('contact_title', ['place' => $place]) ?></h2>
        <div class="cinfo">
          <div class="row">
            <div class="ic"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"/><circle cx="12" cy="10" r="3"/></svg></div>
            <div><div class="k"><?= t('contact_address') ?></div><div class="v"><a href="<?= htmlspecialchars($contactMap) ?>" target="_blank" rel="noopener"><?= $contactAddress ?></a></div></div>
          </div>
          <div class="row">
            <div class="ic"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z"/></svg></div>
            <div><div class="k"><?= t('contact_phone') ?></div><div class="v"><?= $phoneLink ?></div></div>
          </div>
          <div class="row">
            <div class="ic"><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="2" y="4" width="20" height="16" rx="2"/><path d="m22 7-10 5L2 7"/></svg></div>
          <div><div class="k"><?= t('contact_email') ?></div><div class="v"><?= $emailLink ?></div></div>
      </div>
    </div>
    <div style="display:flex;gap:10px;flex-wrap:wrap;margin-top:22px">
      <a class="btn btn-primary hide-mob" href="#enquiry"><?= t('cta_partner') ?></a>
      <a class="btn btn-navy hide-mob" href="tel:<?= $topPhoneUrl ?>"><?= t('contact_call') ?></a>
      <a class="btn btn-ghost" href="<?= htmlspecialchars($contactMap) ?>" target="_blank" rel="noopener"><?= t('contact_map') ?></a>
    </div>
  </div>
</div>
</section>
</main>


<?php
$formEndpoint = '/api/process_form.php';
$mbarPhoneUrl = $topPhoneUrl;
include __DIR__ . '/includes/footer.php';
?>