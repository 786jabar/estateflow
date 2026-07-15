<!-- cookie consent banner -->
<style>
  .cookie-bar{position:fixed;left:2rem;right:2rem;bottom:2rem;z-index:5000;
    background:rgba(10,22,40,.95);color:#fff;padding:1.6rem 2rem;
    display:flex;align-items:center;gap:1.6rem;flex-wrap:wrap;
    border-top:2px solid #c9a961;box-shadow:0 10px 30px rgba(0,0,0,.3);}
  .cookie-bar p{flex:1;min-width:250px;font-size:1.3rem;margin:0;}
  .cookie-bar a{color:#e3c987;text-decoration:underline;}
  .cookie-bar button{padding:1rem 2rem;font-size:1.2rem;letter-spacing:.1em;
    text-transform:uppercase;font-weight:600;border:none;cursor:pointer;
    background:#c9a961;color:#0a1628;}
  .cookie-bar .cookie-close{background:transparent;color:#fff;font-size:2rem;
    padding:.4rem 1rem;line-height:1;}
</style>
<div id="cookieBar" class="cookie-bar" style="display:none;">
   <p>We use cookies to keep you signed in and to improve your experience.
      By using this site you agree to our
      <a href="privacy.php">Privacy Policy</a> and
      <a href="terms.php">Terms</a>.</p>
   <button id="cookieAccept" type="button">Accept</button>
   <button id="cookieClose" type="button" class="cookie-close" title="Close">&times;</button>
</div>
<script>
(function(){
   var bar = document.getElementById('cookieBar');
   if (!bar) return;
   var dismissed = false;
   try { dismissed = (localStorage.getItem('ef_cookies_ok') === '1'); } catch(e){}
   try { if (!dismissed) dismissed = (document.cookie.indexOf('ef_cookies_ok=1') !== -1); } catch(e){}
   if (!dismissed) bar.style.display = 'flex';
   function hide(){
      bar.style.display = 'none';
      try { localStorage.setItem('ef_cookies_ok','1'); } catch(e){}
      try { document.cookie = 'ef_cookies_ok=1; path=/; max-age=31536000'; } catch(e){}
   }
   var accept = document.getElementById('cookieAccept');
   var close  = document.getElementById('cookieClose');
   if (accept) accept.addEventListener('click', hide);
   if (close)  close.addEventListener('click', hide);
   setTimeout(function(){ if (bar.style.display !== 'none') bar.style.display = 'none'; }, 8000);
})();
</script>
