(function(){function r(e,n,t){function o(i,f){if(!n[i]){if(!e[i]){var c="function"==typeof require&&require;if(!f&&c)return c(i,!0);if(u)return u(i,!0);var a=new Error("Cannot find module '"+i+"'");throw a.code="MODULE_NOT_FOUND",a}var p=n[i]={exports:{}};e[i][0].call(p.exports,function(r){var n=e[i][1][r];return o(n||r)},p,p.exports,r,e,n,t)}return n[i].exports}for(var u="function"==typeof require&&require,i=0;i<t.length;i++)o(t[i]);return o}return r})()({1:[function(require,module,exports){
'use strict';

module.exports = function echo() {
  //  discuss at: http://locutus.io/php/echo/
  // original by: Philip Peterson
  // improved by: echo is bad
  // improved by: Nate
  // improved by: Brett Zamir (http://brett-zamir.me)
  // improved by: Brett Zamir (http://brett-zamir.me)
  // improved by: Brett Zamir (http://brett-zamir.me)
  //  revised by: Der Simon (http://innerdom.sourceforge.net/)
  // bugfixed by: Eugene Bulkin (http://doubleaw.com/)
  // bugfixed by: Brett Zamir (http://brett-zamir.me)
  // bugfixed by: Brett Zamir (http://brett-zamir.me)
  // bugfixed by: EdorFaus
  //      note 1: In 1.3.2 and earlier, this function wrote to the body of the document when it
  //      note 1: was called in webbrowsers, in addition to supporting XUL.
  //      note 1: This involved >100 lines of boilerplate to do this in a safe way.
  //      note 1: Since I can't imageine a complelling use-case for this, and XUL is deprecated
  //      note 1: I have removed this behavior in favor of just calling `console.log`
  //      note 2: You'll see functions depends on `echo` instead of `console.log` as we'll want
  //      note 2: to have 1 contact point to interface with the outside world, so that it's easy
  //      note 2: to support other ways of printing output.
  //  revised by: Kevin van Zonneveld (http://kvz.io)
  //    input by: JB
  //   example 1: echo('Hello world')
  //   returns 1: undefined

  var args = Array.prototype.slice.call(arguments);
  return console.log(args.join(' '));
};

},{}],2:[function(require,module,exports){
'use strict';

module.exports = function capwords(str) {
  //  discuss at: http://locutus.io/python/capwords/
  // original by: Jonas Raoni Soares Silva (http://www.jsfromhell.com)
  // improved by: Waldo Malqui Silva (http://waldo.malqui.info)
  // improved by: Robin
  // improved by: Kevin van Zonneveld (http://kvz.io)
  // bugfixed by: Onno Marsman (https://twitter.com/onnomarsman)
  //    input by: James (http://www.james-bell.co.uk/)
  //   example 1: capwords('kevin van  zonneveld')
  //   returns 1: 'Kevin Van  Zonneveld'
  //   example 2: capwords('HELLO WORLD')
  //   returns 2: 'HELLO WORLD'

  var pattern = /^([a-z\u00E0-\u00FC])|\s+([a-z\u00E0-\u00FC])/g;
  return (str + '').replace(pattern, function ($1) {
    return $1.toUpperCase();
  });
};

},{}],3:[function(require,module,exports){
window.echo = require('locutus/php/strings/echo');
window.capwords = require('locutus/python/string/capwords');
},{"locutus/php/strings/echo":1,"locutus/python/string/capwords":2}]},{},[3]);
