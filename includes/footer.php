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
      </div>
    </div>
  </div>
</footer>

<div class="mbar">
  <a class="btn btn-primary" href="#enquiry"><?= t('mbar_form') ?></a>
  <a class="btn btn-navy" href="tel:<?= $mbarPhoneUrl ?>"><?= t('mbar_call') ?></a>
</div>

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