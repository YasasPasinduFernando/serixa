(function () {
  var COOKIE = 'serixa_theme';
  function readQuery() {
    try {
      return new URLSearchParams(location.search).get('theme');
    } catch (e) { return null; }
  }
  function readCookie() {
    var m = document.cookie.match(/(?:^|; )serixa_theme=(dark|light)/);
    return m ? m[1] : null;
  }
  function writeCookie(theme) {
    document.cookie = COOKIE + '=' + theme + ';path=/;max-age=31536000;SameSite=Lax';
  }
  function apply(theme) {
    var root = document.querySelector('.site-shell') || document.documentElement;
    root.setAttribute('data-theme', theme);
    if (theme === 'dark') root.classList.add('dark');
    else root.classList.remove('dark');
    document.querySelectorAll('a.site-nav__theme').forEach(function (a) {
      var next = theme === 'dark' ? 'light' : 'dark';
      a.textContent = theme === 'dark' ? 'Light' : 'Dark';
      try {
        var u = new URL(a.getAttribute('href') || location.pathname, location.href);
        u.searchParams.set('theme', next);
        // drop obsolete page param if any
        u.searchParams.delete('page');
        a.setAttribute('href', u.pathname + u.search);
      } catch (e) {}
    });
  }
  var q = readQuery();
  var theme = (q === 'dark' || q === 'light') ? q : (readCookie() || 'light');
  if (q === 'dark' || q === 'light') writeCookie(theme);
  function run() { apply(theme); }
  if (document.querySelector('.site-shell')) run();
  else document.addEventListener('DOMContentLoaded', run);
})();
