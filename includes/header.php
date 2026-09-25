<?php
$pageLang = $pageLang ?? 'en';
$pageTitle = $pageTitle ?? 'Acibadem International | Partner Program';
$gaID = $gaID ?? '<?= $gaID ?>';
$topEmail = $topEmail ?? '<?= $topEmail ?>';
$topPhoneDisplay = $topPhoneDisplay ?? '<?= $topPhoneDisplay ?>';
$topPhoneUrl = $topPhoneUrl ?? '<?= $topPhoneUrl ?>';
?>

<!DOCTYPE html>
<html lang="<?= $pageLang ?>">
<head>
<!-- Google tag (gtag.js) -->
<script async src="https://www.googletagmanager.com/gtag/js?id=<?= $gaID ?>"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', '<?= $gaID ?>');
</script>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, viewport-fit=cover" />
<title><?= $pageTitle ?></title>
<meta name="description" content="Grow your business with Acibadem International. Become an official agency, market our treatments, or refer patients to JCI-accredited Turkish healthcare." />
<meta name="theme-color" content="#092c74" />
<meta name="robots" content="index, follow" />
<link rel="preconnect" href="https://fonts.googleapis.com" />
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@600;700;800&display=swap" rel="stylesheet" />
<style>
  :root{
    --navy:#092c74; --navy-deep:#06204f; --navy-700:#0e3da0;
    --teal:#11a39a; --teal-dark:#0c7d76; --gold:#f5b301;
    --ink:#0f1b3d; --muted:#5b6478;
    --bg:#ffffff; --bg-soft:#f4f7fb; --line:#e3e9f2;
    --shadow:0 12px 34px -12px rgba(9,44,116,.22);
    --shadow-sm:0 4px 14px -6px rgba(9,44,116,.18);
    --radius:16px; --radius-sm:10px; --maxw:1180px;
    --display:'Plus Jakarta Sans',system-ui,sans-serif;
    --body:'Inter',system-ui,sans-serif;
  }
  *{box-sizing:border-box}
  html{scroll-behavior:smooth}
  body{margin:0;font-family:var(--body);color:var(--ink);background:var(--bg);line-height:1.55;-webkit-font-smoothing:antialiased}
  h1,h2,h3,h4{font-family:var(--display);line-height:1.16;margin:0;color:var(--navy);letter-spacing:-.01em}
  p{margin:0}
  a{color:inherit;text-decoration:none}
  img{max-width:100%;display:block}
  .wrap{max-width:var(--maxw);margin:0 auto;padding:0 22px}
  .eyebrow{font-family:var(--body);font-weight:600;font-size:.78rem;letter-spacing:.14em;text-transform:uppercase;color:var(--teal-dark)}
  .lead{color:var(--muted);font-size:1.06rem}
  section{padding:74px 0}
  .sec-head{max-width:720px;margin-bottom:40px}
  .sec-head h2{font-size:clamp(1.7rem,3.4vw,2.4rem);font-weight:800;margin:.5rem 0 .7rem}
  :focus-visible{outline:3px solid var(--teal);outline-offset:2px;border-radius:6px}

  .btn{display:inline-flex;align-items:center;gap:.5rem;font-family:var(--body);font-weight:600;font-size:.98rem;
    padding:.85rem 1.45rem;border-radius:999px;border:1.5px solid transparent;cursor:pointer;transition:.18s ease;white-space:nowrap}
  .btn-primary{background:var(--teal);color:#fff}
  .btn-primary:hover{background:var(--teal-dark);transform:translateY(-1px)}
  .btn-navy{background:var(--navy);color:#fff}
  .btn-navy:hover{background:var(--navy-700)}
  .btn-ghost{background:transparent;color:var(--navy);border-color:var(--line)}
  .btn-ghost:hover{border-color:var(--navy);background:#fff}
  .btn-ghost-light{background:rgba(255,255,255,.08);color:#fff;border-color:rgba(255,255,255,.4)}
  .btn-ghost-light:hover{background:rgba(255,255,255,.16)}

  /* Top bar */
  .topbar{background:var(--navy-deep);color:#dfe7f6;font-size:.8rem}
  .topbar .wrap{display:flex;align-items:center;justify-content:space-between;gap:14px;min-height:38px;padding:6px 22px}
  .topbar .creds{display:flex;gap:10px;flex-wrap:wrap;opacity:.92}
  .topbar .creds span{position:relative;padding-right:10px}
  .topbar .creds span:not(:last-child)::after{content:"·";position:absolute;right:0;opacity:.5}
  .topbar .right{display:flex;align-items:center;gap:16px;white-space:nowrap}
  .topbar a{color:#fff;font-weight:600}

  /* Header */
  header.site{position:sticky;top:0;z-index:60;background:var(--navy);color:#fff;box-shadow:0 2px 16px rgba(6,32,79,.25)}
  header.site .wrap{display:flex;align-items:center;justify-content:space-between;gap:18px;min-height:70px}
  .logo{flex-shrink:0}
  .logo img{height:34px;width:auto;display:block}
  .hnav{display:flex;align-items:center;gap:12px}
  .hnav .phone{display:flex;align-items:center;gap:8px;font-weight:700;color:#fff;font-size:.96rem}

  /* Hero */
  .hero{position:relative;color:#fff;overflow:hidden;background:
      radial-gradient(1100px 600px at 80% -10%,rgba(17,163,154,.30),transparent 60%),
      radial-gradient(900px 700px at 0% 110%,rgba(14,61,160,.55),transparent 55%),
      linear-gradient(160deg,var(--navy) 0%,var(--navy-deep) 100%)}
  .hero::before{content:"";position:absolute;inset:0;opacity:.06;
    background-image:radial-gradient(circle at 1px 1px,#fff 1px,transparent 0);background-size:26px 26px}
  .hero .wrap{position:relative;display:grid;grid-template-columns:1.05fr .95fr;gap:48px;align-items:center;padding:62px 22px 70px}
  .ad-tag{display:inline-block;font-size:.68rem;letter-spacing:.18em;text-transform:uppercase;font-weight:700;
    color:#9fb0d4;border:1px solid rgba(255,255,255,.25);border-radius:6px;padding:4px 9px;margin-bottom:18px}
  .hero h1{color:#fff;font-size:clamp(2.1rem,4.6vw,3.3rem);font-weight:800;letter-spacing:-.02em}
  .hero p.sub{color:#cdd9f2;font-size:1.1rem;margin:1.1rem 0 1.7rem;max-width:540px}
  .hero .eyebrow{color:#7fe3da}
  .hero-cta{display:flex;gap:12px;flex-wrap:wrap;margin-bottom:30px}
  .hero-badges{display:flex;gap:10px;flex-wrap:wrap;margin-bottom:24px}
  .hero-badge{display:flex;align-items:center;gap:6px;font-size:.86rem;color:#cdd9f2;border:1px solid rgba(255,255,255,.2);border-radius:999px;padding:6px 14px;background:rgba(0,0,0,.15);font-family:var(--body);font-weight:500}
  .hero-badge svg{width:15px;height:15px;color:#7fe3da}
  .hero-stats{display:grid;grid-template-columns:repeat(3,1fr);border:1px solid rgba(255,255,255,.2);border-radius:16px;background:rgba(0,0,0,.15);padding:18px;text-align:center;gap:10px}
  .hero-stats > div{position:relative}
  .hero-stats > div:not(:last-child)::after{content:"";position:absolute;right:-5px;top:15%;bottom:15%;width:1px;background:rgba(255,255,255,.2)}
  .hero-stats .n{font-family:var(--display);font-weight:700;font-size:clamp(1.4rem, 2.5vw, 1.8rem);color:#fff;line-height:1.1}
  .hero-stats .l{font-size:.8rem;color:#bccbe9;margin-top:4px}

  /* Glass form */
  .lead-card{position:relative;overflow:hidden;border-radius:26px;
    background:linear-gradient(165deg,rgba(20,52,120,.55),rgba(6,24,64,.62));
    border:1px solid rgba(255,255,255,.16);box-shadow:0 44px 90px -34px rgba(0,0,0,.7);
    -webkit-backdrop-filter:blur(10px);backdrop-filter:blur(10px)}
  .lead-card::before{content:"";position:absolute;inset:0;pointer-events:none;
    background:radial-gradient(520px 320px at 88% -5%,rgba(17,163,154,.16),transparent 60%)}
  .lead-card form{position:relative;padding:30px 30px}
  .lc-eyebrow{display:flex;align-items:center;gap:10px;font-weight:700;font-size:.78rem;letter-spacing:.16em;text-transform:uppercase;color:var(--gold);margin:0}
  .lc-eyebrow::before{content:"";width:26px;height:2px;background:var(--gold);border-radius:2px}
  .lead-card h3.lc-title{color:#fff;font-size:1.5rem;font-weight:800;margin:.55rem 0 .4rem}
  .lead-card p.fhint{color:#bccbe9;font-size:.9rem;margin-bottom:20px}
  .field{margin-bottom:12px}
  label.sr-only{position:absolute;width:1px;height:1px;padding:0;margin:-1px;overflow:hidden;clip:rect(0 0 0 0);white-space:nowrap;border:0}
  .flabel{display:block;font-size:.78rem;font-weight:600;color:#bccbe9;margin-bottom:5px}
  .req{color:var(--gold)}
  .control{width:100%;font-family:var(--body);font-size:1rem;color:#fff;background:rgba(255,255,255,.07);
    border:1px solid rgba(255,255,255,.18);border-radius:14px;padding:.95rem 1.05rem;transition:.15s}
  .control::placeholder{color:rgba(255,255,255,.55)}
  .control:focus{border-color:rgba(255,255,255,.55);outline:none;background:rgba(255,255,255,.11);box-shadow:0 0 0 3px rgba(17,163,154,.18)}
  select.control{appearance:none;-webkit-appearance:none;cursor:pointer;
    background-image:url("data:image/svg+xml;utf8,<svg xmlns='http://www.w3.org/2000/svg' width='20' height='20' fill='none' stroke='%23ffffff' stroke-width='2'><path d='M5 8l5 5 5-5'/></svg>");
    background-repeat:no-repeat;background-position:right 1rem center;padding-right:2.6rem}
  select.control option{color:#0f1b3d}
  textarea.control{resize:vertical;min-height:60px}
  .phone-wrap{display:grid;grid-template-columns:auto 1fr;align-items:stretch;background:rgba(255,255,255,.07);
    border:1px solid rgba(255,255,255,.18);border-radius:14px;transition:.15s}
  .phone-wrap:focus-within{border-color:rgba(255,255,255,.55);background:rgba(255,255,255,.11)}
  .phone-wrap select{appearance:none;-webkit-appearance:none;background:transparent;border:0;color:#fff;font-family:var(--body);
    font-size:1rem;padding:.95rem .55rem .95rem 1.05rem;border-right:1px solid rgba(255,255,255,.18);cursor:pointer}
  .phone-wrap select option{color:#0f1b3d}
  .phone-wrap input{background:transparent;border:0;color:#fff;font-family:var(--body);font-size:1rem;padding:.95rem 1.05rem;width:100%}
  .phone-wrap input::placeholder{color:rgba(255,255,255,.55)}
  .phone-wrap input:focus,.phone-wrap select:focus{outline:none}
  .consent{display:flex;gap:10px;align-items:flex-start;font-size:.82rem;color:#bccbe9;margin:8px 0 14px}
  .consent input{margin-top:2px;flex:0 0 auto;width:18px;height:18px;accent-color:var(--teal)}
  .hp{position:absolute;left:-9999px;width:1px;height:1px;overflow:hidden}
  .err{color:#ff9b8f;font-size:.78rem;margin-top:5px;display:none}
  .field.invalid .control,.field.invalid .phone-wrap{border-color:#ff7b6b}
  .field.invalid .err{display:block}
  .btn-submit{width:100%;display:flex;align-items:center;justify-content:center;border:0;cursor:pointer;color:#fff;font-weight:700;
    font-size:1.04rem;padding:1.02rem;border-radius:999px;font-family:var(--body);
    background:linear-gradient(135deg,#3f70d8,#5b8cea);transition:.18s;box-shadow:0 14px 30px -12px rgba(63,112,216,.7)}
  .btn-submit:hover{filter:brightness(1.06);transform:translateY(-1px)}
  .btn-submit:disabled{opacity:.7;cursor:default;transform:none}
  .form-msg{display:none;text-align:center;padding:40px 22px;position:relative}
  .form-msg svg{margin:0 auto 12px}
  .form-msg h3{font-size:1.3rem;margin-bottom:6px;color:#fff}
  .form-msg p{color:#bccbe9;font-size:.95rem}
  .form-msg p a{color:#7fe3da;font-weight:600}
  .form-msg.show{display:block}

  /* Ribbon */
  .ribbon{background:var(--bg-soft);border-top:1px solid var(--line);border-bottom:1px solid var(--line)}
  .ribbon .wrap{display:flex;flex-wrap:wrap;gap:14px;justify-content:center;align-items:center;padding:18px 22px;text-align:center}
  .ribbon .item{display:flex;align-items:center;gap:8px;font-weight:600;color:var(--navy);font-size:.92rem;padding:0 6px}
  .ribbon .item svg{color:var(--teal)}

  /* Pathways */
  .pathways{display:grid;grid-template-columns:repeat(3,1fr);gap:18px}
  .path{background:#fff;border:1px solid var(--line);border-radius:var(--radius);padding:26px 22px;box-shadow:var(--shadow-sm);position:relative}
  .path .pn{width:36px;height:36px;border-radius:10px;background:var(--navy);color:#fff;display:grid;place-items:center;
    font-family:var(--display);font-weight:800;margin-bottom:14px}
  .path h3{font-size:1.12rem;font-weight:700;margin-bottom:8px}
  .path p{font-size:.93rem;color:var(--muted);margin-bottom:10px}
  .path .gp{font-size:.85rem;color:var(--navy);background:var(--bg-soft);border-left:3px solid var(--teal);
    padding:10px 12px;border-radius:0 8px 8px 0}
  .path .gp b{color:var(--teal-dark)}

  /* GP questions strip */
  .gpbox{background:var(--bg-soft)}
  .gpbox .inner{display:grid;grid-template-columns:1fr 1.4fr;gap:40px;align-items:center}
  .qlist{display:grid;gap:14px;counter-reset:q}
  .qlist .q{display:flex;gap:14px;align-items:flex-start}
  .qlist .q .d{flex:0 0 auto;width:30px;height:30px;border-radius:50%;background:#fff;border:1px solid var(--line);
    color:var(--navy);display:grid;place-items:center;font-family:var(--display);font-weight:800;font-size:.9rem}
  .qlist .q p{font-size:.98rem}
  .qlist .q b{color:var(--navy)}

  /* Pathway steps (6) */
  .steps6{display:grid;grid-template-columns:repeat(3,1fr);gap:18px}
  .st{position:relative;background:#fff;border:1px solid var(--line);border-radius:var(--radius);padding:26px 20px 20px;box-shadow:var(--shadow-sm)}
  .st .num{position:absolute;top:-16px;left:20px;width:36px;height:36px;border-radius:50%;background:var(--teal);color:#fff;
    display:grid;place-items:center;font-family:var(--display);font-weight:800;box-shadow:var(--shadow-sm)}
  .st h3{font-size:1.02rem;margin:8px 0 5px}
  .st p{font-size:.9rem;color:var(--muted)}

  /* Timeline */
  .timeline{display:grid;grid-template-columns:repeat(5,1fr);gap:24px;position:relative;margin-top:20px}
  .timeline::before{content:"";position:absolute;top:22px;left:40px;right:40px;height:1px;background:var(--line);z-index:0}
  .t-item{position:relative;z-index:1}
  .t-item .num{width:44px;height:44px;border-radius:50%;background:var(--navy);color:#fff;display:grid;place-items:center;font-family:var(--display);font-weight:800;font-size:1.1rem;margin-bottom:16px;box-shadow:0 0 0 6px var(--bg-soft)}
  .t-item h3{font-size:1.05rem;font-weight:700;margin-bottom:6px;color:var(--navy)}
  .t-item p{font-size:.9rem;color:var(--muted)}

  /* FAQ */
  .faq{display:grid;gap:14px;max-width:840px;margin:0 auto}
  .faq details{background:#fff;border:1px solid var(--line);border-radius:var(--radius-sm);overflow:hidden;box-shadow:var(--shadow-sm)}
  .faq summary{padding:22px 24px;font-size:1.05rem;font-weight:700;color:var(--navy);cursor:pointer;list-style:none;display:flex;justify-content:space-between;align-items:center;transition:.2s}
  .faq summary::-webkit-details-marker{display:none}
  .faq summary::after{content:"+";color:var(--navy);font-size:1.4rem;font-weight:400;line-height:1;background:var(--bg-soft);width:36px;height:36px;display:grid;place-items:center;border-radius:10px}
  .faq details[open] summary::after{content:"-"}
  .faq .ans{padding:0 24px 24px;color:var(--muted);font-size:.98rem;line-height:1.6}

  /* Office split */
  .split{display:grid;grid-template-columns:1fr 1fr;gap:48px;align-items:center}
    .visual{position:relative;border-radius:20px;min-height:430px;overflow:hidden;box-shadow:var(--shadow);
    background-image:linear-gradient(to top,rgba(6,32,79,.62),rgba(6,32,79,.06) 55%),url("/assets/hq_office_optimized.jpg");
    background-size:cover;background-position:center}
  .badge-float{position:absolute;left:24px;bottom:24px;background:#fff;border-radius:14px;padding:16px 20px;box-shadow:var(--shadow)}
  .badge-float .n{font-family:var(--display);font-weight:800;font-size:1.4rem;color:var(--navy);line-height:1.1}
  .badge-float .l{font-size:.8rem;color:var(--muted);margin-top:4px}
  .check-list{list-style:none;padding:0;margin:18px 0 24px;display:grid;gap:11px}
  .check-list li{display:flex;gap:10px;align-items:flex-start;font-size:.98rem}
  .check-list li svg{flex:0 0 auto;color:var(--teal);margin-top:3px}

  /* Accreditation */
  .accr{display:grid;grid-template-columns:repeat(4,1fr);gap:18px}
  .accr .a{background:#fff;border:1px solid var(--line);border-radius:var(--radius);padding:22px 18px;text-align:center;box-shadow:var(--shadow-sm)}
  .accr .a .t{font-family:var(--display);font-weight:800;color:var(--navy);font-size:1.05rem}
  .accr .a .s{font-size:.84rem;color:var(--muted);margin-top:6px}
  .value{display:grid;grid-template-columns:repeat(4,1fr);gap:18px}
  .vcard{background:#fff;border:1px solid var(--line);border-radius:var(--radius);padding:24px 20px;box-shadow:var(--shadow-sm)}
  .vcard .vic{width:46px;height:46px;border-radius:12px;display:grid;place-items:center;background:rgba(17,163,154,.12);color:var(--teal-dark);margin-bottom:14px}
  .vcard h3{font-size:1.04rem;font-weight:700;margin-bottom:6px}
  .vcard p{font-size:.92rem;color:var(--muted)}
  .reassure{text-align:center;font-size:.76rem;color:#9fb0d4;margin-top:10px}

  /* CTA band split */
  .ctaband{background:linear-gradient(135deg,var(--navy),var(--navy-deep));color:#fff;border-radius:22px;padding:42px;
    display:grid;grid-template-columns:1fr 1fr;gap:30px;box-shadow:var(--shadow);position:relative;overflow:hidden}
  .ctaband::after{content:"";position:absolute;right:-60px;top:-60px;width:280px;height:280px;border-radius:50%;
    background:radial-gradient(circle,rgba(17,163,154,.5),transparent 65%)}
  .ctaband .col{position:relative}
  .ctaband h3{color:#fff;font-size:1.3rem;font-weight:800}
  .ctaband p{color:#cdd9f2;margin:8px 0 16px;font-size:.95rem}
  .ctaband .sep{position:absolute;left:50%;top:10%;bottom:10%;width:1px;background:rgba(255,255,255,.14)}

  /* Contact */
  .contact{background:var(--bg-soft)}
  .contact .inner{display:grid;grid-template-columns:1.2fr 1fr;gap:40px;align-items:center}
  .cinfo{display:grid;gap:16px}
  .cinfo .row{display:flex;gap:14px;align-items:flex-start}
  .cinfo .ic{flex:0 0 auto;width:44px;height:44px;border-radius:12px;background:#fff;border:1px solid var(--line);
    display:grid;place-items:center;color:var(--navy);box-shadow:var(--shadow-sm)}
  .cinfo .k{font-size:.72rem;letter-spacing:.12em;text-transform:uppercase;color:var(--muted);font-weight:600}
  .cinfo .v{font-size:1.05rem;color:var(--navy);font-weight:600}
  .cinfo .v a{color:var(--navy)}
  .mapcard{background:#fff;border:1px solid var(--line);border-radius:var(--radius);padding:26px;box-shadow:var(--shadow-sm);text-align:center}
  .mapcard h3{font-size:1.1rem;margin-bottom:6px}
  .mapcard p{font-size:.9rem;color:var(--muted);margin-bottom:16px}

  /* Footer */
  footer.site{background:var(--navy-deep);color:#c9d4ec}
  .legal{padding:24px 0}
  .legal .wrap{display:flex;justify-content:space-between;gap:14px;flex-wrap:wrap;font-size:.8rem;color:#8ea2cf}
  .legal a{color:#aebcdb;transition:.2s}
  .legal a:hover{color:#fff}

  /* Mobile sticky bar */
  .mbar{position:fixed;left:0;right:0;bottom:0;z-index:70;display:none;background:#fff;border-top:1px solid var(--line);
    box-shadow:0 -6px 20px rgba(6,32,79,.12);padding:8px;gap:8px}
  .mbar a{flex:1;justify-content:center;font-size:.84rem;padding:.7rem .3rem}

  @media(max-width:980px){
    .timeline { grid-template-columns: repeat(2, 1fr); gap: 32px; }
    .timeline::before { display: none; }
    .hero .wrap{grid-template-columns:1fr;gap:32px;padding-top:44px;padding-bottom:48px}
    .pathways,.steps6,.accr,.value{grid-template-columns:repeat(2,1fr)}
    .split,.gpbox .inner,.contact .inner{grid-template-columns:1fr;gap:28px}
    .ctaband{grid-template-columns:1fr;gap:24px}.ctaband .sep{display:none}
    .fcols{grid-template-columns:1fr 1fr}
    .topbar .creds{display:none}
  }
  @media(max-width:620px){
    .hide-mob { display: none !important; }
    .timeline { grid-template-columns: 1fr; gap: 28px; }
    section{padding:52px 0}
    .pathways,.steps6,.accr,.value{grid-template-columns:1fr}
    .ctaband{padding:28px}
    .fcols{grid-template-columns:1fr}
    .hnav{display:none}
    header.site .wrap{justify-content:center}
    .mbar{display:flex}
    body{padding-bottom:64px}
  }
  @media(prefers-reduced-motion:reduce){*{animation:none!important;transition:none!important;scroll-behavior:auto!important}}
</style>
</head>
<body id="top">

<div class="topbar">
  <div class="wrap">
    <div class="creds">
      <span>JCI-accredited</span><span>Multidisciplinary specialist teams</span><span>Global clinical coordination</span>
    </div>
    <div class="right">
      <a href="mailto:<?= $topEmail ?>"><?= $topEmail ?></a>
      <a href="tel:<?= $topPhoneUrl ?>"><?= $topPhoneDisplay ?></a>
    </div>
  </div>
</div>

<header class="site">
  <div class="wrap">
    <a class="logo" href="#top" aria-label="Acibadem Global Headquarters"><img src="data:image/png;base64,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" alt="Acibadem Healthcare Group" /></a>
    <nav class="hnav">
      <a class="phone" href="tel:<?= $topPhoneUrl ?>">
        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72c.13.96.36 1.9.7 2.81a2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45c.91.34 1.85.57 2.81.7A2 2 0 0 1 22 16.92z"/></svg>
        <span><?= $topPhoneDisplay ?></span>
      </a>
      <a class="btn btn-primary" href="#enquiry">Become a Partner</a>
    </nav>
  </div>
</header>

