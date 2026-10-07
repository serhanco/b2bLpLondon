<?php
$formEndpoint = $formEndpoint ?? '/api/process_form.php';
$mbarPhoneUrl = $mbarPhoneUrl ?? '+902164445544';
$officeSlug = $officeSlug ?? '';
?>
<footer class="site">
  <div class="legal">
    <div class="disclaimer">
      <div class="wrap">
        <b><?= t('ad_label') ?></b> <?= t('ad_text') ?>
        <button type="button" class="cookie-link" id="cookieSettings"><?= t('cookie_settings') ?></button>
      </div>
    </div>
  </div>
</footer>

<div class="mbar">
  <a class="btn btn-primary" href="#enquiry"><?= t('mbar_form') ?></a>
  <a class="btn btn-navy" href="tel:<?= $mbarPhoneUrl ?>"><?= t('mbar_call') ?></a>
</div>

<div class="cookiebar" id="cookieBar" role="region" aria-label="<?= t('cookie_settings') ?>" hidden>
  <p><?= t('cookie_text') ?></p>
  <div class="cb-actions">
    <button type="button" class="btn btn-navy" data-consent="denied"><?= t('cookie_reject') ?></button>
    <button type="button" class="btn btn-primary" data-consent="granted"><?= t('cookie_accept') ?></button>
  </div>
</div>

<script>
// Cookie consent (Google Consent Mode v2; the default "denied" is set in header.php)
(function(){
  "use strict";
  var bar = document.getElementById("cookieBar"), KEY = "cookie_consent", stored = null;
  try { stored = localStorage.getItem(KEY); } catch (e) {}
  if (stored !== "granted" && stored !== "denied") bar.hidden = false;

  function clearGaCookies(){
    var host = location.hostname.replace(/^www\./, "");
    document.cookie.split(";").forEach(function(c){
      var name = c.split("=")[0].trim();
      if (/^_ga/.test(name)) {
        ["", "; domain=" + host, "; domain=." + host].forEach(function(d){
          document.cookie = name + "=; expires=Thu, 01 Jan 1970 00:00:00 GMT; path=/" + d;
        });
      }
    });
  }
  bar.addEventListener("click", function(e){
    var btn = e.target.closest("[data-consent]");
    if (!btn) return;
    var choice = btn.getAttribute("data-consent");
    try { localStorage.setItem(KEY, choice); } catch (err) {}
    if (window.gtag) gtag("consent", "update", {analytics_storage: choice});
    if (choice === "denied") clearGaCookies();
    bar.hidden = true;
  });
  document.getElementById("cookieSettings").addEventListener("click", function(){ bar.hidden = false; });
})();
</script>

<script>
(function(){
  "use strict";
  var FORM_ENDPOINT = "<?= $formEndpoint ?>"; // Global referral endpoint
  var PAGE = <?= json_encode(['lang' => $lang, 'office' => $officeSlug], JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) ?>;
  var T = <?= json_encode([
    'sending' => t_raw('sending'),
    'clinical' => t_raw('message_placeholder_clinical'),
    'patient' => t_raw('message_placeholder_patient'),
  ], JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) ?>;

  // Close the language menu when clicking elsewhere.
  var langsw = document.querySelector(".langsw");
  if (langsw) document.addEventListener("click", function(e){ if (!langsw.contains(e.target)) langsw.removeAttribute("open"); });

  // role toggle adjusts the summary placeholder
  var role = document.getElementById("role"), msg = document.getElementById("message");
  function syncRole(){
    if(!role.value) return;
    msg.placeholder = role.value.indexOf("Physician") > -1 || role.value.indexOf("Manager") > -1
      ? T.clinical
      : T.patient;
  }
  role.addEventListener("change", syncRole); syncRole();

  function getParams(){
    var p = new URLSearchParams(location.search), o = {};
    ["utm_source","utm_medium","utm_campaign","utm_content","utm_term","gclid"].forEach(function(k){ if(p.get(k)) o[k]=p.get(k); });
    o.referrer = document.referrer || ""; o.landing_page = location.pathname; return o;
  }
  function validEmail(v){ return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v); }
  function setInvalid(id, bad){ var el=document.getElementById(id); if(!el) return; var f=el.closest(".field"); if(f) f.classList.toggle("invalid", bad); }

  var form=document.getElementById("leadForm"), success=document.getElementById("formSuccess"),
      errorBox=document.getElementById("formError"), btn=document.getElementById("submitBtn");

  form.addEventListener("submit", function(e){
    e.preventDefault();
    if(form.company && form.company.value) return; // honeypot
    var name=form.name.value.trim(), email=form.email.value.trim(), phone=form.phone.value.trim(), consent=document.getElementById("consent").checked, roleVal=form.role.value;
    var ok=true;
    
    setInvalid("role", !roleVal); if(!roleVal) ok=false;
    setInvalid("name", !name); if(!name) ok=false;
    setInvalid("email", !validEmail(email)); if(!validEmail(email)) ok=false;
    setInvalid("phone", !phone); if(!phone) ok=false;
    document.getElementById("consentErr").style.display = consent ? "none" : "block";
    if(!consent) ok=false;
    if(!ok){ var ff=form.querySelector(".invalid .control, .invalid .phone-wrap input"); (ff||document.getElementById("consent")).focus(); return; }

    var payload = Object.assign({
      role: roleVal, name:name, email:email,
      phone: form.country_code.value + " " + phone,
      volume: form.volume.value, message: form.message.value.trim(),
      source: form.source ? form.source.value : "",
      lang: PAGE.lang, office: PAGE.office,
      form_token: form.form_token ? form.form_token.value : "",
      submitted_at: new Date().toISOString()
    }, getParams());

    btn.disabled=true; btn.textContent=T.sending;
    fetch(FORM_ENDPOINT, {method:"POST", headers:{"Content-Type":"application/json"}, body:JSON.stringify(payload)})
      .then(function(r){ if(!r.ok) throw new Error("bad"); return r; })
      .then(function(){ form.style.display="none"; success.classList.add("show"); if(window.gtag) gtag("event","generate_lead",{role:payload.role}); })
      .catch(function(){ form.style.display="none"; errorBox.classList.add("show"); });
  });

  document.querySelectorAll('a[href^="tel:"], a[href^="mailto:"]').forEach(function(a){
    a.addEventListener("click", function(){ if(window.gtag) gtag("event","contact",{method: a.href.indexOf("mailto")>-1?"email":"phone"}); });
  });
})();
</script>
</body>
</html>